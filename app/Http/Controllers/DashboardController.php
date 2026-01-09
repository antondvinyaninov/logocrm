<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Child;
use App\Models\TherapySession;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'superadmin' => $this->superadminDashboard(),
            'organization' => $this->organizationHome(),
            'admin' => $this->organizationHome(), // Для обратной совместимости
            'specialist' => $this->specialistHome(),
            'parent' => $this->parentHome(),
            default => abort(403, 'Unauthorized'),
        };
    }

    public function home()
    {
        $user = Auth::user();

        // SuperAdmin использует dashboard, не home
        if ($user->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        return match ($user->role) {
            'organization' => $this->organizationHome(),
            'specialist' => $this->specialistHome(),
            'parent' => $this->parentHome(),
            default => abort(403, 'Unauthorized'),
        };
    }

    private function superadminDashboard()
    {
        $stats = [
            'organizations' => \App\Models\Organization::count(),
            'centers' => \App\Models\Organization::where('type', 'center')->count(),
            'individuals' => \App\Models\Organization::where('type', 'individual')->count(),
            'users' => User::count(),
            'specialists' => User::where('role', 'specialist')->count(),
            'children' => Child::count(),
        ];

        $recentOrganizations = \App\Models\Organization::latest()->limit(5)->get();
        $activeOrganizations = \App\Models\Organization::where('is_active', true)->get();

        return view('dashboards.superadmin', compact('stats', 'recentOrganizations', 'activeOrganizations'));
    }

    // Home-страницы для workspace
    private function organizationHome()
    {
        $user = Auth::user();
        $organization = $user->organization;

        if (!$organization) {
            return redirect()->route('profile.edit')
                ->with('error', 'Организация не найдена');
        }

        $stats = [
            'specialists' => User::where('role', 'specialist')
                ->where('organization_id', $organization->id)
                ->count(),
            'children' => Child::where('organization_id', $organization->id)->count(),
            'today_sessions' => TherapySession::where('organization_id', $organization->id)
                ->whereDate('start_time', today())
                ->count(),
            'week_sessions' => TherapySession::where('organization_id', $organization->id)
                ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'sessions_today' => TherapySession::where('organization_id', $organization->id)
                ->whereDate('start_time', today())
                ->count(),
            'new_leads' => Lead::where('organization_id', $organization->id)
                ->where('status', 'new')
                ->count(),
        ];

        $recentLeads = Lead::where('organization_id', $organization->id)
            ->latest()
            ->limit(5)
            ->get();

        $upcomingSessions = TherapySession::with(['child', 'specialist'])
            ->where('organization_id', $organization->id)
            ->where('status', 'planned')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        // Используем тот же view что и для специалиста
        $todaySessions = TherapySession::with(['child', 'specialist'])
            ->where('organization_id', $organization->id)
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();

        return view('home.lk', compact('stats', 'todaySessions', 'upcomingSessions', 'recentLeads'));
    }

    private function specialistHome()
    {
        $user = Auth::user();
        $specialistProfile = $user->specialistProfile;

        if (!$specialistProfile) {
            return redirect()->route('profile.edit')
                ->with('error', 'Пожалуйста, заполните профиль специалиста');
        }

        $stats = [
            'children' => $specialistProfile->children->count(),
            'today_sessions' => TherapySession::where('specialist_id', $specialistProfile->id)
                ->whereDate('start_time', today())
                ->count(),
            'week_sessions' => TherapySession::where('specialist_id', $specialistProfile->id)
                ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        $todaySessions = TherapySession::with('child')
            ->where('specialist_id', $specialistProfile->id)
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();

        $upcomingSessions = TherapySession::with('child')
            ->where('specialist_id', $specialistProfile->id)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return view('home.lk', compact('stats', 'todaySessions', 'upcomingSessions'));
    }

    private function parentHome()
    {
        $user = Auth::user();
        $parentProfile = $user->parentProfile;

        if (!$parentProfile) {
            return redirect()->route('profile.edit')
                ->with('error', 'Пожалуйста, заполните профиль родителя');
        }

        $children = $parentProfile->children()->with(['specialist'])->get();

        $upcomingSessions = TherapySession::whereIn('child_id', $children->pluck('id'))
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $recentHomeworks = \App\Models\Homework::whereIn('child_id', $children->pluck('id'))
            ->latest()
            ->limit(5)
            ->get();

        return view('home.parent', compact('children', 'upcomingSessions', 'recentHomeworks'));
    }
}

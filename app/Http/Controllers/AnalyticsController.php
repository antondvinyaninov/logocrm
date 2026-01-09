<?php

namespace App\Http\Controllers;

use App\Models\TherapySession;
use App\Models\Child;
use App\Models\Payment;
use App\Models\SpecialistProfile;
use App\Models\Homework;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isOrganization()) {
            return $this->organizationAnalytics();
        } elseif ($user->isSpecialist()) {
            return $this->specialistAnalytics();
        } elseif ($user->isParent()) {
            return $this->parentAnalytics();
        }
        
        abort(403);
    }
    
    private function organizationAnalytics()
    {
        $organizationId = auth()->user()->organization_id;
        
        // Период для статистики
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Общая статистика
        $totalClients = Child::where('organization_id', $organizationId)->count();
        $totalSpecialists = SpecialistProfile::where('organization_id', $organizationId)->count();
        $totalSessions = TherapySession::where('organization_id', $organizationId)->count();
        
        // Занятия
        $sessionsThisMonth = TherapySession::where('organization_id', $organizationId)
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        $sessionsLastMonth = TherapySession::where('organization_id', $organizationId)
            ->whereDate('start_time', '>=', $lastMonth)
            ->whereDate('start_time', '<', $thisMonth)
            ->count();
        $sessionsCompleted = TherapySession::where('organization_id', $organizationId)
            ->where('status', 'completed')
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        $sessionsCancelled = TherapySession::where('organization_id', $organizationId)
            ->where('status', 'cancelled')
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        
        // Финансы
        $revenueThisMonth = Payment::where('organization_id', $organizationId)
            ->where('type', 'payment')
            ->whereDate('payment_date', '>=', $thisMonth)
            ->sum('amount');
        $revenueLastMonth = Payment::where('organization_id', $organizationId)
            ->where('type', 'payment')
            ->whereDate('payment_date', '>=', $lastMonth)
            ->whereDate('payment_date', '<', $thisMonth)
            ->sum('amount');
        
        // Предстоящие занятия
        $upcomingSessions = TherapySession::where('organization_id', $organizationId)
            ->where('status', 'scheduled')
            ->whereDate('start_time', '>=', $today)
            ->orderBy('start_time')
            ->limit(5)
            ->with(['child', 'specialist'])
            ->get();
        
        // Топ специалистов по количеству занятий
        $topSpecialists = SpecialistProfile::where('organization_id', $organizationId)
            ->withCount(['therapySessions' => function($query) use ($thisMonth) {
                $query->whereDate('start_time', '>=', $thisMonth);
            }])
            ->orderBy('therapy_sessions_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('analytics.organization', compact(
            'totalClients',
            'totalSpecialists',
            'totalSessions',
            'sessionsThisMonth',
            'sessionsLastMonth',
            'sessionsCompleted',
            'sessionsCancelled',
            'revenueThisMonth',
            'revenueLastMonth',
            'upcomingSessions',
            'topSpecialists'
        ));
    }
    
    private function specialistAnalytics()
    {
        $specialist = auth()->user()->specialistProfile;
        
        if (!$specialist) {
            abort(404, 'Профиль специалиста не найден');
        }
        
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Общая статистика
        $totalClients = Child::where('specialist_id', $specialist->id)->count();
        $totalSessions = TherapySession::where('specialist_id', $specialist->id)->count();
        
        // Занятия
        $sessionsThisMonth = TherapySession::where('specialist_id', $specialist->id)
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        $sessionsLastMonth = TherapySession::where('specialist_id', $specialist->id)
            ->whereDate('start_time', '>=', $lastMonth)
            ->whereDate('start_time', '<', $thisMonth)
            ->count();
        $sessionsCompleted = TherapySession::where('specialist_id', $specialist->id)
            ->where('status', 'completed')
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        $sessionsCancelled = TherapySession::where('specialist_id', $specialist->id)
            ->where('status', 'cancelled')
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        
        // Финансы (для частных специалистов)
        $revenueThisMonth = 0;
        $revenueLastMonth = 0;
        if (auth()->user()->organization_id === auth()->user()->id) {
            $revenueThisMonth = Payment::where('organization_id', auth()->user()->organization_id)
                ->where('type', 'payment')
                ->whereDate('payment_date', '>=', $thisMonth)
                ->sum('amount');
            $revenueLastMonth = Payment::where('organization_id', auth()->user()->organization_id)
                ->where('type', 'payment')
                ->whereDate('payment_date', '>=', $lastMonth)
                ->whereDate('payment_date', '<', $thisMonth)
                ->sum('amount');
        }
        
        // Предстоящие занятия
        $upcomingSessions = TherapySession::where('specialist_id', $specialist->id)
            ->where('status', 'scheduled')
            ->whereDate('start_time', '>=', $today)
            ->orderBy('start_time')
            ->limit(5)
            ->with('child')
            ->get();
        
        // Домашние задания
        $activeHomeworks = Homework::where('specialist_id', $specialist->id)
            ->where('status', 'active')
            ->count();
        $completedHomeworks = Homework::where('specialist_id', $specialist->id)
            ->where('status', 'completed_by_parent')
            ->count();
        
        // Рейтинг и отзывы
        $averageRating = $specialist->rating;
        $totalReviews = $specialist->reviews_count;
        $recentReviews = Review::where('specialist_id', $specialist->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('analytics.specialist', compact(
            'totalClients',
            'totalSessions',
            'sessionsThisMonth',
            'sessionsLastMonth',
            'sessionsCompleted',
            'sessionsCancelled',
            'revenueThisMonth',
            'revenueLastMonth',
            'upcomingSessions',
            'activeHomeworks',
            'completedHomeworks',
            'averageRating',
            'totalReviews',
            'recentReviews'
        ));
    }
    
    private function parentAnalytics()
    {
        $parentId = auth()->id();
        
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Дети родителя
        $children = Child::where('parent_id', $parentId)->get();
        $childrenIds = $children->pluck('id');
        
        // Общая статистика
        $totalChildren = $children->count();
        $totalSessions = TherapySession::whereIn('child_id', $childrenIds)->count();
        
        // Занятия
        $sessionsThisMonth = TherapySession::whereIn('child_id', $childrenIds)
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        $sessionsCompleted = TherapySession::whereIn('child_id', $childrenIds)
            ->where('status', 'completed')
            ->whereDate('start_time', '>=', $thisMonth)
            ->count();
        
        // Предстоящие занятия
        $upcomingSessions = TherapySession::whereIn('child_id', $childrenIds)
            ->where('status', 'scheduled')
            ->whereDate('start_time', '>=', $today)
            ->orderBy('start_time')
            ->limit(5)
            ->with(['child', 'specialist'])
            ->get();
        
        // Домашние задания
        $activeHomeworks = Homework::whereIn('child_id', $childrenIds)
            ->where('status', 'active')
            ->count();
        $completedHomeworks = Homework::whereIn('child_id', $childrenIds)
            ->where('status', 'completed_by_parent')
            ->count();
        $checkedHomeworks = Homework::whereIn('child_id', $childrenIds)
            ->where('status', 'checked_by_specialist')
            ->whereDate('updated_at', '>=', $thisMonth)
            ->count();
        
        return view('analytics.parent', compact(
            'totalChildren',
            'totalSessions',
            'sessionsThisMonth',
            'sessionsCompleted',
            'upcomingSessions',
            'activeHomeworks',
            'completedHomeworks',
            'checkedHomeworks',
            'children'
        ));
    }
}

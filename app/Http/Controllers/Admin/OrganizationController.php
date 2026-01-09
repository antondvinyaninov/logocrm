<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index()
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        $organizations = Organization::withCount(['users', 'specialists', 'children', 'sessions'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:center,individual',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $organization = Organization::create($validated);

        return redirect()->route('admin.organizations.show', $organization)
            ->with('success', 'Организация успешно создана');
    }

    public function show(Organization $organization)
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        $organization->load([
            'users',
            'specialists.user',
            'children',
            'sessions' => fn($q) => $q->latest()->limit(10)
        ]);

        $stats = [
            'users' => $organization->users()->count(),
            'specialists' => $organization->specialists()->count(),
            'children' => $organization->children()->count(),
            'sessions_total' => $organization->sessions()->count(),
            'sessions_this_month' => $organization->sessions()
                ->whereMonth('start_time', now()->month)
                ->count(),
        ];

        return view('admin.organizations.show', compact('organization', 'stats'));
    }

    public function edit(Organization $organization)
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:center,individual',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $organization->update($validated);

        return redirect()->route('admin.organizations.show', $organization)
            ->with('success', 'Организация успешно обновлена');
    }

    public function destroy(Organization $organization)
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        // Проверяем, есть ли связанные данные
        if ($organization->users()->count() > 0) {
            return back()->with('error', 'Невозможно удалить организацию с пользователями');
        }

        $organization->delete();

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Организация успешно удалена');
    }

    public function toggleActive(Organization $organization)
    {
        // Только superadmin может управлять организациями
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещён');
        }

        $organization->update(['is_active' => !$organization->is_active]);

        return back()->with('success', 'Статус организации изменён');
    }
}

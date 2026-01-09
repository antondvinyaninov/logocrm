<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        // Проверка роли: только superadmin и organization могут управлять заявками
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $query = Lead::query();

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Поиск
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contact', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $leads = $query->latest()->paginate(20);

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead)
    {
        // Проверка роли: только superadmin и organization могут управлять заявками
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        // Проверка роли: только superadmin и organization могут управлять заявками
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,closed',
            'notes' => 'nullable|string|max:5000',
        ]);

        $lead->update($validated);

        return back()->with('success', 'Заявка обновлена');
    }

    public function destroy(Lead $lead)
    {
        // Проверка роли: только superadmin и organization могут управлять заявками
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Заявка удалена');
    }
}

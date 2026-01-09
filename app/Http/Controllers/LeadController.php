<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        // Только специалисты могут видеть заявки
        if (!auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещён. Только специалисты могут обрабатывать заявки.');
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

        return view('leads.index', compact('leads'));
    }

    public function show(Lead $lead)
    {
        // Только специалисты могут видеть заявки
        if (!auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещён');
        }

        return view('leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        // Только специалисты могут обновлять заявки
        if (!auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещён');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,closed',
            'notes' => 'nullable|string|max:5000',
        ]);

        $lead->update($validated);

        return back()->with('success', 'Заявка обновлена');
    }
}

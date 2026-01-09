<?php

namespace App\Http\Controllers;

use App\Models\SessionReport;
use App\Models\TherapySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SessionReportController extends Controller
{
    /**
     * Форма создания отчёта
     */
    public function create($sessionId)
    {
        Gate::authorize('create', SessionReport::class);

        $session = TherapySession::with(['child', 'specialist'])->findOrFail($sessionId);

        // Проверка доступа к занятию
        Gate::authorize('view', $session);

        // Проверка, что отчёт ещё не создан
        if ($session->report) {
            return redirect()->route('session-reports.edit', $session->report)
                ->with('info', 'Отчёт уже существует. Вы можете его отредактировать.');
        }

        return view('session-reports.create', compact('session'));
    }

    /**
     * Сохранение отчёта
     */
    public function store(Request $request, $sessionId)
    {
        \Log::info('SessionReport store called', [
            'sessionId' => $sessionId,
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
        ]);

        Gate::authorize('create', SessionReport::class);

        $session = TherapySession::findOrFail($sessionId);
        Gate::authorize('view', $session);

        if ($session->report) {
            return redirect()->route('session-reports.edit', $session->report)
                ->with('info', 'Отчёт уже существует');
        }

        $validated = $request->validate([
            'goals' => 'nullable|array',
            'goals.*' => 'string|max:500',
            'comment' => 'required|string|max:5000',
            'rating' => 'nullable|integer|min:1|max:5',
        ], [
            'comment.required' => 'Комментарий обязателен для заполнения',
            'comment.max' => 'Комментарий не должен превышать 5000 символов',
        ]);

        \Log::info('Validation passed', ['validated' => $validated]);

        $report = SessionReport::create([
            'session_id' => $session->id,
            'organization_id' => $session->organization_id,
            'goals_json' => $validated['goals'] ?? [],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'] ?? 3, // Дефолтное значение если не указано
        ]);

        \Log::info('Report created', ['report_id' => $report->id]);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Отчёт успешно создан');
    }

    /**
     * Форма редактирования отчёта
     */
    public function edit(SessionReport $sessionReport)
    {
        Gate::authorize('update', $sessionReport);

        $sessionReport->load(['session.child', 'session.specialist']);

        return view('session-reports.edit', [
            'report' => $sessionReport,
            'session' => $sessionReport->session
        ]);
    }

    /**
     * Обновление отчёта
     */
    public function update(Request $request, SessionReport $sessionReport)
    {
        Gate::authorize('update', $sessionReport);

        $validated = $request->validate([
            'goals' => 'nullable|array',
            'goals.*' => 'string|max:500',
            'comment' => 'required|string|max:5000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $sessionReport->update([
            'goals_json' => $validated['goals'] ?? [],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
        ]);

        return redirect()->route('sessions.show', $sessionReport->session)
            ->with('success', 'Отчёт успешно обновлён');
    }

    /**
     * Удаление отчёта
     */
    public function destroy(SessionReport $sessionReport)
    {
        Gate::authorize('delete', $sessionReport);

        $session = $sessionReport->session;
        $sessionReport->delete();

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Отчёт успешно удалён');
    }
}

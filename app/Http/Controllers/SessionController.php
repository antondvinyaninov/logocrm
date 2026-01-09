<?php

namespace App\Http\Controllers;

use App\Models\TherapySession;
use App\Models\Child;
use App\Models\SpecialistProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SessionController extends Controller
{
    /**
     * Список занятий
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = TherapySession::with(['child', 'specialist']);

        // Фильтрация по ролям
        if ($user->isSpecialist()) {
            $query->forSpecialist($user->specialistProfile->id);
        } elseif ($user->isParent()) {
            $query->forParent($user->parentProfile->id);
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Фильтр по дате
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        } elseif ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('start_time', $date);
        }

        // Фильтр по ребёнку (для админа и специалиста)
        if ($request->filled('child_id') && !$user->isParent()) {
            $query->forChild($request->child_id);
        }

        // Фильтр по специалисту (только для админа)
        if ($request->filled('specialist_id') && $user->isAdmin()) {
            $query->forSpecialist($request->specialist_id);
        }

        // Сортировка
        $sortBy = $request->get('sort', 'start_time');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $sessions = $query->paginate(20);

        // Данные для фильтров
        $children = $this->getChildrenForUser($user);
        $specialists = $user->isAdmin() ? SpecialistProfile::all() : collect();

        return view('sessions.index', compact('sessions', 'children', 'specialists'));
    }

    /**
     * Форма создания занятия
     */
    public function create()
    {
        Gate::authorize('create', TherapySession::class);

        $user = auth()->user();
        $children = $this->getChildrenForUser($user);
        $specialists = $user->isOrganization() ? SpecialistProfile::where('organization_id', $user->organization_id)->get() : collect();
        $services = \App\Models\Service::where('organization_id', $user->organization_id)
            ->where('is_active', true)
            ->get();

        // Преобразуем детей в формат для Alpine.js
        $childrenData = $children->map(function($child) {
            return [
                'id' => $child->id,
                'full_name' => $child->full_name,
                'first_name' => $child->first_name,
                'last_name' => $child->last_name,
                'middle_name' => $child->middle_name,
                'phone' => $child->phone,
                'email' => $child->email,
            ];
        });

        return view('sessions.create', compact('children', 'childrenData', 'specialists', 'services'));
    }

    /**
     * Сохранение нового занятия
     */
    public function store(Request $request)
    {
        Gate::authorize('create', TherapySession::class);

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'specialist_id' => 'required|exists:specialist_profiles,id',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'type' => 'required|in:individual,group',
            'format' => 'required|in:online,offline',
            'status' => 'nullable|in:planned,confirmed,done,cancelled',
            'service_id' => 'nullable|exists:services,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Объединяем дату и время
        $validated['start_time'] = $validated['session_date'] . ' ' . $validated['session_time'];
        unset($validated['session_date'], $validated['session_time']);

        // Проверка доступа к ребёнку
        $child = Child::findOrFail($validated['child_id']);
        if (auth()->user()->isSpecialist()) {
            if ($child->specialist_id !== auth()->user()->specialistProfile->id) {
                abort(403, 'Вы не можете создавать занятия для этого ребёнка');
            }
        }

        $validated['status'] = $validated['status'] ?? 'planned';

        $session = TherapySession::create($validated);

        return redirect()->route('sessions.index')
            ->with('success', 'Занятие успешно создано');
    }

    /**
     * Просмотр занятия
     */
    public function show(TherapySession $session)
    {
        Gate::authorize('view', $session);

        $session->load(['child.parent', 'specialist.user', 'report', 'homeworks']);

        return view('sessions.show', compact('session'));
    }

    /**
     * Форма редактирования занятия
     */
    public function edit(TherapySession $session)
    {
        Gate::authorize('update', $session);

        $user = auth()->user();
        $children = $this->getChildrenForUser($user);
        $specialists = $user->isOrganization() ? SpecialistProfile::where('organization_id', $user->organization_id)->get() : collect([$session->specialist]);
        $services = \App\Models\Service::where('organization_id', $user->organization_id)
            ->where('is_active', true)
            ->get();

        // Преобразуем детей в формат для Alpine.js
        $childrenData = $children->map(function($child) {
            return [
                'id' => $child->id,
                'full_name' => $child->full_name,
                'first_name' => $child->first_name,
                'last_name' => $child->last_name,
                'middle_name' => $child->middle_name,
                'phone' => $child->phone,
                'email' => $child->email,
            ];
        });

        return view('sessions.edit', compact('session', 'children', 'childrenData', 'specialists', 'services'));
    }

    /**
     * Обновление занятия
     */
    public function update(Request $request, TherapySession $session)
    {
        Gate::authorize('update', $session);

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'specialist_id' => 'required|exists:specialist_profiles,id',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'type' => 'required|in:individual,group',
            'format' => 'required|in:online,offline',
            'status' => 'required|in:planned,confirmed,done,cancelled',
            'service_id' => 'nullable|exists:services,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Объединяем дату и время
        $validated['start_time'] = $validated['session_date'] . ' ' . $validated['session_time'];
        unset($validated['session_date'], $validated['session_time']);

        $session->update($validated);

        return redirect()->route('sessions.index')
            ->with('success', 'Занятие успешно обновлено');
    }

    /**
     * Удаление занятия
     */
    public function destroy(TherapySession $session)
    {
        Gate::authorize('delete', $session);

        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Занятие успешно удалено');
    }

    /**
     * Перемещение занятия (drag & drop)
     */
    public function move(Request $request, TherapySession $session)
    {
        Gate::authorize('update', $session);

        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $newStartTime = $validated['date'] . ' ' . $validated['time'];
        
        $session->update([
            'start_time' => $newStartTime
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Занятие успешно перемещено'
        ]);
    }

    /**
     * Получить список детей для текущего пользователя
     */
    private function getChildrenForUser($user)
    {
        if ($user->isAdmin()) {
            return Child::with('parent')->get();
        } elseif ($user->isSpecialist()) {
            return Child::where('specialist_id', $user->specialistProfile->id)->get();
        } elseif ($user->isParent()) {
            return Child::where('parent_id', $user->parentProfile->id)->get();
        }

        return collect();
    }

    /**
     * Обновление статуса занятия
     */
    public function updateStatus(Request $request, TherapySession $session)
    {
        Gate::authorize('update', $session);

        $validated = $request->validate([
            'status' => 'required|in:planned,confirmed,done,cancelled',
        ]);

        $session->update($validated);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Статус занятия обновлен');
    }

    /**
     * Обновление записей специалиста
     */
    public function updateNotes(Request $request, TherapySession $session)
    {
        Gate::authorize('update', $session);

        $validated = $request->validate([
            'specialist_notes' => 'nullable|string|max:5000',
        ]);

        $session->update($validated);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Записи специалиста обновлены');
    }

    /**
     * Обновление списка игр
     */
    public function updateGames(Request $request, TherapySession $session)
    {
        Gate::authorize('update', $session);

        $validated = $request->validate([
            'games_used' => 'nullable|json',
        ]);

        $session->update([
            'games_used' => json_decode($validated['games_used'], true)
        ]);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Список игр и материалов обновлен');
    }
}

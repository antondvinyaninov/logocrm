<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\Child;
use App\Models\TherapySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeworkController extends Controller
{
    /**
     * Список домашних заданий
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Homework::with(['child', 'session']);

        // Фильтрация по ролям
        if ($user->isSpecialist()) {
            $query->whereHas('child', function($q) use ($user) {
                $q->where('specialist_id', $user->specialistProfile->id);
            });
        } elseif ($user->isParent()) {
            $query->whereHas('child', function($q) use ($user) {
                $q->where('parent_id', $user->parentProfile->id);
            });
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Фильтр по ребёнку
        if ($request->filled('child_id')) {
            $query->where('child_id', $request->child_id);
        }

        $homeworks = $query->latest()->paginate(20);

        // Данные для фильтров
        $children = $this->getChildrenForUser($user);

        return view('homeworks.index', compact('homeworks', 'children'));
    }

    /**
     * Форма создания ДЗ
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Homework::class);

        $user = auth()->user();
        $children = $this->getChildrenForUser($user);
        
        $sessionId = $request->get('session_id');
        $session = $sessionId ? TherapySession::find($sessionId) : null;

        return view('homeworks.create', compact('children', 'session'));
    }

    /**
     * Сохранение ДЗ
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Homework::class);

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'session_id' => 'nullable|exists:therapy_sessions,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'resource_url' => 'nullable|url|max:500',
        ]);

        // Проверка доступа к ребёнку
        $child = Child::findOrFail($validated['child_id']);
        if (auth()->user()->isSpecialist()) {
            if ($child->specialist_id !== auth()->user()->specialistProfile->id) {
                abort(403, 'Вы не можете создавать ДЗ для этого ребёнка');
            }
        }

        $validated['status'] = 'active';

        $homework = Homework::create($validated);

        if ($request->has('session_id') && $request->session_id) {
            return redirect()->route('sessions.show', $request->session_id)
                ->with('success', 'Домашнее задание успешно создано');
        }

        return redirect()->route('homeworks.index')
            ->with('success', 'Домашнее задание успешно создано');
    }

    /**
     * Просмотр ДЗ
     */
    public function show(Homework $homework)
    {
        Gate::authorize('view', $homework);

        $homework->load(['child', 'session']);

        return view('homeworks.show', compact('homework'));
    }

    /**
     * Форма редактирования ДЗ
     */
    public function edit(Homework $homework)
    {
        Gate::authorize('update', $homework);

        $user = auth()->user();
        $children = $this->getChildrenForUser($user);

        return view('homeworks.edit', compact('homework', 'children'));
    }

    /**
     * Обновление ДЗ
     */
    public function update(Request $request, Homework $homework)
    {
        Gate::authorize('update', $homework);

        $user = auth()->user();

        // Родитель может только отмечать выполнение
        if ($user->isParent()) {
            $validated = $request->validate([
                'status' => 'required|in:active,done_by_parent',
            ]);

            $homework->update(['status' => $validated['status']]);

            return back()->with('success', 'Статус обновлён');
        }

        // Специалист и админ могут редактировать всё
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'session_id' => 'nullable|exists:therapy_sessions,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'resource_url' => 'nullable|url|max:500',
            'status' => 'required|in:active,done_by_parent,checked_by_specialist',
        ]);

        $homework->update($validated);

        return redirect()->route('homeworks.index')
            ->with('success', 'Домашнее задание успешно обновлено');
    }

    /**
     * Удаление ДЗ
     */
    public function destroy(Homework $homework)
    {
        Gate::authorize('delete', $homework);

        $homework->delete();

        return redirect()->route('homeworks.index')
            ->with('success', 'Домашнее задание успешно удалено');
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
}

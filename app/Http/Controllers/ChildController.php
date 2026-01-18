<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChildRequest;
use App\Http\Requests\UpdateChildRequest;
use App\Models\Child;
use App\Models\SpecialistProfile;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChildController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Child::with(['parent', 'specialist']);

        // Фильтрация по роли
        if ($user->role === 'specialist' && $user->specialistProfile) {
            // Специалист видит только своих клиентов
            $query->where('specialist_id', $user->specialistProfile->id);
        } elseif ($user->role === 'parent' && $user->parentProfile) {
            // Родитель видит только своих детей
            $query->where('parent_id', $user->parentProfile->id);
        }
        // Админ видит всех

        // Поиск
        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        // Фильтр по специалисту (только для админа)
        if (($user->isSuperAdmin() || $user->isOrganization()) && $request->filled('specialist_id')) {
            $query->where('specialist_id', $request->specialist_id);
        }

        $children = $query->latest()->paginate(15);

        // Для фильтров
        $specialists = ($user->isSuperAdmin() || $user->isOrganization()) ? SpecialistProfile::all() : collect();

        return view('children.index', compact('children', 'specialists'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Список специалистов
        if ($user->isSuperAdmin() || $user->isOrganization()) {
            $specialists = SpecialistProfile::all();
        } elseif ($user->role === 'specialist') {
            $specialists = collect([$user->specialistProfile]);
        } else {
            $specialists = SpecialistProfile::all();
        }

        // Список родителей
        if ($user->isSuperAdmin() || $user->isOrganization() || $user->role === 'specialist') {
            $parents = ParentProfile::all();
        } else {
            $parents = collect([$user->parentProfile]);
        }

        return view('children.create', compact('specialists', 'parents'));
    }

    public function store(StoreChildRequest $request)
    {
        $user = auth()->user();
        
        $data = $request->validated();
        
        // Добавляем organization_id
        $data['organization_id'] = $user->organization_id;
        
        // Если родитель создаёт ребёнка, автоматически привязываем к его профилю
        if ($user->role === 'parent' && $user->parentProfile) {
            $data['parent_id'] = $user->parentProfile->id;
        }
        
        // Если специалист создаёт ребёнка, автоматически привязываем к нему
        if ($user->role === 'specialist' && $user->specialistProfile) {
            $data['specialist_id'] = $user->specialistProfile->id;
        }

        Child::create($data);

        return redirect()->route('children.index')
                        ->with('success', 'Ребёнок успешно добавлен');
    }

    public function show(Child $child)
    {
        $this->authorize('view', $child);
        
        $child->load([
            'parent', 
            'specialist', 
            'therapySessions.specialist', 
            'homeworks',
            'conclusions.specialist.user',
            'specialistHistory.specialist.user'
        ]);
        
        // Загружаем список специалистов, если у ребёнка нет специалиста
        $specialists = null;
        if (!$child->specialist_id) {
            $specialists = SpecialistProfile::all();
        }
        
        // Получаем прошедшие занятия с отчетами
        $pastSessions = $child->therapySessions()
            ->with(['specialist', 'report'])
            ->where('start_time', '<', now())
            ->orderBy('start_time', 'desc')
            ->limit(10)
            ->get();
        
        // Получаем предстоящие занятия
        $upcomingSessions = $child->therapySessions()
            ->with('specialist')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();
        
        // Статистика
        $stats = [
            'total_sessions' => $child->therapySessions()->count(),
            'completed_sessions' => $child->therapySessions()->where('status', 'done')->count(),
            'active_homeworks' => $child->homeworks()->where('status', 'active')->count(),
        ];
        
        return view('children.show', compact('child', 'specialists', 'pastSessions', 'upcomingSessions', 'stats'));
    }

    public function edit(Child $child)
    {
        $this->authorize('update', $child);
        
        $user = auth()->user();
        
        // Список специалистов
        if ($user->isSuperAdmin() || $user->isOrganization()) {
            $specialists = SpecialistProfile::all();
        } elseif ($user->role === 'specialist') {
            $specialists = collect([$user->specialistProfile]);
        } else {
            $specialists = SpecialistProfile::all();
        }

        // Список родителей
        if ($user->isSuperAdmin() || $user->isOrganization() || $user->role === 'specialist') {
            $parents = ParentProfile::all();
        } else {
            $parents = collect([$user->parentProfile]);
        }

        return view('children.edit', compact('child', 'specialists', 'parents'));
    }

    public function update(UpdateChildRequest $request, Child $child)
    {
        $this->authorize('update', $child);
        
        $child->update($request->validated());

        return redirect()->route('children.index')
                        ->with('success', 'Данные ребёнка успешно обновлены');
    }

    public function destroy(Child $child)
    {
        $this->authorize('delete', $child);
        
        $child->delete();

        return redirect()->route('children.index')
                        ->with('success', 'Ребёнок успешно удалён');
    }

    public function assignSpecialist(Request $request, Child $child)
    {
        $this->authorize('update', $child);
        
        $request->validate([
            'specialist_id' => ['required', 'exists:specialist_profiles,id'],
        ]);

        $child->update([
            'specialist_id' => $request->specialist_id,
        ]);

        return redirect()->route('children.show', $child)
                        ->with('success', 'Специалист успешно назначен');
    }

    public function updateSpecialists(Request $request, Child $child)
    {
        $this->authorize('update', $child);
        
        $validated = $request->validate([
            'other_specialists' => 'nullable|json',
        ]);

        $child->update([
            'other_specialists' => json_decode($validated['other_specialists'], true)
        ]);

        return redirect()->route('children.show', $child)
                        ->with('success', 'Список специалистов обновлен');
    }

    public function updateConclusion(Request $request, Child $child)
    {
        $this->authorize('update', $child);
        
        $validated = $request->validate([
            'specialist_conclusion' => 'nullable|string|max:5000',
        ]);

        $child->update($validated);

        return redirect()->route('children.show', $child)
                        ->with('success', 'Заключение специалиста обновлено');
    }

    /**
     * Сохранение нового заключения специалиста
     */
    public function storeConclusion(Request $request, Child $child)
    {
        $this->authorize('update', $child);

        $validated = $request->validate([
            'content' => 'required|string|max:10000',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('conclusions', 'public');
                $attachments[] = $path;
            }
        }

        \App\Models\SpecialistConclusion::create([
            'child_id' => $child->id,
            'specialist_id' => auth()->user()->specialistProfile->id,
            'organization_id' => $child->organization_id,
            'content' => $validated['content'],
            'attachments' => $attachments,
        ]);

        return redirect()->route('children.show', $child)
            ->with('success', 'Заключение добавлено');
    }

    /**
     * Удаление заключения специалиста
     */
    public function destroyConclusion(Child $child, \App\Models\SpecialistConclusion $conclusion)
    {
        $this->authorize('update', $child);

        // Проверяем, что специалист удаляет свое заключение
        if ($conclusion->specialist_id !== auth()->user()->specialistProfile->id) {
            abort(403, 'Вы можете удалять только свои заключения');
        }

        // Удаляем файлы
        if ($conclusion->attachments) {
            foreach ($conclusion->attachments as $attachment) {
                \Storage::disk('public')->delete($attachment);
            }
        }

        $conclusion->delete();

        return redirect()->route('children.show', $child)
            ->with('success', 'Заключение удалено');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SpecialistProfile;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpecialistProfileController extends Controller
{
    /**
     * Каталог специалистов (публичный)
     */
    public function catalog(Request $request)
    {
        $query = SpecialistProfile::with(['user', 'reviews', 'organization']);

        // Фильтр по организации
        if ($request->filled('organization')) {
            $query->where('organization_id', $request->organization);
        }

        // Фильтр по специализации
        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        // Фильтр по формату (онлайн/офлайн)
        if ($request->filled('format')) {
            if ($request->format === 'online') {
                $query->where('available_online', true);
            } elseif ($request->format === 'offline') {
                $query->where('available_offline', true);
            }
        }

        // Фильтр по рейтингу
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Фильтр по стоимости
        if ($request->filled('max_price')) {
            $query->where('price_per_session', '<=', $request->max_price);
        }

        // Сортировка
        $sortBy = $request->get('sort', 'rating');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['rating', 'price_per_session', 'experience_years', 'reviews_count'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $specialists = $query->paginate(12);
        
        // Получаем список организаций для фильтра
        $organizations = \App\Models\Organization::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('specialists.catalog', compact('specialists', 'organizations'));
    }

    /**
     * Публичный профиль специалиста
     */
    public function show($id)
    {
        $specialist = SpecialistProfile::with(['user', 'reviews.user', 'children'])
            ->findOrFail($id);

        return view('specialists.show', compact('specialist'));
    }

    /**
     * Форма редактирования профиля (для специалиста)
     */
    public function edit()
    {
        $specialist = auth()->user()->specialistProfile;
        
        if (!$specialist) {
            abort(403, 'У вас нет профиля специалиста');
        }

        return view('specialists.edit', compact('specialist'));
    }

    /**
     * Обновление профиля специалиста
     */
    public function update(Request $request)
    {
        $specialist = auth()->user()->specialistProfile;
        
        if (!$specialist) {
            abort(403, 'У вас нет профиля специалиста');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'about' => 'nullable|string|max:5000',
            'education' => 'nullable|string|max:5000',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'certificates' => 'nullable|array',
            'certificates.*' => 'string|max:500',
            'price_per_session' => 'nullable|numeric|min:0',
            'available_online' => 'boolean',
            'available_offline' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Обработка загрузки фото
        if ($request->hasFile('photo')) {
            // Удаляем старое фото
            if ($specialist->photo) {
                Storage::disk('public')->delete($specialist->photo);
            }

            $path = $request->file('photo')->store('specialists', 'public');
            $validated['photo'] = $path;
        }

        $specialist->update($validated);

        return redirect()->route('specialists.edit')
            ->with('success', 'Профиль успешно обновлён');
    }

    /**
     * Добавление отзыва
     */
    public function storeReview(Request $request, $specialistId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Проверяем, что пользователь - родитель
        if (!auth()->user()->hasRole('parent')) {
            abort(403, 'Только родители могут оставлять отзывы');
        }

        // Проверяем, что родитель имел занятия с этим специалистом
        $hasChildren = auth()->user()->parentProfile->children()
            ->where('specialist_id', $specialistId)
            ->exists();

        if (!$hasChildren) {
            abort(403, 'Вы можете оставлять отзывы только специалистам, с которыми работали');
        }

        // Проверяем, что отзыв ещё не оставлен
        $existingReview = Review::where('user_id', auth()->id())
            ->where('specialist_id', $specialistId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Вы уже оставили отзыв этому специалисту');
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'specialist_id' => $specialistId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        // Обновляем рейтинг специалиста
        $specialist = SpecialistProfile::findOrFail($specialistId);
        $specialist->updateRating();

        return back()->with('success', 'Отзыв успешно добавлен');
    }
    /**
     * Сохранение расписания специалиста
     */
    public function saveSchedule(Request $request)
    {
        $specialist = auth()->user()->specialistProfile;
        
        if (!$specialist) {
            return response()->json(['success' => false, 'message' => 'Профиль специалиста не найден'], 404);
        }

        $validated = $request->validate([
            'repeat_type' => 'required|in:weekly,biweekly,monthly',
            'schedule' => 'required|array',
            'schedule.*.day' => 'required|integer|min:0|max:6',
            'schedule.*.enabled' => 'required|boolean',
            'schedule.*.start_time' => 'required_if:schedule.*.enabled,true|nullable|date_format:H:i',
            'schedule.*.end_time' => 'required_if:schedule.*.enabled,true|nullable|date_format:H:i',
            'schedule.*.break_start' => 'nullable|date_format:H:i',
            'schedule.*.break_end' => 'nullable|date_format:H:i',
        ]);

        // Удаляем старое расписание
        $specialist->schedules()->delete();

        // Создаем новое расписание
        foreach ($validated['schedule'] as $daySchedule) {
            if ($daySchedule['enabled']) {
                \App\Models\SpecialistSchedule::create([
                    'specialist_id' => $specialist->id,
                    'organization_id' => auth()->user()->organization_id,
                    'day_of_week' => $daySchedule['day'],
                    'start_time' => $daySchedule['start_time'],
                    'end_time' => $daySchedule['end_time'],
                    'break_start' => $daySchedule['break_start'] ?? null,
                    'break_end' => $daySchedule['break_end'] ?? null,
                    'repeat_type' => $validated['repeat_type'],
                    'is_active' => true,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Расписание успешно сохранено'
        ]);
    }
    
    /**
     * Сохранение календаря работы специалиста
     */
    public function saveCalendar(Request $request)
    {
        $specialist = auth()->user()->specialistProfile;
        
        if (!$specialist) {
            return response()->json(['success' => false, 'message' => 'Профиль специалиста не найден'], 404);
        }

        $validated = $request->validate([
            'workingDays' => 'required|array',
            'templates' => 'required|array',
        ]);

        // Сохраняем данные в JSON формате в таблице specialist_profiles
        $specialist->update([
            'work_calendar' => json_encode([
                'workingDays' => $validated['workingDays'],
                'templates' => $validated['templates'],
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Календарь успешно сохранён'
        ]);
    }
}

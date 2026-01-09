<x-home-layout :currentDate="$currentDate" :noPadding="true">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('calendar.day', ['date' => $currentDate->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                
                <div class="text-center">
                    @php
                        $startOfWeek = $currentDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                        $endOfWeek = $currentDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                    @endphp
                    <div class="text-lg font-semibold text-gray-800">
                        {{ $startOfWeek->translatedFormat('d F') }} — {{ $endOfWeek->translatedFormat('d F Y') }}
                    </div>
                </div>
                
                <a href="{{ route('calendar.day', ['date' => $currentDate->copy()->addWeek()->format('Y-m-d')]) }}" 
                   class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                
                <a href="{{ route('calendar.day', ['date' => now()->format('Y-m-d')]) }}" 
                   class="ml-2 px-3 py-1.5 text-sm border rounded hover:bg-gray-50">
                    Сегодня
                </a>
                
                <!-- Легенда -->
                @if(auth()->user()->isSpecialist() && $workCalendar)
                    <div class="ml-6 flex items-center gap-4 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-white border border-gray-300 rounded"></div>
                            <span class="text-gray-600">Рабочее время</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded"></div>
                            <span class="text-gray-600">Нерабочее время</span>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="flex items-center gap-3">
                <button class="px-3 py-1.5 text-sm border rounded hover:bg-gray-50">Продать</button>
                <select class="px-3 py-1.5 text-sm border rounded" onchange="window.location.href=this.value">
                    <option value="{{ route('calendar.single-day', ['date' => $currentDate->format('Y-m-d')]) }}">День</option>
                    <option value="{{ route('calendar.day', ['date' => $currentDate->format('Y-m-d')]) }}" selected>Неделя</option>
                </select>
                <button class="px-3 py-1.5 text-sm border rounded hover:bg-gray-50">Неделя</button>
                <button class="px-3 py-1.5 text-sm border rounded hover:bg-gray-50">Приют</button>
                <button class="p-1.5 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <button class="p-1.5 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-8rem)] flex flex-col bg-white"
         x-data="{ 
             draggedSession: null,
             updateSession(sessionId, newDate, newTime) {
                 fetch(`/lk/sessions/${sessionId}/move`, {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                     },
                     body: JSON.stringify({ date: newDate, time: newTime })
                 })
                 .then(response => response.json())
                 .then(data => {
                     if (data.success) {
                         window.location.reload();
                     }
                 });
             }
         }">
        <!-- Календарная сетка -->
        <div class="flex-1 overflow-auto">
            <div class="min-w-max">
                <!-- Заголовки дней недели -->
                <div class="flex bg-white border-b sticky top-0 z-10">
                    <div class="w-20 flex-shrink-0 border-r"></div>
                    <div class="flex-1 grid grid-cols-7">
                        @php
                            $startOfWeek = $currentDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                        @endphp
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $day = $startOfWeek->copy()->addDays($i);
                                $isToday = $day->isToday();
                                $dayKey = $day->format('Y-m-d');
                            @endphp
                            <div class="text-center py-3 border-r {{ $isToday ? 'bg-blue-50' : '' }}">
                                <div class="text-xs text-gray-500 uppercase">{{ $day->translatedFormat('D') }}</div>
                                <div class="text-lg font-medium {{ $isToday ? 'text-blue-600' : 'text-gray-900' }}">
                                    {{ $day->day }} {{ $day->translatedFormat('M') }}
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Временные слоты -->
                @for($hour = 9; $hour <= 20; $hour++)
                    <div class="flex border-b" style="height: 80px;">
                        <!-- Колонка времени -->
                        <div class="w-20 flex-shrink-0 border-r bg-gray-50 px-2 py-1 text-right">
                            <span class="text-xs text-gray-500">{{ sprintf('%02d', $hour) }}<sup>00</sup></span>
                            <div class="text-xs text-gray-400 mt-4">30</div>
                        </div>
                        
                        <!-- Колонки дней -->
                        <div class="flex-1 grid grid-cols-7">
                            @for($i = 0; $i < 7; $i++)
                                @php
                                    $day = $startOfWeek->copy()->addDays($i);
                                    $dayKey = $day->format('Y-m-d');
                                    $isToday = $day->isToday();
                                    
                                    // Получаем расписание для этого конкретного дня из work_calendar
                                    $isWorkingHour = false;
                                    $isBreakHour = false;
                                    if ($workCalendar && isset($workCalendar['workingDays'][$dayKey])) {
                                        $daySchedule = $workCalendar['workingDays'][$dayKey];
                                        $workStart = (int) substr($daySchedule['start'], 0, 2);
                                        $workEnd = (int) substr($daySchedule['end'], 0, 2);
                                        $isWorkingHour = $hour >= $workStart && $hour < $workEnd;
                                        
                                        if (isset($daySchedule['hasBreak']) && $daySchedule['hasBreak'] && 
                                            isset($daySchedule['breakStart']) && isset($daySchedule['breakEnd'])) {
                                            $breakStart = (int) substr($daySchedule['breakStart'], 0, 2);
                                            $breakEnd = (int) substr($daySchedule['breakEnd'], 0, 2);
                                            $isBreakHour = $hour >= $breakStart && $hour < $breakEnd;
                                        }
                                    }
                                    
                                    // Получаем занятия для этого дня и часа
                                    $daySessions = $sessions->filter(function($session) use ($dayKey) {
                                        return $session->start_time->format('Y-m-d') === $dayKey;
                                    });
                                    
                                    $hourStart = $day->copy()->setTime($hour, 0);
                                    $hourEnd = $day->copy()->setTime($hour, 59);
                                    $hourSessions = $daySessions->filter(function($session) use ($hourStart, $hourEnd) {
                                        return $session->start_time >= $hourStart && $session->start_time <= $hourEnd;
                                    });
                                    
                                    // Определяем цвет фона
                                    $bgClass = 'bg-gray-100'; // По умолчанию нерабочее время
                                    if ($isWorkingHour && !$isBreakHour) {
                                        $bgClass = $isToday ? 'bg-blue-50/30' : 'bg-white'; // Рабочее время белое
                                    }
                                @endphp
                                <div class="border-r relative {{ $bgClass }} hover:bg-gray-50 cursor-pointer group"
                                     @dragover.prevent="$el.classList.add('bg-indigo-50')"
                                     @dragleave="$el.classList.remove('bg-indigo-50')"
                                     @drop.prevent="
                                         $el.classList.remove('bg-indigo-50');
                                         if (draggedSession) {
                                             updateSession(draggedSession, '{{ $day->format('Y-m-d') }}', '{{ sprintf('%02d:00', $hour) }}');
                                         }
                                     ">
                                    <!-- Линия половины часа -->
                                    <div class="absolute top-1/2 left-0 right-0 border-t border-gray-100"></div>
                                    
                                    <!-- Занятия -->
                                    @foreach($hourSessions as $session)
                                        <div draggable="true"
                                             @dragstart="draggedSession = {{ $session->id }}; $el.classList.add('opacity-50')"
                                             @dragend="draggedSession = null; $el.classList.remove('opacity-50')"
                                             class="absolute left-2 right-2 top-1 bg-teal-500 rounded px-3 py-1 text-xs text-white hover:bg-teal-600 transition-colors z-10 cursor-move"
                                             style="height: calc({{ $session->duration_minutes }}px * 80 / 60);">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="font-medium">{{ $session->start_time->format('H:i') }} новая запись</div>
                                                    <div class="text-xs opacity-90 mt-1">{{ $session->child->name ?? 'Клиент' }}</div>
                                                </div>
                                                <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <!-- Кнопка добавления при наведении (только в рабочие часы) -->
                                    @if((auth()->user()->isOrganization() || auth()->user()->isSpecialist()) && $isWorkingHour && !$isBreakHour)
                                        <a href="{{ route('sessions.create', ['date' => $day->format('Y-m-d'), 'time' => sprintf('%02d:00', $hour)]) }}" 
                                           class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-gray-400 text-2xl">+</span>
                                        </a>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</x-home-layout>

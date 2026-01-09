<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Карточка ребёнка
            </h2>
            <div class="flex items-center gap-3">
                @can('update', $child)
                    <a href="{{ route('children.edit', $child) }}" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Редактировать
                    </a>
                @endcan
                <a href="{{ route('children.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Назад к списку
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Левая колонка - Основная информация -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Основная информация -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $child->full_name }}</h3>
                                <p class="text-gray-600 mt-1">{{ $child->age }} лет ({{ $child->birth_date->format('d.m.Y') }})</p>
                            </div>
                        </div>
                        <!-- Статистика -->
                        <div class="flex gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_sessions'] }}</div>
                                <div class="text-xs text-gray-500">Занятий</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $stats['completed_sessions'] }}</div>
                                <div class="text-xs text-gray-500">Завершено</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $stats['active_homeworks'] }}</div>
                                <div class="text-xs text-gray-500">Заданий</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Родитель -->
                        @if($child->parent)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Родитель</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $child->parent->full_name }}</p>
                                @if($child->parent->phone)
                                    <p class="text-sm text-gray-600 mt-1">{{ $child->parent->phone }}</p>
                                @endif
                            </div>
                        @endif

                        <!-- Специалисты -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Специалисты</span>
                                </div>
                            </div>
                            
                            <!-- Текущий специалист -->
                            <div class="mb-4 p-3 bg-white rounded-lg border-2 border-indigo-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-indigo-600 font-medium mb-1">Текущий специалист</p>
                                        @if($child->specialist)
                                            <p class="text-base font-semibold text-gray-900">{{ $child->specialist->full_name }}</p>
                                            @if($child->specialist->specialization)
                                                <p class="text-sm text-gray-600 mt-1">{{ $child->specialist->specialization }}</p>
                                            @endif
                                        @else
                                            <p class="text-base font-semibold text-gray-400">Не назначен</p>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                                        Активен
                                    </span>
                                </div>
                            </div>

                            <!-- История специалистов -->
                            @if($child->specialistHistory && $child->specialistHistory->count() > 0)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 font-medium mb-2">История смены специалистов:</p>
                                    <div class="space-y-2">
                                        @foreach($child->specialistHistory as $history)
                                            <div class="p-3 bg-gray-100 rounded-lg text-sm">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <p class="font-medium text-gray-900">{{ $history->specialist->full_name }}</p>
                                                        @if($history->specialist->specialization)
                                                            <p class="text-xs text-gray-600">{{ $history->specialist->specialization }}</p>
                                                        @endif
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ $history->started_at->format('d.m.Y') }} 
                                                            @if($history->ended_at)
                                                                — {{ $history->ended_at->format('d.m.Y') }}
                                                            @else
                                                                — настоящее время
                                                            @endif
                                                        </p>
                                                        @if($history->notes)
                                                            <p class="text-xs text-gray-600 mt-1 italic">{{ $history->notes }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($child->diagnosis)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Диагноз</h4>
                            <p class="text-gray-700">{{ $child->diagnosis }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Заключения специалиста -->
            @if(auth()->user()->isSpecialist() || auth()->user()->isOrganization())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-white p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Заключения специалиста</h3>
                            </div>
                            @if(auth()->user()->isSpecialist())
                                <button @click="showAddForm = !showAddForm" type="button" x-data="{ showAddForm: false }"
                                    class="text-sm text-indigo-600 hover:text-indigo-900">
                                    + Добавить запись
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Форма добавления новой записи -->
                        @if(auth()->user()->isSpecialist())
                            <div x-data="{ showAddForm: false }" class="mb-6">
                                <div x-show="!showAddForm">
                                    <button @click="showAddForm = true" type="button"
                                        class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-green-400 hover:text-green-600 transition-colors">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Добавить новую запись
                                    </button>
                                </div>
                                
                                <form x-show="showAddForm" x-cloak method="POST" action="{{ route('children.conclusions.store', $child) }}" 
                                    enctype="multipart/form-data" class="border border-green-200 rounded-lg p-4 bg-green-50">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Текст заключения *</label>
                                            <textarea name="content" rows="6" required
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                placeholder="Опишите результаты работы, рекомендации, динамику развития..."></textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Прикрепить файлы</label>
                                            <input type="file" name="attachments[]" multiple
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200">
                                            <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG (макс. 10 МБ каждый)</p>
                                        </div>
                                        
                                        <div class="flex gap-3">
                                            <button type="submit"
                                                class="px-4 py-2 bg-yellow-400 text-gray-900 rounded-md hover:bg-yellow-500 font-medium">
                                                Сохранить запись
                                            </button>
                                            <button type="button" @click="showAddForm = false"
                                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                                Отмена
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Список заключений -->
                        @if($child->conclusions && $child->conclusions->count() > 0)
                            <div class="space-y-4">
                                @foreach($child->conclusions as $conclusion)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $conclusion->specialist->full_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $conclusion->created_at->format('d.m.Y в H:i') }}</p>
                                                </div>
                                            </div>
                                            @if(auth()->user()->isSpecialist() && auth()->user()->specialistProfile->id === $conclusion->specialist_id)
                                                <form method="POST" action="{{ route('children.conclusions.destroy', [$child, $conclusion]) }}" 
                                                    onsubmit="return confirm('Удалить эту запись?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        
                                        <div class="text-gray-700 whitespace-pre-wrap mb-3">{{ $conclusion->content }}</div>
                                        
                                        @if($conclusion->attachments && count($conclusion->attachments) > 0)
                                            <div class="border-t border-gray-200 pt-3">
                                                <p class="text-xs font-medium text-gray-500 mb-2">Прикрепленные файлы:</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($conclusion->attachments as $attachment)
                                                        <a href="{{ Storage::url($attachment) }}" target="_blank"
                                                            class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                            {{ basename($attachment) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-gray-400 italic">Заключений пока нет</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- История занятий -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">История занятий</h3>
                        </div>
                        <a href="{{ route('sessions.create', ['child_id' => $child->id]) }}" 
                            class="text-sm text-indigo-600 hover:text-indigo-900">
                            + Добавить занятие
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($pastSessions->count() > 0)
                        <div class="space-y-4">
                            @foreach($pastSessions as $session)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="text-sm font-semibold text-gray-900">
                                                    {{ $session->start_time->format('d.m.Y H:i') }}
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($session->status === 'done') bg-green-100 text-green-800
                                                    @elseif($session->status === 'confirmed') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    @if($session->status === 'done') Завершено
                                                    @elseif($session->status === 'confirmed') Подтверждено
                                                    @else {{ $session->status }}
                                                    @endif
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                Специалист: {{ $session->specialist->full_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Длительность: {{ $session->duration_minutes }} мин
                                            </p>
                                            @if($session->specialist_notes)
                                                <p class="text-sm text-gray-700 mt-2">
                                                    {{ Str::limit($session->specialist_notes, 100) }}
                                                </p>
                                            @endif
                                            @if($session->report)
                                                <div class="mt-2 flex items-center gap-1">
                                                    <span class="text-xs text-gray-500">Оценка:</span>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-4 w-4 {{ $i <= $session->report->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('sessions.show', $session) }}" 
                                            class="text-sm text-indigo-600 hover:text-indigo-900">
                                            Подробнее →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($child->therapySessions()->where('start_time', '<', now())->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('sessions.index', ['child_id' => $child->id]) }}" 
                                    class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Показать все занятия →
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-center text-gray-400 italic py-8">История занятий пуста</p>
                    @endif
                </div>
            </div>

            <!-- Анамнез и цели -->
            @if($child->anamnesis || $child->goals)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($child->anamnesis)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="border-b border-gray-200 p-4 bg-gray-50">
                                <h3 class="text-base font-semibold text-gray-900">Анамнез</h3>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $child->anamnesis }}</p>
                            </div>
                        </div>
                    @endif

                    @if($child->goals)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="border-b border-gray-200 p-4 bg-gray-50">
                                <h3 class="text-base font-semibold text-gray-900">Цели занятий</h3>
                            </div>
                            <div class="p-4">
                                @if(is_array($child->goals) && count($child->goals) > 0)
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($child->goals as $goal)
                                            <li class="text-sm text-gray-700">{{ $goal }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-400 italic">Цели не указаны</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Правая колонка - Дополнительная информация -->
        <div class="space-y-6">
            <!-- Предстоящие занятия -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-base font-semibold text-gray-900">Предстоящие занятия</h3>
                </div>
                <div class="p-4">
                    @if($upcomingSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingSessions as $session)
                                <div class="border-l-4 border-blue-500 pl-3 py-2">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $session->start_time->format('d.m.Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $session->specialist->full_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $session->duration_minutes }} мин
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic text-center py-4">Нет предстоящих занятий</p>
                    @endif
                </div>
            </div>

            <!-- Домашние задания -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-orange-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Домашние задания</h3>
                        @if(auth()->user()->isSpecialist())
                            <a href="{{ route('homeworks.create', ['child_id' => $child->id]) }}" 
                                class="text-sm text-indigo-600 hover:text-indigo-900">
                                + Добавить
                            </a>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    @if($child->homeworks->count() > 0)
                        <div class="space-y-3">
                            @foreach($child->homeworks->take(5) as $homework)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-sm text-gray-900">{{ $homework->title }}</h4>
                                            @if($homework->description)
                                                <p class="mt-1 text-xs text-gray-600">{{ Str::limit($homework->description, 60) }}</p>
                                            @endif
                                        </div>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full
                                            @if($homework->status === 'active') bg-orange-100 text-orange-800
                                            @elseif($homework->status === 'done_by_parent') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($homework->status === 'active') Активно
                                            @elseif($homework->status === 'done_by_parent') Выполнено
                                            @else Проверено
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($child->homeworks->count() > 5)
                            <div class="mt-3 text-center">
                                <a href="{{ route('homeworks.index', ['child_id' => $child->id]) }}" 
                                    class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Показать все →
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-gray-400 italic text-center py-4">Нет домашних заданий</p>
                    @endif
                </div>
            </div>

            <!-- Быстрые действия -->
            @if(auth()->user()->isSpecialist() || auth()->user()->isOrganization())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 p-4">
                        <h3 class="text-base font-semibold text-gray-900">Быстрые действия</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('sessions.create', ['child_id' => $child->id]) }}" 
                            class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Создать занятие</span>
                        </a>
                        <a href="{{ route('homeworks.create', ['child_id' => $child->id]) }}" 
                            class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Добавить домашнее задание</span>
                        </a>
                        <a href="{{ route('children.edit', $child) }}" 
                            class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Редактировать карточку</span>
                        </a>
                        @if($child->parent)
                            <a href="{{ route('parents.show', $child->parent->user_id) }}" 
                                class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Профиль родителя</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Теги -->
            @if($child->tags && count($child->tags) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 p-4">
                        <h3 class="text-base font-semibold text-gray-900">Теги</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($child->tags as $tag)
                                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Модальное окно выбора специалиста -->
    @if($specialists && $specialists->count() > 0 && !$child->specialist_id)
        <div id="specialist-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Выбрать специалиста</h3>
                    <button onclick="document.getElementById('specialist-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('children.assign-specialist', $child) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="specialist_id" class="block text-sm font-medium text-gray-700 mb-2">Специалист</label>
                        <select name="specialist_id" id="specialist_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Выберите специалиста</option>
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}">
                                    {{ $specialist->full_name }}
                                    @if($specialist->specialization)
                                        - {{ $specialist->specialization }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('specialist-modal').classList.add('hidden')" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150">
                            Отмена
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                            Назначить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-home-layout>

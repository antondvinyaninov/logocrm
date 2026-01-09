<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Занятие {{ $session->start_time->format('d.m.Y H:i') }}
            </h2>
            <div class="flex items-center gap-3">
                @can('update', $session)
                    <a href="{{ route('sessions.edit', $session) }}" 
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Редактировать
                    </a>
                @endcan
                <a href="{{ route('sessions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Назад к списку
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Левая колонка - Основная информация -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Основная информация -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $session->start_time->format('d.m.Y в H:i') }}
                            </h3>
                            <p class="mt-1 text-gray-600">Длительность: {{ $session->duration_minutes }} минут</p>
                        </div>
                        <span class="rounded-full px-4 py-2 text-sm font-semibold
                            @if($session->status === 'planned') bg-blue-100 text-blue-800
                            @elseif($session->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($session->status === 'done') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($session->status === 'planned') Ожидание
                            @elseif($session->status === 'confirmed') Пришел
                            @elseif($session->status === 'done') Подтвержден
                            @else Не пришел
                            @endif
                        </span>
                    </div>

                    @if(auth()->user()->isSpecialist() || auth()->user()->isOrganization())
                        <!-- Быстрое изменение статуса -->
                        <form method="POST" action="{{ route('sessions.update-status', $session) }}" 
                            x-data="{ status: '{{ $session->status }}' }"
                            @change="$el.submit()">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="planned" 
                                        x-model="status"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Ожидание</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="confirmed" 
                                        x-model="status"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Пришел</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="cancelled" 
                                        x-model="status"
                                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Не пришел</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="done" 
                                        x-model="status"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Подтвержден</span>
                                </label>
                            </div>
                        </form>
                    @endif
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Ребёнок -->
                        <div>
                            <h4 class="mb-3 text-sm font-semibold text-gray-700">Ребёнок</h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-lg font-semibold text-gray-900">{{ $session->child->full_name }}</p>
                                <p class="text-sm text-gray-600">
                                    Возраст: {{ $session->child->age }} лет
                                </p>
                                @if($session->child->diagnosis)
                                    <p class="mt-2 text-sm text-gray-600">
                                        Диагноз: {{ $session->child->diagnosis }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Специалист -->
                        <div>
                            <h4 class="mb-3 text-sm font-semibold text-gray-700">Специалист</h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-lg font-semibold text-gray-900">{{ $session->specialist->full_name }}</p>
                                @if($session->specialist->specialization)
                                    <p class="text-sm text-gray-600">{{ $session->specialist->specialization }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <span class="rounded-full px-4 py-2 text-sm font-medium bg-gray-100 text-gray-800">
                            {{ $session->type === 'individual' ? 'Индивидуальное' : 'Групповое' }}
                        </span>
                        <span class="rounded-full px-4 py-2 text-sm font-medium
                            @if($session->format === 'online') bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ $session->format === 'online' ? 'Онлайн' : 'Офлайн' }}
                        </span>
                    </div>

                    @if($session->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="mb-2 text-sm font-semibold text-gray-700">Комментарий к записи</h4>
                            <p class="text-gray-600">{{ $session->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Результаты занятия / Отчёт -->
            @if($session->report)
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-white p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Результаты занятия</h3>
                            </div>
                            @can('update', $session->report)
                                <a href="{{ route('session-reports.edit', $session->report) }}" 
                                    class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Редактировать
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="p-6">
                        @if($session->report->goals_json && count($session->report->goals_json) > 0)
                            <div class="mb-4">
                                <h4 class="mb-2 font-semibold text-gray-900">Цели занятия:</h4>
                                <ul class="list-inside list-disc space-y-1">
                                    @foreach($session->report->goals_json as $goal)
                                        <li class="text-gray-700">{{ $goal }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-900">Комментарий:</h4>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $session->report->comment }}</p>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->isSpecialist() || auth()->user()->isOrganization())
                <!-- Быстрая форма добавления результатов -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg" 
                    x-data="{ 
                        showForm: false,
                        goals: [''],
                        addGoal() {
                            this.goals.push('');
                        },
                        removeGoal(index) {
                            this.goals.splice(index, 1);
                        }
                    }">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Результаты занятия</h3>
                            </div>
                            <button @click="showForm = !showForm" type="button"
                                class="text-sm text-indigo-600 hover:text-indigo-900">
                                <span x-show="!showForm">+ Добавить результаты</span>
                                <span x-show="showForm">Отмена</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Сообщение когда нет результатов -->
                        <div x-show="!showForm" class="text-center py-8">
                            @if($errors->any())
                                <div class="mb-4 rounded-lg bg-red-50 p-4 text-left">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Ошибка при сохранении</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-400 italic">Результаты занятия пока не добавлены</p>
                            <button @click="showForm = true" type="button"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-yellow-400 text-gray-900 rounded-md hover:bg-yellow-500 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Добавить результаты
                            </button>
                        </div>

                        <!-- Форма добавления результатов -->
                        <form x-show="showForm" x-cloak method="POST" action="{{ route('session-reports.store', $session) }}" class="space-y-6">
                            @csrf

                            <!-- Цели занятия -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Цели занятия</label>
                                    <button type="button" @click="addGoal()" 
                                        class="text-sm text-indigo-600 hover:text-indigo-900">
                                        + Добавить цель
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(goal, index) in goals" :key="index">
                                        <div class="flex gap-2">
                                            <input type="text" :name="'goals[]'" x-model="goals[index]"
                                                placeholder="Цель занятия"
                                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="button" @click="removeGoal(index)" x-show="goals.length > 1"
                                                class="px-3 py-2 text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Комментарий -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Комментарий к занятию *</label>
                                <textarea name="comment" rows="6" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Опишите как прошло занятие, что удалось, над чем нужно поработать..."></textarea>
                            </div>

                            <!-- Кнопки -->
                            <div class="flex gap-3">
                                <button type="submit"
                                    class="px-6 py-2 bg-yellow-400 text-gray-900 rounded-md hover:bg-yellow-500 font-medium">
                                    Сохранить результаты
                                </button>
                                <button type="button" @click="showForm = false"
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    Отмена
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Правая колонка - Дополнительная информация -->
        <div class="space-y-6">
            <!-- Игры и материалы -->
            @if(auth()->user()->isSpecialist() || auth()->user()->isOrganization())
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg" 
                    x-data="{ 
                        editing: false, 
                        games: {{ json_encode($session->games_used ?? []) }},
                        newGame: '',
                        addGame() {
                            if (this.newGame.trim()) {
                                this.games.push(this.newGame.trim());
                                this.newGame = '';
                            }
                        },
                        removeGame(index) {
                            this.games.splice(index, 1);
                        }
                    }">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-base font-semibold text-gray-900">Игры и материалы</h3>
                            </div>
                            <button @click="editing = !editing" type="button"
                                class="text-sm text-indigo-600 hover:text-indigo-900">
                                <span x-show="!editing">Редактировать</span>
                                <span x-show="editing">Отмена</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <div x-show="!editing">
                            <template x-if="games.length > 0">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(game, index) in games" :key="index">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <span x-text="game"></span>
                                        </span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="games.length === 0">
                                <p class="text-sm text-gray-400 italic text-center py-4">Игры и материалы пока не добавлены</p>
                            </template>
                        </div>
                        <form x-show="editing" method="POST" action="{{ route('sessions.update-games', $session) }}" style="display: none;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="games_used" :value="JSON.stringify(games)">
                            
                            <div class="mb-3">
                                <div class="flex gap-2">
                                    <input type="text" x-model="newGame" @keydown.enter.prevent="addGame()"
                                        placeholder="Название игры или материала"
                                        class="flex-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" @click="addGame()"
                                        class="px-3 py-2 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700">
                                        +
                                    </button>
                                </div>
                            </div>

                            <template x-if="games.length > 0">
                                <div class="mb-3 space-y-2">
                                    <template x-for="(game, index) in games" :key="index">
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                                            <span x-text="game" class="text-sm text-gray-700"></span>
                                            <button type="button" @click="removeGame(index)"
                                                class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="px-3 py-2 bg-yellow-400 text-gray-900 text-sm rounded-md hover:bg-yellow-500 font-medium">
                                    Сохранить
                                </button>
                                <button type="button" @click="editing = false"
                                    class="px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                                    Отмена
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Домашние задания -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Домашние задания</h3>
                        @if(auth()->user()->isSpecialist())
                            <a href="{{ route('homeworks.create', ['session_id' => $session->id]) }}" 
                                class="text-sm text-indigo-600 hover:text-indigo-900">
                                + Добавить
                            </a>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    @if($session->homeworks->count() > 0)
                        <div class="space-y-3">
                            @foreach($session->homeworks as $homework)
                                <div class="rounded-lg border border-gray-200 p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-sm text-gray-900">{{ $homework->title }}</h4>
                                            @if($homework->description)
                                                <p class="mt-1 text-xs text-gray-600">{{ Str::limit($homework->description, 60) }}</p>
                                            @endif
                                        </div>
                                        <span class="ml-2 rounded-full px-2 py-1 text-xs font-semibold
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
                    @else
                        <p class="text-sm text-gray-400 italic text-center py-4">Нет домашних заданий</p>
                    @endif
                </div>
            </div>

            <!-- Быстрые действия -->
            @if(auth()->user()->isSpecialist() || auth()->user()->isOrganization())
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 p-4">
                        <h3 class="text-base font-semibold text-gray-900">Быстрые действия</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @if(!$session->report && $session->status === 'done')
                            <a href="{{ route('session-reports.create', $session) }}" 
                                class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Создать отчёт</span>
                            </a>
                        @endif
                        <a href="{{ route('homeworks.create', ['session_id' => $session->id]) }}" 
                            class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Добавить домашнее задание</span>
                        </a>
                        <a href="{{ route('children.show', $session->child) }}" 
                            class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Профиль ребёнка</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-home-layout>

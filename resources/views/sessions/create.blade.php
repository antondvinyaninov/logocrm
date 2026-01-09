<x-home-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Занятия
        </h2>
    </x-slot>

    <!-- Модальное окно создания занятия -->
    <div x-data="{ 
        open: true,
        activeTab: 'services',
        selectedService: null,
        selectedChild: null,
        services: {{ json_encode($services ?? []) }},
        children: {{ json_encode($childrenData ?? []) }},
        searchService: '',
        get filteredServices() {
            if (!this.searchService) return this.services;
            return this.services.filter(s => 
                s.name.toLowerCase().includes(this.searchService.toLowerCase())
            );
        },
        selectService(service) {
            this.selectedService = service;
            document.getElementById('service_id').value = service.id;
            document.getElementById('duration_minutes').value = service.duration_minutes;
        },
        selectChild(childId) {
            this.selectedChild = this.children.find(c => c.id == childId);
        }
    }" 
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        <!-- Modal -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div @click.away="open = false" 
                class="relative w-full max-w-6xl bg-white rounded-lg shadow-xl">
                
                <!-- Close button -->
                <button @click="window.location.href='{{ route('sessions.index') }}'" 
                    class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <form method="POST" action="{{ route('sessions.store') }}" class="flex h-[85vh]">
                    @csrf
                    
                    <!-- Левая панель - дополнительные настройки -->
                    <div class="w-64 border-r border-gray-200 bg-white overflow-y-auto">
                        <div class="p-4 space-y-4">
                            <!-- Сотрудник -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Сотрудник</label>
                                @if(auth()->user()->isOrganization())
                                    <select name="specialist_id" required class="w-full text-sm border-gray-300 rounded-md">
                                        <option value="">Выберите</option>
                                        @foreach($specialists as $specialist)
                                            <option value="{{ $specialist->id }}">{{ $specialist->full_name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="specialist_id" value="{{ auth()->user()->specialistProfile->id }}">
                                    <div class="text-sm font-medium bg-gray-50 p-2 rounded">{{ auth()->user()->specialistProfile->full_name }}</div>
                                @endif
                            </div>

                            <!-- Дата -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Дата</label>
                                <input type="date" name="session_date" required
                                    value="{{ old('session_date', request('date', now()->format('Y-m-d'))) }}"
                                    class="w-full text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Время и длительность -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Время и Длительность записи</label>
                                <div class="space-y-2">
                                    <div class="flex gap-2 items-center">
                                        <input type="time" name="session_time" required value="{{ old('session_time', '10:00') }}"
                                            class="flex-1 text-sm border-gray-300 rounded-md">
                                        <span class="text-gray-400">-</span>
                                        <input type="time" readonly class="flex-1 text-sm border-gray-300 rounded-md bg-gray-50">
                                    </div>
                                    <select onchange="document.getElementById('duration_minutes').value = this.value" 
                                        class="w-full text-sm border-gray-300 rounded-md">
                                        <option value="30">30 мин</option>
                                        <option value="45" selected>45 мин</option>
                                        <option value="60">1 ч</option>
                                        <option value="90">1 ч 30 мин</option>
                                    </select>
                                    <input type="hidden" name="duration_minutes" id="duration_minutes" value="45">
                                </div>
                            </div>

                            <!-- Технический перерыв -->
                            <div>
                                <button type="button" class="w-full text-left text-sm text-gray-600 hover:text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Добавить перерыв
                                </button>
                            </div>

                            <!-- Закрепленные поля -->
                            <div class="pt-4 border-t border-gray-200">
                                <button type="button" class="w-full text-left text-sm text-gray-600 hover:text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    Закрепленные поля
                                </button>
                            </div>

                            <!-- Комментарий к записи -->
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Комментарий к записи</label>
                                <textarea name="notes" rows="3" 
                                    class="w-full text-sm border-gray-300 rounded-md resize-none"
                                    placeholder="Дополнительная информация...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Расширенные поля -->
                            <div class="pt-4 border-t border-gray-200">
                                <button type="button" class="w-full text-center text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Расширенные поля
                                </button>
                            </div>

                            <!-- Повторяющиеся записи -->
                            <div class="pt-4 border-t border-gray-200">
                                <button type="button" class="w-full text-center text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Повторяющиеся записи
                                </button>
                            </div>

                            <!-- Уведомления о визите -->
                            <div class="pt-4 border-t border-gray-200">
                                <button type="button" class="w-full text-center text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    Уведомления о визите
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Центральная часть - основная форма -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Header с статусами -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200">
                                    <input type="radio" name="status" value="planned" checked class="text-indigo-600">
                                    <span class="text-sm font-medium">Ожидание</span>
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200">
                                    <input type="radio" name="status" value="confirmed" class="text-green-600">
                                    <span class="text-sm font-medium">Пришел</span>
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200">
                                    <input type="radio" name="status" value="cancelled" class="text-red-600">
                                    <span class="text-sm font-medium">Не пришел</span>
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200">
                                    <input type="radio" name="status" value="done" class="text-blue-600">
                                    <span class="text-sm font-medium">Подтвержден</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="border-b border-gray-200 px-6">
                            <div class="flex gap-4">
                                <button type="button" @click="activeTab = 'services'" 
                                    :class="activeTab === 'services' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'"
                                    class="py-3 px-1 border-b-2 font-medium text-sm">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Услуги
                                </button>
                                <button type="button" @click="activeTab = 'goods'" 
                                    :class="activeTab === 'goods' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'"
                                    class="py-3 px-1 border-b-2 font-medium text-sm">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Товары
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <!-- Services Tab -->
                            <div x-show="activeTab === 'services'">
                                <input type="text" x-model="searchService" placeholder="Поиск по услугам"
                                    class="w-full mb-4 text-sm border-gray-300 rounded-md">
                                
                                <input type="hidden" name="service_id" id="service_id">
                                <input type="hidden" name="type" value="individual">
                                <input type="hidden" name="format" value="offline">

                                <div class="grid grid-cols-2 gap-4">
                                    <template x-for="service in filteredServices" :key="service.id">
                                        <div @click="selectService(service)" 
                                            :class="selectedService?.id === service.id ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'"
                                            class="border-2 rounded-lg p-4 cursor-pointer hover:border-indigo-400 transition">
                                            <div class="font-medium text-sm mb-1" x-text="service.name"></div>
                                            <div class="text-xs text-gray-500 mb-2">
                                                <span x-text="service.price"></span> ₽
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                <span x-text="service.duration_minutes"></span> мин.
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                @if(isset($services) && $services->isEmpty())
                                    <div class="text-center py-12 text-gray-500">
                                        <p>Нет доступных услуг</p>
                                        <a href="{{ route('services.create') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                                            Создать услугу
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Goods Tab -->
                            <div x-show="activeTab === 'goods'" class="text-center py-12 text-gray-500">
                                <p>Раздел товаров в разработке</p>
                            </div>
                        </div>

                        <!-- Footer buttons -->
                        <div class="border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                <span x-show="selectedService" x-text="'Выбрано: ' + selectedService?.name"></span>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('sessions.index') }}" 
                                    class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                    Отмена
                                </a>
                                <button type="submit" 
                                    class="px-6 py-2 text-sm font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600">
                                    Сохранить запись
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Правая часть - информация о клиенте -->
                    <div class="w-80 border-l border-gray-200 bg-gray-50 overflow-y-auto">
                        <div class="p-6 space-y-6">
                            <!-- Выбор клиента -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Клиент *</label>
                                <select name="child_id" @change="selectChild($event.target.value)" required
                                    class="w-full text-sm border-gray-300 rounded-md">
                                    <option value="">Выберите клиента</option>
                                    @foreach($children as $child)
                                        <option value="{{ $child->id }}" {{ old('child_id', request('child_id')) == $child->id ? 'selected' : '' }}>
                                            {{ $child->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Информация о клиенте -->
                            <template x-if="selectedChild">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Имя</label>
                                        <div class="text-sm font-medium" x-text="selectedChild.first_name"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Фамилия</label>
                                        <div class="text-sm font-medium" x-text="selectedChild.last_name"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Отчество</label>
                                        <div class="text-sm" x-text="selectedChild.middle_name || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Телефон</label>
                                        <div class="text-sm" x-text="selectedChild.phone || '-'"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Email</label>
                                        <div class="text-sm" x-text="selectedChild.email || '-'"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Комментарий -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Дополнительные согласия</label>
                                <div class="space-y-2">
                                    <label class="flex items-start gap-2">
                                        <input type="checkbox" class="mt-1 rounded border-gray-300 text-indigo-600">
                                        <span class="text-sm text-gray-600">Записывает другого посетителя</span>
                                    </label>
                                    <label class="flex items-start gap-2">
                                        <input type="checkbox" class="mt-1 rounded border-gray-300 text-indigo-600">
                                        <span class="text-sm text-gray-600">Клиент дал согласие на обработку персональных данных</span>
                                    </label>
                                    <label class="flex items-start gap-2">
                                        <input type="checkbox" class="mt-1 rounded border-gray-300 text-indigo-600">
                                        <span class="text-sm text-gray-600">Клиент дал согласие на отправку информационно-рекламной рассылки</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Предыдущие клиенты -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Предыдущие клиенты</label>
                                <div class="space-y-2 text-sm text-gray-600">
                                    @if($children->count() > 0)
                                        @foreach($children->take(3) as $child)
                                            <div class="flex items-center justify-between py-2 hover:bg-gray-50 rounded px-2 cursor-pointer"
                                                @click="document.querySelector('select[name=child_id]').value = '{{ $child->id }}'; selectChild('{{ $child->id }}')">
                                                <span>{{ $child->full_name }}</span>
                                                <span class="text-gray-400">{{ $child->phone ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-gray-400 text-sm">Нет предыдущих клиентов</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-home-layout>

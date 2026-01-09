<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Профиль специалиста
            </h2>
            <a href="{{ route('specialists.show', $specialist->id) }}" 
                class="text-sm text-indigo-600 hover:text-indigo-900">
                Просмотр профиля →
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <div x-data="{ activeTab: 'profile' }">
        <!-- Вкладки -->
        <div class="mb-6 border-b border-gray-200 bg-white rounded-t-lg">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Профиль
                </button>
                <button @click="activeTab = 'services'" 
                        :class="activeTab === 'services' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Услуги
                </button>
                <button @click="activeTab = 'schedule'" 
                        :class="activeTab === 'schedule' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                    Расписание
                </button>
            </nav>
        </div>

        <!-- Вкладка "Профиль" -->
        <div x-show="activeTab === 'profile'" x-transition>
            <form method="POST" action="{{ route('specialists.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Левая колонка - 9/12 -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Основная информация -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Основная информация</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ФИО -->
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700">ФИО *</label>
                                <input type="text" name="full_name" id="full_name" required
                                    value="{{ old('full_name', $specialist->full_name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Специализация -->
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700">Специализация</label>
                                <input type="text" name="specialization" id="specialization"
                                    value="{{ old('specialization', $specialist->specialization) }}"
                                    placeholder="Например: Логопед-дефектолог"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('specialization')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Должность -->
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">Должность</label>
                                <input type="text" name="position" id="position"
                                    value="{{ old('position', $specialist->position) }}"
                                    placeholder="Например: Старший логопед"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-1 text-xs text-gray-500">Отображается в сайдбаре для сотрудников организации</p>
                                @error('position')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Опыт работы -->
                            <div>
                                <label for="experience_years" class="block text-sm font-medium text-gray-700">Опыт работы (лет)</label>
                                <input type="number" name="experience_years" id="experience_years" min="0" max="100"
                                    value="{{ old('experience_years', $specialist->experience_years) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('experience_years')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Стоимость занятия -->
                            <div>
                                <label for="price_per_session" class="block text-sm font-medium text-gray-700">Стоимость занятия (₽)</label>
                                <input type="number" name="price_per_session" id="price_per_session" min="0" step="0.01"
                                    value="{{ old('price_per_session', $specialist->price_per_session) }}"
                                    placeholder="3000"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('price_per_session')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Форматы работы -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Форматы работы</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="available_online" value="1" 
                                            {{ old('available_online', $specialist->available_online) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Онлайн занятия</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="available_offline" value="1" 
                                            {{ old('available_offline', $specialist->available_offline) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Офлайн занятия</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Профессиональная информация -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Профессиональная информация</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- О себе -->
                            <div>
                                <label for="about" class="block text-sm font-medium text-gray-700">О себе</label>
                                <textarea name="about" id="about" rows="5" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Расскажите о себе, своём подходе к работе, методиках...">{{ old('about', $specialist->about) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Эта информация будет видна в вашем публичном профиле</p>
                                @error('about')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Образование -->
                            <div>
                                <label for="education" class="block text-sm font-medium text-gray-700">Образование</label>
                                <textarea name="education" id="education" rows="5" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Укажите ваше образование, учебные заведения, специальности...">{{ old('education', $specialist->education) }}</textarea>
                                @error('education')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Сертификаты -->
                        <div class="mt-6" x-data="{ 
                            certificates: {{ json_encode(old('certificates', $specialist->certificates ?? [])) }},
                            addCertificate() {
                                this.certificates.push('');
                            },
                            removeCertificate(index) {
                                this.certificates.splice(index, 1);
                            }
                        }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Сертификаты и повышение квалификации</label>
                            <div class="space-y-2">
                                <template x-for="(certificate, index) in certificates" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" :name="'certificates[' + index + ']'" x-model="certificates[index]"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Название сертификата или курса">
                                        <button type="button" @click="removeCertificate(index)"
                                            class="rounded-md bg-red-100 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-200 whitespace-nowrap">
                                            Удалить
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="addCertificate"
                                class="mt-2 rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                                + Добавить сертификат
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка - 3/12 -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Фото профиля -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Фото</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            @if($specialist->photo)
                                <img src="{{ Storage::url($specialist->photo) }}" 
                                    alt="{{ $specialist->full_name }}"
                                    class="h-32 w-32 rounded-full object-cover mb-4">
                            @else
                                <div class="flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 mb-4">
                                    <span class="text-4xl font-bold text-white">
                                        {{ substr($specialist->full_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-2 text-xs text-center text-gray-500">JPG, PNG до 2MB</p>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Статистика -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Статистика</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1 mb-1">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($specialist->rating, 1) }}</span>
                            </div>
                            <p class="text-xs text-gray-500">Рейтинг</p>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="text-center mb-3">
                                <div class="text-2xl font-bold text-gray-900">{{ $specialist->reviews_count }}</div>
                                <p class="text-xs text-gray-500">Отзывов</p>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $specialist->children->count() }}</div>
                                <p class="text-xs text-gray-500">Клиентов</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Кнопки действий -->
                <div class="mt-6 flex items-center justify-end gap-4 bg-white px-6 py-4 shadow-sm sm:rounded-lg">
                    <a href="{{ route('home') }}" 
                        class="rounded-md bg-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                        Отмена
                    </a>
                    <button type="submit" 
                        class="rounded-md bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-gray-900 hover:bg-yellow-500">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>

        <!-- Вкладка "Услуги" -->
        <div x-show="activeTab === 'services'" x-transition>
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Мои услуги</h3>
                    <a href="{{ route('services.create') }}" 
                       class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        + Добавить услугу
                    </a>
                </div>
                
                @php
                    $services = \App\Models\Service::where('organization_id', auth()->user()->organization_id)
                        ->where('is_active', true)
                        ->get();
                @endphp
                
                @if($services->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Нет услуг</h3>
                        <p class="mt-1 text-sm text-gray-500">Начните с добавления первой услуги</p>
                        <div class="mt-6">
                            <a href="{{ route('services.create') }}" 
                               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                + Добавить услугу
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($services as $service)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="text-base font-semibold text-gray-900">{{ $service->name }}</h4>
                                    <a href="{{ route('services.edit', $service) }}" 
                                       class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                </div>
                                
                                @if($service->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($service->description, 100) }}</p>
                                @endif
                                
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Стоимость:</span>
                                        <span class="font-semibold text-gray-900">{{ number_format($service->price, 0, ',', ' ') }} ₽</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Длительность:</span>
                                        <span class="text-gray-900">{{ $service->duration_minutes }} мин</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Тип:</span>
                                        <span class="text-gray-900">
                                            @if($service->session_type === 'individual')
                                                Индивидуальное
                                            @else
                                                Групповое (до {{ $service->max_participants }} чел.)
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Формат:</span>
                                        <span class="text-gray-900">
                                            @if($service->format === 'online')
                                                Онлайн
                                            @elseif($service->format === 'offline')
                                                Офлайн
                                            @else
                                                Онлайн/Офлайн
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Вкладка "Расписание" -->
        <div x-show="activeTab === 'schedule'" x-transition 
             x-data="scheduleCalendar(
                 @js($specialist->work_calendar['workingDays'] ?? []),
                 @js($specialist->work_calendar['templates'] ?? null)
             )">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Календарь работы</h3>
                    <p class="text-sm text-gray-600">Отметьте дни, когда вы работаете. Кликните на день, чтобы переключить статус.</p>
                </div>

                <!-- Навигация по месяцам -->
                <div class="flex items-center justify-between mb-4">
                    <button @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <h4 class="text-lg font-medium capitalize" x-text="monthName"></h4>
                    
                    <button @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Шаблоны -->
                <div class="mb-4 p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-indigo-100">
                    <div class="text-xs text-gray-600 mb-2 font-medium">Шаблоны (применяются через меню ⋮ в календаре):</div>
                    <div class="flex flex-wrap gap-2">
                        <!-- Быстрое заполнение месяца -->
                        <button @click="applyTemplateToMonth('weekdays')" 
                                class="px-3 py-1.5 text-xs bg-white border border-gray-300 rounded hover:bg-indigo-50 hover:border-indigo-400 transition-colors">
                            Пн-Пт
                        </button>
                        <button @click="applyTemplateToMonth('weekends')" 
                                class="px-3 py-1.5 text-xs bg-white border border-gray-300 rounded hover:bg-indigo-50 hover:border-indigo-400 transition-colors">
                            Сб-Вс
                        </button>
                        <button @click="applyTemplateToMonth('all')" 
                                class="px-3 py-1.5 text-xs bg-white border border-gray-300 rounded hover:bg-indigo-50 hover:border-indigo-400 transition-colors">
                            Все дни
                        </button>
                        <button @click="applyTemplateToMonth('clear')" 
                                class="px-3 py-1.5 text-xs bg-white border border-red-300 text-red-600 rounded hover:bg-red-50 hover:border-red-400 transition-colors">
                            Очистить
                        </button>
                        <!-- Мои шаблоны дня -->
                        <template x-for="(template, index) in savedTemplates" :key="template.name">
                            <div class="px-3 py-1.5 text-xs bg-white border border-indigo-300 rounded shadow-sm flex items-center gap-2 group">
                                <div>
                                    <span class="font-medium text-indigo-700" x-text="template.name"></span>
                                    <span class="text-gray-500 ml-1">
                                        (<span x-text="template.start"></span>-<span x-text="template.end"></span>)
                                    </span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click.stop="editTemplate(index)" 
                                            class="text-gray-400 hover:text-indigo-600 transition-colors"
                                            title="Редактировать шаблон">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="savedTemplates.splice(index, 1)" 
                                            class="text-gray-400 hover:text-red-600 transition-colors"
                                            title="Удалить шаблон">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Календарь -->
                <div class="border rounded-lg overflow-hidden">
                    <!-- Заголовки дней недели -->
                    <div class="grid grid-cols-7 bg-gray-50 border-b">
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Пн</div>
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Вт</div>
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Ср</div>
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Чт</div>
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Пт</div>
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Сб</div>
                        <div class="text-center py-2 text-sm font-medium text-gray-600">Вс</div>
                    </div>
                    
                    <!-- Дни месяца -->
                    <div class="grid grid-cols-7">
                        <template x-for="(day, index) in getDaysInMonth()" :key="index">
                            <div class="border-r border-b p-2 pr-7 hover:bg-gray-50 transition-all relative"
                                 :style="isWorking(day) ? 'min-height: 100px;' : 'height: 64px;'"
                                 :class="{ 'bg-gray-50': !day }">
                                <div x-show="day" class="flex gap-2 w-full h-full" x-data="{ showMenu: false }">
                                    <!-- Три точки в правом верхнем углу -->
                                    <button @click.stop="showMenu = !showMenu" 
                                            class="absolute top-1 right-1 text-gray-800 hover:text-black bg-white hover:bg-gray-100 rounded shadow-sm border border-gray-200 p-1 z-30"
                                            style="font-size: 16px; line-height: 1; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                        ⋮
                                    </button>
                                    
                                    <!-- Выпадающее меню -->
                                    <div x-show="showMenu" 
                                         @click.away="showMenu = false"
                                         x-transition
                                         class="absolute top-6 right-1 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20 whitespace-nowrap"
                                         style="min-width: 150px;">
                                        <!-- Применить шаблон (для всех дней) -->
                                        <div x-show="savedTemplates.length > 0">
                                            <div class="px-3 py-1 text-xs text-gray-500 font-medium">Применить шаблон:</div>
                                            <template x-for="template in savedTemplates" :key="template.name">
                                                <button @click="applyTemplate(template, day); showMenu = false"
                                                        class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-50 flex items-center gap-2">
                                                    <span>📋</span>
                                                    <span x-text="template.name"></span>
                                                </button>
                                            </template>
                                        </div>
                                        
                                        <!-- Действия для рабочего дня -->
                                        <template x-if="isWorking(day)">
                                            <div :class="savedTemplates.length > 0 ? 'border-t border-gray-100' : ''">
                                                <button @click="toggleBreak(day); showMenu = false"
                                                        class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-50 flex items-center gap-2">
                                                    <span x-show="!getWorkTime(day)?.hasBreak">+ Добавить перерыв</span>
                                                    <span x-show="getWorkTime(day)?.hasBreak">✕ Убрать перерыв</span>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Левая часть: номер дня и кружок -->
                                    <div class="flex flex-col items-center gap-1 flex-shrink-0">
                                        <span class="text-sm text-gray-500 cursor-pointer" @click="toggleDay(day)" x-text="day"></span>
                                        
                                        <div class="cursor-pointer" @click="toggleDay(day)">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                                 :class="isWorking(day) ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300'">
                                                <svg x-show="isWorking(day)" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Правая часть: поля ввода времени -->
                                    <div x-show="isWorking(day)" x-transition class="flex-1 space-y-1" @click.stop>
                                        <!-- Рабочее время -->
                                        <input type="time" 
                                               x-model="getWorkTime(day).start"
                                               class="w-full text-xs border border-gray-300 rounded px-1 py-0.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                               style="font-size: 10px;">
                                        <input type="time" 
                                               x-model="getWorkTime(day).end"
                                               class="w-full text-xs border border-gray-300 rounded px-1 py-0.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                               style="font-size: 10px;">
                                        
                                        <!-- Поля перерыва -->
                                        <div x-show="getWorkTime(day)?.hasBreak" x-transition class="space-y-1 pt-1 border-t border-gray-200">
                                            <div class="text-xs text-gray-400 text-center" style="font-size: 9px;">Перерыв</div>
                                            <input type="time" 
                                                   x-model="getWorkTime(day).breakStart"
                                                   placeholder="--:--"
                                                   class="w-full text-xs border border-gray-300 rounded px-1 py-0.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                   style="font-size: 10px;">
                                            <input type="time" 
                                                   x-model="getWorkTime(day).breakEnd"
                                                   placeholder="--:--"
                                                   class="w-full text-xs border border-gray-300 rounded px-1 py-0.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                   style="font-size: 10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Легенда -->
                <div class="mt-4 flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full bg-indigo-600 border-2 border-indigo-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700">Рабочий день</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        <span class="text-gray-700">Выходной</span>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="mt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('home') }}" 
                        class="rounded-md bg-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                        Отмена
                    </a>
                    <button type="button" 
                            @click="saveCalendar()"
                            class="rounded-md bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-gray-900 hover:bg-yellow-500">
                        Сохранить календарь
                    </button>
                </div>
            </div>
            
            <!-- Модальное окно для сохранения/редактирования шаблона -->
            <div x-show="showTemplateModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                 @click.self="showTemplateModal = false">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="editingTemplateIndex !== null ? 'Редактировать шаблон' : 'Сохранить как шаблон'"></h3>
                    
                    <div class="space-y-4">
                        <!-- Название шаблона -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Название шаблона</label>
                            <input type="text" 
                                   x-model="newTemplateName"
                                   placeholder="Например: Короткий день"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                   @keydown.enter="confirmSaveTemplate()">
                        </div>
                        
                        <!-- Дни недели -->
                        <div x-show="editingTemplateIndex !== null && editingTemplateIndex < 2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Рабочие дни недели</label>
                            <div class="grid grid-cols-7 gap-2">
                                <template x-for="(day, index) in ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']" :key="index">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs text-gray-600" x-text="day"></span>
                                        <button type="button"
                                                @click="toggleDayOfWeek(index + 1)"
                                                class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-colors"
                                                :class="isDayOfWeekWorking(index + 1) ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300'">
                                            <svg x-show="isDayOfWeekWorking(index + 1)" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Рабочее время -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Рабочее время</label>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600">с</label>
                                    <input type="time" 
                                           x-model="templateToSave.start"
                                           class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <span class="text-gray-400">—</span>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600">до</label>
                                    <input type="time" 
                                           x-model="templateToSave.end"
                                           class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Перерыв -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Перерыв</label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           x-model="templateToSave.hasBreak"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                                    <span class="text-sm text-gray-600">Добавить перерыв</span>
                                </label>
                            </div>
                            <div x-show="templateToSave.hasBreak" x-transition class="flex items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600">с</label>
                                    <input type="time" 
                                           x-model="templateToSave.breakStart"
                                           class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <span class="text-gray-400">—</span>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-600">до</label>
                                    <input type="time" 
                                           x-model="templateToSave.breakEnd"
                                           class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button"
                                @click="showTemplateModal = false; editingTemplateIndex = null"
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md">
                            Отмена
                        </button>
                        <button type="button"
                                @click="confirmSaveTemplate()"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                            <span x-text="editingTemplateIndex !== null ? 'Сохранить изменения' : 'Создать шаблон'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

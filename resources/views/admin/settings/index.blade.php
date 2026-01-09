<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Настройки системы
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Общие настройки -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white p-6">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Общие настройки</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">Название центра</div>
                                    <div class="text-sm text-gray-600">Логопедический центр</div>
                                </div>
                                <button class="text-sm text-indigo-600 hover:text-indigo-900">Изменить</button>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">Email для уведомлений</div>
                                    <div class="text-sm text-gray-600">admin@logoped.test</div>
                                </div>
                                <button class="text-sm text-indigo-600 hover:text-indigo-900">Изменить</button>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">Телефон</div>
                                    <div class="text-sm text-gray-600">+7 (XXX) XXX-XX-XX</div>
                                </div>
                                <button class="text-sm text-indigo-600 hover:text-indigo-900">Изменить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Системная информация -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Системная информация</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Версия Laravel</span>
                                <span class="font-semibold text-gray-900">{{ app()->version() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Версия PHP</span>
                                <span class="font-semibold text-gray-900">{{ PHP_VERSION }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Окружение</span>
                                <span class="font-semibold text-gray-900">{{ app()->environment() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">База данных</span>
                                <span class="font-semibold text-gray-900">{{ config('database.default') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Управление кэшем -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-white p-6">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Управление кэшем</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <button onclick="clearCache('config')" 
                                class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all">
                                <span class="font-medium text-gray-900">Очистить кэш конфигурации</span>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            
                            <button onclick="clearCache('view')" 
                                class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all">
                                <span class="font-medium text-gray-900">Очистить кэш представлений</span>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            
                            <button onclick="clearCache('route')" 
                                class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all">
                                <span class="font-medium text-gray-900">Очистить кэш маршрутов</span>
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <p class="mt-4 text-xs text-gray-500">
                            Примечание: Очистка кэша выполняется через команды Artisan в терминале
                        </p>
                    </div>
                </div>

                <!-- Резервное копирование -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white p-6">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Резервное копирование</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="p-4 bg-orange-50 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="text-sm text-orange-800">
                                        Функция резервного копирования будет доступна в следующей версии
                                    </div>
                                </div>
                            </div>
                            
                            <button disabled 
                                class="w-full p-4 bg-gray-100 rounded-lg text-gray-400 cursor-not-allowed">
                                Создать резервную копию
                            </button>
                        </div>
                    </div>
                </div>
    </div>

    <script>
        function clearCache(type) {
            alert('Для очистки кэша выполните в терминале:\nphp artisan ' + type + ':clear');
        }
    </script>
</x-admin-layout>

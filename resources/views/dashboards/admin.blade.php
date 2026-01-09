<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Панель администратора') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ now()->locale('ru')->isoFormat('D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Статистика с градиентами -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Всего пользователей -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium opacity-90">Всего пользователей</div>
                                <div class="text-4xl font-bold mt-2">{{ $stats['users'] }}</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Логопедов -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium opacity-90">Специалистов</div>
                                <div class="text-4xl font-bold mt-2">{{ $stats['specialists'] }}</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Детей -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium opacity-90">Детей</div>
                                <div class="text-4xl font-bold mt-2">{{ $stats['children'] }}</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Новых заявок -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium opacity-90">Новых заявок</div>
                                <div class="text-4xl font-bold mt-2">{{ $stats['leads'] }}</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Быстрые действия -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Быстрые действия</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('admin.users.create') }}" 
                                class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-150 group">
                                <div class="bg-blue-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Создать пользователя</div>
                                    <div class="text-sm text-gray-600">Добавить специалиста или родителя</div>
                                </div>
                            </a>

                            <a href="{{ route('admin.users.index') }}" 
                                class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-150 group">
                                <div class="bg-purple-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Управление пользователями</div>
                                    <div class="text-sm text-gray-600">Просмотр и редактирование</div>
                                </div>
                            </a>

                            <a href="{{ route('admin.payments.index') }}" 
                                class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-150 group">
                                <div class="bg-green-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Учёт платежей</div>
                                    <div class="text-sm text-gray-600">Финансовый контроль</div>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.index') }}" 
                                class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:from-gray-100 hover:to-gray-200 transition-all duration-150 group">
                                <div class="bg-gray-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Настройки системы</div>
                                    <div class="text-sm text-gray-600">Конфигурация платформы</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Системная информация -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
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
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Всего пользователей</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $stats['users'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Специалистов</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $stats['specialists'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Детей в системе</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $stats['children'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Новых заявок</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $stats['leads'] }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('children.index') }}" 
                                class="block text-center text-sm text-indigo-600 hover:text-indigo-900">
                                Просмотреть всех детей →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

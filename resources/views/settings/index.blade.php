<x-home-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Настройки</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Личный профиль -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Личный профиль</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Личные данные
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Безопасность
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Уведомления
                    </a>
                </li>
            </ul>
        </div>

        @if(Auth::user()->isOrganization() && isset($organization))
        <!-- Профиль организации -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Профиль организации</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('organizations.edit-own') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Основная информация
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizations.edit-own') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Контактные данные
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizations.edit-own') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Часы работы
                    </a>
                </li>
            </ul>
        </div>
        @endif

        @if(Auth::user()->isSpecialist())
        <!-- Профиль специалиста -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Профиль специалиста</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('specialists.edit') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        О себе
                    </a>
                </li>
                <li>
                    <a href="{{ route('specialists.edit') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Образование и опыт
                    </a>
                </li>
                <li>
                    <a href="{{ route('specialists.edit') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Стоимость и форматы
                    </a>
                </li>
            </ul>
        </div>
        @endif

        <!-- Финансы -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Финансы</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('payments.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Приём платежей
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Настройки оплаты
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Настройки нефискального чека
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Настройки ККМ
                    </a>
                </li>
            </ul>
        </div>

        <!-- Занятия -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Настройки занятий</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Услуги
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Должности
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Сотрудники
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        График работы
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Ресурсы
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Журнал записи
                    </a>
                </li>
            </ul>
        </div>

        <!-- Категории -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Категории</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Категории записей
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Категории событий
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Категории клиентов
                    </a>
                </li>
            </ul>
        </div>

        <!-- Аналитика -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Аналитика</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Настройки вознаграждённости
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Сквозная аналитика (Roistat)
                    </a>
                </li>
            </ul>
        </div>

        <!-- Системные настройки -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Системные настройки</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Уведомления
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        Сети
                    </a>
                </li>
                <li>
                    <a href="#" class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                        WebHook
                    </a>
                </li>
            </ul>
        </div>

    </div>
</x-home-layout>

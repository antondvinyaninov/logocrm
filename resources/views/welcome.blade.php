<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LogoCRM - CRM для логопедических кабинетов</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-application-logo class="h-10 w-auto" />
                    <span class="text-2xl font-bold text-gray-900">LogoCRM</span>
                </div>
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/lk') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                            Личный кабинет
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-gray-900 font-medium">
                            Войти
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                Попробовать бесплатно
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        CRM для логопедических кабинетов
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Автоматизируйте работу вашего центра: от записи клиентов до учета финансов. Все в одной системе.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-lg font-semibold text-lg hover:bg-indigo-700 transition-colors text-center">
                            Начать бесплатно
                        </a>
                        <a href="{{ route('specialists.catalog') }}" class="inline-block px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-lg hover:border-gray-400 transition-colors text-center">
                            Каталог специалистов
                        </a>
                    </div>
                    <p class="mt-6 text-sm text-gray-500">
                        ✓ Бесплатный период 14 дней &nbsp;&nbsp; ✓ Без привязки карты &nbsp;&nbsp; ✓ Поддержка 24/7
                    </p>
                </div>
                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            <div class="grid grid-cols-3 gap-4 mt-6">
                                <div class="h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg"></div>
                                <div class="h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-lg"></div>
                                <div class="h-20 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg"></div>
                            </div>
                            <div class="h-32 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg mt-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Все для управления вашим центром</h2>
                <p class="text-xl text-gray-600">Полный набор инструментов для эффективной работы</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Управление клиентами</h3>
                    <p class="text-gray-600">База родителей и детей с полной историей занятий, целями терапии и прогрессом. Добавляйте контакты без учетных записей</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Календарь и расписание</h3>
                    <p class="text-gray-600">Умный календарь с месячным и дневным видом, настройка рабочих дней, онлайн-запись клиентов</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Аналитика и дашборды</h3>
                    <p class="text-gray-600">Виджеты с ключевыми метриками, статистика по клиентам, занятиям и доходам. Сравнение с предыдущим периодом</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Учет финансов</h3>
                    <p class="text-gray-600">Платежи, абонементы, отчеты по доходам. Полный контроль над финансами центра</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Домашние задания</h3>
                    <p class="text-gray-600">Создавайте задания для детей, отслеживайте выполнение, получайте обратную связь от родителей</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Отзывы и рейтинг</h3>
                    <p class="text-gray-600">Система отзывов, рейтинги специалистов, публичные профили для привлечения новых клиентов</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Управление командой</h3>
                    <p class="text-gray-600">Добавляйте специалистов, управляйте доступами, смотрите статистику работы каждого сотрудника</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Гибкие настройки</h3>
                    <p class="text-gray-600">Настройка расписания специалистов, услуг, тарифов. Адаптируйте систему под свой центр</p>
                </div>

                <div class="p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Мультитенантность</h3>
                    <p class="text-gray-600">Полная изоляция данных между организациями. Безопасность и конфиденциальность на высшем уровне</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-br from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-5xl font-bold mb-2">10+</div>
                    <div class="text-indigo-200">Организаций</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">50+</div>
                    <div class="text-indigo-200">Специалистов</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">200+</div>
                    <div class="text-indigo-200">Клиентов</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">1000+</div>
                    <div class="text-indigo-200">Занятий</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Готовы начать?
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Попробуйте LogoCRM бесплатно в течение 14 дней
            </p>
            <a href="{{ route('register') }}" class="inline-block px-12 py-4 bg-indigo-600 text-white rounded-lg font-semibold text-lg hover:bg-indigo-700 transition-colors">
                Начать бесплатно
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <x-application-logo class="h-8 w-auto" />
                        <span class="text-xl font-bold">LogoCRM</span>
                    </div>
                    <p class="text-gray-400">CRM для логопедических кабинетов</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Продукт</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Возможности</a></li>
                        <li><a href="#" class="hover:text-white">Тарифы</a></li>
                        <li><a href="{{ route('specialists.catalog') }}" class="hover:text-white">Каталог</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Компания</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">О нас</a></li>
                        <li><a href="#" class="hover:text-white">Блог</a></li>
                        <li><a href="#" class="hover:text-white">Контакты</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Поддержка</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Документация</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                        <li><a href="#" class="hover:text-white">Поддержка</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} LogoCRM. Все права защищены.</p>
            </div>
        </div>
    </footer>
</body>
</html>

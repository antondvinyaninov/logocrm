<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $organization->name }} - LogoCRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <x-application-logo class="h-10 w-auto" />
                    <span class="text-2xl font-bold text-gray-900">LogoCRM</span>
                </a>
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Личный кабинет
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-indigo-600 transition-colors">
                            Войти
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                Регистрация
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-purple-600 to-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-white mx-auto mb-6">
                    <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $organization->name }}</h1>
                @if($organization->description)
                    <p class="text-xl text-purple-100 max-w-3xl mx-auto">{{ $organization->description }}</p>
                @endif
            </div>

            <!-- Статистика -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold mb-2">{{ $organization->specialists_count }}</div>
                    <div class="text-purple-100">{{ trans_choice('Специалист|Специалиста|Специалистов', $organization->specialists_count) }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold mb-2">{{ $organization->children_count }}+</div>
                    <div class="text-purple-100">Клиентов</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold mb-2">{{ $organization->specialists->avg('experience_years') ? round($organization->specialists->avg('experience_years')) : 0 }}</div>
                    <div class="text-purple-100">Лет опыта</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center">
                    <div class="text-3xl font-bold mb-2">{{ $organization->specialists->avg('rating') ? number_format($organization->specialists->avg('rating'), 1) : '5.0' }}</div>
                    <div class="text-purple-100">Рейтинг</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Контактная информация -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if($organization->phone)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Телефон</h3>
                            <a href="tel:{{ $organization->phone }}" class="text-indigo-600 hover:text-indigo-700">
                                {{ $organization->phone }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($organization->email)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Email</h3>
                            <a href="mailto:{{ $organization->email }}" class="text-indigo-600 hover:text-indigo-700">
                                {{ $organization->email }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($organization->address)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Адрес</h3>
                            <p class="text-gray-600">{{ $organization->address }}</p>
                        </div>
                    </div>
                @endif
            </div>

            @if($organization->website)
                <div class="mt-8 text-center">
                    <a href="{{ $organization->website }}" target="_blank" rel="noopener noreferrer" 
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        Посетить сайт
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Специалисты -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Наши специалисты</h2>
                <p class="text-lg text-gray-600">Команда профессионалов с большим опытом работы</p>
            </div>

            @if($organization->specialists->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($organization->specialists as $specialist)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="p-8">
                                <div class="text-center mb-6">
                                    @if($specialist->photo)
                                        <img src="{{ Storage::url($specialist->photo) }}" 
                                            alt="{{ $specialist->full_name }}"
                                            class="h-32 w-32 rounded-full object-cover mx-auto mb-4">
                                    @else
                                        <div class="flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 mx-auto mb-4">
                                            <span class="text-4xl font-bold text-white">
                                                {{ substr($specialist->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $specialist->full_name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ $specialist->specialization }}</p>
                                </div>

                                <div class="flex items-center justify-center gap-1 mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-5 w-5 {{ $i <= $specialist->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="text-gray-600 ml-2">{{ number_format($specialist->rating, 1) }}</span>
                                </div>

                                @if($specialist->experience_years)
                                    <p class="text-center text-gray-600 mb-4">
                                        Опыт: {{ $specialist->experience_years }} {{ trans_choice('год|года|лет', $specialist->experience_years) }}
                                    </p>
                                @endif

                                @if($specialist->price_per_session)
                                    <p class="text-center text-2xl font-bold text-indigo-600 mb-6">
                                        {{ number_format($specialist->price_per_session, 0, ',', ' ') }} ₽
                                    </p>
                                @endif

                                <a href="{{ route('specialists.show', $specialist->id) }}" 
                                    class="block w-full text-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-500 transition-colors">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">Информация о специалистах скоро появится</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} {{ $organization->name }}. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>

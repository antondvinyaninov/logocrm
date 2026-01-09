<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $specialist->full_name }} - LogoCRM</title>
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

    <!-- Page Content -->
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Левая колонка - основная информация -->
                <div class="lg:col-span-2">
                    <!-- Карточка профиля -->
                    <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-col items-center md:flex-row md:items-start">
                                <!-- Фото -->
                                <div class="mb-4 md:mb-0 md:mr-6">
                                    @if($specialist->photo)
                                        <img src="{{ Storage::url($specialist->photo) }}" 
                                            alt="{{ $specialist->full_name }}"
                                            class="h-32 w-32 rounded-full object-cover">
                                    @else
                                        <div class="flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600">
                                            <span class="text-5xl font-bold text-white">
                                                {{ substr($specialist->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Основная информация -->
                                <div class="flex-1 text-center md:text-left">
                                    <h1 class="mb-2 text-3xl font-bold text-gray-900">
                                        {{ $specialist->full_name }}
                                    </h1>

                                    @if($specialist->specialization)
                                        <p class="mb-3 text-lg text-gray-600">
                                            {{ $specialist->specialization }}
                                        </p>
                                    @endif

                                    <!-- Рейтинг -->
                                    <div class="mb-3 flex items-center justify-center gap-2 md:justify-start">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-6 w-6 {{ $i <= $specialist->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-lg font-semibold text-gray-700">
                                            {{ number_format($specialist->rating, 1) }}
                                        </span>
                                        <span class="text-gray-600">
                                            ({{ $specialist->reviews_count }} {{ trans_choice('отзыв|отзыва|отзывов', $specialist->reviews_count) }})
                                        </span>
                                    </div>

                                    <!-- Опыт и форматы -->
                                    <div class="flex flex-wrap items-center justify-center gap-3 md:justify-start">
                                        @if($specialist->experience_years)
                                            <span class="rounded-full bg-indigo-100 px-4 py-1 text-sm font-medium text-indigo-800">
                                                Опыт: {{ $specialist->experience_years }} {{ trans_choice('год|года|лет', $specialist->experience_years) }}
                                            </span>
                                        @endif
                                        @if($specialist->available_online)
                                            <span class="rounded-full bg-green-100 px-4 py-1 text-sm font-medium text-green-800">
                                                Онлайн
                                            </span>
                                        @endif
                                        @if($specialist->available_offline)
                                            <span class="rounded-full bg-blue-100 px-4 py-1 text-sm font-medium text-blue-800">
                                                Офлайн
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- О себе -->
                    @if($specialist->about)
                        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="mb-4 text-xl font-semibold text-gray-900">О себе</h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($specialist->about)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Образование -->
                    @if($specialist->education)
                        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="mb-4 text-xl font-semibold text-gray-900">Образование</h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($specialist->education)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Сертификаты -->
                    @if($specialist->certificates && count($specialist->certificates) > 0)
                        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="mb-4 text-xl font-semibold text-gray-900">Сертификаты и повышение квалификации</h2>
                                <ul class="list-inside list-disc space-y-2 text-gray-700">
                                    @foreach($specialist->certificates as $certificate)
                                        <li>{{ $certificate }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Отзывы -->
                    <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="mb-4 text-xl font-semibold text-gray-900">
                                Отзывы ({{ $specialist->reviews_count }})
                            </h2>

                            @if($specialist->reviews->isEmpty())
                                <p class="text-gray-500">Пока нет отзывов</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($specialist->reviews as $review)
                                        <div class="border-b border-gray-200 pb-4 last:border-0">
                                            <div class="mb-2 flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-gray-900">
                                                        {{ $review->user->parentProfile->full_name ?? 'Родитель' }}
                                                    </p>
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500">
                                                    {{ $review->created_at->format('d.m.Y') }}
                                                </span>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-gray-700">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Форма добавления отзыва (только для родителей) -->
                            @auth
                                @if(auth()->user()->hasRole('parent'))
                                    @php
                                        $hasChildren = auth()->user()->parentProfile->children()
                                            ->where('specialist_id', $specialist->id)
                                            ->exists();
                                        $hasReview = $specialist->reviews->where('user_id', auth()->id())->isNotEmpty();
                                    @endphp

                                    @if($hasChildren && !$hasReview)
                                        <div class="mt-6 border-t border-gray-200 pt-6">
                                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Оставить отзыв</h3>
                                        <form method="POST" action="{{ route('specialists.reviews.store', $specialist->id) }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Оценка</label>
                                                <div class="mt-2 flex gap-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="rating" value="{{ $i }}" required class="sr-only peer">
                                                            <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400" 
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="comment" class="block text-sm font-medium text-gray-700">Комментарий (необязательно)</label>
                                                <textarea name="comment" id="comment" rows="3" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            </div>
                                            <button type="submit" 
                                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                                Отправить отзыв
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Правая колонка - статистика и действия -->
                <div class="lg:col-span-1">
                    <!-- Стоимость -->
                    @if($specialist->price_per_session)
                        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="mb-2 text-sm font-medium text-gray-700">Стоимость занятия</h3>
                                <p class="text-3xl font-bold text-indigo-600">
                                    {{ number_format($specialist->price_per_session, 0, ',', ' ') }} ₽
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Статистика -->
                    <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Статистика</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Клиентов:</span>
                                    <span class="font-semibold text-gray-900">{{ $specialist->children->count() }}</span>
                                </div>
                                @if($specialist->experience_years)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Опыт работы:</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ $specialist->experience_years }} {{ trans_choice('год|года|лет', $specialist->experience_years) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Отзывов:</span>
                                    <span class="font-semibold text-gray-900">{{ $specialist->reviews_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка выбора специалиста (для родителей) -->
                    @auth
                        @if(auth()->user()->hasRole('parent'))
                            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <a href="{{ route('children.create', ['specialist_id' => $specialist->id]) }}" 
                                        class="block w-full rounded-md bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-indigo-500">
                                        Выбрать специалиста
                                    </a>
                                    <p class="mt-2 text-center text-xs text-gray-500">
                                        Добавьте ребёнка и назначьте этого специалиста
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} LogoCRM. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>

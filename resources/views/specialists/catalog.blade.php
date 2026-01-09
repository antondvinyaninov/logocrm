<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Каталог специалистов - LogoCRM</title>
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
            <!-- Фильтры -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('specialists.catalog') }}" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                            <!-- Организация -->
                            <div>
                                <label for="organization" class="block text-sm font-medium text-gray-700">Центр</label>
                                <select name="organization" id="organization" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Все центры</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ request('organization') == $org->id ? 'selected' : '' }}>
                                            {{ $org->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Специализация -->
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700">Специализация</label>
                                <input type="text" name="specialization" id="specialization" 
                                    value="{{ request('specialization') }}"
                                    placeholder="Например: дислалия"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Формат -->
                            <div>
                                <label for="format" class="block text-sm font-medium text-gray-700">Формат</label>
                                <select name="format" id="format" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Все</option>
                                    <option value="online" {{ request('format') === 'online' ? 'selected' : '' }}>Онлайн</option>
                                    <option value="offline" {{ request('format') === 'offline' ? 'selected' : '' }}>Офлайн</option>
                                </select>
                            </div>

                            <!-- Минимальный рейтинг -->
                            <div>
                                <label for="min_rating" class="block text-sm font-medium text-gray-700">Мин. рейтинг</label>
                                <select name="min_rating" id="min_rating" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Любой</option>
                                    <option value="4" {{ request('min_rating') == 4 ? 'selected' : '' }}>4+</option>
                                    <option value="4.5" {{ request('min_rating') == 4.5 ? 'selected' : '' }}>4.5+</option>
                                </select>
                            </div>

                            <!-- Макс. стоимость -->
                            <div>
                                <label for="max_price" class="block text-sm font-medium text-gray-700">Макс. цена</label>
                                <input type="number" name="max_price" id="max_price" 
                                    value="{{ request('max_price') }}"
                                    placeholder="5000"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex gap-2">
                                <button type="submit" 
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Применить фильтры
                                </button>
                                <a href="{{ route('specialists.catalog') }}" 
                                    class="inline-flex items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                                    Сбросить
                                </a>
                            </div>

                            <!-- Сортировка -->
                            <div class="flex items-center gap-2">
                                <label for="sort" class="text-sm font-medium text-gray-700">Сортировка:</label>
                                <select name="sort" id="sort" onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>По рейтингу</option>
                                    <option value="price_per_session" {{ request('sort') === 'price_per_session' ? 'selected' : '' }}>По цене</option>
                                    <option value="experience_years" {{ request('sort') === 'experience_years' ? 'selected' : '' }}>По опыту</option>
                                    <option value="reviews_count" {{ request('sort') === 'reviews_count' ? 'selected' : '' }}>По отзывам</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Список специалистов -->
            @if($specialists->isEmpty())
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        Специалисты не найдены
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($specialists as $specialist)
                        <div class="overflow-hidden bg-white shadow-sm transition-shadow hover:shadow-lg sm:rounded-lg">
                            <div class="p-6">
                                <!-- Фото -->
                                <div class="mb-4 flex justify-center">
                                    @if($specialist->photo)
                                        <img src="{{ Storage::url($specialist->photo) }}" 
                                            alt="{{ $specialist->full_name }}"
                                            class="h-32 w-32 rounded-full object-cover">
                                    @else
                                        <div class="flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600">
                                            <span class="text-4xl font-bold text-white">
                                                {{ substr($specialist->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Имя -->
                                <h3 class="mb-2 text-center text-xl font-semibold text-gray-900">
                                    {{ $specialist->full_name }}
                                </h3>

                                <!-- Специализация -->
                                @if($specialist->specialization)
                                    <p class="mb-3 text-center text-sm text-gray-600">
                                        {{ $specialist->specialization }}
                                    </p>
                                @endif

                                <!-- Рейтинг и отзывы -->
                                <div class="mb-3 flex items-center justify-center gap-2">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-5 w-5 {{ $i <= $specialist->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">
                                        {{ number_format($specialist->rating, 1) }} ({{ $specialist->reviews_count }})
                                    </span>
                                </div>

                                <!-- Опыт -->
                                @if($specialist->experience_years)
                                    <p class="mb-2 text-center text-sm text-gray-600">
                                        Опыт: {{ $specialist->experience_years }} {{ trans_choice('год|года|лет', $specialist->experience_years) }}
                                    </p>
                                @endif

                                <!-- Форматы -->
                                <div class="mb-4 flex justify-center gap-2">
                                    @if($specialist->available_online)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                            Онлайн
                                        </span>
                                    @endif
                                    @if($specialist->available_offline)
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                            Офлайн
                                        </span>
                                    @endif
                                </div>

                                <!-- Цена -->
                                @if($specialist->price_per_session)
                                    <p class="mb-4 text-center text-lg font-semibold text-indigo-600">
                                        {{ number_format($specialist->price_per_session, 0, ',', ' ') }} ₽/занятие
                                    </p>
                                @endif

                                <!-- Кнопка -->
                                <a href="{{ route('specialists.show', $specialist->id) }}" 
                                    class="block w-full rounded-md bg-indigo-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-500">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Пагинация -->
                <div class="mt-6">
                    {{ $specialists->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Логопедический центр. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>

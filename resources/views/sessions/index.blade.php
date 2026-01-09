<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Занятия
            </h2>
            @can('create', App\Models\TherapySession::class)
                <a href="{{ route('sessions.create') }}" 
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Создать занятие
                </a>
            @endcan
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Фильтры -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('sessions.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Дата</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Все</option>
                                    <option value="planned" {{ request('status') === 'planned' ? 'selected' : '' }}>Запланировано</option>
                                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Завершено</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Отменено</option>
                                </select>
                            </div>

                            @if($children->count() > 0 && !auth()->user()->isParent())
                                <div>
                                    <label for="child_id" class="block text-sm font-medium text-gray-700">Ребёнок</label>
                                    <select name="child_id" id="child_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Все</option>
                                        @foreach($children as $child)
                                            <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if(auth()->user()->isAdmin() && $specialists->count() > 0)
                                <div>
                                    <label for="specialist_id" class="block text-sm font-medium text-gray-700">Специалист</label>
                                    <select name="specialist_id" id="specialist_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Все</option>
                                        @foreach($specialists as $specialist)
                                            <option value="{{ $specialist->id }}" {{ request('specialist_id') == $specialist->id ? 'selected' : '' }}>
                                                {{ $specialist->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                Применить
                            </button>
                            <a href="{{ route('sessions.index') }}" 
                                class="inline-flex items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                                Сбросить
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Список занятий -->
            @if($sessions->isEmpty())
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        Занятия не найдены
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($sessions as $session)
                        <div class="overflow-hidden bg-white shadow-sm transition-shadow hover:shadow-lg sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-4 mb-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-lg font-semibold text-gray-900">
                                                    {{ $session->start_time->format('d.m.Y H:i') }}
                                                </span>
                                            </div>
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                                @if($session->status === 'planned') bg-blue-100 text-blue-800
                                                @elseif($session->status === 'done') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                @if($session->status === 'planned') Запланировано
                                                @elseif($session->status === 'done') Завершено
                                                @else Отменено
                                                @endif
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                                            <div>
                                                <p class="text-sm text-gray-600">Ребёнок</p>
                                                <p class="font-medium text-gray-900">{{ $session->child->full_name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Специалист</p>
                                                <p class="font-medium text-gray-900">{{ $session->specialist->full_name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Длительность</p>
                                                <p class="font-medium text-gray-900">{{ $session->duration_minutes }} мин</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex gap-2">
                                            <span class="rounded-full px-3 py-1 text-xs font-medium
                                                @if($session->format === 'online') bg-green-100 text-green-800
                                                @else bg-purple-100 text-purple-800
                                                @endif">
                                                {{ $session->format === 'online' ? 'Онлайн' : 'Офлайн' }}
                                            </span>
                                            <span class="rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $session->type === 'individual' ? 'Индивидуальное' : 'Групповое' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('sessions.show', $session) }}" 
                                            class="rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                                            Просмотр
                                        </a>
                                        @can('update', $session)
                                            <a href="{{ route('sessions.edit', $session) }}" 
                                                class="rounded-md bg-indigo-100 px-3 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-200">
                                                Редактировать
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $sessions->links() }}
                </div>
            @endif
</x-home-layout>

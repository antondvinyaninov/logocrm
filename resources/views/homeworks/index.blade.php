<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Домашние задания
            </h2>
            @can('create', App\Models\Homework::class)
                <a href="{{ route('homeworks.create') }}" 
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Создать задание
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Фильтры -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('homeworks.index') }}" class="p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Статус -->
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                            <select id="status" name="status" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Все статусы</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Активно</option>
                                <option value="done_by_parent" {{ request('status') === 'done_by_parent' ? 'selected' : '' }}>Выполнено родителем</option>
                                <option value="checked_by_specialist" {{ request('status') === 'checked_by_specialist' ? 'selected' : '' }}>Проверено специалистом</option>
                            </select>
                        </div>

                        <!-- Ребёнок -->
                        @if($children->count() > 1)
                            <div>
                                <label for="child_id" class="mb-2 block text-sm font-medium text-gray-700">Ребёнок</label>
                                <select id="child_id" name="child_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Все дети</option>
                                    @foreach($children as $child)
                                        <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                            {{ $child->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Кнопки -->
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                Применить
                            </button>
                            <a href="{{ route('homeworks.index') }}" 
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Список заданий -->
            <div class="space-y-4">
                @forelse($homeworks as $homework)
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $homework->title }}
                                        </h3>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            @if($homework->status === 'active') bg-orange-100 text-orange-800
                                            @elseif($homework->status === 'done_by_parent') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($homework->status === 'active') Активно
                                            @elseif($homework->status === 'done_by_parent') Выполнено родителем
                                            @else Проверено специалистом
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $homework->child->full_name }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $homework->created_at->format('d.m.Y') }}
                                        </div>
                                    </div>

                                    @if($homework->description)
                                        <p class="mt-2 text-sm text-gray-700">{{ Str::limit($homework->description, 150) }}</p>
                                    @endif
                                </div>

                                <div class="ml-4 flex gap-2">
                                    <a href="{{ route('homeworks.show', $homework) }}" 
                                        class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                        Просмотр
                                    </a>
                                    @can('update', $homework)
                                        <a href="{{ route('homeworks.edit', $homework) }}" 
                                            class="rounded-md bg-indigo-100 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-200">
                                            Редактировать
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-4 text-gray-500">Домашних заданий пока нет</p>
                        </div>
                    </div>
                @endforelse
            </div>

    <!-- Пагинация -->
    @if($homeworks->hasPages())
        <div class="mt-6">
            {{ $homeworks->links() }}
        </div>
    @endif
</x-home-layout>

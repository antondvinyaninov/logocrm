<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Дети</h1>
            @can('create', App\Models\Child::class)
                <a href="{{ route('children.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Добавить ребёнка
                </a>
            @endcan
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Фильтры -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('children.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Поиск по имени..." 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                @if(auth()->user()->role === 'admin' && $therapists->count() > 0)
                    <div>
                        <select name="therapist_id" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все логопеды</option>
                            @foreach($therapists as $therapist)
                                <option value="{{ $therapist->id }}" {{ request('therapist_id') == $therapist->id ? 'selected' : '' }}>
                                    {{ $therapist->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Фильтр
                </button>
                @if(request('search') || request('therapist_id'))
                    <a href="{{ route('children.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150">
                        Сбросить
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Список детей -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($children as $child)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-lg mr-3">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800">{{ $child->full_name }}</h3>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($child->birth_date)->age }} лет</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-3 mb-4">
                        @if($child->parent)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-gray-600">Родитель:</span>
                                <span class="ml-1 font-medium text-gray-900">{{ $child->parent->full_name }}</span>
                            </div>
                        @endif

                        @if($child->therapist)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600">Логопед:</span>
                                <span class="ml-1 font-medium text-gray-900">{{ $child->therapist->full_name }}</span>
                            </div>
                        @endif
                    </div>

                    @if($child->tags && count($child->tags) > 0)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach($child->tags as $tag)
                                <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center justify-end space-x-2 pt-4 border-t border-gray-200">
                        @can('view', $child)
                            <a href="{{ route('children.show', $child) }}" class="text-green-600 hover:text-green-900" title="Просмотр">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        @endcan
                        @can('update', $child)
                            <a href="{{ route('children.edit', $child) }}" class="text-indigo-600 hover:text-indigo-900" title="Редактировать">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        @endcan
                        @can('delete', $child)
                            <form action="{{ route('children.destroy', $child) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Дети не найдены</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($children->hasPages())
        <div class="mt-6">
            {{ $children->links() }}
        </div>
    @endif
</x-home-layout>

<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Заявки с сайта
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Фильтры -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('admin.leads.index') }}" class="p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Статус -->
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                            <select id="status" name="status" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Все статусы</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Новая</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>В работе</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Закрыта</option>
                            </select>
                        </div>

                        <!-- Поиск -->
                        <div>
                            <label for="search" class="mb-2 block text-sm font-medium text-gray-700">Поиск</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Имя, контакт, сообщение...">
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                Применить
                            </button>
                            <a href="{{ route('admin.leads.index') }}" 
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Список заявок -->
            <div class="space-y-4">
                @forelse($leads as $lead)
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $lead->name }}
                                        </h3>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            @if($lead->status === 'new') bg-orange-100 text-orange-800
                                            @elseif($lead->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($lead->status === 'new') Новая
                                            @elseif($lead->status === 'in_progress') В работе
                                            @else Закрыта
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $lead->contact }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $lead->created_at->format('d.m.Y H:i') }}
                                        </div>
                                    </div>

                                    @if($lead->message)
                                        <p class="mt-2 text-sm text-gray-700">{{ Str::limit($lead->message, 150) }}</p>
                                    @endif
                                </div>

                                <div class="ml-4">
                                    <a href="{{ route('admin.leads.show', $lead) }}" 
                                        class="rounded-md bg-indigo-100 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-200">
                                        Просмотр
                                    </a>
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
                            <p class="mt-4 text-gray-500">Заявок пока нет</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Пагинация -->
            @if($leads->hasPages())
                <div class="mt-6">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>
</x-home-layout>

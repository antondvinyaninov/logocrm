<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Домашнее задание
            </h2>
            <a href="{{ route('homeworks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <!-- Основная информация -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $homework->title }}</h3>
                            <p class="mt-1 text-gray-600">Создано: {{ $homework->created_at->format('d.m.Y в H:i') }}</p>
                        </div>
                        <span class="rounded-full px-4 py-2 text-sm font-semibold
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
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Ребёнок -->
                        <div>
                            <h4 class="mb-3 text-sm font-semibold text-gray-700">Ребёнок</h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-lg font-semibold text-gray-900">{{ $homework->child->full_name }}</p>
                                <p class="text-sm text-gray-600">
                                    Возраст: {{ \Carbon\Carbon::parse($homework->child->birth_date)->age }} лет
                                </p>
                            </div>
                        </div>

                        <!-- Занятие -->
                        @if($homework->session)
                            <div>
                                <h4 class="mb-3 text-sm font-semibold text-gray-700">Связано с занятием</h4>
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <a href="{{ route('sessions.show', $homework->session) }}" 
                                        class="text-lg font-semibold text-indigo-600 hover:text-indigo-900">
                                        {{ $homework->session->start_time->format('d.m.Y H:i') }}
                                    </a>
                                    <p class="text-sm text-gray-600">
                                        Специалист: {{ $homework->session->specialist->full_name }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Описание -->
                    @if($homework->description)
                        <div class="mt-6">
                            <h4 class="mb-3 text-sm font-semibold text-gray-700">Описание задания</h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="whitespace-pre-line text-gray-700">{{ $homework->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Материалы -->
                    @if($homework->resource_url)
                        <div class="mt-6">
                            <h4 class="mb-3 text-sm font-semibold text-gray-700">Материалы</h4>
                            <a href="{{ $homework->resource_url }}" target="_blank" 
                                class="inline-flex items-center rounded-lg bg-indigo-50 px-4 py-3 text-indigo-700 hover:bg-indigo-100">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Открыть материалы
                            </a>
                        </div>
                    @endif

                    <!-- Действия -->
                    <div class="mt-6 flex items-center gap-3">
                        @can('update', $homework)
                            @if(auth()->user()->isParent() && $homework->status === 'active')
                                <!-- Родитель может отметить выполнение -->
                                <form method="POST" action="{{ route('homeworks.update', $homework) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="done_by_parent">
                                    <button type="submit" 
                                        class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Отметить как выполненное
                                    </button>
                                </form>
                            @elseif(auth()->user()->isParent() && $homework->status === 'done_by_parent')
                                <!-- Родитель может вернуть в активные -->
                                <form method="POST" action="{{ route('homeworks.update', $homework) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" 
                                        class="inline-flex items-center rounded-md bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-500">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Вернуть в активные
                                    </button>
                                </form>
                            @else
                                <!-- Специалист и админ могут редактировать -->
                                <a href="{{ route('homeworks.edit', $homework) }}" 
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Редактировать
                                </a>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

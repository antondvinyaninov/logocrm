<x-home-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Аналитика
        </h2>
    </x-slot>

    <!-- Основные метрики -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Дети -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Дети</h3>
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalChildren }}</div>
            <p class="text-xs text-gray-500 mt-1">Всего детей</p>
        </div>

        <!-- Занятия за месяц -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Занятия</h3>
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $sessionsThisMonth }}</div>
            <p class="text-xs text-gray-500 mt-1">{{ $sessionsCompleted }} проведено</p>
        </div>

        <!-- Активные задания -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Активные задания</h3>
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $activeHomeworks }}</div>
            <p class="text-xs text-gray-500 mt-1">Требуют выполнения</p>
        </div>

        <!-- Выполненные задания -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Выполнено</h3>
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $completedHomeworks }}</div>
            <p class="text-xs text-gray-500 mt-1">{{ $checkedHomeworks }} проверено</p>
        </div>
    </div>

    <!-- Мои дети -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Мои дети</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($children as $child)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center mr-3">
                            <span class="text-white font-semibold text-lg">{{ substr($child->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">{{ $child->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $child->age }} лет</p>
                        </div>
                    </div>
                    @if($child->specialist)
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Специалист:</span> {{ $child->specialist->full_name }}
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>Занятий: {{ $child->therapySessions()->count() }}</span>
                        <a href="{{ route('children.show', $child) }}" class="text-indigo-600 hover:text-indigo-900">
                            Подробнее →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Предстоящие занятия -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Предстоящие занятия</h3>
            <a href="{{ route('sessions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                Смотреть все →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата и время</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ребенок</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Специалист</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Формат</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($upcomingSessions as $session)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $session->start_time->translatedFormat('d M, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $session->child->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $session->specialist->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $session->format === 'online' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $session->format === 'online' ? 'Онлайн' : 'Офлайн' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Нет предстоящих занятий
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-home-layout>

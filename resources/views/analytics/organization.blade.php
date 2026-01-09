<x-home-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Аналитика
        </h2>
    </x-slot>

    <!-- Основные метрики -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Клиенты -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Клиенты</h3>
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalClients }}</div>
            <p class="text-xs text-gray-500 mt-1">Всего детей</p>
        </div>

        <!-- Специалисты -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Специалисты</h3>
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalSpecialists }}</div>
            <p class="text-xs text-gray-500 mt-1">В команде</p>
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
            <div class="flex items-center mt-1">
                @php
                    $change = $sessionsLastMonth > 0 ? (($sessionsThisMonth - $sessionsLastMonth) / $sessionsLastMonth * 100) : 0;
                @endphp
                @if($change > 0)
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs text-green-600">+{{ number_format($change, 1) }}%</span>
                @elseif($change < 0)
                    <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs text-red-600">{{ number_format($change, 1) }}%</span>
                @else
                    <span class="text-xs text-gray-500">без изменений</span>
                @endif
                <span class="text-xs text-gray-500 ml-1">vs прошлый месяц</span>
            </div>
        </div>

        <!-- Доход за месяц -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Доход</h3>
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($revenueThisMonth, 0, ',', ' ') }} ₽</div>
            <div class="flex items-center mt-1">
                @php
                    $revenueChange = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100) : 0;
                @endphp
                @if($revenueChange > 0)
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs text-green-600">+{{ number_format($revenueChange, 1) }}%</span>
                @elseif($revenueChange < 0)
                    <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs text-red-600">{{ number_format($revenueChange, 1) }}%</span>
                @else
                    <span class="text-xs text-gray-500">без изменений</span>
                @endif
                <span class="text-xs text-gray-500 ml-1">vs прошлый месяц</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Статистика занятий -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Статистика занятий за месяц</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Проведено</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $sessionsCompleted }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Отменено</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $sessionsCancelled }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Запланировано</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $sessionsThisMonth - $sessionsCompleted - $sessionsCancelled }}</span>
                </div>
            </div>
        </div>

        <!-- Топ специалистов -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Топ специалистов</h3>
            <div class="space-y-3">
                @forelse($topSpecialists as $specialist)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($specialist->photo)
                                <img src="{{ Storage::url($specialist->photo) }}" alt="{{ $specialist->full_name }}" class="w-10 h-10 rounded-full mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center mr-3">
                                    <span class="text-white font-semibold">{{ substr($specialist->full_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $specialist->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $specialist->specialization }}</div>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-indigo-600">{{ $specialist->therapy_sessions_count }} занятий</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Нет данных</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Предстоящие занятия -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Предстоящие занятия</h3>
            <a href="{{ route('calendar.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                Смотреть все →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата и время</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Клиент</th>
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

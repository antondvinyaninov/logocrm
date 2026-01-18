<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Финансы</h1>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Дашборд -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Должны оплатить -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">Должны оплатить</h3>
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalDue, 0, ',', ' ') }} ₽</p>
                <p class="text-sm text-gray-500 mt-1">За текущий месяц</p>
            </div>
        </div>

        <!-- Оплачено -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">Оплачено</h3>
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPaid, 0, ',', ' ') }} ₽</p>
                <a href="{{ route('payments.index', ['debtors' => 1]) }}" class="text-sm text-indigo-600 hover:text-indigo-900 mt-1 inline-block">
                    Посмотреть должников →
                </a>
            </div>
        </div>

        <!-- План на следующий месяц -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-500">План на {{ \Carbon\Carbon::now()->addMonth()->translatedFormat('F') }}</h3>
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($nextMonthPlan, 0, ',', ' ') }} ₽</p>
                <p class="text-sm text-gray-500 mt-1">Запланировано занятий</p>
            </div>
        </div>
    </div>

    <!-- Список должников (если выбран фильтр) -->
    @if($showDebtors && $debtors->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Должники</h2>
                    <a href="{{ route('payments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← Назад к списку занятий
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($debtors as $debtor)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">{{ $debtor['child']->full_name }}</h3>
                                    <p class="text-sm text-gray-600">Родитель: {{ $debtor['child']->parent->full_name ?? '—' }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Неоплаченных занятий: {{ $debtor['sessions_count'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-red-600">{{ number_format($debtor['debt'], 0, ',', ' ') }} ₽</p>
                                    <p class="text-sm text-gray-500">задолженность</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Список завершенных занятий -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Завершенные занятия за {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ребенок</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Специалист</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Стоимость</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус оплаты</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($completedSessions as $session)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $session->start_time->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('children.show', $session->child) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $session->child->full_name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $session->specialist->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                @if(auth()->user()->isOrganization())
                                    <div x-data="{ editing: false, price: {{ $session->price ?? 0 }} }">
                                        <div x-show="!editing" class="flex items-center gap-2">
                                            <span>{{ $session->price ? number_format($session->price, 0, ',', ' ') . ' ₽' : '—' }}</span>
                                            <button @click="editing = true" type="button" class="text-blue-600 hover:text-blue-900" title="Изменить стоимость">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <form x-show="editing" x-cloak action="{{ route('sessions.update-price', $session) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="number" name="price" x-model="price" step="1" min="0" required
                                                class="w-24 px-2 py-1 text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Сохранить">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                            <button @click="editing = false" type="button" class="text-gray-600 hover:text-gray-900" title="Отмена">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span>{{ $session->price ? number_format($session->price, 0, ',', ' ') . ' ₽' : '—' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($session->payment_status === 'paid')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Оплачено
                                    </span>
                                    @if($session->paid_at)
                                        <p class="text-xs text-gray-500 mt-1">{{ $session->paid_at->format('d.m.Y') }}</p>
                                    @endif
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Не оплачено
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($session->payment_status === 'unpaid')
                                    @if($session->price && $session->price > 0)
                                        @if(auth()->user()->isOrganization())
                                            <form action="{{ route('sessions.mark-paid', $session) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">
                                                    Оплачено
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded cursor-not-allowed" title="Только владелец может отмечать оплату">
                                                Оплачено
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 text-xs font-semibold rounded cursor-not-allowed" title="Сначала укажите стоимость">
                                            Оплачено
                                        </span>
                                    @endif
                                @else
                                    @if(auth()->user()->isOrganization())
                                        <form action="{{ route('sessions.mark-unpaid', $session) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-orange-600 text-white text-xs font-semibold rounded hover:bg-orange-700">
                                                Отменить оплату
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded cursor-not-allowed" title="Оплата подтверждена">
                                            Оплачено ✓
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Завершенных занятий за текущий месяц не найдено
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-home-layout>

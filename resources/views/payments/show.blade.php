<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Просмотр платежа') }}
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('payments.edit', $payment) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                    Редактировать
                </a>
                <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Назад к списку
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Дата платежа</p>
                            <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d.m.Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Сумма</p>
                            <p class="text-lg font-semibold">{{ number_format($payment->amount, 0, ',', ' ') }} ₽</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Клиент</p>
                            <p class="text-lg font-semibold">{{ $payment->child->full_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Способ оплаты</p>
                            <p class="text-lg font-semibold">{{ $payment->payment_method }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Статус</p>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($payment->status === 'completed') bg-green-100 text-green-800
                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($payment->status === 'completed') Оплачено
                                @elseif($payment->status === 'pending') Ожидает
                                @else Отменено
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    @if($payment->notes)
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500">Примечания</p>
                            <p class="mt-2">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

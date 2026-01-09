<x-home-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Добавить платеж') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <form method="POST" action="{{ route('payments.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Клиент -->
                    <div>
                        <label for="child_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Клиент (ребенок)
                        </label>
                        <select name="child_id" id="child_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">Выберите клиента</option>
                            @foreach(\App\Models\Child::where('organization_id', auth()->user()->organization_id)->get() as $child)
                                <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                    {{ $child->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('child_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Сумма -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Сумма (₽)
                        </label>
                        <input type="number" name="amount" id="amount" 
                               value="{{ old('amount') }}"
                               step="0.01" min="0"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Дата платежа -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Дата платежа
                        </label>
                        <input type="date" name="payment_date" id="payment_date" 
                               value="{{ old('payment_date', date('Y-m-d')) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Способ оплаты -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Способ оплаты
                        </label>
                        <select name="payment_method" id="payment_method" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">Выберите способ</option>
                            <option value="Наличные" {{ old('payment_method') === 'Наличные' ? 'selected' : '' }}>Наличные</option>
                            <option value="Карта" {{ old('payment_method') === 'Карта' ? 'selected' : '' }}>Карта</option>
                            <option value="Перевод" {{ old('payment_method') === 'Перевод' ? 'selected' : '' }}>Перевод</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Статус -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Статус
                        </label>
                        <select name="status" id="status" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Ожидает</option>
                            <option value="completed" {{ old('status', 'completed') === 'completed' ? 'selected' : '' }}>Оплачено</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Отменено</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Примечания -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Примечания
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('payments.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150">
                            Отмена
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                            Создать платеж
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-home-layout>

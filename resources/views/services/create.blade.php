<x-home-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Создание услуги
        </h2>
    </x-slot>

    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form method="POST" action="{{ route('services.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Название -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Название услуги *</label>
                        <input type="text" name="name" id="name" required
                            value="{{ old('name') }}"
                            placeholder="Например: Индивидуальное занятие с логопедом"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Описание -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Краткое описание услуги...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Стоимость -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Стоимость (₽) *</label>
                        <input type="number" name="price" id="price" required min="0" step="0.01"
                            value="{{ old('price') }}"
                            placeholder="3000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Длительность -->
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Длительность (минут) *</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" required min="1"
                            value="{{ old('duration_minutes', 45) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Тип занятия -->
                    <div>
                        <label for="session_type" class="block text-sm font-medium text-gray-700">Тип занятия *</label>
                        <select name="session_type" id="session_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="individual" {{ old('session_type') === 'individual' ? 'selected' : '' }}>Индивидуальное</option>
                            <option value="group" {{ old('session_type') === 'group' ? 'selected' : '' }}>Групповое</option>
                        </select>
                        @error('session_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Формат -->
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700">Формат *</label>
                        <select name="format" id="format" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="both" {{ old('format') === 'both' ? 'selected' : '' }}>Онлайн/Офлайн</option>
                            <option value="online" {{ old('format') === 'online' ? 'selected' : '' }}>Только онлайн</option>
                            <option value="offline" {{ old('format') === 'offline' ? 'selected' : '' }}>Только офлайн</option>
                        </select>
                        @error('format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Макс. участников (для групповых) -->
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700">Макс. участников (для групповых)</label>
                        <input type="number" name="max_participants" id="max_participants" min="1"
                            value="{{ old('max_participants') }}"
                            placeholder="Оставьте пустым для индивидуальных"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('max_participants')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Активность -->
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" 
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Услуга активна</span>
                        </label>
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="mt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('services.index') }}" 
                        class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                        Отмена
                    </a>
                    <button type="submit" 
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        Создать услугу
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-home-layout>

<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Редактировать родителя</h1>
            <a href="{{ route('parents.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('parents.update', $parent) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Имя -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Имя родителя *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $parent->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $parent->email) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Телефон -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $parent->parentProfile->phone ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Смена пароля (только для организаций) -->
            @if(Auth::user()->isOrganization())
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Смена пароля</h3>
                    <p class="text-sm text-gray-500 mb-4">Оставьте поля пустыми, если не хотите менять пароль</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Новый пароль -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Новый пароль</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Кнопки -->
            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('parents.index') }}" 
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Отмена
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-yellow-400 border border-transparent rounded-md text-sm font-medium text-gray-900 hover:bg-yellow-500">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</x-home-layout>

<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Добавить родителя</h1>
            <a href="{{ route('parents.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('parents.store') }}" class="p-6" x-data="{ 
            addChild: false, 
            children: [],
            email: '{{ old('email') }}',
            hasEmail() { return this.email && this.email.trim().length > 0; }
        }">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Имя -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Имя родителя *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" x-model="email" value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">Если не указан, родитель будет добавлен как контакт без возможности входа</p>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Телефон -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Пароль (только для организаций и только если указан email) -->
                @if(Auth::user()->isOrganization())
                    <template x-if="hasEmail()">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Пароль *</label>
                            <input type="password" name="password" id="password" :required="hasEmail()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Родитель сможет войти в систему с этим паролем</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </template>

                    <!-- Подтверждение пароля -->
                    <template x-if="hasEmail()">
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" :required="hasEmail()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </template>
                    
                    <!-- Информационное сообщение когда email не указан -->
                    <template x-if="!hasEmail()">
                        <div class="md:col-span-2">
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <p class="text-sm text-blue-800">
                                    <strong>Примечание:</strong> Родитель будет добавлен как контакт без учетной записи. 
                                    Укажите email, чтобы создать учетную запись с возможностью входа в систему.
                                </p>
                            </div>
                        </div>
                    </template>
                @else
                    <div class="md:col-span-2">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <p class="text-sm text-blue-800">
                                <strong>Примечание:</strong> Родитель будет добавлен как контакт без учетной записи. 
                                Он сможет самостоятельно зарегистрироваться позже, используя указанный email.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Добавление детей -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Дети</h3>
                    <button type="button" @click="children.push({ name: '', age: '', diagnosis: '' })"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить ребенка
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(child, index) in children" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="text-sm font-medium text-gray-700">Ребенок <span x-text="index + 1"></span></h4>
                                <button type="button" @click="children.splice(index, 1)"
                                    class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Имя *</label>
                                    <input type="text" :name="'children[' + index + '][name]'" x-model="child.name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Возраст *</label>
                                    <input type="number" :name="'children[' + index + '][age]'" x-model="child.age" min="0" max="18" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Диагноз</label>
                                    <input type="text" :name="'children[' + index + '][diagnosis]'" x-model="child.diagnosis"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="children.length === 0" class="text-center py-6 text-gray-500 text-sm">
                        Нажмите "Добавить ребенка" чтобы добавить детей родителя
                    </div>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('parents.index') }}" 
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Отмена
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-yellow-400 border border-transparent rounded-md text-sm font-medium text-gray-900 hover:bg-yellow-500">
                    Создать родителя
                </button>
            </div>
        </form>
    </div>
</x-home-layout>

<x-home-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Профиль организации
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('organizations.update-own') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Название -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Название организации <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $organization->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Описание -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Описание
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $organization->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Адрес -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Адрес
                            </label>
                            <input type="text" 
                                   name="address" 
                                   id="address" 
                                   value="{{ old('address', $organization->address) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Телефон -->
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Телефон
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $organization->phone) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $organization->email) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Сайт -->
                        <div class="mb-4">
                            <label for="website" class="block text-sm font-medium text-gray-700">
                                Сайт
                            </label>
                            <input type="url" 
                                   name="website" 
                                   id="website" 
                                   value="{{ old('website', $organization->website) }}"
                                   placeholder="https://example.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Логотип -->
                        <div class="mb-4">
                            <label for="logo" class="block text-sm font-medium text-gray-700">
                                Логотип
                            </label>
                            
                            @if($organization->logo)
                                <div class="mt-2 mb-2">
                                    <img src="{{ Storage::url($organization->logo) }}" 
                                         alt="Текущий логотип" 
                                         class="h-32 w-32 object-cover rounded">
                                </div>
                            @endif
                            
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">PNG, JPG до 2MB</p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('dashboard') }}" 
                               class="text-gray-600 hover:text-gray-900">
                                Отмена
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ссылка на публичный профиль -->
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Публичный профиль:</strong>
                    <a href="{{ route('organizations.public.show', $organization->id) }}" 
                       target="_blank"
                       class="text-blue-600 hover:text-blue-800 underline">
                        Посмотреть как видят клиенты
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-home-layout>

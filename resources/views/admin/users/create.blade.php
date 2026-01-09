<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Создание пользователя') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                Назад к списку
            </a>
    </div>
    </x-slot>

    
        
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
                    @csrf

                    <!-- Имя -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Имя *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>

                    <!-- Роль -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Роль *</label>
                        <select name="role" id="role" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                                onchange="toggleProfileFields()">
                            <option value="">Выберите роль</option>
                            <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Суперадмин</option>
                            <option value="organization" {{ old('role') === 'organization' ? 'selected' : '' }}>Владелец центра</option>
                            <option value="specialist" {{ old('role') === 'specialist' ? 'selected' : '' }}>Специалист</option>
                            <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>Родитель</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>

                    <!-- ФИО (для логопеда и родителя) -->
                    <div id="profile-fields" class="mb-6" style="display: none;">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">ФИО *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>

                    <!-- Специализация (только для логопеда) -->
                    <div id="therapist-fields" class="mb-6" style="display: none;">
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Специализация</label>
                        <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                    <!-- Телефон (только для родителя) -->
                    <div id="parent-fields" class="mb-6" style="display: none;">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                    <!-- Пароль -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Пароль *</label>
                        <input type="password" name="password" id="password" required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>

                    <!-- Подтверждение пароля -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Подтверждение пароля *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                    <!-- Кнопки -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150">
                            Отмена
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                            Создать
                        </button>
                </div>
                </form>
        </div>
    </div>
    </div>

    <script>
        function toggleProfileFields() {
            const role = document.getElementById('role').value;
            const profileFields = document.getElementById('profile-fields');
            const therapistFields = document.getElementById('therapist-fields');
            const parentFields = document.getElementById('parent-fields');

            if (role === 'superadmin' || role === 'organization' || role === '') {
                profileFields.style.display = 'none';
                therapistFields.style.display = 'none';
                parentFields.style.display = 'none';
            } else {
                profileFields.style.display = 'block';
                therapistFields.style.display = role === 'specialist' ? 'block' : 'none';
                parentFields.style.display = role === 'parent' ? 'block' : 'none';
            }
        }
    </script>
</x-admin-layout>

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Test Credentials -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-sm font-semibold text-blue-900 mb-3">Тестовые учётные записи:</h3>
        <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between p-2 bg-white rounded">
                <div>
                    <span class="font-medium text-gray-900">SuperAdmin:</span>
                    <span class="text-gray-600 ml-2">superadmin@logoped.test</span>
                </div>
                <button type="button" onclick="fillCredentials('superadmin@logoped.test', 'password')" 
                        class="text-xs px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700">
                    Заполнить
                </button>
            </div>
            <div class="flex items-center justify-between p-2 bg-white rounded">
                <div>
                    <span class="font-medium text-gray-900">Владелец центра:</span>
                    <span class="text-gray-600 ml-2">owner@rechevichok.ru</span>
                </div>
                <button type="button" onclick="fillCredentials('owner@rechevichok.ru', 'password')" 
                        class="text-xs px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Заполнить
                </button>
            </div>
            <div class="flex items-center justify-between p-2 bg-white rounded">
                <div>
                    <span class="font-medium text-gray-900">Специалист:</span>
                    <span class="text-gray-600 ml-2">specialist@logoped.test</span>
                </div>
                <button type="button" onclick="fillCredentials('specialist@logoped.test', 'password')" 
                        class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Заполнить
                </button>
            </div>
            <div class="flex items-center justify-between p-2 bg-white rounded">
                <div>
                    <span class="font-medium text-gray-900">Родитель:</span>
                    <span class="text-gray-600 ml-2">parent@logoped.test</span>
                </div>
                <button type="button" onclick="fillCredentials('parent@logoped.test', 'password')" 
                        class="text-xs px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                    Заполнить
                </button>
            </div>
        </div>
        <p class="text-xs text-blue-700 mt-3">Пароль для всех: <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>

<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $organization->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.organizations.edit', $organization) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Редактировать
                </a>
                <a href="{{ route('admin.organizations.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                    Назад к списку
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600">Пользователи</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['users'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600">Специалисты</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['specialists'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600">Клиенты</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['children'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600">Всего занятий</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['sessions_total'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600">Занятий в месяце</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['sessions_this_month'] }}</div>
                </div>
            </div>

            <!-- Информация об организации -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Информация об организации</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Тип</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $organization->type === 'center' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $organization->type === 'center' ? 'Центр' : 'Частный специалист' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Статус</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $organization->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $organization->is_active ? 'Активна' : 'Неактивна' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Телефон</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->phone ?? '—' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Адрес</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->address ?? '—' }}</dd>
                        </div>
                        @if($organization->website)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Веб-сайт</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ $organization->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $organization->website }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($organization->description)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Описание</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $organization->description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Специалисты -->
            @if($organization->specialists->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Специалисты ({{ $organization->specialists->count() }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($organization->specialists as $specialist)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900">{{ $specialist->full_name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $specialist->specialization ?? 'Логопед' }}</p>
                                    @if($specialist->user)
                                        <p class="text-sm text-gray-500 mt-2">{{ $specialist->user->email }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Последние занятия -->
            @if($organization->sessions->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Последние занятия</h3>
                        <div class="space-y-3">
                            @foreach($organization->sessions as $session)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $session->child->full_name ?? 'Клиент' }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">Специалист: {{ $session->specialist->full_name ?? '—' }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $session->start_time->format('d.m.Y H:i') }} ({{ $session->duration_minutes }} мин)
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $session->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $session->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-home-layout>

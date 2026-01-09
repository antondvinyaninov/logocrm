<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Просмотр пользователя') }}
            </h2>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->role !== 'parent')
                    <a href="{{ route('team.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Редактировать
                    </a>
                @endif
                
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isOrganization())
                    <a href="{{ route('team.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                        Назад к списку
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                        Назад
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Основная информация -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h3>
                            <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Роль -->
                        <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Роль</span>
                            </div>
                            <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block
                                @if($user->role === 'superadmin') bg-red-100 text-red-800
                                @elseif($user->role === 'organization') bg-purple-100 text-purple-800
                                @elseif($user->role === 'specialist') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                @if($user->role === 'superadmin') Суперадмин
                                @elseif($user->role === 'organization') Владелец центра
                                @elseif($user->role === 'specialist') Специалист
                                @else Родитель
                                @endif
                            </span>
                        </div>

                        <!-- Дата регистрации -->
                        <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Дата регистрации</span>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                        </div>

                        <!-- Email подтверждён -->
                        <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Email подтверждён</span>
                            </div>
                            @if($user->email_verified_at)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Да
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Нет
                                </span>
                            @endif
                        </div>

                        <!-- Последнее обновление -->
                        <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Последнее обновление</span>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Профиль специалиста -->
            @if($user->role === 'specialist' && $user->specialistProfile)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Профиль логопеда</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">ФИО</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->specialistProfile->full_name }}</p>
                            </div>

                            <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Специализация</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->specialistProfile->specialization ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Клиенты логопеда -->
                @if(auth()->user()->role !== 'parent')
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">Клиенты</h3>
                                        <p class="text-sm text-gray-600">Всего: {{ $children->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($children && $children->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($children as $child)
                                        <div class="p-4 bg-gradient-to-br from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                                            <div class="flex items-start space-x-3">
                                                <div class="bg-blue-100 p-2 rounded-lg">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-semibold text-gray-900">{{ $child->full_name }}</div>
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        Возраст: {{ \Carbon\Carbon::parse($child->birth_date)->age }} лет
                                                    </div>
                                                    @if($child->parent)
                                                        <div class="text-xs mt-1">
                                                            <span class="text-gray-500">Родитель:</span>
                                                            <a href="{{ route('users.show', $child->parent->user_id) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                                                {{ $child->parent->full_name }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if($child->tags)
                                                        <div class="flex flex-wrap gap-1 mt-2">
                                                            @foreach($child->tags as $tag)
                                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ $tag }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="text-gray-500 text-lg">У логопеда пока нет клиентов</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Для родителей показываем только статистику -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Опыт работы</h3>
                                    <p class="text-sm text-gray-600 mt-1">Работает с {{ $children->count() }} {{ $children->count() == 1 ? 'ребёнком' : ($children->count() < 5 ? 'детьми' : 'детьми') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Профиль родителя -->
            @if($user->role === 'parent' && $user->parentProfile)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Профиль родителя</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">ФИО</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->parentProfile->full_name }}</p>
                            </div>

                            <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Телефон</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->parentProfile->phone ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Дети родителя -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-lg mr-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Дети</h3>
                                    <p class="text-sm text-gray-600">Всего: {{ $children->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($children && $children->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($children as $child)
                                    <div class="p-5 bg-gradient-to-br from-green-50 to-white rounded-xl border border-green-200 hover:shadow-lg transition-all duration-200">
                                        <div class="flex items-start space-x-4">
                                            <div class="bg-green-100 p-3 rounded-lg">
                                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-lg text-gray-900">{{ $child->full_name }}</div>
                                                <div class="text-sm text-gray-600 mt-1 space-y-1">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Возраст: {{ \Carbon\Carbon::parse($child->birth_date)->age }} лет
                                                    </div>
                                                    @if($child->therapist)
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            <span class="text-gray-500">Логопед:</span>
                                                            <a href="{{ route('users.show', $child->therapist->user_id) }}" class="ml-1 text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                                                {{ $child->therapist->full_name }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($child->tags)
                                                    <div class="flex flex-wrap gap-1 mt-3">
                                                        @foreach($child->tags as $tag)
                                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ $tag }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-500 text-lg">У родителя пока нет детей в системе</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Действия -->
            @if(auth()->user()->role !== 'parent')
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Действия с пользователем</h3>
                                <p class="text-sm text-gray-600">Управление учётной записью пользователя</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('team.edit', $user) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Редактировать
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('team.destroy', $user) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-150 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Удалить
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-home-layout>

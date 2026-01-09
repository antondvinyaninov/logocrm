<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $parent->name }}</h1>
            <div class="flex items-center gap-3">
                @if(Auth::user()->isOrganization() || Auth::user()->isSpecialist())
                    <a href="{{ route('parents.edit', $parent) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                        Редактировать
                    </a>
                @endif
                <a href="{{ route('parents.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Назад к списку
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Информация о родителе -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Контактная информация</h2>
                
                <!-- Статус учетной записи -->
                @if(!$parent->hasAccount())
                    <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-md p-3">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Контакт без учетной записи</p>
                                <p class="text-xs text-yellow-700 mt-1">Родитель может зарегистрироваться самостоятельно, используя указанный email</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-3">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-800">Активная учетная запись</p>
                                <p class="text-xs text-green-700 mt-1">Родитель может войти в систему</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $parent->email }}</p>
                    </div>
                    
                    @if($parent->parentProfile && $parent->parentProfile->phone)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Телефон</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $parent->parentProfile->phone }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Дата регистрации</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $parent->created_at->format('d.m.Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дети родителя -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Дети</h2>
                    @if(Auth::user()->isOrganization() || Auth::user()->isSpecialist())
                        <a href="{{ route('children.create', ['parent_id' => $parent->id]) }}" 
                           class="inline-flex items-center px-3 py-2 bg-yellow-400 border border-transparent rounded-md text-sm font-medium text-gray-900 hover:bg-yellow-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Добавить ребенка
                        </a>
                    @endif
                </div>

                @if($children->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($children as $child)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center mr-3">
                                            <span class="text-white font-semibold text-lg">{{ substr($child->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-semibold text-gray-900">{{ $child->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $child->age }} лет</p>
                                        </div>
                                    </div>
                                </div>

                                @if($child->diagnosis)
                                    <div class="mb-3">
                                        <span class="text-xs font-medium text-gray-500">Диагноз:</span>
                                        <p class="text-sm text-gray-900">{{ $child->diagnosis }}</p>
                                    </div>
                                @endif

                                @if($child->specialist)
                                    <div class="mb-3">
                                        <span class="text-xs font-medium text-gray-500">Специалист:</span>
                                        <p class="text-sm text-gray-900">{{ $child->specialist->full_name }}</p>
                                    </div>
                                @endif

                                @if($child->therapy_goals)
                                    <div class="mb-3">
                                        <span class="text-xs font-medium text-gray-500">Цели терапии:</span>
                                        <p class="text-sm text-gray-700">{{ Str::limit($child->therapy_goals, 100) }}</p>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                    <div class="text-xs text-gray-500">
                                        Занятий: {{ $child->therapySessions()->count() }}
                                    </div>
                                    <a href="{{ route('children.show', $child) }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-900">
                                        Подробнее →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Нет детей</h3>
                        <p class="mt-1 text-sm text-gray-500">Добавьте первого ребенка для этого родителя</p>
                        @if(Auth::user()->isOrganization() || Auth::user()->isSpecialist())
                            <div class="mt-6">
                                <a href="{{ route('children.create', ['parent_id' => $parent->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md text-sm font-medium text-gray-900 hover:bg-yellow-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Добавить ребенка
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Последние занятия -->
            @if($children->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Последние занятия</h2>
                    
                    @php
                        $recentSessions = \App\Models\TherapySession::whereIn('child_id', $children->pluck('id'))
                            ->orderBy('start_time', 'desc')
                            ->limit(5)
                            ->with(['child', 'specialist'])
                            ->get();
                    @endphp

                    @if($recentSessions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ребенок</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Специалист</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentSessions as $session)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $session->start_time->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $session->child->name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $session->specialist->full_name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($session->status === 'completed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Проведено</span>
                                                @elseif($session->status === 'scheduled')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Запланировано</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отменено</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Занятий пока нет</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-home-layout>

<x-home-layout>
    <!-- Мои дети -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Мои дети</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($children as $child)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-xl font-bold text-gray-900">{{ $child->full_name }}</h4>
                    <p class="text-sm text-gray-600 mt-2">
                        Возраст: {{ \Carbon\Carbon::parse($child->birth_date)->age }} лет
                    </p>
                    @if($child->specialist)
                        <p class="text-sm text-gray-600 mt-1">
                            Специалист: {{ $child->specialist->full_name }}
                        </p>
                    @endif
                    <a href="{{ route('children.show', $child) }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900">
                        Подробнее →
                    </a>
                </div>
            @empty
                <p class="text-gray-500">Нет добавленных детей</p>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Ближайшие занятия -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Ближайшие занятия</h3>
                            <a href="{{ route('sessions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Все занятия →</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($upcomingSessions as $session)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $session->child->full_name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $session->start_time->format('d.m.Y H:i') }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $session->format === 'online' ? 'Онлайн' : 'Офлайн' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Нет запланированных занятий</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Домашние задания -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Домашние задания</h3>
                            <a href="{{ route('homeworks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Все задания →</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentHomeworks as $homework)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $homework->child->full_name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($homework->description, 50) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($homework->status === 'completed') bg-green-100 text-green-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $homework->status === 'completed' ? 'Выполнено' : 'В работе' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Нет домашних заданий</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

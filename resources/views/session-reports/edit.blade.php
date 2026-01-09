<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Редактировать отчёт
            </h2>
            <a href="{{ route('sessions.show', $session) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Назад к занятию
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-white p-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Занятие {{ $session->start_time->format('d.m.Y в H:i') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Ребёнок: {{ $session->child->full_name }}
                    </p>
                </div>

                <form method="POST" action="{{ route('session-reports.update', $report) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Цели занятия -->
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Цели занятия
                        </label>
                        <div id="goals-container" class="space-y-2">
                            @if($report->goals_json && count($report->goals_json) > 0)
                                @foreach($report->goals_json as $goal)
                                    <div class="flex gap-2">
                                        <input type="text" name="goals[]" value="{{ $goal }}"
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Введите цель занятия">
                                        <button type="button" onclick="removeGoal(this)" 
                                            class="rounded-md bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex gap-2">
                                    <input type="text" name="goals[]" 
                                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Введите цель занятия">
                                    <button type="button" onclick="removeGoal(this)" 
                                        class="rounded-md bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addGoal()" 
                            class="mt-2 inline-flex items-center rounded-md bg-indigo-100 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-200">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Добавить цель
                        </button>
                        @error('goals')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Комментарий -->
                    <div class="mb-6">
                        <label for="comment" class="mb-2 block text-sm font-medium text-gray-700">
                            Комментарий <span class="text-red-500">*</span>
                        </label>
                        <textarea id="comment" name="comment" rows="6" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Опишите ход занятия, достижения ребёнка, рекомендации...">{{ old('comment', $report->comment) }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Оценка -->
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Оценка занятия <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" 
                                        class="peer sr-only" {{ old('rating', $report->rating) == $i ? 'checked' : '' }} required>
                                    <svg class="h-10 w-10 text-gray-300 transition-colors peer-checked:text-yellow-400 hover:text-yellow-300" 
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="flex items-center justify-between">
                        <form method="POST" action="{{ route('session-reports.destroy', $report) }}" 
                            onsubmit="return confirm('Вы уверены, что хотите удалить этот отчёт?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                                Удалить отчёт
                            </button>
                        </form>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('sessions.show', $session) }}" 
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Отмена
                            </a>
                            <button type="submit" 
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                Сохранить изменения
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addGoal() {
            const container = document.getElementById('goals-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="goals[]" 
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Введите цель занятия">
                <button type="button" onclick="removeGoal(this)" 
                    class="rounded-md bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            container.appendChild(div);
        }

        function removeGoal(button) {
            const container = document.getElementById('goals-container');
            if (container.children.length > 1) {
                button.parentElement.remove();
            }
        }
    </script>
</x-home-layout>

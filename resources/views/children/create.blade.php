<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Добавить ребёнка') }}
            </h2>
            <a href="{{ route('children.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <form method="POST" action="{{ route('children.store') }}" class="p-6">
                    @csrf

                    <!-- ФИО -->
                    <div class="mb-6">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">ФИО ребёнка *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Дата рождения -->
                    <div class="mb-6">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Дата рождения *</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                               max="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Логопед (для родителей и админа) -->
                    @if(auth()->user()->role === 'parent' || auth()->user()->role === 'admin')
                        <div class="mb-6">
                            <label for="therapist_id" class="block text-sm font-medium text-gray-700 mb-2">Логопед</label>
                            <select name="therapist_id" id="therapist_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('therapist_id') border-red-500 @enderror">
                                <option value="">Выберите логопеда</option>
                                @foreach($therapists as $therapist)
                                    <option value="{{ $therapist->id }}" {{ old('therapist_id') == $therapist->id ? 'selected' : '' }}>
                                        {{ $therapist->full_name }}
                                        @if($therapist->specialization)
                                            - {{ $therapist->specialization }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('therapist_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Можно выбрать позже</p>
                        </div>
                    @endif

                    @if(auth()->user()->role !== 'parent')
                        <!-- Родитель (только для админа и логопеда) -->
                        <div class="mb-6">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Родитель *</label>
                            <select name="parent_id" id="parent_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                                <option value="">Выберите родителя</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->full_name }} ({{ $parent->user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Логопед (только для админа) -->
                        @if(auth()->user()->role === 'admin')
                            <div class="mb-6">
                                <label for="therapist_id" class="block text-sm font-medium text-gray-700 mb-2">Логопед</label>
                                <select name="therapist_id" id="therapist_id"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('therapist_id') border-red-500 @enderror">
                                    <option value="">Выберите логопеда</option>
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->id }}" {{ old('therapist_id') == $therapist->id ? 'selected' : '' }}>
                                            {{ $therapist->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('therapist_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Анамнез -->
                        <div class="mb-6">
                            <label for="anamnesis" class="block text-sm font-medium text-gray-700 mb-2">Анамнез</label>
                            <textarea name="anamnesis" id="anamnesis" rows="4"
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('anamnesis') border-red-500 @enderror">{{ old('anamnesis') }}</textarea>
                            @error('anamnesis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">История развития, особенности здоровья</p>
                        </div>

                        <!-- Цели -->
                        <div class="mb-6">
                            <label for="goals" class="block text-sm font-medium text-gray-700 mb-2">Цели занятий</label>
                            <textarea name="goals" id="goals" rows="4"
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('goals') border-red-500 @enderror">{{ old('goals') }}</textarea>
                            @error('goals')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Что планируется достичь в процессе занятий</p>
                        </div>

                        <!-- Теги -->
                        <div class="mb-6">
                            <label for="tags_input" class="block text-sm font-medium text-gray-700 mb-2">Теги</label>
                            <div id="tags-container" class="flex flex-wrap gap-2 mb-2"></div>
                            <div class="flex gap-2">
                                <input type="text" id="tags_input" placeholder="Добавить тег..."
                                       class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <button type="button" onclick="addTag()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Добавить
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Например: дислалия, заикание, ОНР</p>
                        </div>
                    @endif

                    <!-- Кнопки -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('children.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150">
                            Отмена
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                            Добавить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(auth()->user()->role !== 'parent')
        <script>
            let tags = @json(old('tags', []));

            function renderTags() {
                const container = document.getElementById('tags-container');
                container.innerHTML = '';
                
                tags.forEach((tag, index) => {
                    const tagEl = document.createElement('div');
                    tagEl.className = 'flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
                    tagEl.innerHTML = `
                        <span>${tag}</span>
                        <button type="button" onclick="removeTag(${index})" class="text-blue-600 hover:text-blue-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <input type="hidden" name="tags[]" value="${tag}">
                    `;
                    container.appendChild(tagEl);
                });
            }

            function addTag() {
                const input = document.getElementById('tags_input');
                const tag = input.value.trim();
                
                if (tag && !tags.includes(tag)) {
                    tags.push(tag);
                    renderTags();
                    input.value = '';
                }
            }

            function removeTag(index) {
                tags.splice(index, 1);
                renderTags();
            }

            document.getElementById('tags_input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addTag();
                }
            });

            // Инициализация
            renderTags();
        </script>
    @endif
</x-home-layout>

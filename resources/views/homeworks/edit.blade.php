<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Редактировать домашнее задание
            </h2>
            <a href="{{ route('homeworks.show', $homework) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Назад к заданию
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white p-6">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $homework->title }}</h3>
                </div>

                <form method="POST" action="{{ route('homeworks.update', $homework) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Ребёнок -->
                    <div class="mb-6">
                        <label for="child_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Ребёнок <span class="text-red-500">*</span>
                        </label>
                        <select id="child_id" name="child_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Выберите ребёнка</option>
                            @foreach($children as $child)
                                <option value="{{ $child->id }}" 
                                    {{ old('child_id', $homework->child_id) == $child->id ? 'selected' : '' }}>
                                    {{ $child->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('child_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Название -->
                    <div class="mb-6">
                        <label for="title" class="mb-2 block text-sm font-medium text-gray-700">
                            Название задания <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $homework->title) }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Например: Упражнения на звук Р">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Описание -->
                    <div class="mb-6">
                        <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                            Описание
                        </label>
                        <textarea id="description" name="description" rows="6"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Подробное описание задания, инструкции для родителей...">{{ old('description', $homework->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ссылка на материалы -->
                    <div class="mb-6">
                        <label for="resource_url" class="mb-2 block text-sm font-medium text-gray-700">
                            Ссылка на материалы
                        </label>
                        <input type="url" id="resource_url" name="resource_url" value="{{ old('resource_url', $homework->resource_url) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="https://example.com/materials">
                        <p class="mt-1 text-xs text-gray-500">Ссылка на видео, документы или другие материалы</p>
                        @error('resource_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Статус -->
                    <div class="mb-6">
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                            Статус <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active" {{ old('status', $homework->status) === 'active' ? 'selected' : '' }}>
                                Активно
                            </option>
                            <option value="done_by_parent" {{ old('status', $homework->status) === 'done_by_parent' ? 'selected' : '' }}>
                                Выполнено родителем
                            </option>
                            <option value="checked_by_specialist" {{ old('status', $homework->status) === 'checked_by_specialist' ? 'selected' : '' }}>
                                Проверено специалистом
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="flex items-center justify-between">
                        @can('delete', $homework)
                            <form method="POST" action="{{ route('homeworks.destroy', $homework) }}" 
                                onsubmit="return confirm('Вы уверены, что хотите удалить это задание?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                                    Удалить задание
                                </button>
                            </form>
                        @endcan

                        <div class="flex items-center gap-3">
                            <a href="{{ route('homeworks.show', $homework) }}" 
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
</x-home-layout>

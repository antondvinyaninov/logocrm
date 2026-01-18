<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Редактировать данные ребёнка') }}
            </h2>
            <a href="{{ route('children.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <form method="POST" action="{{ route('children.update', $child) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- ФИО -->
                    <div class="mb-6">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">ФИО ребёнка *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $child->full_name) }}" required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Дата рождения -->
                    <div class="mb-6">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Дата рождения *</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $child->birth_date?->format('Y-m-d')) }}" required
                               max="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Логопед (для родителей и админа) -->
                    @if(auth()->user()->role === 'parent' || auth()->user()->role === 'admin')
                        <div class="mb-6">
                            <label for="specialist_id" class="block text-sm font-medium text-gray-700 mb-2">Логопед</label>
                            <select name="specialist_id" id="specialist_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('specialist_id') border-red-500 @enderror">
                                <option value="">Выберите логопеда</option>
                                @foreach($specialists as $specialist)
                                    <option value="{{ $specialist->id }}" {{ old('specialist_id', $child->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                        {{ $specialist->full_name }}
                                        @if($specialist->specialization)
                                            - {{ $specialist->specialization }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('specialist_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $child->parent_id) == $parent->id ? 'selected' : '' }}>
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
                                <label for="specialist_id" class="block text-sm font-medium text-gray-700 mb-2">Логопед</label>
                                <select name="specialist_id" id="specialist_id"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('specialist_id') border-red-500 @enderror">
                                    <option value="">Выберите логопеда</option>
                                    @foreach($specialists as $specialist)
                                        <option value="{{ $specialist->id }}" {{ old('specialist_id', $child->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                            {{ $specialist->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialist_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endif

                    <!-- Кнопки -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('children.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150">
                            Отмена
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-home-layout>

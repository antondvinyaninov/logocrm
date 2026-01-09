<x-home-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Заявка от {{ $lead->name }}
            </h2>
            <a href="{{ route('admin.leads.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <!-- Основная информация -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $lead->name }}</h3>
                            <p class="mt-1 text-gray-600">{{ $lead->created_at->format('d.m.Y в H:i') }}</p>
                        </div>
                        <span class="rounded-full px-4 py-2 text-sm font-semibold
                            @if($lead->status === 'new') bg-orange-100 text-orange-800
                            @elseif($lead->status === 'in_progress') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            @if($lead->status === 'new') Новая
                            @elseif($lead->status === 'in_progress') В работе
                            @else Закрыта
                            @endif
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Контакт -->
                    <div class="mb-6">
                        <h4 class="mb-2 text-sm font-semibold text-gray-700">Контакт</h4>
                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="text-lg text-gray-900">{{ $lead->contact }}</p>
                        </div>
                    </div>

                    <!-- Сообщение -->
                    @if($lead->message)
                        <div class="mb-6">
                            <h4 class="mb-2 text-sm font-semibold text-gray-700">Сообщение</h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="whitespace-pre-line text-gray-700">{{ $lead->message }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Заметки -->
                    <div class="mb-6">
                        <h4 class="mb-2 text-sm font-semibold text-gray-700">Заметки администратора</h4>
                        <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
                            @csrf
                            @method('PUT')
                            <textarea name="notes" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Добавьте заметки о работе с этой заявкой...">{{ old('notes', $lead->notes) }}</textarea>
                            
                            <div class="mt-4 flex items-center gap-4">
                                <div class="flex-1">
                                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Статус</label>
                                    <select id="status" name="status" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="new" {{ old('status', $lead->status) === 'new' ? 'selected' : '' }}>Новая</option>
                                        <option value="in_progress" {{ old('status', $lead->status) === 'in_progress' ? 'selected' : '' }}>В работе</option>
                                        <option value="closed" {{ old('status', $lead->status) === 'closed' ? 'selected' : '' }}>Закрыта</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-end gap-2">
                                    <button type="submit" 
                                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Удаление -->
                    <div class="border-t border-gray-200 pt-6">
                        <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" 
                            onsubmit="return confirm('Вы уверены, что хотите удалить эту заявку?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                                Удалить заявку
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

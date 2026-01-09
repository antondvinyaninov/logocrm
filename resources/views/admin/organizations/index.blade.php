<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                Управление организациями
            </h2>
            <a href="{{ route('admin.organizations.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Добавить организацию
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Контакты</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статистика</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($organizations as $org)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $org->name }}</div>
                                            @if($org->website)
                                                <div class="text-sm text-gray-500">
                                                    <a href="{{ $org->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                        {{ Str::limit($org->website, 30) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $org->type === 'center' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $org->type === 'center' ? 'Центр' : 'Частный' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $org->email }}</div>
                                            @if($org->phone)
                                                <div class="text-sm text-gray-500">{{ $org->phone }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                Пользователи: {{ $org->users_count }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Специалисты: {{ $org->specialists_count }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Клиенты: {{ $org->children_count }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Занятия: {{ $org->sessions_count }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('admin.organizations.toggle-active', $org) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $org->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                    {{ $org->is_active ? 'Активна' : 'Неактивна' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.organizations.show', $org) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Просмотр</a>
                                            <a href="{{ route('admin.organizations.edit', $org) }}" class="text-blue-600 hover:text-blue-900 mr-3">Изменить</a>
                                            <form action="{{ route('admin.organizations.destroy', $org) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Вы уверены, что хотите удалить эту организацию?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Нет организаций</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</x-admin-layout>

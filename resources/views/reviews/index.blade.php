<x-home-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-800">Отзывы</h1>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($reviews->isEmpty())
                <p class="text-gray-500">Отзывов пока нет</p>
            @else
                <div class="space-y-6">
                    @foreach($reviews as $review)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="font-semibold text-gray-800">{{ $review->user->name }}</span>
                                                <span class="text-gray-500">→</span>
                                                <span class="text-gray-600">{{ $review->specialist->user->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="flex">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d.m.Y') }}</span>
                                            </div>
                                        </div>
                                        @if(auth()->user()->isOrganization())
                                            <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Вы уверены?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if($review->comment)
                                        <p class="text-gray-700 mb-4">{{ $review->comment }}</p>
                                    @endif

                                    @if($review->response)
                                        <div class="bg-gray-50 rounded-lg p-4 mt-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                <span class="font-semibold text-gray-800">Ответ специалиста</span>
                                                <span class="text-sm text-gray-500">{{ $review->response_at->format('d.m.Y') }}</span>
                                            </div>
                                            <p class="text-gray-700">{{ $review->response }}</p>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('reviews.update', $review) }}" class="mt-4">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="response-{{ $review->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Ответить на отзыв
                                                </label>
                                                <textarea 
                                                    id="response-{{ $review->id }}"
                                                    name="response" 
                                                    rows="3" 
                                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="Напишите ваш ответ..."
                                                ></textarea>
                                            </div>
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                                Отправить ответ
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-home-layout>

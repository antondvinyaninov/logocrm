<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // Специалисты видят отзывы о себе, организации - все отзывы своей организации
        if (auth()->user()->isSpecialist()) {
            $reviews = Review::where('specialist_id', auth()->user()->specialistProfile->id)
                ->with(['user', 'specialist'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } elseif (auth()->user()->isOrganization()) {
            $reviews = Review::where('organization_id', auth()->user()->organization_id)
                ->with(['user', 'specialist'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            abort(403, 'Доступ запрещен');
        }

        return view('reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        // Проверяем права доступа
        if (auth()->user()->isSpecialist()) {
            if ($review->specialist_id !== auth()->user()->specialistProfile->id) {
                abort(403, 'Доступ запрещен');
            }
        } elseif (auth()->user()->isOrganization()) {
            if ($review->organization_id !== auth()->user()->organization_id) {
                abort(403, 'Доступ запрещен');
            }
        } else {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'response' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'response' => $validated['response'],
            'response_at' => now(),
        ]);

        return redirect()->route('reviews.index')
            ->with('success', 'Ответ на отзыв успешно добавлен');
    }

    public function destroy(Review $review)
    {
        // Только организация может удалять отзывы
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        if ($review->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Отзыв успешно удален');
    }
}

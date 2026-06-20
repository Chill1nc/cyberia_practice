<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string',
            'is_anonymous' => 'nullable|boolean',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
        ]);

        $user = $request->user();
        
        $authorName = 'Аноним';
        if (!$request->input('is_anonymous')) {
            $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            $authorName = $fullName ?: ($user->email ?: $user->phone);
        $comment = $request->comment;
        $pros = $request->input('pros');
        $cons = $request->input('cons');
        
        if ($pros || $cons) {
            $meta = [];
            if ($pros) $meta[] = "[Плюсы]: " . $pros;
            if ($cons) $meta[] = "[Минусы]: " . $cons;
            $comment .= "\n\n" . implode("\n", $meta);
        }

        $review = Review::create([
            'book_id' => $request->book_id,
            'author_name' => $authorName,
            'comment' => $comment,
            'rating' => $request->rating,
        ]);

        return response()->json($review, 201);
    }

    public function rate(Request $request, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|in:-1,1',
        ]);

        $userId = $request->user()->id;
        $rating = (int) $request->input('rating');

        $existingRate = \App\Models\ReviewRate::where('user_id', $userId)
            ->where('review_id', $review->id)
            ->first();

        if ($existingRate) {
            if ($existingRate->rating === $rating) {
                $existingRate->delete();
                $message = __('messages.reviews.unrated');
            } else {
                $existingRate->update(['rating' => $rating]);
                $message = __('messages.reviews.rated');
            }
        } else {
            \App\Models\ReviewRate::create([
                'user_id' => $userId,
                'review_id' => $review->id,
                'rating' => $rating,
            ]);
            $message = __('messages.reviews.rated');
        }

        $review->loadCount(['likes', 'dislikes']);

        return response()->json([
            'message' => $message,
            'likes_count' => $review->likes_count,
            'dislikes_count' => $review->dislikes_count,
        ]);
    }
}

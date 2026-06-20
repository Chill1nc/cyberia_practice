<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $filter = new \App\Filters\BookFilter($request->only([
            'author_id', 'genre_id', 'year_from', 'year_to',
            'publisher', 'cover_type', 'age_limit',
            'pages_from', 'pages_to', 'price_from', 'price_to',
            'search',
        ]));
        $sorter = new \App\Filters\BookSorter($request->input('sort'));

        $favorites = $request->user()
            ->favoriteBooks()
            ->with(['author', 'genre', 'media', 'reviews', 'reviews.currentUserRate'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->filter($filter)
            ->sort($sorter)
            ->paginate($request->input('per_page', 10));

        $favorites->through(function ($book) {
            $book->images = $book->getMedia('images')->map->getUrl();
            return $book;
        });

        return response()->json($favorites);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $user = $request->user();
        $user->favoriteBooks()->syncWithoutDetaching([$request->book_id]);

        return response()->json([
            'message' => __('messages.favorites.added')
        ], 201);
    }

    public function destroy(Request $request, $bookId)
    {
        $user = $request->user();
        $user->favoriteBooks()->detach($bookId);

        return response()->json([
            'message' => __('messages.favorites.removed')
        ]);
    }

    public function clear(Request $request)
    {
        $request->user()->favoriteBooks()->detach();

        return response()->json([
            'message' => __('messages.favorites.cleared')
        ]);
    }
}

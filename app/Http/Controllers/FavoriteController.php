<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favoriteBooks()
            ->with(['author', 'genre'])
            ->paginate($request->input('per_page', 10));

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
            'message' => 'Книга добавлена в избранное.'
        ], 201);
    }

    public function destroy(Request $request, $bookId)
    {
        $user = $request->user();
        $user->favoriteBooks()->detach($bookId);

        return response()->json([
            'message' => 'Книга удалена из избранного.'
        ]);
    }

    public function clear(Request $request)
    {
        $request->user()->favoriteBooks()->detach();

        return response()->json([
            'message' => 'Список избранного очищен.'
        ]);
    }
}

<?php
namespace App\Http\Controllers;
use App\Filters\BookFilter;
use App\Filters\BookSorter;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\GenreResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
class CatalogController extends Controller
{
    public function books(Request $request)
    {
        $filter = new BookFilter($request->only(['author_id', 'genre_id', 'year_from', 'year_to']));
        $sorter = new BookSorter($request->input('sort'));
        $books = Book::with(['author', 'genre'])
            ->filter($filter)
            ->sort($sorter)
            ->paginate($request->input('per_page', 10));
        return response()->json($books);
    }
    public function genres(Request $request)
    {
        $genres = Genre::withCount('books')
            ->simplePaginate($request->input('per_page', 10));
        return GenreResource::collection($genres)->response()->getData(true);
    }
    public function authors(Request $request)
    {
        $authors = Author::withCount('books')
            ->with('lastBook')
            ->paginate($request->input('per_page', 10));
        return AuthorResource::collection($authors)->response()->getData(true);
    }
}
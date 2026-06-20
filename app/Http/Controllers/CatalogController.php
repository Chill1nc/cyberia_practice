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
    private const FILTER_KEYS = [
        'author_id', 'genre_id', 'year_from', 'year_to',
        'publisher', 'cover_type', 'age_limit',
        'pages_from', 'pages_to', 'price_from', 'price_to',
        'search',
    ];

    public function books(Request $request)
    {
        $filter = new BookFilter($request->only(self::FILTER_KEYS));
        $sorter = new BookSorter($request->input('sort'));

        $books = Book::with(['author', 'genre', 'media', 'reviews', 'reviews.currentUserRate'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->filter($filter)
            ->sort($sorter)
            ->paginate($request->input('per_page', 10));

        $books->through(function ($book) {
            $book->images = $book->getMedia('images')->map->getUrl();
            return $book;
        });

        return response()->json($books);
    }

    public function show(Book $book)
    {
        $book->load(['author', 'genre', 'media', 'reviews', 'reviews.currentUserRate']);
        $book->loadCount('reviews');
        $book->loadAvg('reviews', 'rating');
        $book->images = $book->getMedia('images')->map->getUrl();

        return response()->json($book);
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
            ->with(['lastBook', 'media'])
            ->paginate($request->input('per_page', 10));

        return AuthorResource::collection($authors)->response()->getData(true);
    }

    public function filters(Request $request)
    {
        $activeFilters = $request->only(self::FILTER_KEYS);

        $query = function (array $excludeKeys) use ($activeFilters) {
            $filtered = array_diff_key($activeFilters, array_flip($excludeKeys));
            return Book::query()->filter(new BookFilter($filtered));
        };

        $genres = Genre::whereIn('id', function ($q) use ($query) {
            $q->select('genre_id')->from('books')
                ->whereIn('id', $query(['genre_id'])->select('id'));
        })->get(['id', 'name']);

        $authors = Author::whereIn('id', function ($q) use ($query) {
            $q->select('author_id')->from('books')
                ->whereIn('id', $query(['author_id'])->select('id'));
        })->get(['id', 'first_name', 'last_name']);

        $publishers = $query(['publisher'])
            ->whereNotNull('publisher')->select('publisher')->distinct()->pluck('publisher');

        $coverTypes = $query(['cover_type'])
            ->whereNotNull('cover_type')->select('cover_type')->distinct()->pluck('cover_type');

        $ageLimits = $query(['age_limit'])
            ->whereNotNull('age_limit')->select('age_limit')->distinct()->pluck('age_limit');

        $rangeFields = [
            'years'   => ['keys' => ['year_from', 'year_to'],     'col' => 'year',   'cast' => 'int'],
            'prices'  => ['keys' => ['price_from', 'price_to'],   'col' => 'price',  'cast' => 'float'],
            'pages'   => ['keys' => ['pages_from', 'pages_to'],   'col' => 'pages',  'cast' => 'int'],
            'weights' => ['keys' => ['weight_from', 'weight_to'], 'col' => 'weight', 'cast' => 'int'],
        ];

        $ranges = [];
        foreach ($rangeFields as $key => $cfg) {
            $stats = $query($cfg['keys'])->selectRaw("MIN({$cfg['col']}) as min, MAX({$cfg['col']}) as max")->first();
            $cast = $cfg['cast'] === 'float' ? 'floatval' : 'intval';
            $ranges[$key] = ['min' => $cast($stats?->min ?? 0), 'max' => $cast($stats?->max ?? 0)];
        }

        return response()->json(array_merge([
            'genres'      => $genres,
            'authors'     => $authors,
            'publishers'  => $publishers,
            'cover_types' => $coverTypes,
            'age_limits'  => $ageLimits,
        ], $ranges));
    }
}
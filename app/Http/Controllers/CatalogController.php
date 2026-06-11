<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;

class CatalogController extends Controller
{
    public function books()
    {
        return response()->json(
            Book::with(['author', 'genre'])->get()
        );
    }

    public function genres()
    {
        return response()->json(
            Genre::all()
        );
    }

    public function authors()
    {
        return response()->json(
            Author::all()
        );
    }
}

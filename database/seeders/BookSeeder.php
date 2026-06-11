<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $authors = Author::all();
        $genres = Genre::all();

        if ($authors->isEmpty() || $genres->isEmpty()) {
            return;
        }

        foreach ($authors as $author) {
            Book::factory(rand(2, 5))->create([
                'author_id' => $author->id,
                'genre_id' => fn() => $genres->random()->id,
            ]);
        }
    }
}

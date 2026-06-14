<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_endpoint_is_paginated()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);
        
        Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'War and Peace',
            'price' => 999.00,
            'year' => 1869
        ]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'price',
                        'year',
                        'author',
                        'genre'
                    ]
                ],
                'first_page_url',
                'last_page',
                'total'
            ]);
    }

    public function test_genres_endpoint_uses_simple_pagination_and_shows_books_count()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);
        
        Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'War and Peace',
            'price' => 999.00,
            'year' => 1869
        ]);

        $response = $this->getJson('/api/genres');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'books_count'
                    ]
                ],
                'links' => [
                    'first',
                    'next',
                    'prev'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'path',
                    'per_page',
                    'to'
                ]
            ]);

        $this->assertEquals(1, $response->json('data.0.books_count'));
    }

    public function test_authors_endpoint_is_paginated_and_shows_books_count_and_last_book()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);
        
        $book1 = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'War and Peace',
            'price' => 999.00,
            'year' => 1869
        ]);

        $book2 = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Anna Karenina',
            'price' => 500.00,
            'year' => 1877
        ]);

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'books_count',
                        'last_book' => [
                            'id',
                            'title'
                        ]
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);

        $this->assertEquals(2, $response->json('data.0.books_count'));
        // The last book should be Anna Karenina (id of book2)
        $this->assertEquals($book2->id, $response->json('data.0.last_book.id'));
    }
}

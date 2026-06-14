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
        $this->assertEquals($book2->id, $response->json('data.0.last_book.id'));
    }

    public function test_books_can_be_filtered_by_author()
    {
        $author1 = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $author2 = Author::create(['first_name' => 'Fyodor', 'last_name' => 'Dostoevsky']);
        $genre = Genre::create(['name' => 'Classic']);

        $book1 = Book::create([
            'author_id' => $author1->id,
            'genre_id' => $genre->id,
            'title' => 'War and Peace',
            'price' => 999.00,
            'year' => 1869
        ]);

        $book2 = Book::create([
            'author_id' => $author2->id,
            'genre_id' => $genre->id,
            'title' => 'Crime and Punishment',
            'price' => 799.00,
            'year' => 1866
        ]);

        $response = $this->getJson("/api/books?author_id={$author1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $book1->id);
    }

    public function test_books_can_be_filtered_by_genre()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre1 = Genre::create(['name' => 'Classic']);
        $genre2 = Genre::create(['name' => 'Drama']);

        $book1 = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre1->id,
            'title' => 'War and Peace',
            'price' => 999.00,
            'year' => 1869
        ]);

        $book2 = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre2->id,
            'title' => 'Anna Karenina',
            'price' => 500.00,
            'year' => 1877
        ]);

        $response = $this->getJson("/api/books?genre_id={$genre2->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $book2->id);
    }

    public function test_books_can_be_filtered_by_year_range()
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

        $response = $this->getJson('/api/books?year_from=1870&year_to=1880');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $book2->id);
    }

    public function test_books_can_be_sorted_by_price()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        $book1 = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Cheap Book',
            'price' => 100.00,
            'year' => 1900
        ]);

        $book2 = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Expensive Book',
            'price' => 2000.00,
            'year' => 1905
        ]);

        $response = $this->getJson('/api/books?sort=price_asc');
        $response->assertStatus(200);
        $this->assertEquals($book1->id, $response->json('data.0.id'));
        $this->assertEquals($book2->id, $response->json('data.1.id'));

        $response = $this->getJson('/api/books?sort=price_desc');
        $response->assertStatus(200);
        $this->assertEquals($book2->id, $response->json('data.0.id'));
        $this->assertEquals($book1->id, $response->json('data.1.id'));
    }
}

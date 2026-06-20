<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
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

    public function test_books_can_be_filtered_by_publisher()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        $book1 = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'War and Peace',
            'price'     => 999.00,
            'year'      => 1869,
            'publisher' => 'Азбука',
        ]);

        Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Anna Karenina',
            'price'     => 500.00,
            'year'      => 1877,
            'publisher' => 'Эксмо',
        ]);

        $response = $this->getJson('/api/books?publisher=Азбука');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $book1->id);
    }

    public function test_books_can_be_filtered_by_cover_type()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        $book1 = Book::create([
            'author_id'  => $author->id,
            'genre_id'   => $genre->id,
            'title'      => 'War and Peace',
            'price'      => 999.00,
            'year'       => 1869,
            'cover_type' => 'твердая',
        ]);

        Book::create([
            'author_id'  => $author->id,
            'genre_id'   => $genre->id,
            'title'      => 'Anna Karenina',
            'price'      => 500.00,
            'year'       => 1877,
            'cover_type' => 'мягкая',
        ]);

        $response = $this->getJson('/api/books?cover_type=твердая');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $book1->id);
    }

    public function test_books_can_be_filtered_by_pages_range()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Short Book',
            'price'     => 300.00,
            'year'      => 1900,
            'pages'     => 100,
        ]);

        $bigBook = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Long Book',
            'price'     => 800.00,
            'year'      => 1910,
            'pages'     => 600,
        ]);

        $response = $this->getJson('/api/books?pages_from=500');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $bigBook->id);
    }

    public function test_books_can_be_filtered_by_price_range()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Cheap Book',
            'price'     => 150.00,
            'year'      => 1900,
        ]);

        $expensive = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Expensive Book',
            'price'     => 2500.00,
            'year'      => 1910,
        ]);

        $response = $this->getJson('/api/books?price_from=1000');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $expensive->id);
    }

    public function test_books_can_be_sorted_by_year()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        $old = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Old Book',
            'price'     => 300.00,
            'year'      => 1800,
        ]);

        $new = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'New Book',
            'price'     => 500.00,
            'year'      => 2020,
        ]);

        $response = $this->getJson('/api/books?sort=year_asc');
        $response->assertStatus(200);
        $this->assertEquals($old->id, $response->json('data.0.id'));

        $response = $this->getJson('/api/books?sort=year_desc');
        $response->assertStatus(200);
        $this->assertEquals($new->id, $response->json('data.0.id'));
    }

    public function test_books_can_be_sorted_by_reviews_count()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        $popular = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Popular Book',
            'price'     => 500.00,
            'year'      => 1900,
        ]);

        $quiet = Book::create([
            'author_id' => $author->id,
            'genre_id'  => $genre->id,
            'title'     => 'Quiet Book',
            'price'     => 300.00,
            'year'      => 1910,
        ]);

        foreach (range(1, 3) as $i) {
            Review::create([
                'book_id'     => $popular->id,
                'author_name' => "Reader {$i}",
                'comment'     => 'Good read',
                'rating'      => 5,
            ]);
        }

        Review::create([
            'book_id'     => $quiet->id,
            'author_name' => 'Reader',
            'comment'     => 'Decent',
            'rating'      => 3,
        ]);

        $response = $this->getJson('/api/books?sort=reviews_count_desc');
        $response->assertStatus(200);
        $this->assertEquals($popular->id, $response->json('data.0.id'));

        $response = $this->getJson('/api/books?sort=reviews_count_asc');
        $response->assertStatus(200);
        $this->assertEquals($quiet->id, $response->json('data.0.id'));
    }

    public function test_filters_endpoint_returns_correct_structure()
    {
        $author = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $genre = Genre::create(['name' => 'Classic']);

        Book::create([
            'author_id'  => $author->id,
            'genre_id'   => $genre->id,
            'title'      => 'War and Peace',
            'price'      => 999.00,
            'year'       => 1869,
            'publisher'  => 'Азбука',
            'cover_type' => 'твердая',
            'age_limit'  => '16+',
            'pages'      => 1200,
            'weight'     => 800,
        ]);

        $response = $this->getJson('/api/books/filters');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'genres'      => [['id', 'name']],
                'authors'     => [['id', 'first_name', 'last_name']],
                'publishers',
                'cover_types',
                'age_limits',
                'years'   => ['min', 'max'],
                'prices'  => ['min', 'max'],
                'pages'   => ['min', 'max'],
                'weights' => ['min', 'max'],
            ]);
    }

    public function test_filters_endpoint_excludes_irrelevant_genres_when_author_is_active()
    {
        $author1 = Author::create(['first_name' => 'Leo', 'last_name' => 'Tolstoy']);
        $author2 = Author::create(['first_name' => 'Fyodor', 'last_name' => 'Dostoevsky']);
        $genre1 = Genre::create(['name' => 'Classic']);
        $genre2 = Genre::create(['name' => 'Drama']);

        Book::create([
            'author_id' => $author1->id,
            'genre_id'  => $genre1->id,
            'title'     => 'War and Peace',
            'price'     => 999.00,
            'year'      => 1869,
        ]);

        Book::create([
            'author_id' => $author2->id,
            'genre_id'  => $genre2->id,
            'title'     => 'Crime and Punishment',
            'price'     => 799.00,
            'year'      => 1866,
        ]);

        $response = $this->getJson("/api/books/filters?author_id={$author1->id}");
        $response->assertStatus(200);

        $genreIds = collect($response->json('genres'))->pluck('id')->all();
        $this->assertContains($genre1->id, $genreIds);
        $this->assertNotContains($genre2->id, $genreIds);
    }
}

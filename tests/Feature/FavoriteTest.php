<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private function createBook()
    {
        $author = Author::firstOrCreate(['first_name' => 'Test', 'last_name' => 'Author']);
        $genre = Genre::firstOrCreate(['name' => 'Test Genre']);
        return Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Test Book ' . uniqid(),
            'price' => 500.00,
            'year' => 2026
        ]);
    }

    public function test_guest_cannot_access_favorites_endpoints()
    {
        $book = $this->createBook();

        // GET index
        $this->getJson('/api/favorites')
            ->assertStatus(401);

        // POST store
        $this->postJson('/api/favorites', ['book_id' => $book->id])
            ->assertStatus(401);

        // DELETE destroy
        $this->deleteJson("/api/favorites/{$book->id}")
            ->assertStatus(401);

        // DELETE clear
        $this->deleteJson('/api/favorites')
            ->assertStatus(401);
    }

    public function test_user_can_add_book_to_favorites()
    {
        $user = User::factory()->create();
        $book = $this->createBook();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/favorites', [
                'book_id' => $book->id
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Книга добавлена в избранное.']);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);
    }

    public function test_user_cannot_add_nonexistent_book_to_favorites()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/favorites', [
                'book_id' => 9999
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['book_id']);
    }

    public function test_user_can_view_favorites_list_with_pagination()
    {
        $user = User::factory()->create();
        $book = $this->createBook();
        
        $user->favoriteBooks()->attach($book->id);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/favorites');

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

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($book->id, $response->json('data.0.id'));
    }

    public function test_user_does_not_see_other_users_favorites()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book1 = $this->createBook();
        $book2 = $this->createBook();

        $user1->favoriteBooks()->attach($book1->id);
        $user2->favoriteBooks()->attach($book2->id);

        $response = $this->actingAs($user1, 'sanctum')
            ->getJson('/api/favorites');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($book1->id, $response->json('data.0.id'));
    }

    public function test_user_can_remove_book_from_favorites()
    {
        $user = User::factory()->create();
        $book = $this->createBook();

        $user->favoriteBooks()->attach($book->id);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/favorites/{$book->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Книга удалена из избранного.']);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);
    }

    public function test_user_can_clear_all_favorites()
    {
        $user = User::factory()->create();
        $book1 = $this->createBook();
        $book2 = $this->createBook();

        $user->favoriteBooks()->attach([$book1->id, $book2->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/favorites');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Список избранного очищен.']);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id
        ]);
    }
}

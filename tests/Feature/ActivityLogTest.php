<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_logs_activity()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'registered',
            'loggable_type' => User::class,
        ]);

        $log = ActivityLog::where('action', 'registered')->first();
        $this->assertNotNull($log);
        $this->assertEquals(['email' => 'newuser@example.com'], $log->payload);
    }

    public function test_user_login_logs_activity()
    {
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'login_code' => '4321',
            'login_code_expires_at' => now()->addMinutes(5)
        ]);

        $response = $this->postJson('/api/login/verify', [
            'email' => 'loginuser@example.com',
            'code' => '4321'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'login',
            'loggable_type' => User::class,
            'loggable_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }

    public function test_book_crud_logs_activity()
    {
        $author = Author::factory()->create();
        $genre = Genre::factory()->create();

        // 1. Create
        $book = Book::create([
            'title' => 'Test Logged Book',
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'price' => 499.00,
            'year' => 2026,
            'publisher' => 'Publisher X',
            'pages' => 300,
            'size' => 'A5',
            'cover_type' => 'Hardback',
            'weight' => 500,
            'age_limit' => '16+',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'loggable_type' => Book::class,
            'loggable_id' => $book->id,
        ]);

        $log = ActivityLog::where('action', 'created')
            ->where('loggable_type', Book::class)
            ->where('loggable_id', $book->id)
            ->first();
        $this->assertNotNull($log);
        $this->assertEquals('Test Logged Book', $log->payload['title'] ?? null);

        // 2. Update
        $book->update([
            'title' => 'Test Logged Book Updated',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'loggable_type' => Book::class,
            'loggable_id' => $book->id,
        ]);

        $logUpdate = ActivityLog::where('action', 'updated')
            ->where('loggable_type', Book::class)
            ->where('loggable_id', $book->id)
            ->first();
        $this->assertNotNull($logUpdate);
        $this->assertEquals('Test Logged Book Updated', $logUpdate->payload['changes']['title'] ?? null);

        // 3. Delete
        $book->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'deleted',
            'loggable_type' => Book::class,
            'loggable_id' => $book->id,
        ]);

        $logDelete = ActivityLog::where('action', 'deleted')
            ->where('loggable_type', Book::class)
            ->where('loggable_id', $book->id)
            ->first();
        $this->assertNotNull($logDelete);
        $this->assertEquals('Test Logged Book Updated', $logDelete->payload['title'] ?? null);
    }

    public function test_author_crud_logs_activity()
    {
        // 1. Create
        $author = Author::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'loggable_type' => Author::class,
            'loggable_id' => $author->id,
        ]);

        $log = ActivityLog::where('action', 'created')
            ->where('loggable_type', Author::class)
            ->where('loggable_id', $author->id)
            ->first();
        $this->assertNotNull($log);
        $this->assertEquals('Smith', $log->payload['last_name'] ?? null);

        // 2. Update
        $author->update([
            'last_name' => 'Doe',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'loggable_type' => Author::class,
            'loggable_id' => $author->id,
        ]);

        $logUpdate = ActivityLog::where('action', 'updated')
            ->where('loggable_type', Author::class)
            ->where('loggable_id', $author->id)
            ->first();
        $this->assertNotNull($logUpdate);
        $this->assertEquals('Doe', $logUpdate->payload['changes']['last_name'] ?? null);

        // 3. Delete
        $author->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'deleted',
            'loggable_type' => Author::class,
            'loggable_id' => $author->id,
        ]);

        $logDelete = ActivityLog::where('action', 'deleted')
            ->where('loggable_type', Author::class)
            ->where('loggable_id', $author->id)
            ->first();
        $this->assertNotNull($logDelete);
        $this->assertEquals('Doe', $logDelete->payload['last_name'] ?? null);
    }

    public function test_genre_crud_logs_activity()
    {
        // 1. Create
        $genre = Genre::create([
            'name' => 'Sci-Fi',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'loggable_type' => Genre::class,
            'loggable_id' => $genre->id,
        ]);

        $log = ActivityLog::where('action', 'created')
            ->where('loggable_type', Genre::class)
            ->where('loggable_id', $genre->id)
            ->first();
        $this->assertNotNull($log);
        $this->assertEquals('Sci-Fi', $log->payload['name'] ?? null);

        // 2. Update
        $genre->update([
            'name' => 'Science Fiction',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'loggable_type' => Genre::class,
            'loggable_id' => $genre->id,
        ]);

        $logUpdate = ActivityLog::where('action', 'updated')
            ->where('loggable_type', Genre::class)
            ->where('loggable_id', $genre->id)
            ->first();
        $this->assertNotNull($logUpdate);
        $this->assertEquals('Science Fiction', $logUpdate->payload['changes']['name'] ?? null);

        // 3. Delete
        $genre->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'deleted',
            'loggable_type' => Genre::class,
            'loggable_id' => $genre->id,
        ]);

        $logDelete = ActivityLog::where('action', 'deleted')
            ->where('loggable_type', Genre::class)
            ->where('loggable_id', $genre->id)
            ->first();
        $this->assertNotNull($logDelete);
        $this->assertEquals('Science Fiction', $logDelete->payload['name'] ?? null);
    }
}

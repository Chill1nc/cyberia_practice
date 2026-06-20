<?php

namespace App\Observers;

use App\Models\Book;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class BookObserver
{
    private function causer()
    {
        return Auth::guard('sanctum')->user() ?? Auth::guard('admin')->user();
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        ActivityLogger::log('created', $book, $this->causer(), [
            'title' => $book->title,
        ]);
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        ActivityLogger::log('updated', $book, $this->causer(), [
            'changes' => $book->getChanges(),
        ]);
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        ActivityLogger::log('deleted', $book, $this->causer(), [
            'title' => $book->title,
        ]);
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}

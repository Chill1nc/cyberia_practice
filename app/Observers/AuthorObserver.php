<?php

namespace App\Observers;

use App\Models\Author;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class AuthorObserver
{
    private function causer()
    {
        return Auth::guard('sanctum')->user() ?? Auth::guard('admin')->user();
    }

    /**
     * Handle the Author "created" event.
     */
    public function created(Author $author): void
    {
        ActivityLogger::log('created', $author, $this->causer(), [
            'last_name' => $author->last_name,
        ]);
    }

    /**
     * Handle the Author "updated" event.
     */
    public function updated(Author $author): void
    {
        ActivityLogger::log('updated', $author, $this->causer(), [
            'changes' => $author->getChanges(),
        ]);
    }

    /**
     * Handle the Author "deleted" event.
     */
    public function deleted(Author $author): void
    {
        ActivityLogger::log('deleted', $author, $this->causer(), [
            'last_name' => $author->last_name,
        ]);
    }
}

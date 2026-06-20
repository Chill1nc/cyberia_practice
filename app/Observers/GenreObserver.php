<?php

namespace App\Observers;

use App\Models\Genre;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class GenreObserver
{
    private function causer()
    {
        return Auth::guard('sanctum')->user() ?? Auth::guard('admin')->user();
    }

    /**
     * Handle the Genre "created" event.
     */
    public function created(Genre $genre): void
    {
        ActivityLogger::log('created', $genre, $this->causer(), [
            'name' => $genre->name,
        ]);
    }

    /**
     * Handle the Genre "updated" event.
     */
    public function updated(Genre $genre): void
    {
        ActivityLogger::log('updated', $genre, $this->causer(), [
            'changes' => $genre->getChanges(),
        ]);
    }

    /**
     * Handle the Genre "deleted" event.
     */
    public function deleted(Genre $genre): void
    {
        ActivityLogger::log('deleted', $genre, $this->causer(), [
            'name' => $genre->name,
        ]);
    }
}

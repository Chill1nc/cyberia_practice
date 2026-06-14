<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class BookSorter
{
    /**
     * Create a new class instance.
     */
    protected ?string $sortBy;

    public function __construct(?string $sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function apply(Builder $builder): Builder
    {
        switch ($this->sortBy) {
            case 'created_at_asc':
                $builder->orderBy('created_at', 'asc');
                break;
            case 'created_at_desc':
                $builder->orderBy('created_at', 'desc');
                break;
            case 'price_asc':
                $builder->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $builder->orderBy('price', 'desc');
                break;
            default:
                $builder->orderBy('created_at', 'desc');
                break;
        }

        return $builder;
    }
}

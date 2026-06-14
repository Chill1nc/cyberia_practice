<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class BookFilter
{
    /**
     * Create a new class instance.
     */
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function apply(Builder $builder): Builder
    {
        if (!empty($this->filters['author_id'])) {
            $builder->where('author_id', $this->filters['author_id']);
        }

        if (!empty($this->filters['genre_id'])) {
            $builder->where('genre_id', $this->filters['genre_id']);
        }

        if (!empty($this->filters['year_from'])) {
            $builder->where('year', '>=', $this->filters['year_from']);
        }

        if (!empty($this->filters['year_to'])) {
            $builder->where('year', '<=', $this->filters['year_to']);
        }

        return $builder;
    }
}

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
        $method = $this->sortBy;

        if ($method && method_exists($this, $method)) {
            $this->$method($builder);
        } else {
            $this->sortByDefault($builder);
        }

        return $builder;
    }

    public function price_asc(Builder $builder)
    {
        $builder->orderBy('price', 'asc');
    }

    public function price_desc(Builder $builder)
    {
        $builder->orderBy('price', 'desc');
    }

    public function year_asc(Builder $builder)
    {
        $builder->orderBy('year', 'asc');
    }

    public function year_desc(Builder $builder)
    {
        $builder->orderBy('year', 'desc');
    }

    public function reviews_count_asc(Builder $builder)
    {
        $builder->withCount('reviews')->orderBy('reviews_count', 'asc');
    }

    public function reviews_count_desc(Builder $builder)
    {
        $builder->withCount('reviews')->orderBy('reviews_count', 'desc');
    }

    public function created_at_asc(Builder $builder)
    {
        $builder->orderBy('created_at', 'asc');
    }

    public function created_at_desc(Builder $builder)
    {
        $builder->orderBy('created_at', 'desc');
    }

    public function sortByDefault(Builder $builder)
    {
        $builder->orderBy('created_at', 'desc');
    }
}
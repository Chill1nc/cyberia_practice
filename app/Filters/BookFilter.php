<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class BookFilter
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = array_filter($filters, function ($value) {
            return $value !== null && $value !== '';
        });
    }

    public function apply(Builder $builder): Builder
    {
        foreach ($this->filters as $name => $value) {
            if (method_exists($this, $name)) {
                $this->$name($builder, $value);
            }
        }
        return $builder;
    }

    public function author_id(Builder $builder, $value): void
    {
        $builder->where('author_id', $value);
    }

    public function genre_id(Builder $builder, $value): void
    {
        $builder->where('genre_id', $value);
    }

    public function year_from(Builder $builder, $value): void
    {
        $builder->where('year', '>=', $value);
    }

    public function year_to(Builder $builder, $value): void
    {
        $builder->where('year', '<=', $value);
    }

    public function publisher(Builder $builder, $value): void
    {
        $builder->where('publisher', 'like', '%' . $value . '%');
    }

    public function cover_type(Builder $builder, $value): void
    {
        $builder->where('cover_type', $value);
    }

    public function age_limit(Builder $builder, $value): void
    {
        $builder->where('age_limit', $value);
    }

    public function pages_from(Builder $builder, $value): void
    {
        $builder->where('pages', '>=', $value);
    }

    public function pages_to(Builder $builder, $value): void
    {
        $builder->where('pages', '<=', $value);
    }

    public function price_from(Builder $builder, $value): void
    {
        $builder->where('price', '>=', $value);
    }

    public function price_to(Builder $builder, $value): void
    {
        $builder->where('price', '<=', $value);
    }

    public function search(Builder $builder, $value): void
    {
        $builder->where('title', 'ilike', '%' . $value . '%');
    }
}
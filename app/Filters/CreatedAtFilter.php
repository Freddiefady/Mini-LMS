<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

final readonly class CreatedAtFilter
{
    public function __construct(
        private ?string $date = null,
        private string $operator = '>=',
    ) {}

    public function __invoke(Builder $query, $next): Builder
    {
        if ($this->date !== null && $this->date !== '') {
            return $query->where('created_at', $this->operator, $this->date);
        }

        return $next($query);
    }
}

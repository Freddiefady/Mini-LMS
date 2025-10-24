<?php

declare(strict_types=1);

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final readonly class CompletedDateFilter
{
    public function __construct(
        private ?string $date = null,
        private string $operator = '>=',
    ) {}

    /**
     * @param  Builder<Model>  $query
     */
    public function __invoke(Builder $query, Closure $next): mixed
    {
        if ($this->date !== null && $this->date !== '') {
            return $query->where('completed_at', $this->operator, $this->date);
        }

        return $next($query);
    }
}

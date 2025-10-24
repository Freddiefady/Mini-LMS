<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $sort_order
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
final class Level extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'sort_order'];

    /**
     * @return HasMany<Course, $this>
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}

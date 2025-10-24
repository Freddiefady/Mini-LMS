<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\LessonProgressPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read int $lesson_id
 * @property-read Carbon $started_at
 * @property-read Carbon $completed_at
 * @property-read int $watch_seconds
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
#[UsePolicy(LessonProgressPolicy::class)]
final class LessonProgress extends Model
{
    /** @use HasFactory<Factory> */
    use HasFactory;

    protected $fillable = ['user_id', 'lesson_id', 'started_at', 'completed_at', 'watch_seconds'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
        'started_at' => 'datetime',
        'watch_seconds' => 'integer',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

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
final class LessonProgress extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'lesson_id' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'watch_seconds' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

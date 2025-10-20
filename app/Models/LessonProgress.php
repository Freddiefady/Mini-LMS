<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LessonProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lesson_id', 'started_at', 'completed_at', 'watch_seconds'];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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

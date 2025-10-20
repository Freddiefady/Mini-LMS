<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['course_id', 'title', 'order', 'video_url', 'duration_seconds', 'is_free_preview'];

    protected $casts = ['is_free_preview' => 'boolean'];

    public function getNext(): ?self
    {
        return $this->course
            ->lessons()
            ->where('order', '>', $this->order)
            ->first();
    }

    public function getPrevious(): ?self
    {
        return $this->course
            ->lessons()
            ->where('order', '<', $this->order)
            ->orderByDesc('order')
            ->first();
    }

    /**
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return HasMany<LessonProgress, $this>
     */
    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }
}

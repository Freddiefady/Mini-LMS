<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusCoursesEnum;
use App\Policies\CoursePolicy;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read int $level_id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string $description
 * @property-read string $image_url
 * @property-read string $status
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Level $level
 * @property-read Collection<int, Lesson> $lessons
 * @property-read Collection<int, Enrollment> $enrollments
 * @property-read Collection<int, CourseCompletion> $completions
 */
#[UsePolicy(CoursePolicy::class)]
final class Course extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = ['level_id', 'title', 'slug', 'description', 'image_url', 'status'];

    /**
     * @return BelongsTo<Level, $this>
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * @return HasMany<Lesson, $this>
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * @return HasMany<Enrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * @return HasMany<CourseCompletion, $this>
     */
    public function completions(): HasMany
    {
        return $this->hasMany(CourseCompletion::class);
    }

    public function getTotalLessons(): int
    {
        return $this->lessons()->count();
    }

    public function getUserProgress(User $user): float
    {
        $total = $this->getTotalLessons();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->lessons()
            ->whereHas('progress', function ($q) use ($user): void {
                $q->where('user_id', $user->id)
                    ->whereNotNull('completed_at');
            })
            ->count();

        return round(($completed / $total) * 100);
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level_id' => 'integer',
            'title' => 'string',
            'slug' => 'string',
            'description' => 'string',
            'image_url' => 'string',
            'status' => StatusCoursesEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('status', StatusCoursesEnum::PUBLISHED->value);
    }
}

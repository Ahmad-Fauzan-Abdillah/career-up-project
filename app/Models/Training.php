<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trainings';

    protected $fillable = [
        'slug',
        'title',
        'category',
        'schedule_date',
        'start_time',
        'end_time',
        'platform',
        'zoom_link',
        'mentor_name',
        'mentor_role',
        'mentor_avatar',
        'duration',
        'level',
        'description',
        'syllabus',
        'thumbnail',
        'status',
        'quota',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
            'quota' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Training $training): void {
            if (blank($training->slug) || $training->isDirty('title')) {
                $training->slug = $training->uniqueSlug(Str::slug($training->title));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function startAt(): ?Carbon
    {
        return $this->schedule_date && $this->start_time
            ? Carbon::parse($this->schedule_date->toDateString().' '.$this->start_time, config('app.timezone'))
            : null;
    }

    public function endAt(): ?Carbon
    {
        return $this->schedule_date && $this->end_time
            ? Carbon::parse($this->schedule_date->toDateString().' '.$this->end_time, config('app.timezone'))
            : null;
    }

    public function isFull(): bool
    {
        return $this->quota !== null && $this->enrollments()->count() >= $this->quota;
    }

    public function scopeGratis(Builder $query): Builder
    {
        return $query->where('category', 'Gratis');
    }

    public function scopePremium(Builder $query): Builder
    {
        return $query->where('category', 'Premium');
    }

    public function getFormattedScheduleDateAttribute(): string
    {
        if (! $this->schedule_date) {
            return '';
        }

        return Carbon::parse($this->schedule_date)->locale('id')->isoFormat('dddd, D MMMM Y');
    }

    public function getFormattedScheduleTimeAttribute(): string
    {
        if (! $this->start_time || ! $this->end_time) {
            return '';
        }

        return Carbon::parse($this->start_time)->format('H.i')
            .' - '.Carbon::parse($this->end_time)->format('H.i').' WIB';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'terbit');
    }

    private function uniqueSlug(string $base): string
    {
        $base = $base !== '' ? $base : 'pelatihan';
        $candidate = $base;
        $suffix = 2;

        while (static::withTrashed()
            ->where('slug', $candidate)
            ->when($this->exists, fn (Builder $query) => $query->whereKeyNot($this->getKey()))
            ->exists()) {
            $candidate = $base.'-'.$suffix++;
        }

        return $candidate;
    }
}

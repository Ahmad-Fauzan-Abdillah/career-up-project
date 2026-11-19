<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_listings';

    protected $fillable = [
        'slug',
        'title',
        'company_name',
        'company_logo',
        'location',
        'type',
        'work_system',
        'duration',
        'department',
        'description',
        'qualifications',
        'is_featured',
        'status',
        'deadline',
        'salary_min',
        'salary_max',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'deadline' => 'date',
            'salary_min' => 'integer',
            'salary_max' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (JobListing $job): void {
            if (blank($job->slug) || $job->isDirty(['title', 'company_name'])) {
                $job->slug = $job->uniqueSlug(Str::slug($job->title.'-'.$job->company_name));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isClosed(): bool
    {
        return $this->status === 'ditutup'
            || ($this->deadline !== null && $this->deadline->isBefore(today()));
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function scopeLowongan(Builder $query): Builder
    {
        return $query->where('type', 'lowongan');
    }

    public function scopeMagang(Builder $query): Builder
    {
        return $query->where('type', 'magang');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'terbit');
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($keyword) {
            $subQuery->where('title', 'like', "%{$keyword}%")
                ->orWhere('company_name', 'like', "%{$keyword}%")
                ->orWhere('location', 'like', "%{$keyword}%");
        });
    }

    private function uniqueSlug(string $base): string
    {
        $base = $base !== '' ? $base : 'lowongan';
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

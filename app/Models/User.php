<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'location',
        'bio',
        'avatar',
        'email_verified_at',
        'is_notification_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_notification_active' => 'boolean',
        ];
    }

    public function skills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(UserEducation::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(UserExperience::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function trainingEnrollments(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'aktif')
            ->where('valid_until', '>=', today())
            ->latest('valid_until')
            ->first();
    }

    public function profileCompleteness(): int
    {
        $checks = [
            filled($this->name),
            filled($this->email),
            filled($this->phone),
            filled($this->location),
            filled($this->bio),
            $this->skills()->exists(),
            $this->educations()->exists(),
            $this->experiences()->exists(),
        ];

        return (int) round(count(array_filter($checks)) / count($checks) * 100);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->userNotifications()
            ->where('is_read', false)
            ->count();
    }
}

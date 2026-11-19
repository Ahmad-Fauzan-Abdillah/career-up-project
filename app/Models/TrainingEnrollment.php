<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $table = 'training_enrollments';

    protected $fillable = [
        'user_id',
        'training_id',
        'certificate_issued',
        'reminder_sent_at',
        'certificate_issued_at',
    ];

    protected function casts(): array
    {
        return [
            'certificate_issued' => 'boolean',
            'reminder_sent_at' => 'datetime',
            'certificate_issued_at' => 'datetime',
        ];
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'confirmed_by',
        'training_id',
        'plan_type',
        'price',
        'payment_method',
        'payment_proof',
        'unique_code',
        'status',
        'valid_until',
        'confirmed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'confirmed_at' => 'datetime',
            'price' => 'integer',
            'unique_code' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}

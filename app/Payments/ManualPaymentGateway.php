<?php

namespace App\Payments;

use App\Contracts\PaymentGateway;
use App\Models\Subscription;
use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ManualPaymentGateway implements PaymentGateway
{
    public function paymentInstructions(): array
    {
        return [
            'image' => (string) config('payment.qris_image'),
            'instructions' => (string) config('payment.instructions'),
        ];
    }

    public function submitPaymentProof(
        User $user,
        Training $training,
        string $plan,
        int $price,
        int $uniqueCode,
        string $proofPath,
    ): Subscription {
        return DB::transaction(fn () => Subscription::create([
            'user_id' => $user->id,
            'training_id' => $training->id,
            'plan_type' => $plan,
            'price' => $price,
            'payment_method' => 'QRIS',
            'payment_proof' => $proofPath,
            'unique_code' => $uniqueCode,
            'status' => 'pending',
            'valid_until' => null,
        ]));
    }
}

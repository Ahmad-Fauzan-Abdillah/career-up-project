<?php

namespace App\Contracts;

use App\Models\Subscription;
use App\Models\Training;
use App\Models\User;

interface PaymentGateway
{
    /** @return array{image: string, instructions: string} */
    public function paymentInstructions(): array;

    public function submitPaymentProof(
        User $user,
        Training $training,
        string $plan,
        int $price,
        int $uniqueCode,
        string $proofPath,
    ): Subscription;
}

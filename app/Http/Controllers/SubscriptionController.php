<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class SubscriptionController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $activeSubscription = $request->user()->activeSubscription();

        if ($activeSubscription) {
            return view('subscriptions.active', compact('activeSubscription'));
        }

        if (! $request->query('training')) {
            return redirect()->route('pelatihan.index');
        }

        $validated = $request->validate([
            'training' => ['required', 'integer', 'exists:trainings,id'],
        ]);
        $training = Training::findOrFail($validated['training']);
        abort_unless($training->category === 'Premium' && $training->status === 'terbit', 404);

        return view('subscriptions.index', compact('training'));
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $activeSubscription = $request->user()->activeSubscription();

        if ($activeSubscription) {
            return redirect()->route('pelatihan.index')
                ->with('subscription_active_until', $activeSubscription->valid_until);
        }

        $validated = $request->validated();
        $training = Training::findOrFail($validated['training_id']);
        abort_unless($training->category === 'Premium' && $training->status === 'terbit', 404);

        session(['subscription_checkout' => [
            'training_id' => $training->id,
            'plan_type' => $validated['plan_type'],
        ]]);

        return redirect()->route('subscriptions.payment');
    }

    public function payment(Request $request, PaymentGateway $gateway): View|RedirectResponse
    {
        $checkout = $request->session()->get('subscription_checkout');

        if (! is_array($checkout) || empty($checkout['training_id']) || empty($checkout['plan_type'])) {
            return redirect()->route('subscriptions.index');
        }

        $training = Training::findOrFail($checkout['training_id']);
        abort_unless($training->category === 'Premium' && $training->status === 'terbit', 404);

        $activeSubscription = $request->user()->activeSubscription();
        if ($activeSubscription) {
            return view('subscriptions.active', compact('activeSubscription'));
        }

        abort_unless(in_array($checkout['plan_type'], ['bulanan', 'tahunan'], true), 404);

        $price = $this->priceFor($checkout['plan_type']);
        [$minimumCode, $maximumCode] = $this->uniqueCodeBounds();
        $uniqueCode = $checkout['unique_code'] ?? random_int($minimumCode, $maximumCode);
        $checkout['unique_code'] = $uniqueCode;
        $request->session()->put('subscription_checkout', $checkout);
        $payment = $gateway->paymentInstructions();

        $amount = $price + $uniqueCode;

        return view('subscriptions.payment', compact('checkout', 'training', 'price', 'uniqueCode', 'amount', 'payment'));
    }

    public function pay(Request $request, PaymentGateway $gateway): RedirectResponse
    {
        $checkout = $request->session()->get('subscription_checkout');

        if (! is_array($checkout) || empty($checkout['training_id']) || empty($checkout['plan_type'])) {
            return redirect()->route('subscriptions.index');
        }

        $training = Training::findOrFail($checkout['training_id']);
        abort_unless($training->category === 'Premium' && $training->status === 'terbit', 404);
        abort_unless(in_array($checkout['plan_type'], ['bulanan', 'tahunan'], true), 404);

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $activeSubscription = $request->user()->activeSubscription();
        if ($activeSubscription) {
            $request->session()->forget('subscription_checkout');

            return redirect()->route('pelatihan.index')
                ->with('subscription_active_until', $activeSubscription->valid_until);
        }

        $plan = $checkout['plan_type'];
        $price = $this->priceFor($plan);
        [$minimumCode, $maximumCode] = $this->uniqueCodeBounds();
        $uniqueCode = filter_var($checkout['unique_code'] ?? null, FILTER_VALIDATE_INT);
        abort_unless(
            $uniqueCode !== false
                && $uniqueCode >= $minimumCode
                && $uniqueCode <= $maximumCode,
            409,
        );

        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'local');
        if (! $proofPath) {
            throw new RuntimeException('Bukti pembayaran gagal disimpan. Silakan coba kembali.');
        }

        try {
            $subscription = $gateway->submitPaymentProof(
                $request->user(),
                $training,
                $plan,
                $price,
                $uniqueCode,
                $proofPath,
            );
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($proofPath);
            throw $exception;
        }

        $request->session()->forget('subscription_checkout');

        return redirect()->route('subscriptions.success', $subscription);
    }

    public function success(Subscription $subscription): View
    {
        Gate::authorize('view', $subscription);
        $subscription->load('training');

        return view('subscriptions.success', compact('subscription'));
    }

    private function priceFor(string $plan): int
    {
        return (int) config($plan === 'bulanan' ? 'payment.monthly_price' : 'payment.annual_price');
    }

    /**
     * @return array{int, int}
     */
    private function uniqueCodeBounds(): array
    {
        $minimum = (int) config('payment.unique_code_min', 100);
        $maximum = (int) config('payment.unique_code_max', 999);

        if ($minimum < 100 || $maximum > 999 || $minimum > $maximum) {
            throw new RuntimeException('Konfigurasi kode unik pembayaran harus berada pada rentang 100-999.');
        }

        return [$minimum, $maximum];
    }
}

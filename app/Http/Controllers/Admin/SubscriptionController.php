<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifySubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Subscription::class);

        $subscriptions = Subscription::query()
            ->with(['user', 'training'])
            ->when(
                in_array($request->query('status'), ['pending', 'aktif', 'ditolak', 'kedaluwarsa'], true),
                fn ($query) => $query->where('status', $request->query('status')),
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function downloadProof(Subscription $subscription)
    {
        Gate::authorize('verify', $subscription);
        abort_unless($subscription->payment_proof && Storage::disk('local')->exists($subscription->payment_proof), 404);

        return Storage::disk('local')->download($subscription->payment_proof);
    }

    public function verify(VerifySubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        Gate::authorize('verify', $subscription);
        abort_unless($subscription->status === 'pending', 409, 'Langganan ini sudah diverifikasi.');

        if ($request->validated('action') === 'reject') {
            $subscription->update([
                'status' => 'ditolak',
                'confirmed_by' => $request->user()->id,
                'confirmed_at' => now(),
                'rejection_reason' => $request->validated('rejection_reason'),
            ]);

            return back()->with('status', 'Pembayaran ditolak.');
        }

        $subscription->update([
            'status' => 'aktif',
            'valid_until' => $subscription->plan_type === 'bulanan' ? today()->addMonth() : today()->addYear(),
            'confirmed_by' => $request->user()->id,
            'confirmed_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('status', 'Langganan berhasil diverifikasi.');
    }
}

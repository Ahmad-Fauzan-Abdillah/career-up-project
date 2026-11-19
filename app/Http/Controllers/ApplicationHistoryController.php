<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:Diproses,Diterima,Ditolak,Dibatalkan'],
        ]);

        $applications = $request->user()
            ->applications()
            ->with('jobListing')
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.riwayat', [
            'applications' => $applications,
            'status' => $validated['status'] ?? null,
        ]);
    }

    public function show(Application $application): View
    {
        Gate::authorize('view', $application);
        $application->load('jobListing');

        return view('applications.show', compact('application'));
    }

    public function cancel(Application $application): RedirectResponse
    {
        Gate::authorize('cancel', $application);
        $application->update(['status' => 'Dibatalkan']);

        return redirect()->route('riwayat.show', $application)->with('status', 'Lamaran berhasil dibatalkan.');
    }
}

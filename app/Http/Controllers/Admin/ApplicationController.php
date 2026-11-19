<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Application::class);

        $applications = Application::query()
            ->with(['user', 'jobListing'])
            ->when(
                in_array($request->query('status'), ['Diproses', 'Diterima', 'Ditolak', 'Dibatalkan'], true),
                fn ($query) => $query->where('status', $request->query('status')),
            )
            ->when($request->filled('job_listing_id'), fn ($query) => $query->where('job_listing_id', $request->integer('job_listing_id')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('full_name', 'like', '%'.$request->string('q').'%')
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', '%'.$request->string('q').'%'));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $jobs = \App\Models\JobListing::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.applications.index', compact('applications', 'jobs'));
    }

    public function show(Application $application): View
    {
        Gate::authorize('view', $application);
        $application->load(['user.skills', 'user.educations', 'user.experiences', 'jobListing']);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): RedirectResponse
    {
        Gate::authorize('updateStatus', $application);
        $application->update($request->validated());

        return back()->with('status', 'Status lamaran berhasil diperbarui.');
    }

    public function downloadCv(Application $application): StreamedResponse
    {
        Gate::authorize('view', $application);
        abort_unless(Storage::disk('local')->exists($application->cv_path), 404, 'Berkas CV tidak ditemukan.');

        return Storage::disk('local')->download($application->cv_path);
    }
}

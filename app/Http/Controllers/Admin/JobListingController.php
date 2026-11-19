<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveJobListingRequest;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class JobListingController extends Controller
{
    public function index(Request $request, string $type): View
    {
        Gate::authorize('viewAny', JobListing::class);
        $type = $request->query('type', $type);
        $this->ensureType($type);
        $keyword = is_string($request->query('q')) ? $request->query('q') : null;

        $jobs = JobListing::query()
            ->where('type', $type)
            ->search($keyword)
            ->when(
                in_array($request->query('status'), ['draft', 'terbit', 'ditutup'], true),
                fn ($query) => $query->where('status', $request->query('status')),
            )
            ->withCount('applications')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs', 'type'));
    }

    public function create(string $type): View
    {
        Gate::authorize('create', JobListing::class);
        $this->ensureType($type);

        return view('admin.jobs.form', ['job' => new JobListing(), 'type' => $type]);
    }

    public function store(SaveJobListingRequest $request, string $type): RedirectResponse
    {
        $this->ensureType($type);
        $attributes = $request->validated();
        unset($attributes['company_logo']);

        if ($request->hasFile('company_logo')) {
            $attributes['company_logo'] = $this->storeLogo($request);
        }

        JobListing::create($attributes + [
            'type' => $type,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.jobs.index', ['type' => $type])
            ->with('status', 'Lowongan berhasil dibuat.');
    }

    public function edit(string $type, JobListing $job): View
    {
        Gate::authorize('update', $job);
        $this->ensureMatchingType($job, $type);

        return view('admin.jobs.form', compact('job', 'type'));
    }

    public function update(SaveJobListingRequest $request, string $type, JobListing $job): RedirectResponse
    {
        Gate::authorize('update', $job);
        $this->ensureMatchingType($job, $type);
        $attributes = $request->validated();
        unset($attributes['company_logo']);

        $oldLogo = $job->company_logo;
        if ($request->hasFile('company_logo')) {
            $attributes['company_logo'] = $this->storeLogo($request);
        }

        $job->update($attributes + ['type' => $type]);

        if (isset($attributes['company_logo']) && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        return redirect()->route('admin.jobs.index', ['type' => $type])
            ->with('status', 'Perubahan lowongan berhasil disimpan.');
    }

    public function publish(string $type, JobListing $job): RedirectResponse
    {
        Gate::authorize('publish', $job);
        $this->ensureMatchingType($job, $type);
        $job->update(['status' => 'terbit']);

        return back()->with('status', 'Lowongan berhasil diterbitkan.');
    }

    public function close(string $type, JobListing $job): RedirectResponse
    {
        Gate::authorize('close', $job);
        $this->ensureMatchingType($job, $type);
        $job->update(['status' => 'ditutup']);

        return back()->with('status', 'Lowongan berhasil ditutup.');
    }

    public function destroy(string $type, JobListing $job): RedirectResponse
    {
        Gate::authorize('delete', $job);
        $this->ensureMatchingType($job, $type);

        if ($job->applications()->exists()) {
            return back()->with('error', 'Lowongan memiliki pelamar. Tutup lowongan, jangan hapus.');
        }

        $job->delete();

        return redirect()->route('admin.jobs.index', ['type' => $type])
            ->with('status', 'Lowongan berhasil dihapus.');
    }

    private function storeLogo(SaveJobListingRequest $request): string
    {
        $path = $request->file('company_logo')->store('job-logos', 'public');

        if (! $path) {
            throw new RuntimeException('Logo perusahaan gagal disimpan. Silakan coba kembali.');
        }

        return $path;
    }

    private function ensureType(string $type): void
    {
        abort_unless(in_array($type, ['lowongan', 'magang'], true), 404);
    }

    private function ensureMatchingType(JobListing $job, string $type): void
    {
        $this->ensureType($type);
        abort_unless($job->type === $type, 404);
    }
}

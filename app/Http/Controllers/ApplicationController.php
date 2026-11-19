<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class ApplicationController extends Controller
{
    public function create(JobListing $job): View|RedirectResponse
    {
        abort_unless($job->status === 'terbit', 404);
        abort_if($job->isClosed(), 403, 'Lamaran untuk lowongan ini sudah ditutup.');

        $application = $job->applications()
            ->where('user_id', auth()->id())
            ->first();

        if ($application) {
            return redirect()->route('riwayat.show', $application);
        }

        return view('jobs.apply', compact('job'));
    }

    public function store(StoreApplicationRequest $request, JobListing $job): RedirectResponse
    {
        abort_unless($job->status === 'terbit', 404);
        abort_if($job->isClosed(), 403, 'Lamaran untuk lowongan ini sudah ditutup.');

        $cvPath = $request->file('cv')->store('cvs', 'local');

        if (! $cvPath) {
            throw new RuntimeException('CV gagal disimpan. Silakan coba kembali.');
        }

        try {
            $application = DB::transaction(function () use ($request, $job, $cvPath) {
                $application = Application::create([
                    'user_id' => $request->user()->id,
                    'job_listing_id' => $job->id,
                    'full_name' => $request->validated('full_name'),
                    'phone' => $request->validated('phone'),
                    'email' => $request->validated('email'),
                    'domicile' => $request->validated('domicile'),
                    'cv_path' => $cvPath,
                    'status' => 'Diproses',
                ]);

                return $application;
            });
        } catch (QueryException $exception) {
            Storage::disk('local')->delete($cvPath);

            if (! $this->isDuplicateApplication($exception)) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'job_listing_id' => 'Anda sudah pernah melamar posisi ini.',
            ]);
        }

        return redirect()->route('riwayat.show', $application)
            ->with('success', 'Lamaran berhasil dikirim.');
    }

    public function downloadCv(Application $application)
    {
        Gate::authorize('view', $application);
        abort_unless(Storage::disk('local')->exists($application->cv_path), 404, 'Berkas CV tidak ditemukan.');

        return Storage::disk('local')->download($application->cv_path);
    }

    private function isDuplicateApplication(QueryException $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'applications_user_id_job_listing_id_unique')
            || str_contains($message, 'applications.user_id, applications.job_listing_id');
    }
}

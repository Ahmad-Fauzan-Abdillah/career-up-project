<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Training;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Show the landing page for guests or redirect authenticated users.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('beranda');
        }

        $featuredJobs = JobListing::published()->featured()->latest()->take(3)->get();
        $internships = JobListing::published()->magang()->latest()->take(3)->get();
        $featuredTrainings = Training::published()->withCount('enrollments')->latest()->take(3)->get();
        $statistics = Cache::remember('landing.statistics.v1', now()->addMinutes(5), function () {
            return collect([
                'Lowongan tersedia' => $this->availableJobs('lowongan'),
                'Program magang' => $this->availableJobs('magang'),
                'Pelatihan' => Training::published()->count(),
            ])->filter(static fn (int $count): bool => $count > 0);
        });

        return view('landing', compact('featuredJobs', 'internships', 'featuredTrainings', 'statistics'));
    }

    private function availableJobs(string $type): int
    {
        return JobListing::published()
            ->where('type', $type)
            ->where(static function (Builder $query): void {
                $query->whereNull('deadline')->orWhereDate('deadline', '>=', today());
            })
            ->count();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Training;
use Illuminate\View\View;

class BerandaController extends Controller
{
    /**
     * Show the user beranda / dashboard.
     */
    public function index(): View
    {
        $latestJobs = JobListing::published()->lowongan()->latest()->take(8)->get();
        $internships = JobListing::published()->magang()->latest()->take(8)->get();
        $popularTrainings = Training::published()
            ->whereDate('schedule_date', '>=', today())
            ->withCount('enrollments')
            ->orderBy('schedule_date')
            ->take(8)
            ->get();

        $user = auth()->user();
        $profileCompleteness = $user->profileCompleteness();
        $activeApplicationsCount = $user->applications()->where('status', 'Diproses')->count();
        $unreadNotificationsCount = $user->unreadNotificationsCount();
        $hasCareerActivity = $user->applications()->exists() || $user->trainingEnrollments()->exists();

        return view('beranda', compact(
            'latestJobs',
            'internships',
            'popularTrainings',
            'user',
            'profileCompleteness',
            'activeApplicationsCount',
            'unreadNotificationsCount',
            'hasCareerActivity',
        ));
    }
}

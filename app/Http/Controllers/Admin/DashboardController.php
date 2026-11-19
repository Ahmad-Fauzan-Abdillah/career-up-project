<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\Subscription;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewDashboard', User::class);

        $latestApplications = Application::query()
            ->with(['user', 'jobListing'])
            ->latest()
            ->take(5)
            ->get();

        $startDate = today()->subDays(29);
        $applicationCounts = Application::query()
            ->whereDate('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as application_date, COUNT(*) as total')
            ->groupBy('application_date')
            ->pluck('total', 'application_date');

        $chartLabels = collect(range(29, 0))
            ->map(fn (int $daysAgo) => Carbon::today()->subDays($daysAgo)->format('d M'))
            ->values();
        $chartValues = collect(range(29, 0))
            ->map(fn (int $daysAgo) => (int) $applicationCounts->get(
                Carbon::today()->subDays($daysAgo)->toDateString(),
                0,
            ))
            ->values();
        $hasChartData = $chartValues->sum() > 0;

        $statistics = [
            'Lowongan terbit' => JobListing::query()->where('type', 'lowongan')->where('status', 'terbit')->count(),
            'Magang terbit' => JobListing::query()->where('type', 'magang')->where('status', 'terbit')->count(),
            'Pelatihan terbit' => Training::query()->where('status', 'terbit')->count(),
            'Pelamar Diproses' => Application::query()->where('status', 'Diproses')->count(),
            'Total pengguna' => User::query()->where('role', 'pengguna')->count(),
            'Langganan menunggu verifikasi' => Subscription::query()->where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('statistics', 'latestApplications', 'chartLabels', 'chartValues', 'hasChartData'));
    }
}

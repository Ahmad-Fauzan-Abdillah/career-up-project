<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobListingController extends Controller
{
    /**
     * Display a listing of lowongan.
     */
    public function indexLowongan(Request $request): View
    {
        $keyword = $this->queryString($request, 'q');
        $jobs = $this->listingQuery($request, 'lowongan', $keyword)->paginate(10)->withQueryString();

        return view('jobs.index', [
            'title' => 'Lowongan Kerja',
            'type' => 'lowongan',
            'jobs' => $jobs,
            'keyword' => $keyword,
            'location' => $this->queryString($request, 'location'),
            'workSystem' => $this->queryString($request, 'work_system'),
            'sort' => $this->sortOrder($request),
        ]);
    }

    /**
     * Display a listing of magang.
     */
    public function indexMagang(Request $request): View
    {
        $keyword = $this->queryString($request, 'q');
        $jobs = $this->listingQuery($request, 'magang', $keyword)->paginate(9)->withQueryString();

        return view('jobs.index', [
            'title' => 'Program Magang',
            'type' => 'magang',
            'jobs' => $jobs,
            'keyword' => $keyword,
            'location' => $this->queryString($request, 'location'),
            'workSystem' => $this->queryString($request, 'work_system'),
            'sort' => $this->sortOrder($request),
        ]);
    }

    /**
     * Display detail of a lowongan.
     */
    public function showLowongan(JobListing $job): View
    {
        abort_unless($job->type === 'lowongan' && $job->status === 'terbit', 404);

        return $this->showDetail($job);
    }

    /**
     * Display detail of a magang.
     */
    public function showMagang(JobListing $job): View
    {
        abort_unless($job->type === 'magang' && $job->status === 'terbit', 404);

        return $this->showDetail($job);
    }

    private function showDetail(JobListing $job): View
    {
        $application = Auth::check()
            ? $job->applications()->where('user_id', Auth::id())->first()
            : null;
        $relatedJobs = JobListing::published()
            ->where('type', $job->type)
            ->whereKeyNot($job->getKey())
            ->latest()
            ->take(3)
            ->get();

        return view('jobs.show', compact('job', 'application', 'relatedJobs'));
    }

    private function listingQuery(Request $request, string $type, ?string $keyword)
    {
        $query = JobListing::query()->where('type', $type)->published()->search($keyword);
        $location = $this->queryString($request, 'location');
        $workSystem = $this->queryString($request, 'work_system');

        if ($location) {
            $query->where('location', 'like', '%'.$location.'%');
        }
        if ($workSystem) {
            $query->where('work_system', $workSystem);
        }

        return $this->sortOrder($request) === 'deadline'
            ? $query->orderByRaw('deadline IS NULL')->orderBy('deadline')->orderByDesc('created_at')
            : $query->latest();
    }

    private function queryString(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }

    private function sortOrder(Request $request): string
    {
        return $request->query('sort') === 'deadline' ? 'deadline' : 'latest';
    }
}

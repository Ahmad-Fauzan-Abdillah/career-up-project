<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings.
     */
    public function index(Request $request): View
    {
        $keyword = is_string($request->query('q')) ? $request->query('q') : null;
        $trainings = Training::published()
            ->withCount('enrollments')
            ->withExists(['enrollments as is_enrolled' => fn ($query) => $query->where('user_id', $request->user()->id)])
            ->when($keyword, function ($query, $keyword) {
                $query->where('title', 'like', '%'.$keyword.'%');
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();
        $hasActiveSubscription = $request->user()->activeSubscription() !== null;

        return view('trainings.index', compact('trainings', 'keyword', 'hasActiveSubscription'));
    }

    /**
     * Display the specified training.
     */
    public function show(Training $training): View
    {
        abort_unless($training->status === 'terbit', 404);

        $user = request()->user();
        $hasActiveSubscription = $user->activeSubscription() !== null;
        $isEnrolled = $user->trainingEnrollments()->where('training_id', $training->id)->exists();
        $startAt = $training->startAt();
        $endAt = $training->endAt();
        $canJoin = $isEnrolled && $startAt && $endAt
            && now()->between($startAt->copy()->subMinutes(30), $endAt);
        $isFinished = $endAt && now()->gt($endAt);
        $isFull = $training->isFull();

        return view('trainings.show', compact('training', 'hasActiveSubscription', 'isEnrolled', 'startAt', 'endAt', 'canJoin', 'isFinished', 'isFull'));
    }
}

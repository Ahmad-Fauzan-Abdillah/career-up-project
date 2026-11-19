<?php

namespace App\Http\Controllers;

use App\Models\TrainingEnrollment;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TrainingCertificateController extends Controller
{
    public function show(TrainingEnrollment $enrollment): View
    {
        Gate::authorize('view', $enrollment);
        abort_unless($enrollment->certificate_issued, 404);
        $enrollment->load(['training', 'user']);

        return view('trainings.certificate', compact('enrollment'));
    }
}

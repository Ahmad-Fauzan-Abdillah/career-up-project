<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveTrainingRequest;
use App\Models\Training;
use App\Models\TrainingEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class TrainingController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Training::class);
        $keyword = is_string($request->query('q')) ? $request->query('q') : null;

        $trainings = Training::query()
            ->when($keyword !== null && $keyword !== '', fn ($query) => $query->where('title', 'like', '%'.$keyword.'%'))
            ->when(
                in_array($request->query('status'), ['draft', 'terbit'], true),
                fn ($query) => $query->where('status', $request->query('status')),
            )
            ->withCount('enrollments')
            ->latest('schedule_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.trainings.index', compact('trainings'));
    }

    public function create(): View
    {
        Gate::authorize('create', Training::class);

        return view('admin.trainings.form', ['training' => new Training()]);
    }

    public function store(SaveTrainingRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        unset($attributes['thumbnail'], $attributes['mentor_avatar']);

        if ($request->hasFile('thumbnail')) {
            $attributes['thumbnail'] = $this->storeImage($request, 'thumbnail', 'training-thumbnails');
        }
        if ($request->hasFile('mentor_avatar')) {
            $attributes['mentor_avatar'] = $this->storeImage($request, 'mentor_avatar', 'mentor-avatars');
        }

        Training::create($attributes);

        return redirect()->route('admin.trainings.index')->with('status', 'Pelatihan berhasil dibuat.');
    }

    public function edit(Training $training): View
    {
        Gate::authorize('update', $training);

        return view('admin.trainings.form', compact('training'));
    }

    public function update(SaveTrainingRequest $request, Training $training): RedirectResponse
    {
        Gate::authorize('update', $training);
        $attributes = $request->validated();
        unset($attributes['thumbnail'], $attributes['mentor_avatar']);
        $oldThumbnail = $training->thumbnail;
        $oldMentorAvatar = $training->mentor_avatar;

        if ($request->hasFile('thumbnail')) {
            $attributes['thumbnail'] = $this->storeImage($request, 'thumbnail', 'training-thumbnails');
        }
        if ($request->hasFile('mentor_avatar')) {
            $attributes['mentor_avatar'] = $this->storeImage($request, 'mentor_avatar', 'mentor-avatars');
        }

        $training->update($attributes);

        if (isset($attributes['thumbnail']) && $oldThumbnail) {
            Storage::disk('public')->delete($oldThumbnail);
        }
        if (isset($attributes['mentor_avatar']) && $oldMentorAvatar) {
            Storage::disk('public')->delete($oldMentorAvatar);
        }

        return redirect()->route('admin.trainings.index')->with('status', 'Perubahan pelatihan berhasil disimpan.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        Gate::authorize('delete', $training);
        $training->delete();

        return redirect()->route('admin.trainings.index')->with('status', 'Pelatihan berhasil dihapus.');
    }

    public function participants(Training $training): View
    {
        Gate::authorize('viewParticipants', $training);
        $participants = $training->enrollments()->with('user')->latest()->paginate(20);

        return view('admin.trainings.participants', compact('training', 'participants'));
    }

    public function issueCertificate(Training $training, TrainingEnrollment $enrollment): RedirectResponse
    {
        Gate::authorize('viewParticipants', $training);
        abort_unless($enrollment->training_id === $training->id, 404);
        abort_if($enrollment->certificate_issued, 409, 'Sertifikat sudah diterbitkan.');

        $enrollment->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);

        return back()->with('status', 'Sertifikat berhasil diterbitkan.');
    }

    private function storeImage(SaveTrainingRequest $request, string $field, string $directory): string
    {
        $path = $request->file($field)->store($directory, 'public');

        if (! $path) {
            throw new RuntimeException('Gambar pelatihan gagal disimpan. Silakan coba kembali.');
        }

        return $path;
    }
}

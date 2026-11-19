<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user()
            ->load(['skills', 'educations', 'experiences', 'trainingEnrollments' => fn ($query) => $query->where('certificate_issued', true)->with('training')])
            ->loadCount([
                'applications',
                'applications as accepted_applications_count' => fn ($query) => $query->where('status', 'Diterima'),
                'trainingEnrollments',
                'trainingEnrollments as certificates_count' => fn ($query) => $query->where('certificate_issued', true),
            ]);

        return view('pages.profil', compact('user'));
    }

    public function edit(): View
    {
        return view('pages.profil-edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $oldAvatar = $user->avatar;
        $skills = $validated['skills'] ?? [];
        $educations = $validated['educations'] ?? [];
        $experiences = $validated['experiences'] ?? [];
        unset($validated['avatar'], $validated['skills'], $validated['educations'], $validated['experiences']);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            if (! $avatarPath) {
                throw new RuntimeException('Foto profil gagal disimpan. Silakan coba kembali.');
            }

            $validated['avatar'] = $avatarPath;
        }

        if ($validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        DB::transaction(function () use ($user, $validated, $skills, $educations, $experiences): void {
            $user->update($validated);
            $this->replaceRecords($user->skills(), $skills, ['skill_name']);
            $this->replaceRecords($user->educations(), $educations, ['institution', 'major', 'start_year', 'end_year']);
            $this->replaceRecords($user->experiences(), $experiences, ['position', 'company', 'period', 'description']);
        });

        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }

        if (isset($validated['avatar']) && $oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()->route('profil.index')
            ->with('status', 'Perubahan profil berhasil disimpan.');
    }

    private function replaceRecords($relation, array $records, array $fields): void
    {
        $relation->delete();

        foreach ($records as $record) {
            $attributes = array_intersect_key($record, array_flip($fields));
            if (collect($attributes)->every(fn ($value) => blank($value))) {
                continue;
            }

            $relation->create($attributes);
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_an_application_and_view_its_status(): void
    {
        Storage::fake('local');
        $user = User::factory()->create([
            'role' => 'pengguna',
            'phone' => '08123456789',
            'location' => 'Surabaya',
            'is_notification_active' => true,
        ]);
        $job = $this->createJob();

        $response = $this->actingAs($user)->post(route('jobs.apply.store', $job), [
            'full_name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'domicile' => $user->location,
            'cv' => $this->fakePdf(),
        ]);

        $application = Application::firstOrFail();
        $response->assertRedirect(route('riwayat.show', $application));
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'user_id' => $user->id,
            'job_listing_id' => $job->id,
            'status' => 'Diproses',
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'title' => 'Lamaran terkirim',
        ]);
        Storage::disk('local')->assertExists($application->cv_path);
        Storage::disk('public')->assertMissing($application->cv_path);

        $this->get(route('riwayat.show', $application))
            ->assertOk()
            ->assertSee('Lamaran Terkirim')
            ->assertSee($job->title);

        $this->get(route('riwayat.index', ['status' => 'Diproses']))
            ->assertOk()
            ->assertSee($job->title);

        $this->get(route('lowongan.show', $job))
            ->assertOk()
            ->assertSee('Sudah Dilamar')
            ->assertSee(route('riwayat.show', $application));
    }

    public function test_user_cannot_submit_a_duplicate_application(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => 'pengguna']);
        $job = $this->createJob();
        $payload = [
            'full_name' => $user->name,
            'phone' => '08123456789',
            'email' => $user->email,
            'domicile' => 'Surabaya',
            'cv' => $this->fakePdf(),
        ];

        $this->actingAs($user)->post(route('jobs.apply.store', $job), $payload)
            ->assertRedirect(route('riwayat.show', Application::firstOrFail()));

        $this->post(route('jobs.apply.store', $job), [
            ...$payload,
            'cv' => $this->fakePdf(),
        ])->assertSessionHasErrors('job_listing_id');

        $this->assertSame(1, Application::count());
    }

    public function test_application_does_not_create_notification_when_user_disabled_notifications(): void
    {
        Storage::fake('local');
        $user = User::factory()->create([
            'role' => 'pengguna',
            'phone' => '08123456789',
            'location' => 'Surabaya',
            'is_notification_active' => false,
        ]);
        $job = $this->createJob();

        $this->actingAs($user)->post(route('jobs.apply.store', $job), [
            'full_name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'domicile' => $user->location,
            'cv' => $this->fakePdf(),
        ])->assertRedirect(route('riwayat.show', Application::firstOrFail()));

        $this->assertDatabaseMissing('user_notifications', [
            'user_id' => $user->id,
            'title' => 'Lamaran terkirim',
        ]);
    }

    public function test_user_cannot_view_another_users_application_status(): void
    {
        $owner = User::factory()->create(['role' => 'pengguna']);
        $otherUser = User::factory()->create(['role' => 'pengguna']);
        $job = $this->createJob();
        $application = Application::factory()->for($owner)->for($job, 'jobListing')->create([
            'full_name' => $owner->name,
            'phone' => '08123456789',
            'email' => $owner->email,
            'domicile' => 'Surabaya',
            'cv_path' => 'cvs/resume.pdf',
            'status' => 'Diproses',
        ]);

        $this->actingAs($otherUser)
            ->get(route('riwayat.show', $application))
            ->assertForbidden();
    }

    public function test_application_after_deadline_is_rejected(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => 'pengguna']);
        $job = $this->createJob();
        $job->update(['deadline' => today()->subDay()]);

        $this->actingAs($user)
            ->post(route('jobs.apply.store', $job), [
                'full_name' => $user->name,
                'phone' => '08123456789',
                'email' => $user->email,
                'domicile' => 'Surabaya',
                'cv' => $this->fakePdf(),
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_cv_download_is_forbidden_to_another_user(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create(['role' => 'pengguna']);
        $otherUser = User::factory()->create(['role' => 'pengguna']);
        $job = $this->createJob();
        $cvPath = 'cvs/resume.pdf';
        Storage::disk('local')->put($cvPath, '%PDF-1.4');
        $application = Application::factory()->for($owner)->for($job, 'jobListing')->create([
            'cv_path' => $cvPath,
        ]);

        $this->actingAs($otherUser)
            ->get(route('applications.cv', $application))
            ->assertForbidden();

        $this->actingAs($owner)
            ->get(route('applications.cv', $application))
            ->assertOk();
    }

    public function test_admin_status_change_notifies_applicant_with_status_and_note(): void
    {
        $user = User::factory()->create(['role' => 'pengguna', 'is_notification_active' => true]);
        $admin = User::factory()->create(['role' => 'admin']);
        $application = Application::factory()
            ->for($user)
            ->for($this->createJob(), 'jobListing')
            ->create(['status' => 'Diproses']);

        $this->actingAs($admin)
            ->patch(route('admin.applications.update', $application), [
                'status' => 'Diterima',
                'notes' => 'Silakan jadwalkan wawancara.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'title' => 'Status lamaran diperbarui',
            'url' => route('riwayat.show', $application),
        ]);
        $statusNotification = $user->userNotifications()
            ->where('title', 'Status lamaran diperbarui')
            ->latest('id')
            ->firstOrFail();
        $this->assertStringContainsString('Diterima', $statusNotification->message);
        $this->assertStringContainsString('Silakan jadwalkan wawancara.', $statusNotification->message);
    }

    public function test_unverified_user_can_enter_but_cannot_apply(): void
    {
        $user = User::factory()->unverified()->create(['role' => 'pengguna']);
        $job = $this->createJob();

        $this->actingAs($user)->get(route('beranda'))->assertOk()->assertSee('Email Anda belum terverifikasi');
        $this->get(route('jobs.apply', $job))->assertRedirect(route('verification.notice'));
    }

    public function test_listing_search_and_application_form_render_for_the_matching_job_type(): void
    {
        $user = User::factory()->create([
            'role' => 'pengguna',
            'phone' => '08123456789',
            'location' => 'Surabaya',
        ]);
        $job = $this->createJob();
        $internship = $this->createJob('magang', 'UX Design Intern');

        $this->actingAs($user)
            ->get(route('lowongan.index', ['q' => 'Frontend']))
            ->assertOk()
            ->assertSee('Frontend Developer')
            ->assertDontSee('UX Design Intern');

        $this->get(route('magang.index'))
            ->assertOk()
            ->assertSee('UX Design Intern')
            ->assertSee('6 Bulan');

        $this->get(route('jobs.apply', $internship))
            ->assertOk()
            ->assertSee('Lengkapi informasi di bawah ini untuk melamar magang.')
            ->assertSee('name="full_name"', false)
            ->assertSee('value="'.$user->name.'"', false)
            ->assertSee('value="'.$user->location.'"', false);

        $this->get(route('lowongan.show', $job))
            ->assertOk()
            ->assertSee('Lamar sekarang');
    }

    private function createJob(string $type = 'lowongan', string $title = 'Frontend Developer'): JobListing
    {
        return JobListing::factory()
            ->published()
            ->state(['type' => $type, 'duration' => $type === 'magang' ? '6 Bulan' : null])
            ->create(['title' => $title, 'company_name' => 'CareerUp Test Company']);
    }

    private function fakePdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'resume.pdf',
            "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF\n",
        );
    }
}

<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingAndBerandaTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_landing_page_renders_without_seeded_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Temukan peluang yang membawamu lebih maju.')
            ->assertSee('Belum ada lowongan. Pantau terus ya!')
            ->assertSee('Belum ada program magang.')
            ->assertSee('Belum ada pelatihan.');
    }

    public function test_landing_only_displays_published_jobs_and_trainings(): void
    {
        $publishedJob = JobListing::factory()->published()->create([
            'title' => 'Posisi Terbit',
            'is_featured' => true,
        ]);
        JobListing::factory()->create(['title' => 'Posisi Draft']);
        JobListing::factory()->internship()->published()->create(['title' => 'Magang Terbit']);

        $publishedTraining = Training::factory()->published()->create(['title' => 'Kelas Terbit']);
        Training::factory()->create(['title' => 'Kelas Draft']);

        $this->get('/')
            ->assertOk()
            ->assertSee($publishedJob->title)
            ->assertSee('Magang Terbit')
            ->assertSee($publishedTraining->title)
            ->assertDontSee('Posisi Draft')
            ->assertDontSee('Kelas Draft');
    }

    public function test_authenticated_user_visiting_landing_is_redirected_to_beranda(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertRedirect(route('beranda'));
    }

    public function test_guest_cannot_access_beranda_and_user_can_see_empty_sections(): void
    {
        $this->get('/beranda')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get('/beranda')
            ->assertOk()
            ->assertSee('Selamat datang,')
            ->assertSee('Lowongan terbaru')
            ->assertSee('Program magang')
            ->assertSee('Pelatihan mendatang')
            ->assertSee('Belum ada lowongan.')
            ->assertSee('Riwayat Pendaftaran')
            ->assertSee('Keluar');
    }

    public function test_published_job_details_are_public_and_use_slug(): void
    {
        $job = JobListing::factory()->published()->create(['title' => 'Detail Posisi Terbit']);
        $draft = JobListing::factory()->create(['title' => 'Detail Posisi Draft']);
        $internship = JobListing::factory()->internship()->published()->create();
        $expiredJob = JobListing::factory()->published()->create([
            'title' => 'Posisi Kedaluwarsa',
            'deadline' => today()->subDay(),
        ]);

        $this->get(route('lowongan.show', $job))
            ->assertOk()
            ->assertSee($job->title)
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertDontSee('Lamar Sekarang');

        $this->get(route('magang.show', $internship))
            ->assertOk()
            ->assertSee($internship->title)
            ->assertDontSee('Lamar Sekarang');

        $this->get(route('lowongan.show', $draft))->assertNotFound();

        $this->actingAs(User::factory()->create())
            ->get(route('lowongan.show', $expiredJob))
            ->assertOk()
            ->assertSee('Ditutup')
            ->assertDontSee('Lamar Sekarang');
    }

    public function test_training_pages_require_authentication_and_unpublished_training_is_not_public(): void
    {
        $training = Training::factory()->create();
        $this->get('/pelatihan')->assertRedirect(route('login'));
        $this->get(route('pelatihan.show', $training))->assertRedirect(route('login'));

        $publishedTraining = Training::factory()->published()->create();
        $this->actingAs(User::factory()->create())
            ->get(route('pelatihan.show', $publishedTraining))
            ->assertOk()
            ->assertSee($publishedTraining->title);
    }
}

<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_is_forbidden_and_admin_can_open_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Belum ada lamaran');
    }

    public function test_admin_created_job_is_public_only_after_it_is_published(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $payload = [
            'title' => 'Frontend Developer CareerUp',
            'company_name' => 'Perusahaan Tes',
            'location' => 'Jakarta',
            'work_system' => 'Full Time',
            'department' => 'Engineering',
            'description' => 'Membangun aplikasi yang bermanfaat.',
            'qualifications' => "Menguasai PHP\nMampu bekerja dalam tim",
            'status' => 'draft',
            'is_featured' => '0',
        ];

        $this->actingAs($admin)
            ->post(route('admin.jobs.store', ['type' => 'lowongan']), $payload)
            ->assertRedirect(route('admin.jobs.index', ['type' => 'lowongan']));

        $job = JobListing::query()->where('title', $payload['title'])->firstOrFail();
        $this->assertSame('draft', $job->status);

        $this->actingAs(User::factory()->create())
            ->get(route('lowongan.index'))
            ->assertOk()
            ->assertDontSee($payload['title']);

        $this->actingAs($admin);
        $this->patch(route('admin.jobs.publish', ['type' => 'lowongan', 'job' => $job]))
            ->assertRedirect();
        $this->assertSame('terbit', $job->fresh()->status);

        $this->actingAs(User::factory()->create())
            ->get(route('lowongan.index'))
            ->assertOk()
            ->assertSee($payload['title']);
    }
}

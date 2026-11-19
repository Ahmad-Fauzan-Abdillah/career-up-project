<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DeploymentReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_added_to_responses(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_production_requests_redirect_to_https(): void
    {
        Config::set('app.env', 'production');
        Config::set('app.url', 'https://careerup.example.com');

        $this->get('/')->assertRedirect('https://localhost');
    }

    public function test_registration_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/register', [])->assertSessionHasErrors();
        }

        $this->post('/register', [])->assertStatus(429);
    }

    public function test_robots_and_sitemap_include_only_published_records(): void
    {
        $publishedJob = JobListing::factory()->create([
            'type' => 'lowongan',
            'status' => 'terbit',
            'deadline' => today()->addWeek(),
        ]);
        $publishedInternship = JobListing::factory()->create([
            'type' => 'magang',
            'status' => 'terbit',
            'deadline' => today()->addWeek(),
        ]);
        JobListing::factory()->create(['type' => 'magang', 'status' => 'draft']);
        $publishedTraining = Training::factory()->create(['status' => 'terbit']);
        Training::factory()->create(['status' => 'draft']);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee(route('sitemap'), false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('lowongan.show', $publishedJob->slug), false)
            ->assertSee(route('magang.show', $publishedInternship->slug), false)
            ->assertSee(route('pelatihan.show', $publishedTraining->slug), false)
            ->assertDontSee('draft');
    }

    public function test_forbidden_error_page_uses_careerup_layout(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertForbidden()
            ->assertSee('Akses tidak diizinkan');
    }
}

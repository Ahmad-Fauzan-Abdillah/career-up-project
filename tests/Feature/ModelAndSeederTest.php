<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\Application;
use App\Models\Subscription;
use App\Models\Training;
use App\Models\User;
use App\Models\UserNotification;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ModelAndSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_only_the_configured_admin(): void
    {
        Config::set('careerup.admin', [
            'name' => 'Administrator Tes',
            'email' => 'admin@example.test',
            'password' => 'A-Strong-Admin-Pass-42!',
        ]);
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertSame('Administrator Tes', $admin->name);
        $this->assertTrue($admin->isAdmin());
        $this->assertNotNull($admin->email_verified_at);
        $this->assertSame(1, User::count());
        $this->assertSame(0, JobListing::count());
        $this->assertSame(0, Training::count());
        $this->assertDatabaseCount('applications', 0);
        $this->assertDatabaseCount('user_notifications', 0);
    }

    public function test_database_seeder_fails_clearly_when_admin_credentials_are_missing(): void
    {
        Config::set('careerup.admin', ['name' => '', 'email' => '', 'password' => '']);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('ADMIN_NAME, ADMIN_EMAIL, dan ADMIN_PASSWORD');

        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_active_subscription_helper(): void
    {
        $user = User::factory()->create();

        $this->assertNull($user->activeSubscription());

        Subscription::factory()->active()->for($user)->create([
            'valid_until' => today()->subDay(),
        ]);
        $this->assertNull($user->fresh()->activeSubscription());

        $active = Subscription::factory()->active()->for($user)->create([
            'plan_type' => 'tahunan',
            'price' => 490000,
            'valid_until' => today()->addMonth(),
        ]);

        $this->assertNotNull($user->fresh()->activeSubscription());
        $this->assertEquals($active->id, $user->fresh()->activeSubscription()->id);
    }

    public function test_demo_seeder_is_restricted_to_local_environment(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DemoSeeder hanya boleh dijalankan saat APP_ENV=local.');

        $this->seed(DemoSeeder::class);
    }

    public function test_demo_seeder_runs_manually_in_local_and_publishes_its_examples(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        User::factory()->create(['role' => 'admin']);

        $this->seed(DemoSeeder::class);

        $this->assertGreaterThan(0, JobListing::published()->count());
        $this->assertGreaterThan(0, Training::published()->count());
        $this->assertNotEmpty(JobListing::firstOrFail()->slug);
        $this->assertNotEmpty(Training::firstOrFail()->slug);
        $this->assertDatabaseHas('users', ['email' => 'tanaya@gmail.com', 'role' => 'pengguna']);
        $this->assertGreaterThan(0, Application::count());
        $this->assertGreaterThan(0, UserNotification::count());
        $this->assertDatabaseMissing('user_notifications', ['title' => 'Contoh notifikasi']);
    }
}

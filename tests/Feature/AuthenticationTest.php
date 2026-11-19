<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar');
        $response->assertSee('Buat akun baru');
    }

    public function test_pengguna_can_register_and_is_redirected_to_beranda(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'Rahasia123!',
            'role' => 'pengguna',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'role' => 'pengguna',
        ]);
        $this->assertDatabaseCount('user_notifications', 1);
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => User::where('email', 'budi@example.com')->value('id'),
            'title' => 'Selamat datang di CareerUp',
        ]);
        $response->assertRedirect(route('beranda'));
    }

    public function test_registration_fails_if_password_does_not_meet_complexity(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'simple',
            'role' => 'pengguna',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_admin_registration_is_rejected_by_default(): void
    {
        Config::set('auth.allow_admin_registration', false);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Calon Admin',
            'email' => 'calonadmin@example.com',
            'password' => 'AdminRahasia123!',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors(['role' => 'Akun admin dibuat oleh sistem.']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'email' => 'calonadmin@example.com',
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Silakan masuk untuk melanjutkan');
    }

    public function test_pengguna_can_login_and_is_redirected_to_beranda(): void
    {
        $user = User::factory()->create([
            'name' => 'Siswa Pengguna',
            'email' => 'siswa@example.com',
            'password' => 'Password123!',
        ]);

        $response = $this->post('/login', [
            'email' => 'siswa@example.com',
            'password' => 'Password123!',
            'role' => 'pengguna',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('beranda'));
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => 'AdminPass123!',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'superadmin@example.com',
            'password' => 'AdminPass123!',
            'role' => 'admin',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_fails_if_role_tab_does_not_match(): void
    {
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => 'Password123!',
            'role' => 'pengguna',
        ]);

        // Regular user attempts to log in using admin tab
        $response = $this->from('/login')->post('/login', [
            'email' => 'regular@example.com',
            'password' => 'Password123!',
            'role' => 'admin',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('role');
        $this->assertTrue(
            session('errors')->first('email') === 'Akun tidak terdaftar sebagai admin.' ||
            str_contains(session('errors')->first('email'), 'Akun tidak terdaftar sebagai')
        );
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'name' => 'Pengguna Logout',
            'email' => 'logout@example.com',
            'password' => 'Password123!',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('landing'));
    }

    public function test_guest_is_redirected_to_login_when_accessing_protected_routes(): void
    {
        $responseBeranda = $this->get('/beranda');
        $responseBeranda->assertRedirect(route('login'));

        $responseAdmin = $this->get('/admin');
        $responseAdmin->assertRedirect(route('login'));
    }

    public function test_authenticated_user_visiting_login_or_register_is_redirected_to_beranda(): void
    {
        $user = User::factory()->create([
            'name' => 'Pengguna Aktif',
            'email' => 'aktif@example.com',
            'password' => 'Password123!',
        ]);

        $this->actingAs($user);

        $responseLogin = $this->get('/login');
        $responseLogin->assertRedirect(route('beranda'));

        $responseRegister = $this->get('/register');
        $responseRegister->assertRedirect(route('beranda'));
    }
}

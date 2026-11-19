<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_and_edit_form_render_and_profile_can_be_updated_with_avatar(): void
    {
        Storage::fake('public');
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('profil.index'))
            ->assertOk()
            ->assertSee('Lowongan Dilamar')
            ->assertSee('Lowongan Diterima')
            ->assertSee('Tentang Saya')
            ->assertSee('Pendidikan')
            ->assertSee('Pengalaman');

        $this->get(route('profil.edit'))
            ->assertOk()
            ->assertSee('Informasi Pribadi')
            ->assertSee('Nama Lengkap');

        $response = $this->patch(route('profil.update'), [
            'name' => 'Nama Baru',
            'phone' => '08123456789',
            'gender' => 'Perempuan',
            'email' => 'new-user@example.com',
            'location' => 'Bandung',
            'bio' => 'Tentang diri saya.',
            'avatar' => UploadedFile::fake()->createWithContent('avatar.png', $this->tinyPng()),
        ]);

        $response->assertRedirect(route('profil.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'new-user@example.com',
            'location' => 'Bandung',
            'email_verified_at' => null,
        ]);
        Storage::disk('public')->assertExists($user->fresh()->avatar);
    }

    public function test_profile_rejects_avatar_larger_than_two_megabytes(): void
    {
        $this->actingAs($this->createUser())
            ->patch(route('profil.update'), [
                'name' => 'Nama Baru',
                'phone' => '08123456789',
                'gender' => 'Laki-laki',
                'email' => 'user@example.com',
                'location' => 'Jakarta',
                'avatar' => UploadedFile::fake()->createWithContent('large.png', $this->tinyPng().str_repeat('a', 2 * 1024 * 1024)),
            ])
            ->assertSessionHasErrors('avatar');
    }

    public function test_notification_preference_is_saved_and_notifications_can_be_marked_read(): void
    {
        $user = $this->createUser();
        $first = UserNotification::factory()->for($user)->create([
            'title' => 'Notifikasi baru',
            'message' => 'Pesan notifikasi.',
            'type' => 'info',
        ]);
        $second = UserNotification::factory()->for($user)->create([
            'title' => 'Notifikasi lain',
            'message' => 'Pesan lain.',
            'type' => 'success',
        ]);

        $this->actingAs($user)
            ->get(route('pengaturan.index'))
            ->assertOk()
            ->assertSee('Kelola akun dan preferensi Anda')
            ->assertSee('Notifikasi');

        $this->patchJson(route('pengaturan.notifikasi.update'), [
            'is_notification_active' => false,
        ])->assertOk()->assertJson(['is_notification_active' => false]);
        $this->assertFalse($user->fresh()->is_notification_active);

        $this->patch(route('notifikasi.read', $first))
            ->assertRedirect(route('notifikasi.index'));
        $this->assertTrue($first->fresh()->is_read);

        $this->get(route('notifikasi.index'))
            ->assertOk()
            ->assertSee('Tandai semua dibaca')
            ->assertSee('Notifikasi lain');

        $this->patch(route('notifikasi.read-all'))
            ->assertRedirect(route('notifikasi.index'));
        $this->assertDatabaseMissing('user_notifications', [
            'id' => $second->id,
            'is_read' => false,
        ]);
    }

    public function test_user_cannot_mark_another_users_notification_as_read(): void
    {
        $user = $this->createUser();
        $otherUser = $this->createUser('other@example.com');
        $notification = UserNotification::factory()->for($otherUser)->create([
            'title' => 'Privat',
            'message' => 'Pesan privat.',
            'type' => 'info',
        ]);

        $this->actingAs($user)
            ->patch(route('notifikasi.read', $notification))
            ->assertForbidden();
    }

    public function test_password_update_checks_current_password_complexity_and_reuse(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $this->get(route('pengaturan.password'))
            ->assertOk()
            ->assertSee('Kriteria Password')
            ->assertSee('Minimal 8 karakter');

        $this->from(route('pengaturan.password'))
            ->patch(route('pengaturan.password.update'), [
                'current_password' => 'wrong-password',
                'new_password' => 'FreshPassword123!',
                'new_password_confirmation' => 'FreshPassword123!',
            ])
            ->assertSessionHasErrors('current_password');

        $this->from(route('pengaturan.password'))
            ->patch(route('pengaturan.password.update'), [
                'current_password' => 'Password123!',
                'new_password' => 'Password123!',
                'new_password_confirmation' => 'Password123!',
            ])
            ->assertSessionHasErrors('new_password');

        $this->patch(route('pengaturan.password.update'), [
            'current_password' => 'Password123!',
            'new_password' => 'FreshPassword123!',
            'new_password_confirmation' => 'FreshPassword123!',
        ])->assertRedirect(route('pengaturan.index'));

        $this->assertTrue(Hash::check('FreshPassword123!', $user->fresh()->password));
    }

    public function test_email_status_shows_verified_and_unverified_states(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)->get(route('pengaturan.email'))
            ->assertOk()
            ->assertSee('Email Sudah Terverifikasi!')
            ->assertSee($user->email);

        $user->forceFill(['email_verified_at' => null])->save();
        $this->get(route('pengaturan.email'))
            ->assertOk()
            ->assertSee('Belum terverifikasi');
    }

    public function test_only_admin_can_access_admin_placeholder_and_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->actingAs($this->createUser())->get(route('admin.dashboard'))->assertForbidden();

        $admin = $this->createUser('admin@example.com', 'admin');
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin');
        $this->get(route('beranda'))->assertForbidden();
    }

    private function createUser(string $email = 'user@example.com', string $role = 'pengguna'): User
    {
        return User::factory()->create([
            'name' => 'Pengguna Tes',
            'email' => $email,
            'password' => 'Password123!',
            'role' => $role,
            'email_verified_at' => now(),
            'is_notification_active' => true,
        ]);
    }

    private function tinyPng(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/JwoAAAAASUVORK5CYII=',
            true,
        );
    }
}

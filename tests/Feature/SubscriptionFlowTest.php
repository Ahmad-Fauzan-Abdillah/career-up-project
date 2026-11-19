<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\Training;
use App\Models\TrainingEnrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubscriptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_subscription_sees_the_subscribe_action(): void
    {
        $user = User::factory()->create(['role' => 'pengguna']);
        $training = $this->createTraining();

        $this->actingAs($user)
            ->get(route('pelatihan.show', $training))
            ->assertOk()
            ->assertSee('Berlangganan');
    }

    public function test_monthly_subscription_is_pending_until_admin_confirms_payment(): void
    {
        $this->assertPaymentFlow('bulanan', 49000, today()->addMonth()->toDateString());
    }

    public function test_annual_subscription_validity_starts_on_confirmation(): void
    {
        $this->assertPaymentFlow('tahunan', 490000, today()->addYear()->toDateString());
    }

    public function test_payment_page_without_checkout_session_redirects_to_plan_selection(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pengguna']))
            ->get(route('subscriptions.payment'))
            ->assertRedirect(route('subscriptions.index'));
    }

    public function test_user_cannot_view_another_users_subscription_status(): void
    {
        $owner = User::factory()->create(['role' => 'pengguna']);
        $otherUser = User::factory()->create(['role' => 'pengguna']);
        $subscription = Subscription::factory()->active()->for($owner)->create([
            'valid_until' => today()->addMonth(),
        ]);

        $this->actingAs($otherUser)
            ->get(route('subscriptions.success', $subscription))
            ->assertForbidden();
    }

    public function test_only_confirmed_subscription_allows_premium_enrollment_and_zoom_link_is_gated(): void
    {
        $user = User::factory()->create(['role' => 'pengguna']);
        $training = $this->createTraining();
        $subscription = Subscription::factory()->for($user)->for($training)->create([
            'status' => 'pending',
            'valid_until' => null,
        ]);

        $this->actingAs($user)
            ->post(route('pelatihan.join', $training))
            ->assertForbidden();
        $this->assertDatabaseMissing('training_enrollments', ['user_id' => $user->id, 'training_id' => $training->id]);
        $this->assertNull($user->fresh()->activeSubscription());

        $subscription->update([
            'status' => 'aktif',
            'confirmed_at' => now(),
            'confirmed_by' => User::factory()->create(['role' => 'admin'])->id,
            'valid_until' => today()->addMonth(),
        ]);

        $this->post(route('pelatihan.join', $training))
            ->assertRedirect(route('pelatihan.show', $training));
        $this->assertDatabaseHas('training_enrollments', ['user_id' => $user->id, 'training_id' => $training->id]);

        $this->get(route('pelatihan.show', $training))
            ->assertOk()
            ->assertSee('Mulai dalam')
            ->assertDontSee($training->zoom_link);
        $this->get(route('pelatihan.zoom', $training))->assertForbidden();
    }

    public function test_expired_subscription_no_longer_grants_premium_access(): void
    {
        $user = User::factory()->create(['role' => 'pengguna', 'is_notification_active' => true]);
        $training = $this->createTraining();
        $subscription = Subscription::factory()->active()->for($user)->for($training)->create([
            'valid_until' => today()->subDay(),
        ]);

        $this->artisan('subscriptions:expire')->assertSuccessful();

        $this->assertSame('kedaluwarsa', $subscription->fresh()->status);
        $this->assertNull($user->fresh()->activeSubscription());
        $this->actingAs($user)->post(route('pelatihan.join', $training))->assertForbidden();
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'title' => 'Langganan berakhir',
        ]);

        $this->get(route('subscriptions.success', $subscription))
            ->assertOk()
            ->assertSee('Langganan Kedaluwarsa');
    }

    public function test_free_training_requires_enrollment_and_does_not_expose_zoom_before_schedule(): void
    {
        $user = User::factory()->create(['role' => 'pengguna']);
        $training = $this->createTraining('Pelatihan Gratis', 'Gratis');

        $this->actingAs($user)->get(route('pelatihan.show', $training))
            ->assertOk()
            ->assertSee('Daftar')
            ->assertDontSee($training->zoom_link);

        $this->post(route('pelatihan.join', $training))
            ->assertRedirect(route('pelatihan.show', $training));
        $this->post(route('pelatihan.join', $training))
            ->assertRedirect(route('pelatihan.show', $training));
        $this->assertSame(1, TrainingEnrollment::where('user_id', $user->id)->where('training_id', $training->id)->count());
        $this->get(route('pelatihan.zoom', $training))->assertForbidden();
    }

    public function test_full_training_rejects_enrollment(): void
    {
        $user = User::factory()->create(['role' => 'pengguna']);
        $training = $this->createTraining('Pelatihan Penuh', 'Gratis', 1);
        TrainingEnrollment::factory()->for($training)->create();

        $this->actingAs($user)->post(route('pelatihan.join', $training))->assertStatus(409);
        $this->get(route('pelatihan.show', $training))->assertOk()->assertSee('Kuota penuh');
    }

    private function assertPaymentFlow(string $plan, int $price, string $validUntil): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => 'pengguna', 'is_notification_active' => true]);
        $admin = User::factory()->create(['role' => 'admin']);
        $training = $this->createTraining();

        $this->actingAs($user)
            ->get(route('subscriptions.index', ['training' => $training->id]))
            ->assertOk()
            ->assertSee('PAKET BULANAN')
            ->assertSee('PAKET TAHUNAN');

        $this->post(route('subscriptions.store'), [
            'training_id' => $training->id,
            'plan_type' => $plan,
        ])->assertRedirect(route('subscriptions.payment'));

        $this->get(route('subscriptions.payment'))
            ->assertOk()
            ->assertSee('Ringkasan Pembayaran')
            ->assertSee('Kode unik')
            ->assertSee('Unggah bukti pembayaran');

        $this->post(route('subscriptions.pay'), [
            'payment_proof' => $this->fakePdf(),
        ])->assertRedirect();

        $subscription = Subscription::firstOrFail();
        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'user_id' => $user->id,
            'training_id' => $training->id,
            'plan_type' => $plan,
            'price' => $price,
            'status' => 'pending',
            'valid_until' => null,
        ]);
        Storage::disk('local')->assertExists($subscription->payment_proof);
        $this->assertNull($user->fresh()->activeSubscription());
        $this->assertDatabaseMissing('user_notifications', [
            'user_id' => $user->id,
            'title' => 'Langganan dikonfirmasi',
        ]);
        $this->get(route('subscriptions.success', $subscription))
            ->assertOk()
            ->assertSee('Menunggu Verifikasi');

        $this->actingAs($admin)->patch(route('admin.subscriptions.verify', $subscription), [
            'action' => 'confirm',
        ])->assertRedirect();

        $subscription->refresh();
        $this->assertSame('aktif', $subscription->status);
        $this->assertSame($validUntil, $subscription->valid_until->toDateString());
        $this->assertNotNull($subscription->confirmed_at);
        $this->assertSame($admin->id, $subscription->confirmed_by);
        $this->assertNotNull($user->fresh()->activeSubscription());
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'title' => 'Langganan dikonfirmasi',
        ]);

        $this->get(route('subscriptions.success', $subscription))
            ->assertOk()
            ->assertSee('Langganan Aktif')
            ->assertSee('Berlaku sampai');
    }

    private function createTraining(string $title = 'Data Analytics', string $category = 'Premium', ?int $quota = null): Training
    {
        return Training::factory()->published()->create([
            'title' => $title,
            'category' => $category,
            'quota' => $quota,
        ]);
    }

    private function fakePdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'proof.pdf',
            "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF\n",
        );
    }
}

<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobListingController as AdminJobListingController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\TrainingController as AdminTrainingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationHistoryController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TrainingAccessController;
use App\Http\Controllers\TrainingCertificateController;
use App\Http\Controllers\TrainingController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page (Hanya Guest, user login diarahkan ke /beranda)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

// Public Detail Routes (Dapat dibuka oleh guest maupun auth user)
Route::get('/lowongan/{job}', [JobListingController::class, 'showLowongan'])->name('lowongan.show');
Route::get('/magang/{job}', [JobListingController::class, 'showMagang'])->name('magang.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:register');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->middleware('throttle:password-reset')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:password-reset')->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/langganan/berhasil/{subscription}', [SubscriptionController::class, 'success'])
        ->name('subscriptions.success');
    Route::get('/email/verify', fn () => redirect()->route('pengaturan.email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('beranda')->with('status', 'Email berhasil diverifikasi.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('status', 'Email Anda sudah terverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Link verifikasi berhasil dikirim ke email Anda.');
    })->middleware('throttle:verification-resend')->name('verification.send');

    // Pengguna Routes
    Route::middleware('role:pengguna')->group(function () {
        Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

        Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])->middleware('verified')->name('jobs.apply');
        Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->middleware(['verified', 'throttle:uploads'])->name('jobs.apply.store');
        Route::get('/applications/{application}/cv', [ApplicationController::class, 'downloadCv'])->name('applications.cv');

        // Lowongan, Magang & Pelatihan Listing
        Route::get('/lowongan', [JobListingController::class, 'indexLowongan'])->name('lowongan.index');
        Route::get('/magang', [JobListingController::class, 'indexMagang'])->name('magang.index');
        Route::get('/pelatihan', [TrainingController::class, 'index'])->name('pelatihan.index');
        Route::get('/pelatihan/{training}', [TrainingController::class, 'show'])->name('pelatihan.show');
        Route::post('/pelatihan/{training}/join', [TrainingAccessController::class, 'join'])->name('pelatihan.join');
        Route::get('/pelatihan/{training}/zoom', [TrainingAccessController::class, 'zoom'])->name('pelatihan.zoom');

        Route::get('/langganan', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/langganan', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('/langganan/pembayaran', [SubscriptionController::class, 'payment'])->name('subscriptions.payment');
        Route::post('/langganan/bayar', [SubscriptionController::class, 'pay'])->middleware('throttle:uploads')->name('subscriptions.pay');
        // Riwayat Pendaftaran
        Route::get('/riwayat', [ApplicationHistoryController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat/{application}', [ApplicationHistoryController::class, 'show'])->name('riwayat.show');
        Route::patch('/riwayat/{application}/batalkan', [ApplicationHistoryController::class, 'cancel'])->name('riwayat.cancel');
        Route::redirect('/riwayat-pendaftaran', '/riwayat');

        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
        Route::patch('/notifikasi/read-all', [NotificationController::class, 'markAllRead'])->name('notifikasi.read-all');
        Route::patch('/notifikasi/{notification}/read', [NotificationController::class, 'markRead'])->name('notifikasi.read');

        Route::get('/pengaturan', [SettingsController::class, 'index'])->name('pengaturan.index');
        Route::get('/pengaturan/password', [SettingsController::class, 'editPassword'])->name('pengaturan.password');
        Route::patch('/pengaturan/password', [SettingsController::class, 'updatePassword'])->name('pengaturan.password.update');
        Route::get('/pengaturan/email', [SettingsController::class, 'emailStatus'])->name('pengaturan.email');
        Route::patch('/pengaturan/notifikasi', [SettingsController::class, 'updateNotificationPreference'])->name('pengaturan.notifikasi.update');

        Route::get('/profil', [ProfileController::class, 'show'])->name('profil.index');
        Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileController::class, 'update'])->middleware('throttle:uploads')->name('profil.update');
        Route::get('/sertifikat/{enrollment}', [TrainingCertificateController::class, 'show'])->name('certificates.show');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('{type}')->where(['type' => 'lowongan|magang'])->name('jobs.')->group(function () {
            Route::get('/', [AdminJobListingController::class, 'index'])->name('index');
            Route::get('/buat', [AdminJobListingController::class, 'create'])->name('create');
            Route::post('/', [AdminJobListingController::class, 'store'])->middleware('throttle:uploads')->name('store');
            Route::get('/{job}/edit', [AdminJobListingController::class, 'edit'])->name('edit');
            Route::put('/{job}', [AdminJobListingController::class, 'update'])->middleware('throttle:uploads')->name('update');
            Route::patch('/{job}/terbitkan', [AdminJobListingController::class, 'publish'])->name('publish');
            Route::patch('/{job}/tutup', [AdminJobListingController::class, 'close'])->name('close');
            Route::delete('/{job}', [AdminJobListingController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('pelatihan')->name('trainings.')->group(function () {
            Route::get('/', [AdminTrainingController::class, 'index'])->name('index');
            Route::get('/buat', [AdminTrainingController::class, 'create'])->name('create');
            Route::post('/', [AdminTrainingController::class, 'store'])->middleware('throttle:uploads')->name('store');
            Route::get('/{training}/edit', [AdminTrainingController::class, 'edit'])->name('edit');
            Route::put('/{training}', [AdminTrainingController::class, 'update'])->middleware('throttle:uploads')->name('update');
            Route::get('/{training}/peserta', [AdminTrainingController::class, 'participants'])->name('participants');
            Route::patch('/{training}/peserta/{enrollment}/sertifikat', [AdminTrainingController::class, 'issueCertificate'])->name('certificate.issue');
            Route::delete('/{training}', [AdminTrainingController::class, 'destroy'])->name('destroy');
        });

        Route::get('/pelamar', [AdminApplicationController::class, 'index'])->name('applications.index');
        Route::get('/pelamar/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
        Route::get('/pelamar/{application}/cv', [AdminApplicationController::class, 'downloadCv'])->name('applications.cv');
        Route::patch('/pelamar/{application}', [AdminApplicationController::class, 'updateStatus'])->name('applications.update');
        Route::get('/langganan', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::patch('/langganan/{subscription}/verifikasi', [AdminSubscriptionController::class, 'verify'])->name('subscriptions.verify');
        Route::get('/langganan/{subscription}/bukti', [AdminSubscriptionController::class, 'downloadProof'])->name('subscriptions.proof');
        Route::get('/pengguna', [AdminUserController::class, 'index'])->name('users.index');
    });
});

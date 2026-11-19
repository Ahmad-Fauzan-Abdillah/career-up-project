<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        // Pendaftaran role Admin dinonaktifkan secara default
        if ($request->role === 'admin' && ! config('auth.allow_admin_registration', false)) {
            throw ValidationException::withMessages([
                'role' => 'Akun admin dibuat oleh sistem.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_notification_active' => true,
        ]);

        Auth::login($user);
        event(new Registered($user));

        $request->session()->regenerate();

        return redirect()->route('beranda');
    }
}

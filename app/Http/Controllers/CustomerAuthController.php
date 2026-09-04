<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function account(): View|RedirectResponse
    {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.account');
    }

    public function login(): View
    {
        return view('auth.customer-login');
    }

    public function forgotPassword(): View { return view('auth.forgot-password'); }

    public function sendPasswordOtp(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $data['email'])->where('is_admin', false)->first();
        if (! $user) return back()->withErrors(['email' => 'No customer account was found for this email address.']);
        $otp = (string) random_int(100000, 999999);
        $request->session()->put('password_reset_otp', ['email' => $user->email, 'otp' => Hash::make($otp), 'expires_at' => now()->addMinutes(10)->timestamp, 'attempts' => 0]);
        try { Mail::raw("Your Pandiyan Store password reset OTP is {$otp}. It expires in 10 minutes. Do not share this code with anyone.", fn ($message) => $message->to($user->email)->subject('Pandiyan Store password reset OTP')); }
        catch (\Throwable $exception) { report($exception); $request->session()->forget('password_reset_otp'); return back()->withErrors(['email' => 'We could not send the OTP email. Please try again later.']); }
        return redirect()->route('password.otp')->with('success', 'OTP sent to your email address.');
    }

    public function otpForm(Request $request): View|RedirectResponse { return $request->session()->has('password_reset_otp') ? view('auth.password-otp', ['email' => $request->session()->get('password_reset_otp.email')]) : redirect()->route('password.request'); }

    public function verifyPasswordOtp(Request $request): RedirectResponse
    {
        $data = $request->validate(['otp' => ['required', 'digits:6']]); $reset = $request->session()->get('password_reset_otp');
        if (! $reset || now()->timestamp > $reset['expires_at'] || ($reset['attempts'] ?? 0) >= 5) { $request->session()->forget('password_reset_otp'); return redirect()->route('password.request')->withErrors(['email' => 'OTP expired. Please request a new OTP.']); }
        if (! Hash::check($data['otp'], $reset['otp'])) { $reset['attempts']++; $request->session()->put('password_reset_otp', $reset); return back()->withErrors(['otp' => 'Incorrect OTP.']); }
        $request->session()->put('password_reset_verified', ['email' => $reset['email'], 'expires_at' => $reset['expires_at']]); $request->session()->forget('password_reset_otp');
        return redirect()->route('password.reset');
    }

    public function resetPasswordForm(Request $request): View|RedirectResponse
    {
        $reset = $request->session()->get('password_reset_verified');
        if (! $reset || now()->timestamp > $reset['expires_at']) { $request->session()->forget('password_reset_verified'); return redirect()->route('password.request')->withErrors(['email' => 'OTP expired. Please request a new OTP.']); }
        return view('auth.reset-password', ['email' => $reset['email']]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $reset = $request->session()->get('password_reset_verified');
        if (! $reset || now()->timestamp > $reset['expires_at']) { $request->session()->forget('password_reset_verified'); return redirect()->route('password.request')->withErrors(['email' => 'OTP expired. Please request a new OTP.']); }
        $data = $request->validate(['password' => ['required', 'confirmed', Password::min(8)]]);
        User::where('email', $reset['email'])->where('is_admin', false)->firstOrFail()->update(['password' => $data['password']]);
        $request->session()->forget('password_reset_verified');
        return redirect()->route('login')->with('success', 'Password changed successfully. Please login with your new password.');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (! Auth::attempt($credentials, true)) {
            return back()->withErrors(['email' => 'The email ID or password is incorrect.'])->onlyInput('email');
        }
        $request->session()->regenerate();

        if ($request->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        User::create(['name' => $data['name'], 'phone' => $data['phone'], 'email' => $data['email'], 'password' => $data['password']]);

        return redirect()->route('login')->with('success', 'Registration completed successfully. Please login to continue.');
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,'.$request->user()->id],
        ]);
        $request->user()->update($data);

        return back()->with('success', 'Account details updated successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UserController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_verified) {
                Auth::logout(); // logout segera
                return redirect()->back()->with('error', 'Akun Anda belum diverifikasi oleh admin.');
            }

            return redirect()->route('home')->with('success', 'Login berhasil, Selamat datang di GMP App');
        } else {
            return redirect()->back()->with('error', 'Login gagal, silahkan coba kembali !');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda telah berhasil Logout !');
    }

    public function showUnverified()
    {
        $users = User::where('is_verified', false)->get();
        return view('admin.verifikasi', compact('users'));
    }

    public function verifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = true;
        $user->save();

        return redirect()->back()->with('success', 'Akun berhasil diverifikasi.');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6',
            'department' => 'required|string|max:255',
        ]);

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'Guest',
            'department'  => $request->department,
            'is_verified' => false,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan tunggu verifikasi admin sebelum login.');
    }


    public function index()
    {
        $user = User::get();
        return view('user.index', compact('user'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function submit(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'role' => 'required|in:admin,user',
            'department' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'department' => $request->department,
        ]);

        return redirect()->route('user.index')->with('success', 'Berhasil menambahkan akun ! ');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'Berhasil mengedit profile.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.index')->with('failed', 'Akun berhasil di hapus!');
    }

    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email yang dipilih tidak valid.',
        ]);

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('reset_email', $request->email);
        Session::put('otp_expires_at', Carbon::now()->addMinutes(3)); // waktu kedaluwarsa OTP

        return redirect()->route('password.verifyForm')->with('otp_code', $otp);
    }

    public function showOtpForm()
    {
        return view('auth.verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $expiresAt = Session::get('otp_expires_at');

        if (!$expiresAt || now()->greaterThan(Carbon::parse($expiresAt))) {
            Session::forget(['otp', 'otp_expires_at', 'reset_email']);
            return redirect()->route('password.request')->with('error', 'Kode OTP telah kedaluwarsa, silakan coba lagi.');
        }

        if ($request->otp == Session::get('otp')) {
            return view('auth.reset')->with('success', 'Kode OTP berhasil diverifikasi. Silakan buat password baru.');
        }

        return redirect()->back()->with('error', 'Kode OTP salah!');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = Session::get('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Email tidak ditemukan.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Session::forget(['otp', 'reset_email']);

        return redirect()->route('login')->with('success', 'Password berhasil direset, silahkan login!');
    }
}

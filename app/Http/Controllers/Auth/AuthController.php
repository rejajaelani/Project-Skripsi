<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Jika login berhasil
            $user = Auth::user();

            // Menghilangkan kolom password sebelum menyimpan dalam sesi
            $userArray = [
                "username" => $user->username,
                "nama_lengkap" => $user->nama_lengkap,
                "email" => $user->email,
                "hak_akses" => $user->hak_akses,
            ];

            // Simpan data pengguna dalam sesi
            session(['user' => $userArray]);

            // Redirect ke halaman dashboard
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal
        return back()->withErrors(['username' => 'Your username or password is incorrect, Please check again.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Melakukan logout pengguna
        $request->session()->invalidate(); // Menghapus sesi pengguna
        $request->session()->regenerateToken(); // Membuat token sesi baru

        return redirect('/login'); // Mengarahkan pengguna kembali ke halaman login
    }
}

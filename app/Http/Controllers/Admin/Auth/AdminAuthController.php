<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
{
    if (auth()->check()) {
        return redirect()->route('admin.dashboard.index'); // Redirect to the 'home' route if already logged in
    }

    return view('auth.admin.login'); // Otherwise, show the login form
}


    // Proses login
    public function login(Request $request)
    {
        $loginFields = config('auth.login.fields', ['email']);

        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');

        foreach ($loginFields as $field) {
            $credentials = [
                $field => $loginInput,
                'password' => $password,
            ];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->route('admin.dashboard.index');
            }
        }

        return back()->withErrors([
            'login' => 'Kredensial tidak cocok atau akun tidak ditemukan.',
        ])->onlyInput('login');
    }


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login'); // Redirect to the 'home' route if already logged in
   
    }


}

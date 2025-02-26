<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password'); // Extract credentials

        if (Auth::attempt($credentials)) {
            // Determine where to redirect based on role
            $role = Auth::user()->role;
            echo $role;
            // dd($role);
            if ($role === 'admin') {
                // dd("สวัสดีฉันคือ Admin");
                // header('Location: http://www.example.com/');
                return redirect('/profile');
            } elseif ($role === 'staff') {
                return redirect('/profile');
            } else {
                // dd("สวัสดีฉันคือ User");
                return redirect('/profile');
            }
        } else {
            // Redirect back with error message
            return redirect()->route('login')->withErrors([
                'email' => 'อีเมล หรือ รหัสผ่านไม่ถูกต้องครับ.',
            ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

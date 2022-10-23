<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // dd($request->all());
        // validate data 
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // login data 
        if(Auth::attempt($request->only('email', 'password'))) {
            return redirect('home');
        }

        return redirect('login')->withError('Login details are not valid');

    }

    public function register_view() {
        return view('auth.register');
    }

    public function register(Request $request) {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        // save in user table 

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if(Auth::attempt($request->only('email', 'password'))) {
            return redirect('home');
        }

        return redirect('register')->withError('Error');
    }

    public function home() {
        return view('home');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

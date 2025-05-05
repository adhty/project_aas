<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Tampilan Form Login
    public function showLoginForm()
    {
        return view('auth/login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $valid=$request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        return response()->json($valid, 201);
    }

    // Tampilan Form Register
    public function showRegisterForm()
    {   
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        // Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Simpan User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        // Update or create profile
        $profileData = [
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
        ];
        
        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->profile && $user->profile->foto) {
                Storage::disk('public')->delete($user->profile->foto);
            }
            
            $path = $request->file('foto')->store('profile-photos', 'public');
            $profileData['foto'] = $path;
        }
        
        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);
        
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
    
    /**
     * Update user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->password),
        ]);
        
        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui.');
    }
}
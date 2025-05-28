<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load('profile');
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
    
    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
        ]);
        
        // Update user data
        if (isset($validated['name']) || isset($validated['email'])) {
            $userData = array_intersect_key($validated, array_flip(['name', 'email']));
            $user->update($userData);
        }
        
        // Update profile data
        $profileData = array_intersect_key($validated, array_flip(['alamat', 'no_telepon', 'jenis_kelamin', 'tanggal_lahir']));
        if (!empty($profileData)) {
            $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);
        }
        
        $user->load('profile');
        
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }
    
    /**
     * Upload profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $user = $request->user();
        
        // Delete old photo if exists
        if ($user->profile && $user->profile->foto) {
            Storage::disk('public')->delete($user->profile->foto);
        }
        
        $path = $request->file('foto')->store('profile-photos', 'public');
        
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['foto' => $path]
        );
        
        $user->load('profile');
        
        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupload',
            'data' => $user
        ]);
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
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = $request->user();
        $user->update([
            'password' => bcrypt($request->password),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui'
        ]);
    }
}
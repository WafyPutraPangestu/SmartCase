<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
    
        if (!$user->profile) {
            $user->profile()->create([
                'kategori_pelanggan_id' => null,
                'alamat' => '',
                'no_telepon' => '',
            ]);
            
            // FIX: Refresh relationship agar $user tahu profile sudah dibuat
            $user->load('profile'); 
        }
    
        return view('user.profile.index', [
            'user' => $user,
            'profile' => $user->profile,
            'kategoriPelanggan' => KategoriPelanggan::all()
        ]);
    }
    
    public function updateField(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $key = $request->key;
        $value = $request->value;

        if (in_array($key, ['name', 'email'])) {
            $user->update([$key => $value]);
            return response()->json(['status' => 'updated user']);
        }

        // Pastikan profile ada sebelum update (safety)
        if (!$user->profile) {
             return response()->json(['status' => 'error', 'message' => 'Profile not found'], 404);
        }

        $user->profile->update([$key => $value]);

        return response()->json(['status' => 'updated profile']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'kategori_pelanggan_id' => 'nullable|integer'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
    
        // update table users
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
    
        // update table profiles
        // Menggunakan updateOrCreate untuk jaga-jaga jika profile terhapus manual
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'kategori_pelanggan_id' => $request->kategori_pelanggan_id,
            ]
        );
    
        return response()->json(['success' => true]);
    }
}
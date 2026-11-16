<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    // Tampilkan halaman profil
    public function index()
    {
        $user = User::find(session('user_id'));
        return view('user.profil', compact('user'));
    }

    // Update profil
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::find(session('user_id'));

        $data = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        // Upload foto profil kalau ada
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profil', 'public');
            $data['foto_profil'] = $path;
        }

        $user->update($data);

        // Update nama di session
        session(['nama' => $user->nama]);

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
    }
}

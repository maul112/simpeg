<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index() {
        $user = auth()->user();
        return view('pegawai.dashboard', compact('user'));
    }

    // public function setting()
    // {
    //     $user = auth()->user();
    //     return view('pegawai.settings.pengaturan', compact('user'));
    // }

    public function profile()
    {
        $user = auth()->user();
        return view('pegawai.settings.profile', compact('user'));
    }

    public function notification()
    {
        $user = auth()->user();
        return view('pegawai.settings.notifikasi', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
        ]);

        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('pegawai.profil')
            ->with('status', 'Email berhasil diperbarui.');
    }
}

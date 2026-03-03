<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index() {
        $user = auth()->user();
        return view('pegawai.dashboard', compact('user'));
    }

    public function profile()
    {
        return view('pegawai.settings.profil');
    }
}

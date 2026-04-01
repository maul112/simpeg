<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TamuController extends Controller
{
    public function index() {
        $name = Cookie::get('guest_name');
        $address = Cookie::get('guest_address');
        return view('tamu.index', compact('name', 'address'));
    }

    public function masukForm()
    {
        return view('pages.auth.register');
    }

    public function masuk(Request $request)
    {
        $name = $request->input('name');
        $address = $request->input('address');
        Cookie::queue('guest_name', $name, 1440);
        Cookie::queue('guest_address', $address, 1440);
        return redirect()->route('tamu.index');
    }
}
 
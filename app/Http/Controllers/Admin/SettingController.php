<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting', compact('setting'));
    }

    public function update(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat'    => 'required|string',
        ]);

        // Simpan ke database
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'nama_toko' => $request->nama_toko,
                'alamat'    => $request->alamat,
            ]
        );

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}
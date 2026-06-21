<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    // 1. Menampilkan daftar meja
    public function index(Request $request) 
    {
        $query = Meja::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nomor_meja', 'like', '%' . $request->search . '%');
        }

        $mejas = $query->latest()->paginate(10);
        
        return view('admin.meja', compact('mejas'));
    }

    // 2. Simpan Data Meja Baru
    public function store(Request $request)
{
    $request->validate([
        'nomor_meja' => 'required|unique:mejas,nomor_meja|max:50',
    ]);

    Meja::create($request->all());
    return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambah!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nomor_meja' => 'required|unique:mejas,nomor_meja,' . $id . '|max:50',
    ]);

    $meja = Meja::findOrFail($id);
    $meja->update($request->all());
    return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil diupdate!');
}
    // 4. Hapus Data Meja
    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->delete();

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil dihapus!');
    }
}
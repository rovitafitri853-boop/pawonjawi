<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Menampilkan daftar menu
    public function index(Request $request)
    {
        $kategoris = Kategori::all();
        $query = Menu::query();

        if ($request->has('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        $menus = $query->paginate(10)->withQueryString();

        return view('admin.menu', compact('menus', 'kategoris'));
    }

    // Fungsi AJAX untuk mengecek duplikasi nama menu
    public function check(Request $request)
    {
        $nama = $request->query('nama');
        $id = $request->query('id'); // ID akan kosong saat 'store' (tambah baru)

        $exists = Menu::where('nama_menu', $nama)
            ->when($id, function($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    // Menyimpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|unique:menus,nama_menu',
            'kategori_id' => 'required',
            'harga' => 'required|numeric',
        ]);

        Menu::create($request->all());
        return redirect()->back()->with('success', 'Menu berhasil disimpan!');
    }

    // Mengupdate menu
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|unique:menus,nama_menu,' . $id,
            'kategori_id' => 'required',
            'harga' => 'required|numeric',
        ]);

        Menu::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Menu berhasil diupdate!');
    }

    // Menghapus menu
    public function destroy($id)
    {
        Menu::findOrFail($id)->delete();
        return back()->with('success', 'Menu berhasil dihapus!');
    }
}
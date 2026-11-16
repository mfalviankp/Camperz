<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class StokController extends Controller
{
    // Daftar semua item
    public function index()
    {
        $items = Item::orderBy('nama_item')->get();
        return view('admin.stok.index', compact('items'));
    }

    // Form tambah item baru
    public function create()
    {
        return view('admin.stok.edit', ['item' => null]);
    }

    // Simpan item baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'harga_sewa' => 'required|numeric|min:0',
            'jumlah_stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama_item', 'deskripsi', 'kategori', 'harga_sewa', 'jumlah_stok']);
        $data['status'] = 'tersedia';

        // Upload foto kalau ada
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.stok.index')->with('success', 'Item berhasil ditambahkan!');
    }

    // Form edit item
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.stok.edit', compact('item'));
    }

    // Update item
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'harga_sewa' => 'required|numeric|min:0',
            'jumlah_stok' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,maintenance',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item = Item::findOrFail($id);
        $data = $request->only(['nama_item', 'deskripsi', 'kategori', 'harga_sewa', 'jumlah_stok', 'status']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('admin.stok.index')->with('success', 'Item berhasil diperbarui!');
    }

    // Soft delete — ubah status jadi maintenance
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'maintenance']);

        return redirect()->route('admin.stok.index')->with('success', 'Item dipindahkan ke maintenance.');
    }
}

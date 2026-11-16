<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    // Tampilkan halaman katalog dengan filter
    public function index(Request $request)
    {
        $query = Item::where('status', 'tersedia');

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan pencarian nama
        if ($request->filled('cari')) {
            $query->where('nama_item', 'like', '%' . $request->cari . '%');
        }

        $items = $query->orderBy('nama_item')->get();

        // Ambil daftar kategori unik untuk filter
        $kategoris = Item::where('status', 'tersedia')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('user.katalog', compact('items', 'kategoris'));
    }

    // Tampilkan detail item
    public function detail($id)
    {
        $item = Item::findOrFail($id);
        return view('user.detail', compact('item'));
    }
}

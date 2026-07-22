<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaans = Perusahaan::latest()->paginate(10);
        return view('master.perusahaan.index', compact('perusahaans'));
    }

    public function create()
    {
        return view('master.perusahaan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'telp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        Perusahaan::create($validated);

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    public function edit(Perusahaan $perusahaan)
    {
        return view('master.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, Perusahaan $perusahaan)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'telp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $perusahaan->update($validated);

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil diupdate.');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil dihapus.');
    }
}
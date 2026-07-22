<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index()
    {
        $rekenings = Rekening::with('perusahaan')->latest()->paginate(10);
        return view('master.rekening.index', compact('rekenings'));
    }

    public function create()
    {
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();
        return view('master.rekening.create', compact('perusahaans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:255',
        ]);

        Rekening::create($validated);

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function edit(Rekening $rekening)
    {
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();
        return view('master.rekening.edit', compact('rekening', 'perusahaans'));
    }

    public function update(Request $request, Rekening $rekening)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:255',
        ]);

        $rekening->update($validated);

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil diupdate.');
    }

    public function destroy(Rekening $rekening)
    {
        $rekening->delete();

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil dihapus.');
    }

    public function byPerusahaan(Perusahaan $perusahaan)
    {
        return response()->json(
            $perusahaan->rekenings()->get(['id', 'nama_bank', 'no_rekening', 'atas_nama'])
        );
    }
}
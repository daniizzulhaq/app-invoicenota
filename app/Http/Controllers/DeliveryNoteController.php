<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNoteController extends Controller
{
    public function index(Request $request)
    {
        $deliveryNotes = DeliveryNote::with(['perusahaan', 'customer'])
            ->when($request->search, function ($query, $search) {
                $query->where('no_delivery_note', 'like', "%{$search}%")
                    ->orWhere('no_po', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('transaksi.delivery-note.index', compact('deliveryNotes'));
    }

    public function create()
    {
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();
        $customers = Customer::orderBy('nama_customer')->get();
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('transaksi.delivery-note.create', compact('perusahaans', 'customers', 'barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'customer_id' => 'required|exists:customers,id',
            'no_po' => 'nullable|string|max:100',
            'no_delivery_note' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'nullable|exists:barangs,id',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $deliveryNote = DeliveryNote::create([
                'perusahaan_id' => $validated['perusahaan_id'],
                'customer_id' => $validated['customer_id'],
                'user_id' => $request->user()->id,
                'no_po' => $validated['no_po'] ?? null,
                'no_delivery_note' => $validated['no_delivery_note'],
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $deliveryNote->items()->create([
                    'barang_id' => $item['barang_id'] ?? null,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'] ?? null,
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }
        });

        return redirect()->route('delivery-note.index')->with('success', 'Delivery Note berhasil disimpan.');
    }

    public function show(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['perusahaan', 'customer', 'items', 'invoice']);
        return view('transaksi.delivery-note.show', compact('deliveryNote'));
    }

    public function edit(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load('items');
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();
        $customers = Customer::orderBy('nama_customer')->get();
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('transaksi.delivery-note.edit', compact('deliveryNote', 'perusahaans', 'customers', 'barangs'));
    }

    public function update(Request $request, DeliveryNote $deliveryNote)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'customer_id' => 'required|exists:customers,id',
            'no_po' => 'nullable|string|max:100',
            'no_delivery_note' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'nullable|exists:barangs,id',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $deliveryNote) {
            $deliveryNote->update([
                'perusahaan_id' => $validated['perusahaan_id'],
                'customer_id' => $validated['customer_id'],
                'no_po' => $validated['no_po'] ?? null,
                'no_delivery_note' => $validated['no_delivery_note'],
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $deliveryNote->items()->delete();

            foreach ($validated['items'] as $item) {
                $deliveryNote->items()->create([
                    'barang_id' => $item['barang_id'] ?? null,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'] ?? null,
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }
        });

        return redirect()->route('delivery-note.index')->with('success', 'Delivery Note berhasil diupdate.');
    }

    public function destroy(DeliveryNote $deliveryNote)
    {
        $deliveryNote->delete();

        return redirect()->route('delivery-note.index')->with('success', 'Delivery Note berhasil dihapus.');
    }

    public function cetak(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['perusahaan', 'customer', 'items']);
        return view('transaksi.delivery-note.cetak', compact('deliveryNote'));
    }
}
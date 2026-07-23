<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with(['perusahaan', 'customer', 'deliveryNote'])
            ->when($request->search, function ($query, $search) {
                $query->where('no_invoice', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('transaksi.invoice.index', compact('invoices'));
    }

    public function create()
    {
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('transaksi.invoice.create', compact('perusahaans'));
    }

    public function deliveryNotesByPerusahaan(Perusahaan $perusahaan)
    {
        $deliveryNotes = $perusahaan->deliveryNotes()
            ->whereDoesntHave('invoice')
            ->with('customer')
            ->orderByDesc('tanggal')
            ->get(['id', 'no_delivery_note', 'no_po', 'tanggal', 'customer_id']);

        return response()->json($deliveryNotes);
    }

    public function deliveryNoteDetail(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['customer', 'items', 'perusahaan.rekenings']);

        return response()->json($deliveryNote);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'delivery_note_id' => 'required|exists:delivery_notes,id',
            'rekening_id' => 'required|exists:rekenings,id',
            'no_invoice' => 'required|string|max:100',
            'tanggal_invoice' => 'required|date',
            'no_po' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'ppn_persen' => 'nullable|numeric|min:0|max:100',

            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'nullable|exists:barangs,id',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $deliveryNote = DeliveryNote::with('customer')->findOrFail($validated['delivery_note_id']);

        if ($deliveryNote->invoice()->exists()) {
            return back()->withErrors(['delivery_note_id' => 'Delivery Note ini sudah punya invoice.'])->withInput();
        }

        DB::transaction(function () use ($validated, $deliveryNote, $request) {
            $subtotal = collect($validated['items'])->sum(fn ($item) => $item['qty'] * $item['harga']);
            $ppnPersen = $validated['ppn_persen'] ?? 11;
            $ppnNominal = round($subtotal * $ppnPersen / 100, 2);
            $total = $subtotal + $ppnNominal;

            $invoice = Invoice::create([
                'perusahaan_id' => $validated['perusahaan_id'],
                'delivery_note_id' => $deliveryNote->id,
                'customer_id' => $deliveryNote->customer_id,
                'rekening_id' => $validated['rekening_id'],
                'user_id' => $request->user()->id,
                'no_invoice' => $validated['no_invoice'],
                'tanggal_invoice' => $validated['tanggal_invoice'],
                'no_po' => $validated['no_po'] ?? $deliveryNote->no_po,
                'catatan' => $validated['catatan'] ?? null,
                'subtotal' => $subtotal,
                'ppn_persen' => $ppnPersen,
                'ppn_nominal' => $ppnNominal,
                'total' => $total,
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'barang_id' => $item['barang_id'] ?? null,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'] ?? null,
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }
        });

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil disimpan.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['perusahaan', 'customer', 'rekening', 'deliveryNote', 'items']);
        return view('transaksi.invoice.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['items', 'deliveryNote', 'perusahaan.rekenings']);
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('transaksi.invoice.edit', compact('invoice', 'perusahaans'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'rekening_id' => 'required|exists:rekenings,id',
            'no_invoice' => 'required|string|max:100',
            'tanggal_invoice' => 'required|date',
            'no_po' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'ppn_persen' => 'nullable|numeric|min:0|max:100',

            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'nullable|exists:barangs,id',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            $subtotal = collect($validated['items'])->sum(fn ($item) => $item['qty'] * $item['harga']);
            $ppnPersen = $validated['ppn_persen'] ?? 11;
            $ppnNominal = round($subtotal * $ppnPersen / 100, 2);
            $total = $subtotal + $ppnNominal;

            $invoice->update([
                'rekening_id' => $validated['rekening_id'],
                'no_invoice' => $validated['no_invoice'],
                'tanggal_invoice' => $validated['tanggal_invoice'],
                'no_po' => $validated['no_po'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'subtotal' => $subtotal,
                'ppn_persen' => $ppnPersen,
                'ppn_nominal' => $ppnNominal,
                'total' => $total,
            ]);

            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'barang_id' => $item['barang_id'] ?? null,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'] ?? null,
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }
        });

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil diupdate.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function cetak(Invoice $invoice)
    {
        $invoice->load(['perusahaan', 'customer', 'rekening', 'items']);
        return view('transaksi.invoice.cetak', compact('invoice'));
    }
}
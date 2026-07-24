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
        $invoices = Invoice::with(['perusahaan', 'customer', 'deliveryNotes'])
            ->when($request->search, function ($query, $search) {
                $query->where('no_invoice', 'like', "%{$search}%")
                    ->orWhere('no_po', 'like', "%{$search}%");
            })
            ->when($request->perusahaan_id, function ($query, $perusahaanId) {
                $query->where('perusahaan_id', $perusahaanId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('transaksi.invoice.index', compact('invoices', 'perusahaans'));
    }

    public function create()
    {
        $perusahaans = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('transaksi.invoice.create', compact('perusahaans'));
    }

    public function deliveryNotesByPerusahaan(Perusahaan $perusahaan)
    {
        $deliveryNotes = $perusahaan->deliveryNotes()
            ->whereDoesntHave('invoices')
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
            'delivery_note_ids' => 'required|array|min:1',
            'delivery_note_ids.*' => 'exists:delivery_notes,id',
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

        $deliveryNotes = DeliveryNote::with('customer')
            ->whereIn('id', $validated['delivery_note_ids'])
            ->get();

        if ($deliveryNotes->count() !== count($validated['delivery_note_ids'])) {
            return back()->withErrors(['delivery_note_ids' => 'Salah satu Delivery Note tidak ditemukan.'])->withInput();
        }

        $sudahPunyaInvoice = $deliveryNotes->filter(fn ($dn) => $dn->invoices()->exists());
        if ($sudahPunyaInvoice->isNotEmpty()) {
            $noDn = $sudahPunyaInvoice->pluck('no_delivery_note')->implode(', ');
            return back()->withErrors(['delivery_note_ids' => "Delivery Note berikut sudah punya invoice: {$noDn}"])->withInput();
        }

        $customerIds = $deliveryNotes->pluck('customer_id')->unique();
        if ($customerIds->count() > 1) {
            return back()->withErrors(['delivery_note_ids' => 'Semua Delivery Note yang digabung harus punya Customer yang sama.'])->withInput();
        }

        $firstDn = $deliveryNotes->first();

        DB::transaction(function () use ($validated, $deliveryNotes, $firstDn, $request) {
            $subtotal = collect($validated['items'])->sum(fn ($item) => $item['qty'] * $item['harga']);
            $ppnPersen = $validated['ppn_persen'] ?? 11;
            $ppnNominal = round($subtotal * $ppnPersen / 100, 2);
            $total = $subtotal + $ppnNominal;

            $invoice = Invoice::create([
                'perusahaan_id' => $validated['perusahaan_id'],
                'customer_id' => $firstDn->customer_id,
                'rekening_id' => $validated['rekening_id'],
                'user_id' => $request->user()->id,
                'no_invoice' => $validated['no_invoice'],
                'tanggal_invoice' => $validated['tanggal_invoice'],
                'no_po' => $validated['no_po'] ?? $firstDn->no_po,
                'catatan' => $validated['catatan'] ?? null,
                'subtotal' => $subtotal,
                'ppn_persen' => $ppnPersen,
                'ppn_nominal' => $ppnNominal,
                'total' => $total,
            ]);

            $invoice->deliveryNotes()->attach($deliveryNotes->pluck('id'));

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
        $invoice->load(['perusahaan', 'customer', 'rekening', 'deliveryNotes', 'items']);
        return view('transaksi.invoice.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['items', 'deliveryNotes', 'perusahaan.rekenings']);
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
        $invoice->load(['perusahaan', 'customer', 'rekening', 'deliveryNotes', 'items']);
        return view('transaksi.invoice.cetak', compact('invoice'));
    }
}
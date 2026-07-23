<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeliveryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan_id', 'customer_id', 'user_id', 'no_po',
        'no_delivery_note', 'tanggal', 'catatan',
        'pakai_ppn', 'ppn_persen',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pakai_ppn' => 'boolean',
        'ppn_persen' => 'decimal:2',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function getSudahDiinvoiceAttribute(): bool
    {
        return $this->invoice()->exists();
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum('total');
    }

    public function getPpnNominalAttribute()
    {
        if (!$this->pakai_ppn) {
            return 0;
        }

        return $this->subtotal * ($this->ppn_persen / 100);
    }

    public function getGrandTotalAttribute()
    {
        return $this->subtotal + $this->ppn_nominal;
    }
}
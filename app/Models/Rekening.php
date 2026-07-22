<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rekening extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan_id', 'nama_bank', 'no_rekening', 'atas_nama',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->nama_bank} - {$this->no_rekening} a.n {$this->atas_nama}";
    }
}
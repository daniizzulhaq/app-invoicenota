<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->cascadeOnDelete();
            $table->string('nama_bank');
            $table->string('no_rekening');
            $table->string('atas_nama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekenings');
    }
};
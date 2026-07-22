<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('no_po')->nullable();
            $table->string('no_delivery_note');
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['perusahaan_id', 'no_delivery_note']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
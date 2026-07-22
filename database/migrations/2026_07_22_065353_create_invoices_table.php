<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rekening_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('no_invoice');
            $table->date('tanggal_invoice');
            $table->string('no_po')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('ppn_persen', 5, 2)->default(11);
            $table->decimal('ppn_nominal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['perusahaan_id', 'no_invoice']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
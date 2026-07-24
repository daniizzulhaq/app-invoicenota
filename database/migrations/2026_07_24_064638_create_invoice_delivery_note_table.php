<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_delivery_note', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_note_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['invoice_id', 'delivery_note_id']);
        });

        // delivery_note_id lama di invoices jadi nullable dulu (untuk backward-compat data lama),
        // nanti bisa dihapus total setelah data lama dimigrasikan ke pivot.
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('delivery_note_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('delivery_note_id')->nullable(false)->change();
        });

        Schema::dropIfExists('invoice_delivery_note');
    }
};
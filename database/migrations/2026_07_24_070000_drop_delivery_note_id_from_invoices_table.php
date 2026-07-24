<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropForeign(['delivery_note_id']);
        $table->dropColumn('delivery_note_id');
    });
}

public function down(): void
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->foreignId('delivery_note_id')->nullable()->constrained()->nullOnDelete();
    });
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->boolean('pakai_ppn')->default(false)->after('catatan');
            $table->decimal('ppn_persen', 5, 2)->nullable()->default(11)->after('pakai_ppn');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropColumn(['pakai_ppn', 'ppn_persen']);
        });
    }
};
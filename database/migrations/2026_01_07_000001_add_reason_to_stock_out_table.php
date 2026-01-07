<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_out', function (Blueprint $table) {
            $table->string('reason')->nullable()->after('reference_id');
            $table->text('notes')->nullable()->after('reason');
        });
    }

    public function down(): void
    {
        Schema::table('stock_out', function (Blueprint $table) {
            $table->dropColumn(['reason', 'notes']);
        });
    }
};

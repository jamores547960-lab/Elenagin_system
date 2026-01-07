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
        // --- Update sales table structure ---
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('user_id');
            $table->string('payment_method', 50)->default('Cash')->after('total_amount');
            $table->timestamp('sale_date')->default(now())->after('payment_method');
            $table->softDeletes()->after('updated_at'); // Adds the 'deleted_at' column
        });

        // --- Update sale_items table structure ---
        Schema::table('sale_items', function (Blueprint $table) {
            // Remove existing foreign key if any, before adding new columns if necessary.
            // Based on the SQL dump, 'sale_items' was empty, but we'll add all columns here.

            $table->foreignId('sale_id')->after('id')->constrained('sales')->onDelete('cascade');
            $table->string('item_id', 12)->after('sale_id'); // Matches the item_id type from 'items' table
            $table->unsignedInteger('quantity')->after('item_id');
            $table->decimal('unit_price', 10, 2)->after('quantity');
            $table->decimal('line_total', 10, 2)->after('unit_price');
            $table->softDeletes()->after('updated_at'); // Adds the 'deleted_at' column

            // Foreign key for item_id (sale_items_item_id_foreign)
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes made to the sale_items table first
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes 'deleted_at'
            $table->dropForeign(['item_id']);
            $table->dropColumn(['line_total', 'unit_price', 'quantity', 'item_id']);
            $table->dropForeign(['sale_id']);
            $table->dropColumn('sale_id');
        });

        // Reverse the changes made to the sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes 'deleted_at'
            $table->dropColumn(['sale_date', 'payment_method', 'total_amount']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

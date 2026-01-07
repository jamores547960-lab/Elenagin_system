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
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_id')->unique();
            $table->string('item_id');
            $table->bigInteger('user_id')->unsigned();
            $table->enum('adjustment_type', ['spoilage', 'wastage', 'damage', 'expired', 'theft', 'correction', 'return']);
            $table->integer('quantity');
            $table->text('reason')->nullable();
            $table->decimal('cost_impact', 10, 2)->default(0); // Financial impact
            $table->date('adjustment_date');
            $table->string('approved_by')->nullable(); // For approval workflow
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
    }
};

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
        if (!Schema::hasTable('productions')) {
            Schema::create('productions', function (Blueprint $table) {
                $table->id();
                $table->string('production_code'); 
                $table->decimal('total_weight', 8, 2);
                $table->decimal('stock_in', 10, 2);
                $table->decimal('stock_out', 10, 2)->default(0)->nullable();
                $table->timestamps();
            });
        }
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};

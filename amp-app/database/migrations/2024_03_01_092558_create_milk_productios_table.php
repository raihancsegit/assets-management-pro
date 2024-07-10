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
        Schema::create('milk_productions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->useCurrent();
            $table->unsignedBigInteger('category_id');
            $table->decimal('quantity');
            $table->decimal('sell_price')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('milk_production_categories')
                ->onDelete('cascade');

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_productions');
    }
};

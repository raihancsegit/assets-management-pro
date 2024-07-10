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
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->decimal('sell_amount')->nullable()->after('sell_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->dropColumn('sell_amount');
        });
    }
};

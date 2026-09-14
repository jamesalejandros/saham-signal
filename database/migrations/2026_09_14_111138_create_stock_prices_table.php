<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_prices', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code')->index();
            $table->date('date');
            $table->decimal('close_price', 15, 2);
            $table->bigInteger('volume');
            $table->timestamps();

            $table->unique(['stock_code', 'date']); // one row per stock per day
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_prices');
    }
};
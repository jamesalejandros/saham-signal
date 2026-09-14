<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_signals', function (Blueprint $table) {
            $table->id();

            $table->string('stock_code');
            $table->string('stock_name');

            $table->boolean('condition_1')->default(false);
            $table->boolean('condition_2')->default(false);
            $table->boolean('condition_3')->default(false);

            $table->string('signal');
            $table->string('signal_strength');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_signals');
    }
};

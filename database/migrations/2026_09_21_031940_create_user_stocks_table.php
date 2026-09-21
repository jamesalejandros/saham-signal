<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_stocks', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('stock_code');

            $table->foreign('stock_code')
                ->references('stock_code')
                ->on('stocks')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'user_id',
                'stock_code',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_stocks');
    }
};

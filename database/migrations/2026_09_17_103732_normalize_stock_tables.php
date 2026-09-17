<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // stock_prices: add FK to stocks
        Schema::table('stock_prices', function (Blueprint $table) {
            $table->foreign('stock_code')
                ->references('stock_code')
                ->on('stocks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        // stock_signals: add FK to stocks, drop redundant stock_name
        Schema::table('stock_signals', function (Blueprint $table) {
            $table->foreign('stock_code')
                ->references('stock_code')
                ->on('stocks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        Schema::table('stock_signals', function (Blueprint $table) {
            $table->dropColumn('stock_name');
        });
    }

    public function down(): void
    {
        Schema::table('stock_signals', function (Blueprint $table) {
            $table->string('stock_name')->after('stock_code');
        });

        Schema::table('stock_signals', function (Blueprint $table) {
            $table->dropForeign(['stock_code']);
        });

        Schema::table('stock_prices', function (Blueprint $table) {
            $table->dropForeign(['stock_code']);
        });
    }
};
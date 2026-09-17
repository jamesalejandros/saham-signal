<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = [
            'BBCA' => 'Bank Central Asia Tbk',
            'BBRI' => 'Bank Rakyat Indonesia Tbk',
            'TLKM' => 'Telkom Indonesia Tbk',
            'ASII' => 'Astra International Tbk',
            'UNVR' => 'Unilever Indonesia Tbk',
            'GOTO' => 'GoTo Gojek Tokopedia Tbk',
            'BMRI' => 'Bank Mandiri Tbk',
            'ANTM' => 'Aneka Tambang Tbk',
        ];

        foreach ($stocks as $code => $name) {
            $this->seedStock($code, $name);
        }
    }

    private function seedStock(string $stockCode, string $stockName): void
    {
        Stock::updateOrCreate(
            ['stock_code' => $stockCode],
            [
                'stock_name' => $stockName,
                'summary' => null,
                'summary_updated' => null,
            ]
        );
    }
}
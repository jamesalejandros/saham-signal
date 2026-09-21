<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class NewStockSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = [
            'BBNI' => 'Bank Negara Indonesia Tbk',
            'BBTN' => 'Bank Tabungan Negara Tbk',
            'BRIS' => 'Bank Syariah Indonesia Tbk',
            'BDMN' => 'Bank Danamon Indonesia Tbk',
            'BNGA' => 'Bank CIMB Niaga Tbk',
            'BNLI' => 'Bank Permata Tbk',
            'ARTO' => 'Bank Jago Tbk',
            'BBKP' => 'Bank KB Bukopin Tbk',

            'ADRO' => 'Adaro Energy Indonesia Tbk',
            'PTBA' => 'Bukit Asam Tbk',
            'ITMG' => 'Indo Tambangraya Megah Tbk',
            'PGAS' => 'Perusahaan Gas Negara Tbk',
            'MEDC' => 'Medco Energi Internasional Tbk',
            'HRUM' => 'Harum Energy Tbk',
            'INDY' => 'Indika Energy Tbk',

            'INDF' => 'Indofood Sukses Makmur Tbk',
            'ICBP' => 'Indofood CBP Sukses Makmur Tbk',
            'MYOR' => 'Mayora Indah Tbk',
            'KLBF' => 'Kalbe Farma Tbk',
            'SIDO' => 'Industri Jamu dan Farmasi Sido Muncul Tbk',
            'HMSP' => 'H.M. Sampoerna Tbk',
            'GGRM' => 'Gudang Garam Tbk',
            'JPFA' => 'JAPFA Comfeed Indonesia Tbk',

            'SMGR' => 'Semen Indonesia Tbk',
            'INTP' => 'Indocement Tunggal Prakarsa Tbk',
            'WIKA' => 'Wijaya Karya Tbk',
            'WSKT' => 'Waskita Karya Tbk',
            'PTPP' => 'PP Properti Tbk',
            'ADHI' => 'Adhi Karya Tbk',

            'MDKA' => 'Merdeka Copper Gold Tbk',
            'TINS' => 'Timah Tbk',
            'BRPT' => 'Barito Pacific Tbk',
            'AMRT' => 'Sumber Alfaria Trijaya Tbk',
            'ACES' => 'Aspirasi Hidup Indonesia Tbk',
            'MAPI' => 'Mitra Adiperkasa Tbk',
            'ERAA' => 'Erajaya Swasembada Tbk',

            'EXCL' => 'XL Axiata Tbk',
            'ISAT' => 'Indosat Tbk',
            'MTEL' => 'Dayamitra Telekomunikasi Tbk',

            'CTRA' => 'Ciputra Development Tbk',
            'BSDE' => 'Bumi Serpong Damai Tbk',
            'PWON' => 'Pakuwon Jati Tbk',
            'SMRA' => 'Summarecon Agung Tbk',

            'CPIN' => 'Charoen Pokphand Indonesia Tbk',
            'TPIA' => 'Chandra Asri Pacific Tbk',
            'AKRA' => 'AKR Corporindo Tbk',
            'SRTG' => 'Saratoga Investama Sedaya Tbk',
            'EMTK' => 'Elang Mahkota Teknologi Tbk',
            'BUKA' => 'Bukalapak.com Tbk',
            'BELI' => 'Global Digital Niaga Tbk',
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            StockSeeder::class,
            StockPriceSeeder::class, 
            NewStockSeeder::class,
            NewStockPriceSeeder::class,
        ]);
    }
}

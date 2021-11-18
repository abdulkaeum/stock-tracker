<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class RetailerWithProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a product and retailer
        $switch = Product::create(['name' => 'Nintendo Switch']);

        $bestBuy = Retailer::create(['name' => 'Best Buy']);

        // create the supply for the product at the retailer
        $stock = new Stock([
            'price' => 1000,
            'url' => 'http://foo.com',
            'sku' => 12345,
            'in_stock' => false
        ]);

        $bestBuy->addStock($switch, $stock);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use App\Models\User;
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
            'price' => 339900,
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253.p?skuId=6364253&intl=nosplash',
            'sku' => 6364253,
            'in_stock' => true
        ]);

        $bestBuy->addStock($switch, $stock);

        User::factory()->create(['email' => 'ak@example.com']);
    }
}

<?php

namespace Clients;

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestBuyTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    function it_tracks_a_product()
    {
        // given we have a product
        $this->seed(RetailerWithProductSeeder::class);

        // with stock at BestBuy
        $stock = tap(Stock::first())->update([
            'price' => 339900,
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253.p?skuId=6364253&intl=nosplash',
            'sku' => 6364253,
            'in_stock' => true
        ]);


        // if we use the BestBuy client class to track the stock
        // it should return the appropriate StockStatus
        try {
            $StockStatus = (new BestBuy())->checkAvailability($stock);
        } catch (\Exception $e) {
            $this->fail('failed bestBuy API' . $e->getMessage());
        }

        $this->assertTrue(true);
    }
}

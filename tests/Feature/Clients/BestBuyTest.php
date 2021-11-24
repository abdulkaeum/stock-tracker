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
    function it_tracks_a¬_product()
    {
        // given we have a product
        $this->seed(RetailerWithProductSeeder::class);

        // with stock at BestBuy
        $stock = tap(Stock::first())->update([
            'sku' => 6364253
        ]);


        // if we use the BestBuy client class to track the stock
        // it should return the appropriate StockStatus
        try {
            $StockStatus = (new BestBuy())->checkAvailability($stock);
        } catch (\Exception $exception) {
            $this->fail('failed bestBuy API');
        }

        $this->assertTrue(true);
    }
}

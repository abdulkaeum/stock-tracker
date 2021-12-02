<?php

namespace Tests\Unit;

use App\Models\History;
use App\Models\Product;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_records_history_each_time_stock_is_tracked()
    {
        // given we have a stock at a retailer
        $this->seed(RetailerWithProductSeeder::class);

        Http::fake(fn() => ['salePrice' => 99, 'onlineAvailability' => true]);

        $product = Product::first();

        $this->assertCount(0, $product->history);

        $product->track();

        $this->assertCount(1, $product->refresh()->history);

        $history = History::first();
        $stock = $product->stock[0];

        $this->assertEquals($stock->price, $history->price);
        $this->assertEquals($stock->in_stock, $history->in_stock);
        $this->assertEquals($stock->product_id, $history->product_id);
        $this->assertEquals($stock->id, $history->stock_id);
    }
}

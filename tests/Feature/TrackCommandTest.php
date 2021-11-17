<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {
        // Given
        // I have a product with stock
        $switch = Product::create(['name' => 'Nintendo Switch']);
        $bestBuy = Retailer::create(['name' => 'Best Buy']);

        $this->assertFalse($switch->inStock());

        $stock = new Stock([
            'price' => 1000,
            'url' => 'http://foo.com',
            'sku' => 12345,
            'in_stock' => false
        ]);

        $bestBuy->addStock($switch, $stock);

        $this->assertFalse($stock->refresh()->in_stock);

        // fake any Http end points
        Http::fake(function () {
            return [
                'available' => true,
                'price' => 29900
            ];
        });

        // When
        // I trigger the php artisan command
        // AND assuming the stock is available now
        $this->artisan('track');


        // Then
        // The stock details should be refreshed
        $this->assertTrue($stock->refresh()->in_stock);
    }
}

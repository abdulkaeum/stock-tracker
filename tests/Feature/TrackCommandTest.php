<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // test if seeder worked
        //dd(Product::all());

        $this->assertFalse(Product::first()->inStock());

        // fake the/any Http end points for now
        Http::fake(function () {
            return [
                'available' => true,
                'price' => 29900
            ];
        });

        // When we trigger the php artisan command
        // AND assuming the stock is available now
        $this->artisan('track');


        // Then the stock details should be refreshed
        $this->assertTrue(Product::first()->inStock());
    }
}

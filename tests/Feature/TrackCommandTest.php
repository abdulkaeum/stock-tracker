<?php

namespace Tests\Feature;

use App\Clients\StockStatus;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;
use Facades\App\Clients\ClientFactory;
use App\Models\Product;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // fake the StockStatus for now without hitting any of the Client API's
        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockStatus($available = true, $price = 29900));

        // When we trigger the php artisan command
        // AND assuming the stock is available now
        $this->artisan('track')
            ->expectsOutput('Tracking complete');

        // Then the stock details should be refreshed
        $this->assertTrue(Product::first()->inStock());
    }

    /** @test */
    function it_does_not_notify_the_user_when_the_stock_remains_unavailable()
    {
        Notification::fake();

        // given we have a user, product
        $this->seed(RetailerWithProductSeeder::class);

        // fake the StockStatus for now without hitting any of the Client API's
        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockStatus($available = false, $price = 29900));

        $this->artisan('track');

        // if the stock has changes in a notable way i.e price or in_stock
        // then the user should be notified
        Notification::assertNothingSent();
    }

    /** @test */
    function it_notifies_the_user_when_the_stock_is_now_available()
    {
        Notification::fake();

        // given we have a user, product
        $this->seed(RetailerWithProductSeeder::class);

        // fake the StockStatus for now without hitting any of the Client API's
        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockStatus($available = true, $price = 29900));

        $this->artisan('track');

        // if the stock has changes in a notable way i.e price or in_stock
        // then the user should be notified
        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
    }
}

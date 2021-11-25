<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\ClientException;
use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        // given we have a retailer with stock
        $this->seed(RetailerWithProductSeeder::class);

        // if the retailer doesn't have a client class as per the name Foo Retailer
        Retailer::first()->update(['name' => 'Foo Retailer']);

        // then an exception should be thrown
        $this->expectException(ClientException::class);

        // track the stock
        Stock::first()->track();
    }

    /** @test */
    public function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // ClientFactory to determine the appropriate Client
        // CheckAvailability()
        // return ['available' => true, 'price' => 9900]
        ClientFactory::shouldReceive('make')->andReturn(new FakeClient);

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(339900, $stock->price);

    }
}

class FakeClient implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        return new StockStatus($available = true, $price = 9900);
    }
}

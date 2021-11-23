<?php

namespace Tests\Unit;

use App\Clients\ClientException;
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
}

<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results = Http::get($this->endPoint($stock->sku))->json();

        return new StockStatus(
            $results['onlineAvailability'],
            (int) $results['salePrice'] * 100
        );
    }

    public function endPoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');

        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }
}

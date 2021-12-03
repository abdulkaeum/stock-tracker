<?php

namespace App\UseCases;

use App\Clients\StockStatus;
use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;

class TrackStock
{
    protected Stock $stock;
    protected StockStatus $status;

    /**
     * @param Stock $stock
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }


    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordToHistory();
    }

    public function checkAvailability()
    {
        $this->status = $this->stock
            ->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    public function notifyUser()
    {
        if(! $this->stock->in_stock && $this->status->available){
            User::first()->notify(
                new ImportantStockUpdate($this->stock)
            );
        }
    }

    public function refreshStock()
    {
        $this->stock->update([
            'in_stock'  => $this->status->available,
            'price'     => $this->status->price
        ]);
    }

    public function recordToHistory()
    {
        History::create([
            'in_stock' => $this->stock->in_stock,
            'price' => $this->stock->price,
            'stock_id' => $this->stock->id,
            'product_id' => $this->stock->product_id
        ]);
    }
}

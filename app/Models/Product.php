<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function inStock()
    {
        return $this->stock()->where('in_stock', true)->exists();
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function track()
    {
        // for each product call track on stock
        $this->stock->each->track(
            fn($stock) => $this->recordHistory($stock)
        );
    }

    public function recordHistory(Stock $stock)
    {
        $this->history()->create([
            'in_stock' => $stock->in_stock,
            'price' => $stock->price,
            'stock_id' => $stock->id
        ]);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}

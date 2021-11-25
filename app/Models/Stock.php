<?php

namespace App\Models;

use App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'url', 'sku', 'in_stock'];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        $status = $this->retailer
            ->client()
            ->checkAvailability($this);

        $this->update([
            'in_stock'  => $status->available,
            'price'     => $status->price
        ]);

        $this->recordHistory();
    }

    public function recordHistory(): void
    {
        $this->history()->create([
            'product_id' => $this->product_id,
            'price' => $this->price,
            'in_stock' => $this->in_stock
        ]);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}

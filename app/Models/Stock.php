<?php

namespace App\Models;

use App\Clients\ClientFactory;
use App\Events\NowInStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'url', 'sku', 'in_stock'];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track($callable = null)
    {
        $status = $this->retailer
            ->client()
            ->checkAvailability($this);

        if(! $this->in_stock && $status->available){
            event(new NowInStock($this));
        }

        $this->update([
            'in_stock'  => $status->available,
            'price'     => $status->price
        ]);

        $callable && $callable($this);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

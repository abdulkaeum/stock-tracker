<?php

namespace App\Models;

use App\Clients\ClientFactory;
use App\Events\NowInStock;
use App\UseCases\TrackStock;
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
        (new TrackStock($this))->handle();
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

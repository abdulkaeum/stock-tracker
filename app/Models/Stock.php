<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'url', 'sku', 'in_stock'];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        if($this->retailer->name == 'Best Buy'){
            // hit API endpoint for associated retailer
            // fetch the up-to-date details for item
            $results = Http::get('http://foo.test')->json();

            // refresh the current stock record
            $this->update([
                'in_stock' => $results['available'],
                'price' => $results['price'],
            ]);
        }
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutOfStockRequest extends Model
{
    use HasFactory;

    protected $table = 'out_of_stock_requests';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'lat',
        'lng',
        'seller_ids',
    ];

    protected $casts = [
        'seller_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

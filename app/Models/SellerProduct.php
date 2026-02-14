<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;


class SellerProduct extends Model
{

    protected $fillable = ['seller_id', 'product_id', 'stock'];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
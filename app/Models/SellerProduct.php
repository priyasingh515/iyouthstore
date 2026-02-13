<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;


class SellerProduct extends Model
{

    protected $fillable = ['seller_id', 'product_id', 'stock'];

}
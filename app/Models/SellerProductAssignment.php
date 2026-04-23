<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProductAssignment extends Model
{
    use HasFactory;

    protected $table = 'seller_product_assignments';

    protected $fillable = [
        'seller_id',
        'product_id',
        'quantity'
    ];
}

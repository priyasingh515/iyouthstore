<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSellerQueue extends Model
{
    use HasFactory;

    protected $table = 'order_seller_queue';

    protected $fillable = [
        'order_id',
        'seller_id',
        'priority',
        'status'
    ];
}

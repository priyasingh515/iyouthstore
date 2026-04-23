<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPayments extends Model
{
    use HasFactory;

    protected $table = 'seller_payments';

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_method',
        'utr',
        'payment_date',
        'screenshot',
        'note',
        'status'
    ];
}

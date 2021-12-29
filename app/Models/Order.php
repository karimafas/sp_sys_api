<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'delivery_time',
        'order_json',
        'total',
        'items_number',
        'order_number',
        'mobile_order',
        'rider_initials'
    ];
}

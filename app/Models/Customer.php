<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'address', 'address2', 'area', 'credit', 'discount', 'last_name', 'level', 'notes', 'phone'
    ];
}

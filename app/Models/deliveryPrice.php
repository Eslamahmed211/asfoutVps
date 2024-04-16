<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deliveryPrice extends Model
{
    use HasFactory;

    protected $fillable = ["name" , 'delivery_price' , 'return_price' , 'code' , 'order'];
}


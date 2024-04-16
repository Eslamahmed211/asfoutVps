<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commission_system_history extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'commission'
    ];
}

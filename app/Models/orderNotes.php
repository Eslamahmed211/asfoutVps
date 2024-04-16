<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderNotes extends Model
{
    use HasFactory;

    protected $fillable = ["user_id" , "order_id" ,"message"];


    public function user()
    {
       return $this->belongsTo(user::class , 'user_id');
    }

    public function order()
    {
       return $this->belongsTo(order::class , 'order_id');
    }

}

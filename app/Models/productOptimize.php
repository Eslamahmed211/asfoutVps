<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productOptimize extends Model
{
    use HasFactory;

    protected $fillable = ["action" , 'product_id' , 'user_id'];

    function user()  {
       return $this->belongsTo(User::class , "user_id");
    }


}

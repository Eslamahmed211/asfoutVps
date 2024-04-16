<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
      "user_id" ,
      "type",
      "amount",
      "options" ,
      "status" ,
      "paid_at",
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}

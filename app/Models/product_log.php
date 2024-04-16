<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_log extends Model
{
    use HasFactory;


    protected $fillable = [ 'product_id' ,'updated_columns' , 'changer' ,'title' ];


    function editer()  {
      return  $this->belongsTo(User::class , 'changer');
    }

    protected $casts = [
      'updated_columns' => 'json',
  ];


}

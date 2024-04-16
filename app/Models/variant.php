<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class variant extends Model
{
    use HasFactory;

    use SoftDeletes;



    protected $fillable = ['stock' ,'product_id' , "group_id" , 'sku'];


    public function product()
    {
      return $this->belongsTo(product::class , "product_id");
    }


    public function values()
    {
       return $this->belongsToMany(value::class , 'variant_values' , 'variant_id' ,'value_id' );
    }

    public function details()
    {
      return $this->hasMany(order_detail::class, 'variant_id');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_detail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'variant_id', 'discription', 'price', 'qnt', 'ponus', 'comissation', 'TotalComissation', 'traderPrice', 'systemComissation'];

    public function order()
    {
      return $this->belongsTo(order::class,"order_id");
    }



    public function product()
    {
       return $this->belongsTo(product::class , "product_id")->withTrashed();
    }


    public function variant()
    {
       return $this->belongsTo(variant::class , "variant_id")->withTrashed();
    }

}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    use SoftDeletes;


    protected $fillable = ['name', 'systemComissation' ,'slug', 'dis', 'price', 'sku', 'stock', 'show',  'nickName', 'trader_id', 'comissation', 'min_comissation', 'max_comissation', 'ponus', 'drive', 'unavailable'];

    public function categories()
    {
      return $this->belongsToMany(category::class, 'product_categories', 'product_id', 'category_id');
    }



    public function imgs()
    {
      return $this->hasMany(productImage::class, "product_id")->orderBy("order" , "Asc");
    }

    public function firstImg()
    {
      return $this->hasOne(productImage::class, "product_id")->orderBy("order" , "Asc");
    }


    public function attributes_all()
    {
      return $this->hasMany(attribute::class, 'product_id');
    }



    public function attributes()
    {
      return $this->belongsToMany(attribute::class, 'product_attributes', 'product_id', 'attribute_id');
    }

    public function variants()
    {
      return $this->hasMany(variant::class, "product_id");
    }



    public static function category_check($category, $product)
    {
      foreach ($product->categories as  $product_catregory) {
        if ($product_catregory->id ==  $category->id) {
          return true;
        }
      }
    }

    public static function attributes_check($attribute, $productAttributes)
    {





      foreach ($productAttributes as  $attr) {

        if ($attr->id ==  $attribute->id) {
          return true;
        }
      }
    }

    public function trader()
    {
      return $this->belongsTo(User::class, "trader_id");
    }

    public function optimizes()
    {
      return $this->hasMany(productOptimize::class, "product_id")->with('user');
    }

    function logs()  {
      return  $this->hasMany(product_log::class , 'product_id')->with('editer')->orderBy('id' , 'desc');
    }



    public function favourites()
    {
      return $this->belongsToMany(user::class, 'products_favourites', 'product_id', 'user_id');
    }



    public function details()
    {
      return $this->hasMany(order_detail::class, 'product_id');
    }

}

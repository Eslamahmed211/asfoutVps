<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name' , 'key'  , "product_id"];

    public function values()
    {
       return $this->hasMany(value::class , "attribute_id");
    }

    public function product()
    {
       return $this->belongsTo(product::class , 'product_id');
    }

    public function products()
    {
       return $this->belongsToMany(product::class , 'product_attributes' , 'attribute_id' , 'product_id');
    }

    //  protected static function boot() {
    //     self::addGlobalScope(function(Builder $builder){
    //       $builder->where("product_id" , 9);
    //     });
    //  }
}

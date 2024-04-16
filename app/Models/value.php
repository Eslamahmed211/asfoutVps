<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class value extends Model
{
    use HasFactory;
    protected $fillable = ['attribute_id' ,'value'];
    protected $hidden = ['pivot'];
    public function attribute()
    {
       return $this->belongsTo(attribute::class , "attribute_id");
    }
}

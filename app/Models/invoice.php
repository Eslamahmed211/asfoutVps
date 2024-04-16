<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;

    protected $fillable = [
      'traderId',
      'InvoiceName',
      'type' ,

  ];

  function items()  {
    return $this->hasMany(invoiceItem::class , "invoice_id");
  }

  function trader()  {
    return $this->belongsTo(User::class , "traderId");
  }






}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'date',  'product_name', 'variants', 'price', 'qnt', 'total',   "product_id",
        "variant_id"
    ];

    function invoice()
    {
        return $this->belongsTo(invoice::class, "invoice_id");
    }


    function product()
    {
        return $this->belongsTo(product::class, "product_id");
    }


    function variant()
    {
        return $this->belongsTo(variant::class, "variant_id");
    }
}

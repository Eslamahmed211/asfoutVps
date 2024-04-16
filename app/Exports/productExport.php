<?php

namespace App\Exports;

use App\Models\product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class productExport implements FromCollection , WithHeadings , WithMapping
{

  protected $product;

  public function __construct($data)
  {
      $this->product = $data;
  }

  public function collection()
  {
    return collect($this->product);
  }

  public function headings(): array
  {
    return [
      'كود المنتج',
      'اسم المنتج	',
      'اسم التاجر',
      'رقم التاجر',
      "سعر المنتج" ,
      "العمولة" ,
      "البونص" ,
      "الكمية" ,
      "الحد الادني للعمولة" ,
      "الحد الاقصي للعمولة",
      "SKU",
      "كمبة المنتجات الفرعية" ,
      "حالة المنتج" ,
      "محذوف" ,
      "تاريخ الاضافة"
    ];
  }

  public function map($product): array
  {


    $show = match ($product->show) {
      "0"=> "غير نشط",
      "1"=> "نشط",
   };

    $deleted =  !$product->deleted_at ? "" : "محذوف" ;

    $countVariant =   variantStock($product->id) == 0 ? "لا يوجد " :  variantStock($product->id);


    return [
      $product->id,
      $product->name,
      $product->trader->name ?? '',
      $product->trader->mobile ?? '',
      $product->price ,
      $product->comissation ,
      $product->ponus ,
      $product->stock ?? "غير محدود",
      $product->min_comissation,
      $product->max_comissation	,
      $product->sku,
      $countVariant ?? "لا يوجد" ,
      $show,
      $deleted ,
      $product->created_at

    ];






  }
}

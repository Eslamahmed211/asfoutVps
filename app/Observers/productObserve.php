<?php

namespace App\Observers;

use App\Models\product;
use App\Models\product_log;
use App\Models\User;
use App\Notifications\adminProductsNotification;

class productObserve
{

  public $product ;
  public $updatedColumnsNotImportant = [];





  public function updating(product $product): void
  {

    $this->product =  $product ;


    $product->isDirty('name') ? $this->build("name" , 'اسم المنتج') : ""  ;
    $product->isDirty('dis') ? $this->build("name" , 'وصف المنتج') : ""  ;
    $product->isDirty('sku') ? $this->build("name" , 'Sku المنتج') : ""  ;
    $product->isDirty('nickName') ? $this->build("name" , 'اسم  شهرةالمنتج') : ""  ;
    $product->isDirty('drive') ? $this->build("drive" , "درايف المنتج") : ""  ;
    $product->isDirty('show') ? $this->build("show" , "عرض المنتج") : ""  ;
    $product->isDirty('unavailable') ? $this->build("unavailable" , "في المنتجات الغير متوفرة") : ""  ;


    if (!empty($this->updatedColumnsNotImportant)) {
      $updateLog = new product_log([
        "title" => "المعلومات الاساسية" ,
        'product_id' => $product->id,
        'changer' => auth()->user()->id ?? null,
        'updated_columns' => json_encode($this->updatedColumnsNotImportant),
      ]);
      $updateLog->save();
    }


    $product->isDirty('trader_id') ?  $this->buildImportant("trader_id" , "التاجر" ) : ""  ;
    $product->isDirty('price') ?  $this->buildImportant("price" , 'سعر المنتج' ) : ""  ;
    $product->isDirty('stock') ?  $this->buildImportant("stock" , 'كمية المنتج' ) : ""  ;
    $product->isDirty('comissation') ?  $this->buildImportant("comissation" , 'عمولة المسوق' ) : ""  ;
    $product->isDirty('min_comissation') ?  $this->buildImportant("min_comissation" , 'الحد الادني للعمولة') : ""  ;
    $product->isDirty('max_comissation') ?  $this->buildImportant("max_comissation" , 'الحد الاقصي للعمولة') : ""  ;
    $product->isDirty('ponus') ?  $this->buildImportant("ponus" , 'البونص') : ""  ;




  }



  public function build($name , $message)  {
    $oldValue = $this->product->getOriginal($name);
    $newValue = $this->product->getAttribute($name);
    $message = "تم التغير $message  من " . $oldValue . "  الي " . $newValue;
    array_push($this->updatedColumnsNotImportant,   $message);
  }


  public function buildImportant($name , $message )  {

    $important = [] ;
    $title = $message ;
    $oldValue = $this->product->getOriginal($name);
    $newValue = $this->product->getAttribute($name);
    $message = "تم التغير $message  من " . $oldValue . "  الي " . $newValue;

    array_push($important,   $message);
    $updateLog = new product_log([
      "title" =>    $title,
      'product_id' => $this->product->id,
      'changer' => auth()->user()->id ?? null,
      'updated_columns' => json_encode($important),
    ]);
    $updateLog->save();



    // send adminProductsNotification
    // $admins = User::where("role", "admin")->get();
    // $message = $this->product->name  ." \n$message ";
    // foreach ($admins as $admin) {
    //   $content = ['message' =>  $message , "product_id" => $this->product->id, "type" => "productChange"];
    //   $admin->notify(new adminProductsNotification($content));
    // }







  }


}

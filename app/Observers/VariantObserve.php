<?php

namespace App\Observers;

use App\Models\product;
use App\Models\product_log;
use App\Models\User;
use App\Models\variant;
use App\Notifications\adminProductsNotification;

class VariantObserve
{

  public $product;
  public $variant;



  /**
   * Handle the variant "updated" event.
   */
  public function updating(variant $variant): void
  {

    $this->product = $variant->product_id;
    $this->variant = $variant;

    $name = '';

    foreach ($variant->values as $value) {
      $name = $name . ' ' . $value->value;
    }

    $variant->isDirty('stock') ?  $this->buildImportant("stock", 'استوك' . $name) : "";
  }

  /**
   * Handle the variant "deleted" event.
   */
  public function deleted(variant $variant): void
  {
    $this->product = $variant->product_id;
    $this->variant = $variant;

    $name = '';

    foreach ($variant->values as $value) {
      $name = $name . ' ' . $value->value;
    }

    $this->buildImportant("stock",   $name , 'delete');
  }
  /**
   * Handle the variant "restored" event.
   */
  public function restored(variant $variant): void
  {
    //
  }




  public function buildImportant($name, $message, $type = "edit")
  {

    $important = [];
    $title = $message;

    $oldValue = $this->variant->getOriginal($name);
    $newValue = $this->variant->getAttribute($name);

    if ($type == "edit") {
      $message = "تم التغير $message  من " . $oldValue . "  الي " . $newValue;
    } else {
      $message = "تم ازالة $message";
    }

    array_push($important,   $message);



    $updateLog = new product_log([
      "title" =>    $title,
      'product_id' => $this->product,
      'changer' => auth()->user()->id ?? null,
      'updated_columns' => json_encode($important),
    ]);
    $updateLog->save();


    
    // send adminProductsNotification
    $product = product::find($this->product);
    $admins = User::where("role", "admin")->get();
    $message = $product->name  ." \n$message ";
    foreach ($admins as $admin) {
      $content = ['message' =>  $message , "product_id" => $product->id, "type" => "productChange"];
      $admin->notify(new adminProductsNotification($content));
    }





  }
}

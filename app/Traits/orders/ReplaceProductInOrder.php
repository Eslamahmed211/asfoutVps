<?php

namespace App\Traits\orders;

use Illuminate\Support\Facades\DB;

trait ReplaceProductInOrder
{
  function ReplaceNoVariant($product, $data)
  {

    DB::beginTransaction();

    // لو المنتج لا يضاف في الانتظار اتاكد من الاستوك
    // لو يضاف والاستوك اقل من الكمية المطلوبة والاوردر مش انتظار رجعه انتظار

    if ($product->unavailable == "no") {
      if ($product->stock === 0  || $product->stock < $data["stock"] &&  $product->stock != null) {
        return response()->json(["status" => "CoustomErrors", "message" => "عدد القطع المطلوبة اكبر من الكمية المتوفرة"]);
      }
    } else {

        if ($product->stock === 0  || ( $product->stock + $this->details->qnt )   <  $data["stock"]   && $this->details->order->status != "قيد الانتظار") {

            retuenHold($this->details->order);
          }
    }



    try {


      if ($this->details->order->status != "قيد الانتظار") {
        Return_Stock_Using_One_details($this->details);
      }


      $this->details->update(
        [
          "qnt" => $data["stock"],
          "comissation" => $data["comissation"],
          "TotalComissation" =>   $this->details->ponus + $data["comissation"],
        ]
      );


      if ($this->details->order->status != "قيد الانتظار") {

        Take_From_Stock_Using_One_details($this->details);
      }


      DB::commit();

      return response()->json(["status" => "success", "message" => "تم اضافة المنتج بنجاح"]);
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج "]);
    }
  }

  function ReplaceVariant($product, $data)
  {


    DB::beginTransaction();

    $variant =  variantExists($product->id, $data["values"]);

    if (!isset($variant->id)) {
      return response()->json(["status" => "CoustomErrors", "message" => "لا يوجد منتج بتلك الخصائص"]);
    }

    if ($product->unavailable == "no") {


      if ($variant->stock === 0  ||   $variant->stock < $data["stock"] &&  $variant->stock != null) {
        return response()->json(["status" => "CoustomErrors", "message" => "عدد القطع المطلوبة اكبر من الكمية المتوفرة"]);
      }
    } else {

        if ($variant->stock === 0  || ( $variant->stock + $this->details->qnt )   <  $data["stock"]   && $this->details->order->status != "قيد الانتظار") {

            retuenHold($this->details->order);
          }
    }


    try {

      $product = $this->details->product;

      if ($product->stock !== null && $this->details->order->status != "قيد الانتظار") {

        $product->update([
          "stock" => $product->stock + $this->details->qnt,
        ]);
      }

      $variant = $this->details->variant;


      if ($variant->stock !== null && $this->details->order->status != "قيد الانتظار") {

        $variant->update([
          "stock" => $variant->stock + $this->details->qnt,
        ]);
      }


      // الاستوك رجع كده


      $variant2 = variantExists($product->id, $data["values"]);

      $discription =  $this->details->product->nickName . " " . variantName($variant2->id);



      $this->details->update(
        [
          "qnt" => $data["stock"],
          "comissation" => $data["comissation"],
          "TotalComissation" =>   $this->details->ponus + $data["comissation"],
          "variant_id" => $variant2->id,
          'discription' => $discription
        ]
      );

      // التعدلات تمت

      if ($this->details->order->status != "قيد الانتظار") {


        $product->update([
          "stock" => $product->stock - $this->details->qnt,
        ]);

        if ($variant2->stock !== null) {


          $variant2->update([
            "stock" => $variant2->stock - $data["stock"]
          ]);
        }
      }



      //  الاتسوك الجديد اتخصم


      DB::commit();

      return response()->json(["status" => "success", "message" => "تم اضافة المنتج بنجاح"]);
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج "]);
    }
  }
}

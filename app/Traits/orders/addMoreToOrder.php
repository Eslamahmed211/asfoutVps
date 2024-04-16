<?php

namespace App\Traits\orders;
use App\Models\cart;
use App\Models\order_detail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


trait addMoreToOrder
{

  function validation($request)
  {
    $validator = Validator::make($request->data, [
      "product_id" => "required|string",
      "productType" => "required|in:product,variant",
      "stock" => "required|numeric|min:1",
      "comissation" => "required|numeric|min:10",
      "add_new_product" => "nullable|numeric",
      "values" => "nullable|array",
      'values.*' => 'numeric',

    ], [
      "stock.min" => "لا يجب ان يقل الكمية عن قطعة",
      "comissation.min" => "لا تجعل عمولة المسوق اقل من 10 جنية",
      "add_new_product" => "يرجي اختيار اوردر لي اضافة المنتج له"
    ]);

    if ($validator->fails()) {
      return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
    }

    $data = $validator->validate();

    return $data;
  }


  function security($product, $data)
  {

    try {

      if (!$product) {
        return response()->json(["status" => "CoustomErrors", "message" => "المنتج ده غير متوفر"]);
      }

      if (!$product->show) {
        return response()->json(["status" => "CoustomErrors", "message" => "المنتج ده غير متوفر"]);
      }

      if (auth()->user()->role != "admin" && auth()->user()->role != "super") {
        return response()->json(["status" => "CoustomErrors", "message" => "لا يوجد لديك صلاحية لعمل اوردرات علي هذا المنتج"]);
      }

      if ($product->comissation) {

        if ($product->comissation != $data["comissation"]) {
          return response()->json(["status" => "CoustomErrors", "message" => "يرجي الالتزام بالعمولة المحددة"]);
        }
      } else {
        if ($product->max_comissation) {

          if ($product->max_comissation < $data["comissation"]) {
            return response()->json(["status" => "CoustomErrors", "message" => "يرجي الالتزام  بالحد الاقصي للعمولة"]);
          }
        }
        if ($product->min_comissation) {
          if ($product->min_comissation > $data["comissation"]) {
            return response()->json(["status" => "CoustomErrors", "message" => "يرجي الالتزام  بالحد الادني للعمولة"]);
          }
        }
      }

      if ($product->variants_count != 0 &&  $data["productType"] == "product") {
        return response()->json(["status" => "CoustomErrors", "message" => "المنتج يحتوي علي خصائص من فضلك اختار المنتج بطريقة صحيحة"]);
      }

      if ($product->variants_count != 0 &&  !isset($data["values"]) &&  empty($data["values"])) {
        return response()->json(["status" => "CoustomErrors", "message" => "يرجي اختيار خصائص المنتج"]);
      }
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطا ما !"]);
    }
  }


  function noVariant($product, $data)
  {

    if ($product->unavailable == "no") {
      if ($product->stock === 0 || $product->stock < $data["stock"] &&  $product->stock != null) {
        return response()->json(["status" => "CoustomErrors", "message" => "عدد القطع المطلوبة اكبر من الكمية المتوفرة"]);
      }
    }

    try {


      if ($this->order != null && order_detail::where("product_id", $product->id)->where("order_id", $this->order->id)->exists()) {
        return response()->json(["status" => "CoustomErrors", "message" => "هذا المنتج مضاف مسبقا الي الاوردر"]);
      }



      cart::create([
        "user_id" => auth()->user()->id,
        "product_id" => $product->id,
        "qnt" => $data["stock"],
        "comissation" => $data["comissation"]
      ]);

      return   $this->storeOrderDetails($this->order, cart::where("user_id", auth()->user()->id)->get());
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج للاوردر"]);
    }
  }


  function Variant($product, $data)
  {

    $variant =  variantExists($product->id, $data["values"]);

    if (!isset($variant->id)) {
      return response()->json(["status" => "CoustomErrors", "message" => "لا يوجد منتج بتلك الخصائص"]);
    }


    if ($product->unavailable == "no") {

      if ($variant->stock === 0  || $variant->stock < $data["stock"] &&  $variant->stock != null) {
        return response()->json(["status" => "CoustomErrors", "message" => "عدد القطع المطلوبة اكبر من الكمية المتوفرة"]);
      }
    }


    if ($this->order != null && order_detail::where("variant_id", $variant->id)->where("order_id", $this->order->id)->exists()) {
      return response()->json(["status" => "CoustomErrors", "message" => "هذا المنتج مضاف مسبقا الي الاوردر"]);
    }


    try {


      cart::create([
        "user_id" => auth()->user()->id,
        "product_id" => $variant->product->id,
        "variant_id" => $variant->id,
        "qnt" => $data["stock"],
        "comissation" => $data["comissation"]
      ]);

      return   $this->storeOrderDetails($this->order, cart::where("user_id", auth()->user()->id)->get());
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج للاوردر"]);
    }
  }


  function storeOrderDetails($order, $carts)
  {

    try {

      foreach ($carts as $cart) {

        if ($cart->variant_id) {
          $discription =  $cart->product->nickName . " " . variantName($cart->variant->id);
        } else {
          $discription =  $cart->product->nickName;
        }



        if ($order->status == "قيد المراجعة" && holdOrderInCart($carts)) {

          retuenHold($order);
        } else if (!holdOrderInCart($carts) && $order->status != "قيد الانتظار") {

          Take_From_Stock_Using_One_Cart($cart);
        }


        order_detail::create([
          "order_id" => $order->id,
          "product_id" => $cart->product->id,
          "variant_id" => $cart->variant_id ? $cart->variant->id : null,
          "discription" => $discription,
          "price" => $cart->product->price + $cart->product->systemComissation,
          "qnt" => $cart->qnt,
          "ponus" => $cart->product->ponus ? $cart->product->ponus : 0,
          "comissation" => $cart->comissation,
          "TotalComissation" => $cart->product->ponus + $cart->comissation,
          "traderPrice" => $cart->product->price,
          "systemComissation" => $cart->product->systemComissation,
        ]);
      }


      cart::where("user_id", auth()->user()->id)->delete();

      DB::commit();

      return response()->json(["status" => "success", "message" => " تم اضافة المنتج بنجاح "]);
    } catch (\Throwable $th) {

      return response()->json(["status" => "CoustomErrors", "message" =>  "خطا اثناء اضافة المنتج"]);
    }
  }
}

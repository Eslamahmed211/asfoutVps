<?php

namespace App\Http\Controllers\users\cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\cart\CheckOutRequest;
use App\Models\bosta_price;
use App\Models\cart;
use App\Models\deliveryPrice;
use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class cartController extends Controller
{

  public $order;

  function index()
  {

    $carts = cart::where("user_id", auth()->user()->id)->get();


    foreach ($carts as $cart) {

      if ($cart->product == null) {
        $cart->delete();
      }

      if (!$cart->product->show) {
        $cart->delete();
      }

      if ($cart->variant_id && $cart->variant == null) {
        $cart->delete();
      }

      if ($cart->product->stock !== null &&  $cart->product->stock < $cart->qnt &&  $cart->product->unavailable == "no") {
        $cart->delete();
      }


      if ($cart->variant_id && $cart->variant->stock < $cart->qnt && $cart->variant->stock !== null &&  $cart->product->unavailable == "no") {
        $cart->delete();
      }
    }




    $carts = cart::where("user_id", auth()->user()->id)->get();



    return view("users/cart/index", compact("carts"));
  }

  function store(Request $request)
  {

    DB::beginTransaction();


    $data = $this->validation($request);

    if (is_a($data, 'Illuminate\Http\JsonResponse')) {
      return $data;
    }

    $product =  product::withCount("variants")->find($data["product_id"]);

    $security = $this->security($product, $data);
    if (is_a($security, 'Illuminate\Http\JsonResponse')) {
      return $security;
    }



    // لو هضيف قطعة لي اوردر موجود

    if ($data["add_new_product"] != null) {

      $this->order = order::find($data["add_new_product"]);

      if (!$this->order) {
        return response()->json(["status" => "CoustomErrors", "message" => "الاوردر ده غير موجود"]);
      }

      if ($this->order->user_id != auth()->user()->id   && !MyModeratorOrder($this->order)) {
        return response()->json(["status" => "CoustomErrors", "message" => "ليس لديك صلاحية للتعديل علي هذا الاوردر"]);
      }

      if ($this->order->status != "قيد المراجعة" && $this->order->status != "قيد الانتظار") {
        return response()->json(["status" => "CoustomErrors", "message" => "ليس لديك صلاحية للتعديل علي هذا الاوردر"]);
      }


      cart::where("user_id", auth()->user()->id)->delete();
    }

    // لو هضيف قطعة لي اوردر موجود


    if (isset($data["values"])) {

      return $this->Variant($product, $data);
    } else {

      return $this->noVariant($product, $data);
    }
  }

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
      "comissation.min" => "لا تجعل عمولتك اقل من 10 جنية",
    ]);

    if ($validator->fails()) {
      return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
    }

    $data = $validator->validate();

    return $data;
  }

  function security($product, $data)
  {

    // تتاكد من
    // المنتج موجود
    // المسوق غير محظور
    // العمولة واحد الادني والاعلي
    // التاكد لو varaint ومجبش الخصائص

    try {

      if (!$product) {
        return response()->json(["status" => "CoustomErrors", "message" => "المنتج ده غير متوفر"]);
      }

      if (!CanAccess($product->id,  auth()->user()->id)) {
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

      if (cart::where("product_id", $product->id)->where("user_id", auth()->user()->id)->first()) {
        return response()->json(["status" => "CoustomErrors", "message" => "هذا المنتج مضاف مسبقا الي السلة"]);
      }


      if ($this->order != null && order_detail::where("product_id", $product->id)->where("order_id", $this->order->id)->exists()) {
        return response()->json(["status" => "CoustomErrors", "message" => "هذا المنتج مضاف مسبقا الي الاوردر"]);
      }



      cart::create([
        "user_id" => auth()->user()->id,
        "product_id" => $product->id,
        "qnt" => $data["stock"],
        "comissation" => $data["comissation"]
      ]);



      if ($data["add_new_product"] != null) {
        return   $this->storeOrderDetails($this->order, cart::where("user_id", auth()->user()->id)->get());
      } else {

        DB::commit();

        return response()->json(["status" => "success", "message" => "تم اضافة المنتج للسلة بنجاح"]);
      }
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج للسلة"]);
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
    if (cart::where("variant_id", $variant->id)->where("user_id", auth()->user()->id)->first()) {
      return response()->json(["status" => "CoustomErrors", "message" => " هذا المنتج مضاف مسبقا الي السلة بنفس الخصائص"]);
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



      if ($data["add_new_product"] != null) {
        return   $this->storeOrderDetails($this->order, cart::where("user_id", auth()->user()->id)->get());
      } else {
        DB::commit();
        return response()->json(["status" => "success", "message" => "تم اضافة المنتج للسلة بنجاح"]);
      }
    } catch (\Throwable $th) {
      return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج للسلة"]);
    }
  }


  //

  public function destroy(Request $request)
  {

    $cart = cart::where("user_id", auth()->user()->id)->findOrFail($request->cart_id);



    try {
      $cart->delete();


      return Redirect::back()->with("success", "تم الازالة بنجاح");
    } catch (\Exception $e) {
      return Redirect::back()->with("error", "لم يتم الازالة");
    }
  }

  public function destroyAll()
  {

    try {

      cart::where("user_id", auth()->user()->id)->delete();
      return Redirect::back()->with("success", "تم الازالة بنجاح");
    } catch (\Exception $e) {
      return Redirect::back()->with("error", "لم يتم الازالة");
    }
  }


  // checkout

  function checkout(CheckOutRequest  $request)
  {

    $data = $request->validated();


    DB::beginTransaction();


    $carts = $this->checkProductBeforeStore();

    if (is_a($carts, 'Illuminate\Http\RedirectResponse')) {
      return $carts;
    }


    $storeOrder = $this->storeOrder($data,   $carts);

    if (is_a($storeOrder, 'Illuminate\Http\RedirectResponse')) {
      return $storeOrder;
    }

    return   $this->storeOrderDetails($storeOrder, $carts);
  }


  function checkProductBeforeStore()
  {

    $carts = cart::with("product", "variant")->where("user_id", auth()->user()->id)->get();

    foreach ($carts as $cart) {

      if ($cart->product === 0) {
        return redirect()->back()->with("error", "هناك منتج غير متوفر موجود في السلة")->withInput();;
      } elseif (!$cart->product->show) {
        return redirect()->back()->with("error", "هناك منتج غير متوفر موجود في السلة")->withInput();;
      } elseif ($cart->variant_id && $cart->variant === 0) {
        return redirect()->back()->with("error", "هناك منتج غير متوفر موجود في السلة")->withInput();;
      } elseif ($cart->product->stock < $cart->qnt &&  $cart->product->stock !== null &&  $cart->product->unavailable == "no") {
        return redirect()->back()->with("error", "هناك منتج في السلة عدد القطع المطلوبة اكبر من المتوفرة")->withInput();
      } elseif ($cart->variant_id && $cart->variant->stock < $cart->qnt &&  $cart->variant->stock !== null &&  $cart->product->unavailable == "no") {
        return redirect()->back()->with("error", "هناك منتج في السلة عدد القطع المطلوبة اكبر من المتوفرة")->withInput();
      } elseif (!CanAccess($cart->product->id,  auth()->user()->id)) {
        return redirect()->back()->with("error", "لا يوجد لديك صلاحية لعمل اوردرات علي هذا المنتج")->withInput();;
      }
    }

    return $carts;
  }

  function storeOrder($data, $carts)
  {


    try {

      $city =  deliveryPrice::find($data["city"]);

    //   $bosta =  bosta_price::where("code", $city->code)->first();

      $holdOrderInCart = holdOrderInCart($carts);


      $order =   order::create([
        "user_id" => auth()->user()->id,
        "reference" => generateUniqueReference(),
        "clientName" => $data["clientName"],
        "clientPhone" => $data["clientPhone"],
        "clientPhone2" => $data["clientPhone2"],
        "city" => $city->name,
        "address" => $data["address"],
        "page" => $data["page"] ?? "",
        "notes" => $data["notes"],
        "notesBosta" => $data["notesBosta"],
        "delivery_price" => $city->delivery_price,
        "return_price" => $city->return_price,

        "bosta_delivery_price" => 0,
        "bosta_return_price" => 0,

        "status" => $holdOrderInCart ? "قيد الانتظار" : 'قيد المراجعة',

      ]);

      return $order;
    } catch (\Throwable $th) {
      return redirect()->back()->with("error", "خطا اثناء اضافة بيانات العميل")->withInput();;
    }
  }


  function storeOrderDetails($order, $carts)
  {

    try {
        
            if (count($carts) == 0) {
                return redirect()->back()->with("error", "تاكد من اضافة الاوردر لانك قمت بالضغط علي تسجيل الاوردر مرات متتالية")->withInput();
            }

      foreach ($carts as $cart) {

        if ($cart->variant_id) {
          $discription =  $cart->product->nickName . " " . variantName($cart->variant->id);
        } else {
          $discription =  $cart->product->nickName;
        }



        if ($order->status == "قيد المراجعة" && holdOrderInCart($carts)) {

          retuenHold($order);
        } else if (!holdOrderInCart($carts) && $order->status != "قيد الانتظار") {


          if ($cart->product->stock !== null) {

            $cart->product->update([
              "stock" => $cart->product->stock - $cart->qnt
            ]);
          }

          if ($cart->variant_id) {

            if ($cart->variant->stock !== null) {

              $cart->variant->update([
                "stock" => $cart->variant->stock - $cart->qnt
              ]);
            }
          }
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

      if ($this->order != null) {
        return response()->json(["status" => "success", "message" => " تم اضافة المنتج بنجاح "]);
      } else {
        return redirect("users/orders")->with("success", "تم اضافة الاوردر بنجاح");
      }
    } catch (\Throwable $th) {

      if ($this->order != null) {

        return response()->json(["status" => "CoustomErrors", "message" =>  "خطا اثناء اضافة المنتج"]);
      } else {
        return redirect()->back()->with("error", "خطا اثناء اضافة الاوردر")->withInput();
      }
    }
  }
}

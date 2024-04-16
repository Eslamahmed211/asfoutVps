<?php

namespace App\Http\Controllers\admin\deliveryPrice;

use App\Http\Controllers\Controller;
use App\Models\deliveryPrice;
use Illuminate\Http\Request;

class deliveryPriceController extends Controller
{
  public function changeOrder(Request $request)
  {


    deliveryPrice::where('id', $request->id)->update([
      'order' => $request->order + 1
    ]);

    return true;
  }

  public function destroy(Request $request)
  {


    try {

      $deliveryPrice = deliveryPrice::findOrFail($request->deliveryPrice_id);

      $deliveryPrice->delete();

      return redirect()->back()->with("success", "تم الازالة بنجاح");
    } catch (\Throwable $th) {
      return redirect()->back()->with("error", "لم يتم الازالة");
    }
  }

  function edit(Request $request)
  {

    $data =  $request->validate([
      "name" => "required|string",
      "code" => "required|string",
      "delivery_price" => "required|numeric|min:0",
      "return_price" => "required|numeric|min:0",
      "deliveryPrice_id" => "required",
    ]);


    $deliveryPrice =  deliveryPrice::findOrFail($data["deliveryPrice_id"]);


    try {

      $deliveryPrice->update($data);
      return redirect()->back()->with("success", "تم التعديل بنجاح");
    } catch (\Throwable $th) {
      return redirect()->with("error", "لم يتم التعديل")->withInput();;
    }
  }


  function store(Request $request)  {

    $data =  $request->validate([
      "name" => "required|string",
      "code" => "required|string",
      "delivery_price" => "required|numeric|min:0",
      "return_price" => "required|numeric|min:0",
    ],[
        "name.required" => "يرجي كتابة اسم المحافظة" ,
        "code.required" => "يرجي كتابة كود المحافظة",
        "delivery_price.required" => "يرجي كتابة سعر الشحن",
        "return_price.required" => "يرجي كتابة سعر المرتجع"
    ]);

    try {

      deliveryPrice::create($data);
      return redirect()->back()->with("success", "تم الاضافة بنجاح");
    } catch (\Throwable $th) {
      return redirect()->with("error", "لم يتم الاضافة")->withInput();;
    }
  }
}

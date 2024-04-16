<?php

namespace App\Http\Controllers\users\payment;

use App\Http\Controllers\Controller;
use App\Models\paymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class paymentController extends Controller
{

  function create()
  {
    return view("users/paymentMethods/create");
  }


  function store(Request $request)
  {

    $validator = Validator::make($request->data, [
      "title" => "required|string",
      "type" => ["required", "string",   Rule::in(['cash', 'bank']),]
    ], [
      "title.required" => "يرجي كتابة العنوان",
      "type.required" => "يرجي اختيار طريقة السحب",
      "type.in" => "يجب ان يكون  طريقة السحب كاش او بنك"

    ]);

    if ($validator->fails()) {
      return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
    }

    $data = $validator->validate();

    if ($data["type"] == "cash") {

      $validator = Validator::make($request->data, [
        "cash_wallet_type" => ["required", "string",   Rule::in(['vodafone', 'orange', 'etisalat'])],
        "mobile" => ["required", "string", 'confirmed', "numeric", 'digits:11'],
        "mobile_confirmation" => ["required", "string"],
      ], [
        "mobile.numeric" => "يجب ان يكون رقم المحفظة رقما",
        "cash_wallet_type.required" => "يرجي اختيار نوع المحفظة",
        "cash_wallet_type.in" => "يجب ان تكون نوع المحفظة فودافون او اتصالات او اورنج كاش",
        "mobile.required" => "يرجي كتابة  رقم المحفظة",
        "mobile_confirmation.required" => "يرجي تاكيد رقم المحفظة",
        "mobile.confirmed" => "رقم المحفظة غير مطابق",
        "mobile.digits" => "يجب ان  يكون رقم المحفظة  11 رقم",

      ]);


      if ($validator->fails()) {
        return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
      }


      $option = $validator->validate();

      $data["user_id"] =  auth()->user()->id;
      $data["options"] = json_encode($option);
    }


    if ($data["type"] == "bank") {

      $validator = Validator::make($request->data, [
        "name" => ["required", "string"],
        "bank_name" => ["required", "string"],
        "bank_account_id" => ["required", "string"],
        "bank_branch_number" => ["required", "string"],

      ], [

        "name.required" => "يرجي كتابة  اسمك كامل ",
        "bank_name.required" => "يرجي كتابة  اسم البنك ",
        "bank_account_id.required" => "يرجي كتابة  رقم الحساب ",
        "bank_branch_number.required" => "يرجي كتابة  عنوان الفرع ",

      ]);


      if ($validator->fails()) {
        return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
      }


      $option = $validator->validate();

      $data["user_id"] =  auth()->user()->id;
      $data["options"] = json_encode($option);
    }

    try {


      paymentMethod::create($data);

      return response()->json(["status" => "success", "message" => "تم اضافة طريقة السحب بنجاح"]);
    } catch (\Throwable $th) {
      return response()->json(["status" => "error", "message" => "لم يتم الاضافة"]);
    }
  }


  function edit($id)
  {
    $paymentMethod = paymentMethod::where("user_id", auth()->user()->id)->findOrFail($id);


    if ($paymentMethod->type  == "bank") {
      return view("users/paymentMethods/editBank", compact("paymentMethod"));
    }else{
      return view("users/paymentMethods/editCash", compact("paymentMethod"));

    }
  }

  function update(Request $request, $id)
  {
    $paymentMethod = paymentMethod::where("user_id", auth()->user()->id)->findOrFail($id);

    $data = $request->validate([
      "title" => "required|string",
    ], [
      "title.required" => "يرجي كتابة العنوان",
      "type.required" => "يرجي اختيار طريقة السحب",
    ]);


    if ($paymentMethod->type == "bank") {

      $options = $request->validate([
        "name" => ["required", "string"],
        "bank_name" => ["required", "string"],
        "bank_account_id" => ["required", "string"],
        "bank_branch_number" => ["required", "string"],
      ], [
        "name.required" => "يرجي كتابة  اسمك كامل ",
        "bank_name.required" => "يرجي كتابة  اسم البنك ",
        "bank_account_id.required" => "يرجي كتابة  رقم الحساب ",
        "bank_branch_number.required" => "يرجي كتابة  رقم الفرع ",

      ]);
    }

    if ($paymentMethod->type == "cash") {

      $options = $request->validate([
        "cash_wallet_type" => ["required", "string", "in:vodafone,orange,etisalat"],
        "mobile" => ["required", "string", 'confirmed', "numeric", 'digits:11'],
        "mobile_confirmation" => ["required", "string"],
      ], [
        "mobile.numeric" => "يجب ان يكون رقم المحفظة رقما",
        "cash_wallet_type.required" => "يرجي اختيار نوع المحفظة",
        "cash_wallet_type.in" => "يجب ان تكون نوع المحفظة فودافون او اتصالات او اورنج كاش",
        "mobile.required" => "يرجي كتابة  رقم المحفظة",
        "mobile_confirmation.required" => "يرجي تاكيد رقم المحفظة",
        "mobile.confirmed" => "رقم المحفظة غير مطابق",
        "mobile.digits" => "يجب ان  يكون رقم المحفظة  11 رقم",

      ]);
    }


    try {

      $data["options"] = json_encode($options);

      $paymentMethod->update($data);

      return redirect()->back()->with("success", "تم التعديل بنجاح");
    } catch (\Throwable $th) {
      return redirect()->back()->with("error", "لم يتم التعديل");
    }

  }


  public function destroy(Request $request)
  {
    try {

      $payment = paymentMethod::where("user_id", auth()->user()->id)->findOrFail($request->payment_id);

      $payment->delete();

      return redirect()->back()->with("success", "تم الازالة بنجاح");
    } catch (\Throwable $th) {
      return Redirect::back()->with("error", "لم يتم الازالة");
    }
  }
}

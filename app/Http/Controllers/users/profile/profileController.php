<?php

namespace App\Http\Controllers\users\profile;

use App\Http\Controllers\Controller;
use App\Models\paymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class profileController extends Controller
{
    function index(){

        $paymentMethods = auth()->user()->paymentMethods;

        return view("users/profile/index" , get_defined_vars());
    }

    function info(Request $request)
    {

      $data = $request->validate([
        "name" => "required|min:3|string",
        "mobile" => "required|numeric|digits:11",
        "address" => "required|string",
        "city" => "required|string",
      ], [
        "name.required" => "يرجي كتابة اسمك",
        "mobile.required" => "يرجي كتابة رقم التليفون",
        "mobile.digits" => "يجب ان لا يقل رقم التليفون عن 11 رقم",
        "mobile.numeric" => "يجب ان يكون رقم التليفون رقما",
        "address.required" => "يرجي كتابة العنوان",
        "city.required" => "يرجي كتابة المحافظة",
      ]);

      try {

        $user = User::findOrFail(auth()->user()->id);

        $user->update($data);


        return Redirect::back()->with("success", "تم التعديل بنجاح");
      } catch (\Throwable $th) {

        return Redirect::back()->with("error", "لم يتم التعديل");
      }
    }
    function password(Request $request)
    {

      $data = $request->validate([
        "passwordOld" => "required|string",
        "password" => "required|min:8|max:32|confirmed|string",

      ], [
        "passwordOld.required" => "يرجي كتابة كلمة السر القديمة",
        "password.required" => "يرجي كتابة  كلمة المرور الجديدة",
        "password.min" => "يجب ان لا تقل كلمة المرور  الجديدة عن 8 ارقام",
        "password.confirmed" => "كلمة المرور غير متطابقة",
      ]);


        if (!Hash::check($data["passwordOld"], auth()->user()->password)) {
          return Redirect::back()->with("error", "كلمة المرور القديمة خطأ")->withInput();
        }
        $data["password"] = bcrypt($data["password"]);


      try {

        $user = User::findOrFail(auth()->user()->id);

        $user->update($data);


        return Redirect::back()->with("success", "تم  تغير كلمة السر بنجاح");
      } catch (\Throwable $th) {

        return Redirect::back()->with("error", "لم يتم تغير كلمة السر");
      }
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


        dd(auth()->user()->type);

        paymentMethod::create($data);

        return response()->json(["status" => "success", "message" => "تم اضافة طريقة السحب بنجاح"]);
      } catch (\Throwable $th) {
        return response()->json(["status" => "error", "message" => "لم يتم الاضافة"]);
      }
    }

}

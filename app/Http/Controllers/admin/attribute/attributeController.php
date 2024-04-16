<?php

namespace App\Http\Controllers\admin\attribute;

use App\Http\Controllers\Controller;
use App\Models\attribute;
use App\Models\value;
use App\Models\variant_value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class attributeController extends Controller
{
    public function delete_value(Request $request)
    {
        $data = $request->validate(['value_id' => 'required']);


        $VariantCount  = variant_value::where("value_id" , $data['value_id'])->count();
  
        if ($VariantCount != 0) {
  
          return Redirect::back()->with("error", "لا يمكن ازالة الخاصية لانها  مربوطة في منتجات فرعية");
  
        }
  


        try {


            $value =  value::where("id", $data['value_id'])->first();
            


            $value->delete();




            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }


    public function add(Request $request)
    {
        $request->validate([
            "name" => "required",
            "key" => "nullable",
            "values" => "required",
            "product_id" => "required"
        ],[
          "name.required" => "يرجي  اضافة اسم الخاصية" , 
          "values.required" => "يرجي كتابة القيم" , 
        ]);

        $values = explode("-",  $request->values);


        DB::beginTransaction();

        try {

           
                $callback =   attribute::create([
                    "name" => $request->name,
                    "key" => $request->key,
                    "product_id" => $request->product_id,
                ]);
            


            foreach ($values as $value) {

                value::create([
                    "attribute_id" => $callback->id,
                    "value" => $value,
                ]);
            }


            DB::commit();

            return Redirect::back()->with("success", "تم اضافة خاصية جديدة");
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
        }
    }

    public function addValue($id)
    {


        $attr =  attribute::with(["values" =>  function ($q) {
            $q->orderBy('id', 'desc');
        }])->findOrFail($id);

    



        return view("admin/attributes/show", compact("attr"));
    }

    public function addValueStore(Request $request)
    {
        $data =  $request->validate([
            "attribute_id" => "required|integer",
            "value" => "required|string",
        ],[
          "value.required" => "يرجي اضافة قيم"
        ]);


        $attr = attribute::findOrFail($request->attribute_id);

     
        $existValue = value::where("value", $request->value)->where("attribute_id", $attr->id)->first();



        if (!empty($existValue)) {
            return Redirect::back()->with("error", "هذة القيمة موجودة بالفعل")->withInput();
        }


        try {
            value::create($data);
            return Redirect::back()->with("success", "تم اضافة قيمة جديدة");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();;
        }
    }

    public function change_value_value(Request $request)
    {
        $data =  $request->validate([
            "new_value" => "required|string",
            "value_id" => "required|integer"
        ]);

        $value =  value::findOrFail($data["value_id"]);

        $attr = attribute::findOrFail($value->attribute_id);

     


        try {

            $value->update([
                "value" => $data['new_value']
            ]);

            return Redirect::back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم التعديل")->withInput();;
        }
    }

    public function delete(Request $request)
    {

      $attr = attribute::with("values")->findOrFail($request->attribute_id);

      $valueIds = $attr->values->pluck('id')->toArray();

      $VariantCount  = variant_value::whereIn("value_id" , $valueIds)->count();

      if ($VariantCount != 0) {

        return Redirect::back()->with("error", "لا يمكن ازالة الخاصية لانها  مربوطة في منتجات فرعية");

      }



        try {

          $attr->delete();


            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }
}

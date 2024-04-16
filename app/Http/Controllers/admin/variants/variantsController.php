<?php

namespace App\Http\Controllers\admin\variants;

use App\Http\Controllers\Controller;
use App\Models\attribute;
use App\Models\order;
use App\Models\product;
use App\Models\variant;
use App\Models\variant_value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class variantsController extends Controller
{

    function bulkDelete(Request $request)
    {


        $data = $request->validate([
            "ids" => "required|string"
        ]);

        $ids = explode(",", $data["ids"]);

        try {

            foreach ($ids as $id) {
                $variant =  Variant::find($id);


                $orders = order::with("details")->whereHas("details", function ($q) use ($variant) {
                    $q->whereHas("variant", function ($q)  use ($variant) {
                        $q->where("id", $variant->id);
                    });
                })->count();


                if ($orders > 0) {
                    continue;
                } else {
                    $variant->delete();
                }
            }
            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }

    function index(product $product)
    {


        $productAttributes = $product->attributes;


        $attributes = attribute::orderBy('id', 'desc')->get();

        return view('admin/variants/index', compact('product', 'attributes', "productAttributes"));
    }

    function storeAttributes(Request $request, product $product)
    {


        $data =  $request->validate([
            "attributes" => "nullable"
        ], [
            "attributes.required" => "يرجي اضافة خصائص للمنتج"
        ]);

        if (!isset($data['attributes'])) {
            $data['attributes'] = [];
        }


        try {



            $product->attributes()->sync($data['attributes']);

            return Redirect::back()->with("success", "تم الاضافة بنجاح");
        } catch (\Exception $e) {

            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
        }
    }

    function store(Request $request, product $product)
    {


        $data =  $request->validate([
            "attrs" => "required",
            "stock" => "nullable",
        ]);
        $data["product_id"] = $product->id;





        // step 1 create attrs array
        $allAttributeValues = array_values($data['attrs']);
        $combinations = [[]];
        foreach ($allAttributeValues as $attributeValues) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($attributeValues as $value) {
                    $newCombination = $combination;
                    $newCombination[] = $value;
                    $newCombinations[] = $newCombination;
                }
            }
            $combinations = $newCombinations;
        }
        // ===============================

        DB::beginTransaction();

        try {

            foreach ($combinations as $values) {

                if (variantExists($data["product_id"], $values) != null) {
                    continue;
                }

                $data["sku"] = generateProductSKU();
                $inserted = variant::create($data);


                foreach ($values as  $value) {
                    variant_value::create([
                        "variant_id" =>   $inserted->id,
                        "value_id" =>   $value,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في اضافة خصائص المنتج الفرعي")->withInput();
        }



        DB::commit();




        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }


    function updateStock(Request $request)
    {

        try {

            $data = $request->validate([
                "stock" => "required",
                "variant_id" => "required|integer",
            ]);

            $variants =   variant::find($data['variant_id']);

            $variants->update([
                "stock" => $variants->stock += ($data['stock'])
            ]);


            return Redirect::back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم التعديل");
        }
    }

    function destroy(Request $request)
    {

        if ($request->delete != "ازالة") {
            return Redirect::back()->with("error", "قم بكتابة ازالة  ليتم الازالة");
        }


        try {

            $variant = variant::find($request->variant_id);

            $orders = order::with("details")->whereHas("details", function ($q) use ($variant) {
                $q->whereHas("variant", function ($q)  use ($variant) {
                    $q->where("id", $variant->id);
                });
            })->count();


            if ($orders < 0) {
                $variant->delete();
            } else {
                return Redirect::back()->with("error", "لا يمكن ازالة الخاصية لانها تحتوي علي اوردرات");
            }



            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }

    function ajaxVariant(Request $request)
    {
        $id = $request->input("query");
        $variant = variant::where('product_id', $id)->with("values")->get();

        if ($variant->isNotEmpty()) {
            return json(["status" => "success", "variant" => $variant]);
        } else {
            return json(["status" => "error"]);
        }
    }
}

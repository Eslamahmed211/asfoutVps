<?php

namespace App\Http\Controllers\users\orders;

use App\Http\Controllers\Controller;
use App\Models\order_detail;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class detailsController extends Controller
{

    public $details;

    function edit($id)
    {

        $details =  order_detail::with("product", "variant", 'order')->findOrFail($id);

        canAccsessByDetails($details);


        $valueIds = [];

        if ($details->variant) {
            foreach ($details->variant->values as $value) {
                $valueIds[] = $value->id;
            }
        }



        return view("users/orders/detailsEdit", compact("details", 'valueIds'));
    }

    function update(Request $request, $id)
    {

        $data = $this->validation($request);

        if (is_a($data, 'Illuminate\Http\JsonResponse')) {
            return $data;
        }

        $product =  product::withCount("variants")->find($data["product_id"]);

        $security = $this->security($product, $data);
        if (is_a($security, 'Illuminate\Http\JsonResponse')) {
            return $security;
        }

        $this->details =  order_detail::with("product", "variant", 'order')->findOrFail($id);

        canAccsessByDetails($this->details);



        if (!$this->details->order) {
            return response()->json(["status" => "CoustomErrors", "message" => "الاوردر ده غير موجود"]);
        }



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


        DB::beginTransaction();

        if ($product->unavailable == "no") {
            if ($product->stock === 0  || $product->stock < $data["stock"] &&  $product->stock != null) {
                return response()->json(["status" => "CoustomErrors", "message" => "عدد القطع المطلوبة اكبر من الكمية المتوفرة"]);
            }
        } else {

            if ($product->stock === 0  || ($product->stock + $this->details->qnt )   <  $data["stock"]   && $this->details->order->status != "قيد الانتظار") {

                retuenHold($this->details->order);
            }
        }





        try {


            if ($this->details->order->status != "قيد الانتظار") {
                retuenStock($this->details);
            }


            $this->details->update(
                [
                    "qnt" => $data["stock"],
                    "comissation" => $data["comissation"],
                    "TotalComissation" =>   $this->details->ponus + $data["comissation"],
                ]
            );


            if ($this->details->order->status != "قيد الانتظار") {

                deleteStock($this->details);
            }


            DB::commit();

            return response()->json(["status" => "success", "message" => "تم اضافة المنتج بنجاح"]);
        } catch (\Throwable $th) {
            return response()->json(["status" => "CoustomErrors", "message" => "هناك خطأ اثناء اضافة المنتج "]);
        }
    }



    function Variant($product, $data)
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

            if ($variant->stock === 0  || ($variant->stock + $this->details->qnt )  <  $data["stock"]   && $this->details->order->status != "قيد الانتظار") {


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

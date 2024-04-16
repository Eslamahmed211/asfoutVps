<?php

namespace App\Exports\reports;

use App\Models\invoice;
use App\Models\product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class products_orders implements FromArray, WithHeadings
{
    protected $data;

    protected $status = [
        "قيد الانتظار",
        "قيد المراجعة",
        "تم المراجعة",
        "محاولة تانية",
        "تم الالغاء",
        "جاري التجهيز للشحن",
        "تم ارسال الشحن",
        "تم التوصيل",
        "طلب استرجاع",
        "فشل التوصيل",
        "مكتمل"
    ];
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {

        $headings = [];

        array_push($headings, 'اسم المنتج');
        array_push($headings, 'اجمالي المستلم');
        array_push($headings, "المخزون الحالي");

        foreach ($this->status as $state) {
            array_push($headings, $state);
        }
        return $headings;
    }


    public function array(): array
    {
        set_time_limit(0);


        $all = [] ;


        if ($this->data["product_id"] != null) {
            $products = [];
            array_push($products,  product::with("variants")->with("details")->findOrFail($this->data["product_id"]));
        } else {
            $products = product::with("variants")->with("details")->get();
        }

        foreach ($products as $product) {

            $array = [];


            $ids = $product->details->pluck('id')->toArray();


            if (count($product->variants) == 0) {

                $GET_PRODUCT_ALL_STATUS_QNT = GET_PRODUCT_ALL_STATUS_QNT($ids);

                $invoices = invoice::with(["items"  => function ($q) use ($product) {
                    $q->where("product_id", $product->id);
                }])->whereHas("items", function ($q) use ($product) {
                    $q->where("product_id", $product->id);
                })->get();

                $TOTAL_COLLECTION = GET_TOTAL_COLLECTION($invoices);

                array_push($array, $product->name);
                array_push($array, $TOTAL_COLLECTION);
                array_push($array, $product->stock);

                foreach ($this->status as $status) {
                    array_push($array, $GET_PRODUCT_ALL_STATUS_QNT[$status]);
                }

                array_push( $all, $array);

            } else {

                $GET_VARIANTS_ALL_STATUS_QNT = GET_VARIANTS_ALL_STATUS_QNT_NO_ID($product->variants);

                foreach ($GET_VARIANTS_ALL_STATUS_QNT as $VARIANT) {


                    $vairant = [];

                    array_push($vairant, $product->name . " " . $VARIANT['product_name']);
                    array_push($vairant,  $VARIANT['TOTAL_COLLECTION_VARIANT']);
                    array_push($vairant, $VARIANT['variant']->stock);

                    foreach ($this->status as $s) {


                        array_push($vairant, $VARIANT["status"][$s]);
                    }

                    array_push($all, $vairant);


                }
            }
        }



        return $all;
    }
}

<?php

use App\Models\invoice;
use App\Models\order;

function GET_ORDERS_BY_DETAILS($ids, $status, $options = [], $count = false)
{
    $orders = Order::with(["details" => function ($q) use ($ids) {
        $q->whereIn("id", $ids);
    }])->whereIn('status',  $status)->whereHas("details", function ($q) use ($ids) {
        $q->whereIn("id", $ids);
    });

    if (!empty($options)) {
        foreach ($options as $option) {

            if (array_key_exists("date", $option)) {


                $option["date"]["startDate"] != ""  ? $orders = $orders->whereDate("created_at", '>=', $option["date"]["startDate"]) : "";

                isset($option["date"]['endDate']) && !empty($option["date"]['endDate'])  ? $orders = $orders->whereDate("created_at", '<=', $option["date"]['endDate']) :  $orders = $orders->whereDate("created_at", "<=", $option["date"]["startDate"]);
            }
        }
    }

    $count ? $orders = $orders->count() :  $orders = $orders->get();



    return $orders;
}

function SUM_IN_ORDER_DETAILS($orders, $column)
{

    $qnt = 0;

    foreach ($orders as $order) {
        $qnt += $order->details->sum(function ($detail) use ($column) {
            return $detail->$column;
        });
    }

    return $qnt;
}

function GET_PRODUCT_PERCENT($orders_Deliverd, $total)
{
    return count($orders_Deliverd) > 0 ?  (count($orders_Deliverd) / $total)  * 100 : 0;
}


function GET_TOTAL_COLLECTION($invoices)
{
    $total_collect = 0;

    foreach ($invoices as $invoice) {

        foreach ($invoice->items as $item) {
            if ($invoice->type == "مشتريات") {
                $total_collect += $item->qnt;
            } else {
                $total_collect -= $item->qnt;
            }
        }
    }

    return $total_collect;
}


function GET_PRODUCT_ALL_STATUS_QNT($ids)
{

    $pendingReviewCount = GET_ORDERS_BY_DETAILS($ids, ["قيد المراجعة"]);
    $reviewedCount = GET_ORDERS_BY_DETAILS($ids, ["تم المراجعة"]);
    $retryCount = GET_ORDERS_BY_DETAILS($ids, ["محاولة تانية"]);
    $preparingForShippingCount = GET_ORDERS_BY_DETAILS($ids, ["جاري التجهيز للشحن"]) ;
    $shippedCount = GET_ORDERS_BY_DETAILS($ids, ["تم ارسال الشحن"]);
    $deliveredCount = GET_ORDERS_BY_DETAILS($ids, ["تم التوصيل"]);
    $waitingCount = GET_ORDERS_BY_DETAILS($ids, ["قيد الانتظار"]);
    $canceledByMarketerCount = GET_ORDERS_BY_DETAILS($ids, ["تم الالغاء"]);
    $deliveryFailureCount = GET_ORDERS_BY_DETAILS($ids, ["فشل التوصيل"]);
    $done = GET_ORDERS_BY_DETAILS($ids, ["مكتمل"]);
    $return = GET_ORDERS_BY_DETAILS($ids, ["طلب استرجاع"]);
    $tesing =  GET_ORDERS_BY_DETAILS($ids, ["جاهز للتغليف"]) ;


    $STATUSESANDCOUNTS = [
        ["قيد الانتظار", SUM_IN_ORDER_DETAILS($waitingCount, "qnt")],
        ["قيد المراجعة", SUM_IN_ORDER_DETAILS($pendingReviewCount, "qnt")],
        ["تم المراجعة", SUM_IN_ORDER_DETAILS($reviewedCount, "qnt")],
        ["محاولة تانية", SUM_IN_ORDER_DETAILS($retryCount, "qnt")],
        ["جاري التجهيز للشحن", SUM_IN_ORDER_DETAILS($preparingForShippingCount, "qnt") + SUM_IN_ORDER_DETAILS($tesing, "qnt")]   ,
        ["تم ارسال الشحن", SUM_IN_ORDER_DETAILS($shippedCount, "qnt")],
        ["تم التوصيل", SUM_IN_ORDER_DETAILS($deliveredCount, "qnt")],
        ["تم الالغاء" , SUM_IN_ORDER_DETAILS($canceledByMarketerCount, "qnt")],
        ["فشل التوصيل", SUM_IN_ORDER_DETAILS($deliveryFailureCount, "qnt")],
        ["مكتمل", SUM_IN_ORDER_DETAILS($done, "qnt")],
        ["طلب استرجاع",  SUM_IN_ORDER_DETAILS($return, "qnt")],

    ];


    $keyValueArray = [];

    foreach ($STATUSESANDCOUNTS as $item) {
        $keyValueArray[$item[0]] = $item[1];
    }

    return $keyValueArray;
}


function GET_VARIANTS_ALL_STATUS_QNT($variants)
{

    $status = [];

    foreach ($variants as $variant) {

        $invoices = invoice::with(["items"  => function ($q) use ($variant) {
            $q->where("variant_id", $variant->id);
        }])->whereHas("items", function ($q) use ($variant) {
            $q->where("variant_id", $variant->id);
        });

        if (auth()->user()->role == "trader") {
            $invoices = $invoices->where("traderId", auth()->id());
        }

        $invoices = $invoices->get();


        $TOTAL_COLLECTION_VARIANT = GET_TOTAL_COLLECTION($invoices);

        $ids = $variant->details->pluck('id')->toArray();

        array_push($status, [
            "product_name" => variantName($variant->id),
            "variant" =>  $variant,
            "TOTAL_COLLECTION_VARIANT" => $TOTAL_COLLECTION_VARIANT,
            "status" => GET_PRODUCT_ALL_STATUS_QNT($ids),
        ]);
    }

    return $status;
}


function GET_VARIANTS_ALL_STATUS_QNT_NO_ID($variants)
{

    $status = [];

    foreach ($variants as $variant) {


        $invoices = invoice::with(["items"  => function ($q) use ($variant) {
            $q->where("variant_id", $variant->id);
        }])->whereHas("items", function ($q) use ($variant) {
            $q->where("variant_id", $variant->id);
        })->get();

        $TOTAL_COLLECTION_VARIANT = GET_TOTAL_COLLECTION($invoices);


        $ids = $variant->details->pluck('id')->toArray();

        array_push($status, [
            "product_name" => variantName($variant->id),
            "variant" =>  $variant,
            "TOTAL_COLLECTION_VARIANT" => $TOTAL_COLLECTION_VARIANT,
            "status" => GET_PRODUCT_ALL_STATUS_QNT($ids),
        ]);
    }

    return $status;
}

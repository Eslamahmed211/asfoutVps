<?php

use App\Models\order;
use App\Models\product;
use App\Models\User;
use App\Models\variant;

if (!function_exists('generateUniqueReference')) {
    function generateUniqueReference()
    {

        $UniqueReference =  "AS4" . mt_rand(1000000, 9999999);



        while (User::where('id', $UniqueReference)->exists()) {
            $UniqueReference =  "AS4"  . mt_rand(1000000, 9999999);
        }

        return $UniqueReference;
    }
}



if (!function_exists('getOrderData')) {
    function getOrderData($details)
    {

        $total = 0;
        $comissation = 0;
        $ponus = 0;
        $qnt = 0;
        $systemComissation = 0;
        $dis = '';
        $traderPrice = 0;


        $take = 0;
        $get = 0;

        foreach ($details as $detail) {

            $total +=  $detail->qnt  * ($detail->price + $detail->comissation);
            $comissation +=  $detail->qnt  * ($detail->comissation);
            $ponus +=  $detail->qnt  * ($detail->ponus);
            $qnt += $detail->qnt;
            $dis .=  $detail->discription  . ' ( ' . $detail->qnt . ' ) ' . PHP_EOL;
            $systemComissation += $detail->qnt  * ($detail->systemComissation);
            $traderPrice += $detail->qnt  * $detail->traderPrice;

        }
            $take += $detail->order->take;
            $get += $detail->order->get;


        $data = [
            "total" => $total + $take  - $get,
            "comissation" => $comissation + $take -  $get,
            "ponus" => $ponus,
            "qnt" => $qnt,
            "dis" => $dis,
            "systemComissation" => $systemComissation,
            "traderPrice" => $traderPrice,
            "comissationInOrder" => $comissation,
        ];

        return $data;
    }
}


if (!function_exists('StatusClass')) {
    function StatusClass($status)
    {

        return  match ($status) {
            "قيد المراجعة" => "pindding",
            "قيد الانتظار" => "pindding",

            "تم الالغاء" => "cancel",

            "فشل التوصيل" => "cancel",

            "محاولة تانية" => "tryAgain",

            "تم المراجعة" => "blue",

            "تم التوصيل" => "done",

            "مكتمل" => "done",

            default => "blue",
        };
    }
}



if (!function_exists('retuenStock')) {
    function retuenStock($details)
    {

        $product = product::find($details->product->id);

        if ($product->stock !== null) {
            $product->update([
                "stock" => $product->stock + $details->qnt,
            ]);
        }



        if ($details->variant) {

            $variant = variant::find($details->variant->id);;

            if ($variant->stock !== null) {

                $variant->update([
                    "stock" => $variant->stock + $details->qnt,
                ]);
            }
        }

        return true;
    }
}



if (!function_exists('deleteStock')) {
    function deleteStock($details)
    {
        $product = product::find($details->product->id);

        if ($product->stock !== null) {
            $product->update([
                "stock" => $product->stock - $details->qnt,
            ]);
        }



        if ($details->variant) {

            $variant = variant::find($details->variant->id);;

            if ($variant->stock !== null) {

                $variant->update([
                    "stock" => $variant->stock - $details->qnt,
                ]);
            }
        }

        return true;
    }
}


if (!function_exists('MyModeratorOrder')) {
    function MyModeratorOrder($order)
    {

        return in_array($order->user_id, auth()->user()->moderators->modelKeys());
    }
}


if (!function_exists('canAccsessByDetails')) {
    function canAccsessByDetails($details)
    {


        if ($details->order->status != 'قيد المراجعة' && $details->order->status != 'قيد الانتظار') {
            abort(404);
        }

        if (!$details->product->show) {
            abort(404);
        }


        if ($details->order->user_id != auth()->user()->id && !MyModeratorOrder($details->order)) {
            abort(404);
        }
    }
}


if (!function_exists('holdOrderInCart')) {
    function holdOrderInCart($carts)
    {
        foreach ($carts as $cart) {

            if (!$cart->variant_id) {
                if ($cart->product->stock < $cart->qnt  &&  $cart->product->unavailable == "yes") {
                    return true;
                }
            } else {
                if ($cart->variant->stock < $cart->qnt  &&  $cart->product->unavailable == "yes") {
                    return true;
                }
            }
        }

        return false;
    }
}


if (!function_exists('retuenHold')) {
    function retuenHold($order)
    {

        foreach ($order->details as $detail) {


            $product = product::find($detail->product->id);

            if ($product->stock !== null) {
                $product->update([
                    "stock" => $product->stock + $detail->qnt,
                ]);
            }

            if ($detail->variant) {

                $variant = variant::find($detail->variant->id);;

                if ($variant->stock !== null) {

                    $variant->update([
                        "stock" => $variant->stock + $detail->qnt,
                    ]);
                }
            }
        }

        $order->update([
            "status" => "قيد الانتظار"
        ]);

        return true;
    }
}


function fixStatus($status)
{

    return ($status == "تم التوصيل شحن يدوي معلق" || $status == "فشل التوصيل يدوي معلق" || $status == "مؤجل تسليمها شحن يدوي") ? "ارسال شحن يدوي" : $status;
}


function fixData($date)
{

    $dateTimeObj = new DateTime($date);
    $date = $dateTimeObj->format('Y-m-d h:i:s A');

    return $date;
}


function ORDER_ALL_DETAILS($order)
{

    $OrderData = getOrderData($order->details);

    $data = [];


    $data["كود الاوردر"] = $order->reference;
    $data["كود الشحنة"] = $order->trackingNumber;
    $data["اسم المسوق"] = $order->user->role == "user" ?  $order->user->name : User::find($order->user->marketer_id)->name;
    $data["رقم المسوق"] = $order->user->role == "user" ?  $order->user->mobile : User::find($order->user->marketer_id)->mobile;
    $data["المودريتور"] = $order->user->role == "moderator" ?  $order->user->name : "";
    $data["رقم المودريتور"] = $order->user->role == "moderator" ?  $order->user->mobile : "";
    $data["حالة الطلب"] = $order->status;
    $data["اجمالي الاوردر"] = $OrderData['total'] + $order->delivery_price;
    $data["عمولة المسوق"] = $OrderData['comissationInOrder'];
    $data["خصم"] = $order->get;
    $data["اضافة"] = $order->take;
    $data["بونص"] = $OrderData['ponus'];
    $data["صافي العمولة"] = $OrderData['comissation'] + $OrderData['ponus'];
    $data["المحافظة"] = $order->city;
    $data["سعر الشحن"] = $order->delivery_price;
    $data["عمولة الموقع"] = $OrderData['systemComissation'];
    $data["الصافي بعد البونص"] = $OrderData['systemComissation'] - $OrderData['ponus'];
    $data["تاريخ اضافة الاوردر"] = fixData($order->created_at);
    $data["اسم العميل"] = $order->clientName;
    $data["رقم العميل"] = $order->clientPhone;
    $data["رقم العميل الثاني"] = $order->clientPhone2;
    $data["عنوان العميل"] = $order->address;
    $data["محتوي الاوردر"] = $OrderData["dis"];

    return $data;
}


function count_order($number)
{
    return order::where("clientPhone", $number)->orWhere("clientPhone2",  $number)->count() . " طلب ";
}


function ALL_STATUS()
{
    return [
        "قيد الانتظار",
        "قيد المراجعة",
        "تم المراجعة",
        "محاولة تانية",
        "تم الالغاء",
        "جاهز للتغليف",
        "جاري التجهيز للشحن",
        "تم ارسال الشحن",
        "تم التوصيل",
        'طلب استرجاع',
        "فشل التوصيل",
        "مكتمل"
    ];
}


function count_by_status($status)
{
    return order::whereIN("status", $status)->count();
}

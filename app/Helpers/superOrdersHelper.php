<?php

use App\Http\Resources\orderResource;
use App\Models\commission_history;
use App\Models\commission_system_history;
use App\Models\order;
use App\Models\product;
use App\Models\User;
use App\Models\variant;

if (!function_exists('GetOrderDetailsAjax')) {
    function GetOrderDetailsAjax($id)
    {

        $order = order::with(["details.product" => function ($q) {
            $q->withTrashed();
        }])->find($id);


        return new orderResource($order);
    }
}


if (!function_exists('GetOrderDetailsAjaxReference')) {
    function GetOrderDetailsAjaxReference($reference)
    {

        $order = order::with(["details.product" => function ($q) {
            $q->withTrashed();
        }])->with(["details.variant" => function ($q) {
            $q->withTrashed();
        }])->where("reference", $reference)->first();


        return new orderResource($order);
    }
}

if (!function_exists('Take_From_Stock_Using_One_Cart')) {
    function Take_From_Stock_Using_One_Cart($cart)
    {

        if ($cart->product->stock !== null) {

            $product = product::find($cart->product->id);

            $product->update([
                "stock" => $product->stock - $cart->qnt
            ]);
        }

        if ($cart->variant_id) {

            if ($cart->variant->stock !== null) {

                $variant = variant::find($cart->variant->id);

                $variant->update([
                    "stock" => $variant->stock - $cart->qnt
                ]);
            }
        }
    }
}


if (!function_exists('Take_From_Stock_Using_One_details')) {
    function Take_From_Stock_Using_One_details($details)
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



if (!function_exists('Return_Stock_Using_One_details')) {
    function Return_Stock_Using_One_details($details)
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


if (!function_exists('Get_Next_Status')) {
    function Get_Next_Status($order_id)
    {
        $order = order::find($order_id);


        $First = [
            "قيد المراجعة",
            "محاولة تانية",
            "تم المراجعة",
            "تم الالغاء",
            "قيد الانتظار",
        ];

        $status = [
            "قيد الانتظار" => $First,
            "قيد المراجعة" => $First,
            "محاولة تانية" => $First,
            "تم الالغاء" => $First,
            "تم المراجعة" => $First,
            "جاهز للتغليف" => $First
        ];

        return $status[$order->status] ?? [];
    }
}


if (!function_exists('Order_Status_To_Code')) {
    function Order_Status_To_Code($state)
    {
        $status = [
            "قيد المراجعة" => 0,
            "تم المراجعة" => 0,
            "محاولة تانية" => 0,
            "جاري التجهيز للشحن" => 0,
            "تم ارسال الشحن" => 0,
            "تم التوصيل" => 0,
            "مكتمل" => 0,
            'جاهز للتغليف' => 0,
            'طلب استرجاع' => 0,


            "قيد الانتظار" => 1,
            "تم الالغاء" => 1,
            "فشل التوصيل" => 1,

        ];

        return $status[$state];
    }
}


if (!function_exists('Take_From_Stock_Using_Order')) {
    function Take_From_Stock_Using_Order($order_id)
    {

        $order = order::with("details")->findOrFail($order_id);


        foreach ($order->details as $detail) {
            Take_From_Stock_Using_One_details($detail);
        }
    }
}


if (!function_exists('Return_Stock_Using_Order')) {
    function Return_Stock_Using_Order($order_id)
    {

        $order = order::with("details")->findOrFail($order_id);
        foreach ($order->details as $detail) {
            Return_Stock_Using_One_details($detail);
        }
    }
}


if (!function_exists('Change_Order_Status')) {
    function Change_Order_Status($order_id, $status)
    {

        $order = order::findOrFail($order_id);

        if ($order->status == $status) {
            return;
        }

        if (Order_Status_To_Code($order->status) > Order_Status_To_Code($status)) {
            Take_From_Stock_Using_Order($order->id);
        }

        if (Order_Status_To_Code($order->status) < Order_Status_To_Code($status)) {
            Return_Stock_Using_Order($order->id);
        }

        $order->update([
            "status" => trim($status)
        ]);
    }
}



if (!function_exists('isHold')) {
    function isHold($order)
    {

        foreach ($order->details as $detail) {

            if ($detail->variant_id == null) {

                $product = product::find($detail->product_id);

                if ($product->stock < $detail->qnt) {
                    return true;
                }
            } else {
                $variant = variant::find($detail->variant_id);

                if ($variant->stock < $detail->qnt) {
                    return true;
                }
            }
        }

        return false;
    }
}


if (!function_exists('zero_orders')) {
    function zero_orders()
    {
        return [
            "قيد الانتظار",
            "قيد المراجعة",
            "تم المراجعة",
            "محاولة تانية",
            "تم الالغاء",
            'جاهز للتغليف',
            "جاري التجهيز للشحن" ,
            "تم ارسال الشحن"
        ];
    }
}




if (!function_exists('USER_MODERATOR_TAKE_COMISSANTION')) {
    function  USER_TAKE_COMISSANTION($user_id, $data, $order_id)
    {

        $order_user = User::find($user_id);

        if ($order_user->role == "user") {

            $old = commission_history::where("order_id", $order_id)->where("user_id",  $order_user->id)->exists();

            if (!$old) {
                $order_user->update([
                    "wallet" => $order_user->wallet += $data['comissation'] + $data['ponus']
                ]);

                commission_history::create([
                    "order_id" => $order_id,
                    "user_id"  => $order_user->id,
                    "commission" => $data['comissation'] + $data['ponus']
                ]);
            }


            return;
        } else if ($order_user->role == "moderator") {

            $marketer = User::find($order_user->marketer_id);

            $old = commission_history::where("order_id", $order_id)->where("user_id",  $order_user->marketer_id)->exists();

            if (!$old) {

                $marketer->update([
                    "wallet" => $marketer->wallet += $data['comissation'] + $data['ponus']
                ]);


                commission_history::create([
                    "order_id" => $order_id,
                    "user_id"  => $order_user->marketer_id,
                    "commission" => $data['comissation'] + $data['ponus']
                ]);

                $options = $order_user->moderatorOptions;

                $commission =  match ($options->commissionType) {
                    "orderTotal" => $options->commission,
                    "qnt" => $options->commission * ($data['qnt']),
                    "orderTotalPercent" => $options->commission * ($data['comissation'] + $data['ponus']) / 100,
                    "null" => 0,
                };

                $order_user->update(["wallet" => $order_user->wallet += $commission]);


                commission_history::create([
                    "order_id" => $order_id,
                    "user_id"  => $order_user->id,
                    "commission" => $commission
                ]);
            }
        }
    }
}
if (!function_exists('TRADER_TAKE_COMISSANTION')) {
    function  TRADER_TAKE_COMISSANTION($details, $order_id)
    {


        foreach ($details as $detail) {

            $trader = $detail->product->trader;


            $old = commission_history::where("order_id", $order_id)->where("user_id",   $trader->id)->exists();

            if (!$old) {

                $trader->wallet = $trader->wallet +=  ($detail->qnt * $detail->traderPrice);
                $trader->save();

                commission_history::create([
                    "order_id" => $order_id,
                    "user_id"  => $trader->id,
                    "commission" => ($detail->qnt * $detail->traderPrice)
                ]);
            }
        }
    }
}
if (!function_exists('SYSTEM_TAKE_COMISSANTION')) {
    function SYSTEM_TAKE_COMISSANTION($details, $order_id)
    {

        $total = 0;

        $old = commission_system_history::where("order_id", $order_id)->exists();

        if (!$old) {

            foreach ($details as $detail) {

                $admin = user::where("role", 'admin')->first();


                $admin->wallet = $admin->wallet +=  ($detail->qnt * $detail->systemComissation);
                $admin->save();

                $total += ($detail->qnt * $detail->systemComissation);
            }
            commission_system_history::create([
                "order_id" => $order_id,
                "commission" => $total
            ]);
        }
    }
}


if (!function_exists("rollback")) {
    function rollback($order)
    {
        $commission_histories = commission_history::where("order_id", $order->id)->get();

        foreach ($commission_histories as $commission) {

            $user = User::find($commission->user_id);
            $user->update([
                "wallet" => $user->wallet -= $commission->commission
            ]);
            $commission->delete();
        }

        $commission_histories = commission_system_history::where("order_id", $order->id)->first();

        $admin = user::where("role", 'admin')->first();
        $admin->wallet = $admin->wallet -=  ($commission_histories->commission);
        $admin->save();


        $commission_histories->delete();
    }
}


function SUM_IN_ORDER_DETAILS_USING_COLUMNS($orders, $column, $column2)
{

    $total = 0;

    foreach ($orders as $order) {
        $total += $order->details->sum(function ($detail) use ($column, $column2) {
            return $detail->$column * $detail->$column2;
        });
    }

    return $total;
}



if (!function_exists('Return_Stock_Using_One_details_qnt')) {
    function Return_Stock_Using_One_details_qnt($details , $qnt)
    {

        $product = product::find($details->product->id);

        if ($product->stock !== null) {
            $product->update([
                "stock" => $product->stock + $qnt,
            ]);
        }



        if ($details->variant) {

            $variant = variant::find($details->variant->id);;

            if ($variant->stock !== null) {

                $variant->update([
                    "stock" => $variant->stock + $qnt,
                ]);
            }
        }

        return true;
    }
}

<?php

namespace App\Http\Controllers\admin\tools;

use App\Http\Controllers\Controller;
use App\Models\commission_history;
use App\Models\commission_system_history;
use App\Models\order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class toolsControler extends Controller
{

    public function waitingOrders()
    {
        $orders = order::with("details")->where("status", "قيد الانتظار")->select("id", "reference", "status")->get();

        DB::beginTransaction();

        try {
            $updated = [];

            foreach ($orders as $order) {

                if (!isHold($order)) {
                    Change_Order_Status($order->id, "قيد المراجعة");

                    array_push($updated, $order->reference);
                }
            }

            DB::commit();

            $message = " تم تحويل " . count($updated) . "  اوردر ";

            return Redirect::back()->with("success", $message);
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ");
        }
    }
    public function change_order_status(Request $request)
    {
        $data = $request->validate([
            "ids" => "required",
            "status" => "required",
        ]);

        $ids = explode(",", $data["ids"]);

        $ids = array_filter($ids, function ($value) {
            return $value !== "";
        });

        DB::beginTransaction();

        foreach ($ids as $id) {
            try {

                $order = order::where("reference", $id)->first();
                Change_Order_Status($order->id, $data["status"]);

                if ($data["status"] == "مكتمل") {
                    $details = $order->details;
                    $OrderData = getOrderData($details);
                    USER_TAKE_COMISSANTION($order->user_id, $OrderData, $order->id);
                    TRADER_TAKE_COMISSANTION($details, $order->id);
                    SYSTEM_TAKE_COMISSANTION($details, $order->id);
                } else if ($data["status"] == "تم الالغاء" || $data["status"] == "فشل التوصيل") {

                    $commission_histories = commission_history::where("order_id", $order->id)->get();

                    foreach ($commission_histories as $commission) {

                        $user = User::find($commission->user_id);
                        $user->update([
                            "wallet" => $user->wallet -= $commission->commission
                        ]);
                        $commission->delete();
                    }

                    $commission_histories = commission_system_history::where("order_id", $order->id)->first();

                    if(!empty($commission_histories)){

                        $admin = User::where("role", 'admin')->first();
                        $admin->wallet = $admin->wallet -=  ($commission_histories->commission);
                        $admin->save();
                        $commission_histories->delete();
                    }



                }
            } catch (\Throwable $th) {
                return redirect()->back()->with("error", " الاوردر ده مش راضي يتعدل " . $id);
            }
        }

        DB::commit();

        return redirect()->back()->with("success", "تم تغير حالة الطلبات بنجاح");
    }
}

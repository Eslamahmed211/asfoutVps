<?php

namespace App\Http\Controllers\admin\orders;

use App\Exports\required_producst as required_producst_export;
use App\Http\Controllers\Controller;
use App\Models\commission_history;
use App\Models\commission_system_history;
use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ordersActions extends Controller
{
    public function to_turbo(Request $request)
    {

        $data = $request->validate([
            "ids" => "required|string",
            "type" => "required|string|in:turbo,speed,adm",
        ], [
            "ids.required" => "يرجي اختيار اوردرات",
        ]);

        $ids = explode(",", $data["ids"]);

        try {

            foreach ($ids as $reference) {

                DB::beginTransaction();

                $order = order::where("status", "تم المراجعة")->where("reference", $reference)->with("details")->first();
                if ($order == null) {
                    return redirect()->back()->with("error", " الاوردر ده " . " " . $reference . " " . "  مش تم المراجعة ");
                }

                $response = match ($data["type"]) {
                    "turbo" => NEW_ORDER_TERBO($order),
                    "speed" => NEW_ORDER_SPEED($order),
                    "adm" => NEW_ORDER_ADM($order)
                };

                if (is_a($response, 'Illuminate\Http\RedirectResponse')) {
                    return $response;
                }

                DB::commit();
            }

            return redirect()->back()->with("success", "تم الانتهاء");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "هناك خطأ ما");
        }
    }

    public function to_ready($reference)
    {

        try {
            $order = order::where("reference", $reference)->first();
            Change_Order_Status($order->id, "جاري التجهيز للشحن");
            return redirect()->back()->with("success_tostar", "تم تغير حالة الاوردر بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error_tostar", " الاوردر ده مش راضي يتجهز للشحن  ");
        }
    }

    public function toFailed($reference)
    {

        try {
            $order = order::where("reference", $reference)->first();
            Change_Order_Status($order->id, "فشل التوصيل");
            return redirect()->back()->with("success_tostar", "تم تغير حالة الاوردر بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error_tostar", " الاوردر ده مش راضي يتم استرجاعه  ");
        }
    }

    public function change_bulk_orders_status(Request $request)
    {
        $data = $request->validate([
            "ids" => "required|string",
            "status" => "required",
        ], [
            "ids.required" => "يرجي اختيار اوردرات",
        ]);

        $ids = (explode(",", trim($data['ids'])));

        $ids = array_filter($ids, function ($value) {
            return $value !== "";
        });

        $ids = (array_map('trim', $ids));

        DB::beginTransaction();

        foreach ($ids as $reference) {
            try {

                $order = order::where("reference", $reference)->first();
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

                    if (!empty($commission_histories)) {

                        $admin = User::where("role", 'admin')->first();
                        $admin->wallet = $admin->wallet -=  ($commission_histories->commission);
                        $admin->save();
                        $commission_histories->delete();
                    }
                }
            } catch (\Throwable $th) {
                return redirect()->back()->with("error", " الاوردر ده مش راضي يتعدل " . $reference);
            }
        }

        DB::commit();

        return redirect()->back()->with("success", "تم الانتهاء");
    }

    public function to_return(Request $request)
    {

        $order = order::where("status", 'طلب استرجاع')->where("reference", $request->reference)->first();
        if ($order == null) {
            return redirect()->back()->with("error", " الاوردر ده " . " " . $request->reference . " " . "  مش تم طلب استرجاع ");
        }
        $ids = explode(",", $request->ids);

        $ids = array_count_values($ids);

        DB::beginTransaction();

        foreach ($ids as $id => $qnt) {
            $detail = order_detail::with("product", "variant")->find($id);
            Return_Stock_Using_One_details_qnt($detail, $qnt);
            if ($detail->qnt > $qnt) {
                $detail->update([
                    "qnt" => $detail->qnt - $qnt,
                ]);
            } else {
                $detail->delete();
            }
        }

        Change_Order_Status($order->id, "تم التوصيل");

        DB::commit();

        return redirect()->back()->with("success", "تم استراجع الاوردر جزئيا بنجاح");
    }

    public function required_producst($references)
    {
        $references = explode(",", $references);

        $data = [];

        $i = 0;

        foreach ($references as $reference) {
            $order = order::with(["details" => function ($q) {
                $q->with("product", "variant");
            }])->where("reference", $reference)->first();

            foreach ($order->details as $detail) {
                array_push($data, [
                    "product_name" => $detail->product->name,
                    "variant_name" => $detail->variant != null ? variantName($detail->variant->id) : "",
                    "qnt" => $detail->qnt,
                ]);
            }
        }

        $resultArray = [];
        foreach ($data as $item) {
            $key = $item["product_name"] . "_" . $item["variant_name"];

            if (!isset($resultArray[$key])) {
                $resultArray[$key] = $item;
            } else {
                $resultArray[$key]["qnt"] += $item["qnt"];
            }
        }

        $resultArray = array_values($resultArray);

        return Excel::download(new required_producst_export($resultArray), "required_producst.xlsx");
    }
}

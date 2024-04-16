<?php

namespace App\Http\Controllers\admin\orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\cart\CheckOutRequest;

use App\Models\commission_history;
use App\Models\commission_system_history;
use App\Models\deliveryPrice;
use App\Models\order;
use App\Models\orderNotes;
use App\Models\order_detail;
use App\Models\product;
use App\Models\User;
use App\Traits\orders\addMoreToOrder;
use App\Traits\orders\ReplaceProductInOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OrderLogicController extends Controller
{

    use addMoreToOrder;
    use ReplaceProductInOrder;
    public $order;
    public $details;

    public function tryAgian(order $order)
    {

        if ($order->status != "محاولة تانية") {
            return redirect()->back()->with("error", "الاوردر ده مش محاولة تانية");
        }

        try {

            $log = [
                "date" => Carbon::now(),
                "user" => auth()->id(),
                "type" => "message",
                "message" => "تم عمل محاولة تانية للتواصل مع العميل",
            ];

            $logs = array_merge([$log], $order->logs ?? []);
            usort($logs, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });

            $order->logs = $logs;

            $order->save();

            return redirect()->back()->with("success", "تمت اضافة محاولة تواصل مع العميل");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "هناك خطأ ما");
        }
    }
    public function logs_Store(Request $request, order $order)
    {

        try {

            $log = [
                "date" => Carbon::now(),
                "user" => auth()->id(),
                "type" => "message",
                "message" => $request->message,
            ];

            $logs = array_merge([$log], $order->logs ?? []);
            usort($logs, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });

            $order->logs = $logs;

            $order->save();


            $user = User::find($order->user_id);
            $message =  $request->message;
            user_send_message($user, $message, "order_note", $order->id);

            if ($user->role != "user") {
                $user = User::find($user->marketer_id);
                user_send_message($user, $message, "order_note", $order->id);
            }





            return redirect()->back()->with("success", "تمت اضافة الرسالة بنجاح ");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "هناك خطأ ما");
        }
    }

    public function GetOrderDetailsAjax($id)
    {
        try {
            $data = GetOrderDetailsAjax($id);
            return json(["status" => "success", "data" => $data]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "message" => "هناك خطأ ما"]);
        }
    }
    public function GetOrderDetailsAjaxReference($id)
    {
        try {
            $data = GetOrderDetailsAjaxReference($id);
            return json(["status" => "success", "data" => $data]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "message" => "هناك خطأ ما"]);
        }
    }


    public function update(CheckOutRequest $request, order $order)
    {

        $data = $request->validated();

        $d = getOrderData($order->details);

        if (!is_null($data["get"]) && auth()->user()->role != "admin") {
            if ($data["get"] > $d['comissation'] + $d['ponus']) {
                return redirect()->back()->with("error", " لا يمكن عمل خصم اكبر من  اجمالي عمولة المسوق")->withInput();
            }
        }

        DB::beginTransaction();

        try {

            $city = deliveryPrice::find($data["city"]);

            $order->update([
                "clientName" => $data["clientName"],
                "clientPhone" => $data["clientPhone"],
                "clientPhone2" => $data["clientPhone2"],
                "city" => $city->name,
                "address" => $data["address"],
                "notes" => $data["notes"],
                "notesBosta" => $data["notesBosta"],
                "delivery_price" => $city->delivery_price,
                "return_price" => $city->return_price,
                "get" => $data["get"],
                "take" => $data["take"],
            ]);

            if (isset($data["status"])) {
                Change_Order_Status($order->id, $data["status"]);
            }

            DB::commit();

            return redirect()->back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "لم يتم التعديل")->withInput();
        }
    }

    public function addMoreToOrder(Request $request)
    {

        DB::beginTransaction();

        $data = $this->validation($request);

        if (is_a($data, 'Illuminate\Http\JsonResponse')) {
            return $data;
        }

        $product = product::withCount("variants")->find($data["product_id"]);

        $security = $this->security($product, $data);

        if (is_a($security, 'Illuminate\Http\JsonResponse')) {
            return $security;
        }

        $this->order = order::find($data["add_new_product"]);

        if (!$this->order) {
            return response()->json(["status" => "CoustomErrors", "message" => "الاوردر ده غير موجود"]);
        }

        if (isset($data["values"])) {

            return $this->Variant($product, $data);
        } else {

            return $this->noVariant($product, $data);
        }
    }

    public function replace(Request $request, $id)
    {

        $data = $this->validation($request);

        if (is_a($data, 'Illuminate\Http\JsonResponse')) {
            return $data;
        }

        $product = product::withCount("variants")->find($data["product_id"]);

        $security = $this->security($product, $data);
        if (is_a($security, 'Illuminate\Http\JsonResponse')) {
            return $security;
        }

        $this->details = order_detail::with("product", "variant", 'order')->findOrFail($id);

        if (!$this->details->order) {
            return response()->json(["status" => "CoustomErrors", "message" => "الاوردر ده غير موجود"]);
        }

        if (isset($data["values"])) {
            return $this->ReplaceVariant($product, $data);
        } else {

            return $this->ReplaceNoVariant($product, $data);
        }
    }

    public function destroyDetails(Request $request)
    {

        $data = $request->validate([
            "detail_id" => "required|string",
        ]);

        $details = order_detail::with("product", "variant")->with(["order" => function ($q) {
            $q->withCount("details");
        }])->findOrFail($data['detail_id']);

        if ($details->order->details_count == 1) {
            return redirect()->back()->with("error", "لا يمكن ترك الاوردر فارغ");
        }

        try {

            DB::beginTransaction();

            if ($details->order->status != "قيد الانتظار") {
                retuenStock($details);
            }

            $details->delete();

            DB::commit();

            return redirect()->back()->with("success", "تم ازالة المنتج بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "فشل اثناء ازالة المنتج");
        }
    }

    public function store_notes(Request $request)
    {
        $data = $request->validate([
            "notes" => "required|string",
            "order_id" => "required|string",
        ]);

        try {

            orderNotes::create([
                "user_id" => auth()->user()->id,
                "order_id" => $data["order_id"],
                "message" => $data["notes"],
            ]);

            return Redirect::back()->with("success", "تم الاضافة الملاحظة بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
        }
    }

    public function update_notes(Request $request)
    {

        $data = $request->validate([
            "message" => "required|string",
            "note_id" => "required|string",
        ]);

        try {

            $note = orderNotes::findOrfail($data["note_id"]);

            if (!CanAccessComment($note->user_id)) {
                abort(403);
            } else {
                $note->update([
                    "message" => $data["message"],
                ]);
            }

            return Redirect::back()->with("success", "تم تعديل الملاحظة بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم التعديل")->withInput();
        }
    }

    public function delete_notes($id)
    {

        try {

            $note = orderNotes::findOrfail($id);

            if (!CanAccessComment($note->user_id)) {
                abort(403);
            } else {
                $note->delete();
            }

            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }

    // public function checkTryAgain()
    // {

    //     $time = now()->subHours(settings('waiting_orders'));

    //     $orders = order::where("status", "محاولة تانية")->where("created_at", '>=', $time)->select("id", "reference")->get();

    //     DB::beginTransaction();

    //     try {
    //         $updated = [];

    //         foreach ($orders as $order) {

    //             Change_Order_Status($order->id, "لم يتم تأكيد الطلب");

    //             array_push($updated, $order->reference);
    //         }

    //         DB::commit();

    //         $message = " تم تحويل " . count($updated) . "  اوردر ";

    //         return Redirect::back()->with("success", $message);
    //     } catch (\Throwable $th) {
    //         return Redirect::back()->with("error", "خطأ");
    //     }
    // }
    function rollback($reference)
    {
        $order = order::with("details")->whereIn("status", ["تم التوصيل", "تم التوصيل شحن يدوي"])->where("reference", $reference)->firstOrFail();

        try {
            DB::beginTransaction();

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

            DB::commit();

            return redirect()->back()->with("success", "تم استرجاع العمولات بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "هناك خطأ ما")->withInput();
        }
    }
}

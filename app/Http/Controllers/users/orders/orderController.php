<?php

namespace App\Http\Controllers\users\orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\cart\CheckOutRequest;
use App\Http\Resources\orderResource;
use App\Models\commission_history;
use App\Models\deliveryPrice;
use App\Models\order;
use App\Models\order_delivery_notes;
use App\Models\order_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class orderController extends Controller
{
    function index()
    {


        $orders =  order::with("details")->where("user_id", auth()->user()->id)->forUserAndModerators()
            ->orderBy("id", 'desc')->simplePaginate(25);

        $cities = deliveryPrice::orderBy("order", "Asc")->get();




        return view("users/orders/index", compact("orders", 'cities'));
    }

    function search(Request $request)
    {
        // ;

        // dd($request->all());

        $orders =  order::with("details");
        $date = '';


        !empty($request->reference) ? $orders = $orders->where("reference",  "{$request->reference}") : "";
        !empty($request->name) ?  $orders = $orders->where("clientName", "like", "{$request->name}%") : "";
        !empty($request->mobile) ?  $orders = $orders->where("clientPhone", "like", "%{$request->mobile}%")->orWhere("clientPhone2", "like", "{$request->mobile}%") : "";
        !empty($request->city_name) ? $orders = $orders->where("city", "like", "{$request->city_name}%") : "";


        if (!empty($request->status)) {
            if ($request->status == "ارسال شحن يدوي") {

                $orders = $orders->whereIn("status",  ["ارسال شحن يدوي", "تم التوصيل شحن يدوي معلق", "فشل التوصيل يدوي معلق", "مؤجل تسليمها شحن يدوي"]);
            } else {
                $orders = $orders->where("status",  "{$request->status}");
            }
        }





        match ($request->withModerators) {

            "yes" => $orders = $orders->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhere('user_id', auth()->user()->moderators->modelKeys());
            }),

            "no" => $orders = $orders->where("user_id", auth()->user()->id),

            "only" => $orders = $orders->where(function ($query) {
                $query->Where('user_id', auth()->user()->moderators->modelKeys());
            }),

            default => "",
        };

        if (in_array($request->withModerators, auth()->user()->moderators->modelKeys())) {
            $orders = $orders->where("user_id", $request->withModerators);
        } else if (!in_array($request->withModerators, ['yes', 'no', 'only'])) {
            $orders = $orders->where("user_id", auth()->user()->id);
        }




        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ",   $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";
        }

        $date != ""  ? $orders = $orders->whereDate("created_at", '>=', $startDate) : "";


        isset($endDate) && !empty($endDate)  ? $modorderserators = $orders->whereDate("created_at", '<=', $endDate) : "";




        $orders = $orders->orderBy("id", 'desc')->simplePaginate(25);
        $cities = deliveryPrice::orderBy("order", "Asc")->get();
        return view("users/orders/index", compact("orders", 'cities'));
    }



    function GetOrderDetailsAjax($id)
    {

        try {

            $order = Order::with(["details.product" => function ($q) {
                $q->withTrashed();
            }])->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhere('user_id', auth()->user()->moderators->modelKeys());
            })->find($id);


            $data =  new orderResource($order);


            return json(["status" => "success", "data" => $data]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "message" => "هناك خطأ ما"]);
        }
    }


    function edit(order $order)
    {

        if ($order->user_id != auth()->user()->id && !MyModeratorOrder($order)) {
            abort(404);
        }

        if ($order->status != "قيد المراجعة" && $order->status != 'قيد الانتظار') {
            abort(404);
        }

        $cities = deliveryPrice::orderBy("order", "Asc")->get();

        return view("users/orders/edit", compact("order", 'cities'));
    }

    function update(CheckOutRequest $request, order $order)
    {

        if ($order->user_id != auth()->user()->id && !MyModeratorOrder($order)) {
            abort(404);
        }

        if ($order->status != "قيد المراجعة" && $order->status != 'قيد الانتظار') {
            abort(404);
        }

        $data = $request->validated();

        try {

            $city =  deliveryPrice::find($data["city"]);


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

            ]);

            return redirect()->back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "لم يتم التعديل")->withInput();
        }
    }

    function show(order $order)
    {


        if ($order->user_id != auth()->user()->id && !MyModeratorOrder($order)) {
            abort(404);
        }



        return view("users/orders/show", compact("order"));
    }

    function destroyDetails(Request $request)
    {

        $data = $request->validate([
            "detail_id" => "required|string"
        ]);


        $details =  order_detail::with("product", "variant")->with(["order" => function ($q) {
            $q->withCount("details");
        }])->findOrFail($data['detail_id']);

        canAccsessByDetails($details);



        // if ($details->order->user_id != auth()->user()->id || $details->order->status != "قيد المراجعة" ) {
        //   abort(404);
        // }

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

    function cancel(order $order)
    {

        if ($order->user_id != auth()->user()->id && !MyModeratorOrder($order)) {
            abort(404);
        }

        if ($order->status != "قيد المراجعة" && $order->status != "قيد الانتظار") {
            abort(404);
        }

        try {

            DB::beginTransaction();

            $details = $order->details;

            if ($order->status == "قيد المراجعة") {
                foreach ($details as $detail) {
                    retuenStock($detail);
                }
            }

            $order->update([
                "status" => "تم الالغاء"
            ]);

            DB::commit();

            return redirect()->back()->with("success", "تم الغاء الاوردر بنجاح");
        } catch (\Throwable $th) {

            return redirect()->back()->with("error", "لم يتم الغاء الاوردر")->withInput();
        }
    }

    function statusLogs(order $order)
    {
        return view("users/orders/statusLogs", compact("order"));
    }



    function order_commissions()
    {

        $all = commission_history::with(["order", "user"])->AuthAndModerators()->orderBy("id", "desc");

        $all_count = $all->get();

        $all = $all->simplepaginate(50);

        return view("users/orders/commission_histories", compact("all" , 'all_count'));
    }

    function order_commissions_search(Request $request)
    {

        $data =  $request->validate([
            "user_id" => "nullable|exists:users,id",
            "track" => "nullable",
            "date" => "nullable",
        ]);

        $all = commission_history::with(["order", "user"]);

        match ($request->withModerators) {

            "yes" => $all = $all->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhere('user_id', auth()->user()->moderators->modelKeys());
            }),

            "no" => $all = $all->where("user_id", auth()->user()->id),

            "only" => $all = $all->where(function ($query) {
                $query->Where('user_id', auth()->user()->moderators->modelKeys());
            }),

            default => "",
        };

        if (in_array($request->withModerators, auth()->user()->moderators->modelKeys())) {
            $all = $all->where("user_id", $request->withModerators);
        } else if (!in_array($request->withModerators, ['yes', 'no', 'only'])) {
            $all = $all->where("user_id", auth()->user()->id);
        }

        if (!empty($data["date"])) {

            $date = $data["date"];
            $dates = explode(" to ", $date);
            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";

            if ($data["track"] == "order") {

                $all = $all->whereHas("order", function ($q) use ($startDate, $endDate) {
                    $q->whereDate("created_at", '>=', $startDate);
                    isset($endDate) && !empty($endDate) ? $q->whereDate("created_at", '<=', $endDate) : $q->whereDate("created_at", '<=', $startDate);
                });
            } else {
                $all = $all->whereDate("created_at", '>=', $startDate);
                isset($endDate) && !empty($endDate) ? $all = $all->whereDate("created_at", '<=', $endDate) :  $all = $all->whereDate("created_at", '<=', $startDate);
            }
        }
        $all_count = $all->get();

        $all = $all->orderBy("id", "desc")->simplepaginate(50);


        return view("users/orders/commission_histories",  get_defined_vars());
    }
}

<?php

namespace App\Http\Controllers\trader;

use App\Http\Controllers\Controller;
use App\Models\commission_history;
use App\Models\invoice;
use App\Models\order;
use App\Models\product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class traderController extends Controller
{
    public function index()
    {

        $all = order::traderOrders()->count();

        $pending = Order::countByStatus("قيد المراجعة");
        $reviewedCount = Order::countByStatus("تم المراجعة");
        $retryCount = Order::countByStatus("محاولة تانية");
        $canceled = Order::countByStatus("تم الالغاء");
        $Shipping = Order::countByStatus("جاري التجهيز للشحن") + Order::countByStatus('جاهز للتغليف');
        $shippedCount = Order::countByStatus("تم ارسال الشحن");
        $return = Order::countByStatus('طلب استرجاع');
        $deliveryFailureCount = Order::countByStatus("فشل التوصيل");
        $deliveredCount = Order::countByStatus("تم التوصيل");
        $done = Order::countByStatus("مكتمل");
        $waitingCount = Order::countByStatus("قيد الانتظار");

        $total_commation = commission_history::where("user_id", auth()->id())->sum("commission");

        if (($deliveredCount + $deliveryFailureCount + $done) != 0) {
            $precent = number_format((($deliveredCount + $done) / ($deliveredCount + $deliveryFailureCount + $done)) * 100, 2);
        } else {
            $precent = 0;
        }

        return view("trader/home", get_defined_vars());
    }

    public function product_percent(Request $request)
    {

        if ($request->type == "search") {

            $Products = Product::withCount("variants")->with("details")->where('trader_id', auth()->id());
            !empty($request->product_id) ? $Products->where("id", $request->product_id) : "";
            $Products = $Products->get();
        } else {
            $Products = Product::withCount("variants")->with("details")->where('trader_id', auth()->id())->get();
        }

        $all = [];

        foreach ($Products as $product) {

            $options = [];

            if (!empty($request->date)) {

                $date = $request->date;

                $dates = explode(" to ", $date);

                $startDate = $dates[0];
                $endDate = $dates[1] ?? "";

                array_push($options, ["date" => ["startDate" => $startDate, "endDate" => $endDate]]);
            }

            $orders_Deliverd = GET_ORDERS_BY_DETAILS($product->details->pluck('id')->toArray(), ["تم التوصيل ", "مكتمل"], $options);

            $Deliverd_Qnt = SUM_IN_ORDER_DETAILS($orders_Deliverd, "qnt");

            $FAILAD_Deliverd = GET_ORDERS_BY_DETAILS($product->details->pluck('id')->toArray(), ["فشل التوصيل"], $options);

            $FAILAD_Qnt = SUM_IN_ORDER_DETAILS($FAILAD_Deliverd, "qnt");

            $total = count($orders_Deliverd) + count($FAILAD_Deliverd);

            array_push($all, [
                "product" => $product->name,
                "product_id" => $product->id,
                "variants_count" => $product->variants_count,
                "ordersDeliverdCount" => $orders_Deliverd->count(),
                "DeliverdQnt" => $Deliverd_Qnt,
                "DeliveryFailureCount" => $FAILAD_Deliverd->count(),
                "DeliveryFailureQnt" => $FAILAD_Qnt,
                "precent" => GET_PRODUCT_PERCENT($orders_Deliverd, $total),
            ]);
        }

        $Products = Product::withCount("variants")->with("details")->where('trader_id', auth()->id())->get();

        return view("trader/product_percent", compact("all", "Products"));
    }

    public function variant_percent($id)
    {

        $product = Product::with("variants")->where('trader_id', auth()->id())->findOrFail($id);

        if (count($product->variants) == 0) {
            abort(404);
        }

        $all = [];

        foreach ($product->variants as $variant) {

            $orders_Deliverd = GET_ORDERS_BY_DETAILS($variant->details->pluck('id')->toArray(), ["تم التوصيل ", "مكتمل"]);

            $Deliverd_Qnt = SUM_IN_ORDER_DETAILS($orders_Deliverd, "qnt");

            $FAILAD_Deliverd = GET_ORDERS_BY_DETAILS($variant->details->pluck('id')->toArray(), ["فشل التوصيل"]);

            $FAILAD_Qnt = SUM_IN_ORDER_DETAILS($FAILAD_Deliverd, "qnt");

            $total = count($orders_Deliverd) + count($FAILAD_Deliverd);

            array_push($all, [
                "product" => variantName($variant->id),
                "product_id" => $variant->id,
                "variants_count" => 0,
                "ordersDeliverdCount" => $orders_Deliverd->count(),
                "DeliverdQnt" => $Deliverd_Qnt,
                "DeliveryFailureCount" => $FAILAD_Deliverd->count(),
                "DeliveryFailureQnt" => $FAILAD_Qnt,
                "precent" => GET_PRODUCT_PERCENT($orders_Deliverd, $total),
            ]);
        }

        return view("trader/product_percent", compact("all", "product"));
    }

    public function products()
    {
        $products = Product::withCount("variants")->with("details")->where('trader_id', auth()->id())->get();
        return view("trader/products", compact('products'));
    }

    public function show($id)
    {

        $product = product::with("variants")->with("details")->where('trader_id', auth()->id())->findOrFail($id);

        $invoices = invoice::with(["items" => function ($q) use ($product) {
            $q->where("product_id", $product->id);
        }])->whereHas("items", function ($q) use ($product) {
            $q->where("product_id", $product->id);
        })->where("traderId", auth()->id())->get();

        $TOTAL_COLLECTION = GET_TOTAL_COLLECTION($invoices);

        $ids = $product->details->pluck('id')->toArray();

        if (count($product->variants) == 0) {
            $status = GET_PRODUCT_ALL_STATUS_QNT($ids);

            return view("trader/show_no_variant", compact("product", "TOTAL_COLLECTION", "status"));
        } else {
            $product_status = GET_PRODUCT_ALL_STATUS_QNT($ids);

            $status = GET_VARIANTS_ALL_STATUS_QNT($product->variants);

            return view("trader/show_variant", compact("TOTAL_COLLECTION", "product", "status", "product_status"));
        }
    }

    public function orders()
    {

        $orders = order::TraderOrdersDetails()->orderBy("created_at", "desc")->simplePaginate(25);
        $Products = Product::with("details")->where('trader_id', auth()->id())->get();
        return view("trader/orders", compact("orders", 'Products'));
    }

    public function search(Request $request)
    {

        $orders = order::with(["details" => function ($q) use ($request) {
            $q->whereHas("product", function ($q) use ($request) {
                $q->where("trader_id", auth()->id());
                if (!empty($request->product_id)) {
                    $q->where("id", $request->product_id);
                }
            });
        }])->whereHas("details.product", function ($q) use ($request) {
            $q->where("trader_id", auth()->id());
            if (!empty($request->product_id)) {
                $q->where("id", $request->product_id);
            }
        });

        if (!empty($request->status)) {

                $orders = $orders->where("status", "{$request->status}");

        }

        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ", $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";

            $date != "" ? $orders = $orders->whereDate("created_at", '>=', $startDate) : "";
            isset($endDate) && !empty($endDate) ? $orders = $orders->whereDate("created_at", '<=', $endDate) : $orders = $orders->whereDate("created_at", '<=', $startDate);
        }

        $orderTotal = $orders->count();

        $totalQnt = 0;

        $total = 0;

        $all = $orders->get();

        foreach ($all as $order) {
            $data = getOrderData($order->details);
            $total += $data['traderPrice'];
            $totalQnt += $data['qnt'];
        }

        $orders = $orders->orderBy("created_at", "desc")->simplePaginate(25);

        $Products = Product::with("details")->where('trader_id', auth()->id())->get();
        return view("trader/orders", get_defined_vars());
    }

    public function invoices()
    {
        $invoices = invoice::where("traderId", auth()->id())->orderBy("id", "desc")->simplePaginate(25);
        return view("trader/invoices", compact('invoices'));
    }

    public function invoices_search(Request $request)
    {

        $invoices = invoice::with("items")->where("traderId", auth()->id())->orderBy("id", "desc");

        !empty($request->InvoiceName) ? $invoices = $invoices->where("InvoiceName", "like", "%{$request->InvoiceName}%") : "";
        !empty($request->type) ? $invoices = $invoices->where("type", "{$request->type}") : "";

        $date = '';

        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ", $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? Carbon::now();
        }

        $date != "" ? $invoices = $invoices->whereBetween("created_at", [$startDate, $endDate]) : "";

        $all = $invoices->get();

        $qnt = 0;
        $price = 0;

        foreach ($all as $invoice) {
            foreach ($invoice->items as $item) {

                if ($invoice->type == "مشتريات") {
                    $qnt += $item->qnt;
                    $price += $item->total;
                } else {
                    $qnt -= $item->qnt;
                    $price -= $item->total;
                }
            }
        }

        $total = count($all);

        $invoices = $invoices->simplePaginate(25);

        return view("trader/invoices", compact('invoices', "qnt", "price", "total"));
    }

    public function invoice_show(invoice $invoice)
    {

        if ($invoice->traderId != auth()->id()) {
            abort(404);
        }

        return view("admin/invoices/show", compact('invoice'));
    }
}

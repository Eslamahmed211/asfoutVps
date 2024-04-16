<?php

namespace App\Http\Controllers\admin\orders;

use App\Exports\reports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\commission_history;
use App\Models\deliveryPrice;
use App\Models\expenses_and_commissions;
use App\Models\order;
use App\Models\orderNotes;
use App\Models\order_detail;
use App\Models\product;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderViewsController extends Controller
{

    public function AllOrders()
    {

        $cities = deliveryPrice::orderBy("order", "Asc")->get();

        $orders = order::with("details")->orderBy("id", 'desc');

        $count = $orders->count();

        $orders = $orders->simplePaginate(150);

        return view("admin/orders/index", compact("orders", 'cities', 'count'));
    }

    public function search(Request $request)
    {

        $orders = order::with("details");
        $date = '';

        if (!empty($request->reference)) {

            $filteredArray = (explode(",", trim($request->reference)));

            $filteredArray = array_filter($filteredArray, function ($value) {
                return $value !== "";
            });

            $filteredArray = (array_map('trim', $filteredArray));

            $orders = $orders->whereIn("reference", $filteredArray);
        }


        if (!empty($request->trackingNumber)) {

            $filteredArray = (explode(",", trim($request->trackingNumber)));

            $filteredArray = array_filter($filteredArray, function ($value) {
                return $value !== "";
            });

            $filteredArray = (array_map('trim', $filteredArray));

            $orders = $orders->whereIn("trackingNumber", $filteredArray);
        }


        // !empty($request->trackingNumber) ? $orders = $orders->where("trackingNumber", "{$request->trackingNumber}") : "";
        !empty($request->name) ? $orders = $orders->where("clientName", "like", "{$request->name}%") : "";

        !empty($request->mobile) ? $orders = $orders->where(function ($query) use ($request) {
            $query->where("clientPhone", "like", "%{$request->mobile}%")
                ->orWhere("clientPhone2", "like", "%{$request->mobile}%");
        }) : "";

        $cites = $request->city_name ?? [];

        foreach ($cites as $key => $value) {

            if ($value == "كل") {
                unset($cites[$key]);
            }
        }

        !empty($cites) ? $orders = $orders->whereIn("city", $cites) : "";

        !empty($request->status) ? $orders = $orders->where("status", "{$request->status}") : "";

        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ", $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";

            if ($request->track == "الطلبات") {
                $date != "" ? $orders = $orders->whereDate("created_at", '>=', $startDate) : "";
                isset($endDate) && !empty($endDate) ? $orders = $orders->whereDate("created_at", '<=', $endDate) : $orders = $orders->whereDate("created_at", '<=', $startDate);
            } else {
                $date != "" ? $orders = $orders->whereDate("delivery_at", '>=', $startDate) : "";
                isset($endDate) && !empty($endDate) ? $orders = $orders->whereDate("delivery_at", '<=', $endDate) : $orders = $orders->whereDate("delivery_at", '<=', $startDate);
            }
        }

        if (!empty($request->user_id)) {
            $user = User::find($request->user_id);

            $orders = $orders->where(function ($query) use ($request, $user) {
                $query->where("user_id", $request->user_id)
                    ->orWhereIn('user_id', $user->moderators->modelKeys());
            });
        } else {
            $user = null;
        }

        $orderTotal = $orders->count();

        $systemComissation = 0;
        $comissation = 0;
        $total = 0;

        match ($request->has_track) {
            "نعم" => $orders = $orders->where("trackingNumber", "!=", null),
            "لا" => $orders = $orders->where("trackingNumber", null),
            default => ""
        };

        $count = $orders->count();

        if ($request->type == "excel") {
            $all = $orders->get();

            foreach ($all as $order) {
                $data = getOrderData($order->details);
                $systemComissation += $data['systemComissation'];
                $comissation += $data['comissation'] + $data['ponus'];
                $total += $data['total'];
            }
            $orders = $orders->orderBy("id", 'desc')->get();
            return Excel::download(new OrderExport($orders), "orders.xlsx");
        } else {

            $orders = $orders->orderBy("id", 'desc')->simplePaginate(150);
            $cities = deliveryPrice::orderBy("order", "Asc")->get();
            return view("admin/orders/index", compact("orders", 'cities', 'user', 'count'));
        }
    }

    public function show(order $order)
    {


        $cities = deliveryPrice::orderBy("order", "Asc")->get();

        $notes = orderNotes::with("user")->where('order_id', $order->id)->orderBy("id", "desc")->get();

        return view("admin/orders/show", compact("order", 'cities', "notes"));
    }

    public function statusLogs(order $order)
    {
        return view("admin/orders/statusLogs", compact("order"));
    }

    public function notes(order $order)
    {

        $notes = orderNotes::with("user")->where('order_id', $order->id)->orderBy("id", "desc")->get();

        return view("admin/orders/notes", compact("order", 'notes'));
    }

    public function productsPage()
    {

        $products = product::with(['firstImg'])->where('show', "1")->select("id", 'name', 'slug', "price", 'stock', 'systemComissation')->orderBy("id", "desc")->simplePaginate(12);

        $categories = category::orderBy("id", "desc")->get();
        return view('users/products', compact('products', 'categories'));
    }
    public function productsSearch(Request $request)
    {

        $products = product::with(['firstImg'])->where('show', "1")->select("id", 'name', 'slug', "price", 'stock', 'systemComissation');

        !empty($request->name) ? $products = $products->where("name", "like", "%{$request->name}%") : "";

        !empty($request->category_id) ? $products = $products->whereHas('categories', function ($query) use ($request) {
            $query->where("category_id", "{$request->category_id}");
        }) : "";

        match ($request->order) {
            "new" => $products = $products->orderBy("id", "desc"),
            "big_price" => $products = $products->orderBy("price", "desc"),
            "low_price" => $products = $products->orderBy("price", "asc"),
            default => "",
        };

        match ($request->fav) {
            "yes" => $products = $products->whereHas('favourites', function ($query) {
                $query->where('user_id', auth()->user()->id);
            }),
            default => "",
        };

        $products = $products->orderBy("id", "desc")->simplePaginate(12);
        $categories = category::orderBy("id", "asc")->get();
        return view('users/products', compact('products', 'categories'));
    }

    public function showProductPage($slug)
    {

        $product = Product::where("slug", $slug)->where("deleted_at", null)->where("show", '1')->first();

        if (!$product) {
            abort(404);
        }

        return view('users/showProduct', compact('product'));
    }

    public function edit($id)
    {

        $details = order_detail::with("product", "variant", 'order')->findOrFail($id);

        $valueIds = [];

        if ($details->variant) {
            foreach ($details->variant->values as $value) {
                $valueIds[] = $value->id;
            }
        }

        return view("admin/orders/detailsEdit", compact("details", 'valueIds'));
    }

    public function order_commissions()
    {

        $all = commission_history::with(["order", "user"])->whereHas("user", function ($q) {
            $q->where("role", "user");
        })->orderBy("id", "desc")->simplepaginate(50);

        return view("admin/orders/commission_histories", compact("all"));
    }

    public function order_commissions_search(Request $request)
    {

        $data = $request->validate([
            "user_id" => "nullable|exists:users,id",
            "track" => "nullable",
            "date" => "nullable",
        ]);

        $all = commission_history::with(["order", "user"])->whereHas("user", function ($q) use ($data) {
            $q->where("role", "user");
            !empty($data["user_id"]) ? $q->where("id", $data["user_id"]) : "";
        });

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
                isset($endDate) && !empty($endDate) ? $all = $all->whereDate("created_at", '<=', $endDate) : $all = $all->whereDate("created_at", '<=', $startDate);
            }
        }

        $all = $all->orderBy("id", "desc")->simplepaginate(50);

        if (!empty($data["user_id"])) {
            $user = user::find($data["user_id"]);
        }

        return view("admin/orders/commission_histories", get_defined_vars());
    }

    public function print($references)
    {
        $references = explode(",", $references);

        $orders = order::with("details")->whereIn("reference", $references)->get();

        return view('admin/orders/print', compact('orders'));
    }

    public function ExpensesAndCommissionsHistory(Request $request)
    {

        $all = expenses_and_commissions::with(["user"]);

        !empty($request->user_id) ? $all->where("user_id", $request->user_id) : "";
        !empty($request->type) ? $all->where("type", $request->type) : "";

        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ", $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";

            $date != "" ? $all = $all->whereDate("created_at", '>=', $startDate) : "";
            isset($endDate) && !empty($endDate) ? $all = $all->whereDate("created_at", '<=', $endDate) : $orders = $all->whereDate("created_at", '<=', $startDate);
        }

        $all = $all->orderBy("id", "desc")->get();

        $minus = $all->where("type", "خصم")->sum("commission");
        $plus = $all->where("type", "اضافة")->sum("commission");

        $user = User::find($request->user_id);

        return view("admin/orders/ExpensesAndCommissionsHistory", compact('all', "minus", 'plus', 'user'));
    }
}

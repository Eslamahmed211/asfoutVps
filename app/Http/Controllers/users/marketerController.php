<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\ads;
use App\Models\category;
use App\Models\commission_history;
use App\Models\order;
use App\Models\product;
use App\Models\products_favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class marketerController extends Controller
{


    function home()
    {
        $ads = ads::where('show', 'show')->get();

        $all = order::AuthAndModerators()->count();
        $pending = Order::AuthAndModerators()->where("status", "قيد المراجعة")->count();
        $reviewedCount = Order::AuthAndModerators()->where("status", "تم المراجعة")->count();
        $retryCount = Order::AuthAndModerators()->where("status", "محاولة تانية")->count();
        $canceled = Order::AuthAndModerators()->where("status", "تم الالغاء")->count();
        $Shipping = Order::AuthAndModerators()->whereIn("status", ["جاري التجهيز للشحن"  , 'جاهز للتغليف'])->count();
        $shippedCount = Order::AuthAndModerators()->where("status", "تم ارسال الشحن")->count() ;

        $return_request =  Order::AuthAndModerators()->where("status", "طلب استرجاع")->count();

        $deliveryFailureCount = Order::AuthAndModerators()->where("status", "فشل التوصيل")->count();
        $deliveredCount = Order::AuthAndModerators()->where("status", "تم التوصيل")->count();
        $done = Order::AuthAndModerators()->where("status", "مكتمل")->count();
        $waitingCount = Order::AuthAndModerators()->where("status", "قيد الانتظار")->count();

        $total_commation = commission_history::where("user_id" , auth()->id())->sum("commission");


        if (($deliveredCount + $deliveryFailureCount + $done ) != 0) {
            $precent =  number_format((($deliveredCount + $done) / ($deliveredCount + $deliveryFailureCount + $done ))  * 100, 2);
        }
        else{
            $precent = 0;
        }






        return view('users/home', get_defined_vars());

    }


    function productsPage()
    {

        $products =
            product::with(['firstImg', 'favourites' => function ($q) {
                $q->where("user_id", auth()->user()->id);
            }])->where('show', "1")->select("id", 'name', 'slug', "price", 'stock', 'systemComissation')->orderBy("id", "desc")->simplePaginate(12);

        $categories = category::orderBy("id", "desc")->get();
        return view('users/products', compact('products', 'categories'));
    }

    function productsSearch(Request $request)
    {

        $products = product::with(['firstImg', 'favourites' =>  function ($q) {
            $q->where("user_id", auth()->user()->id);
        }])->where('show', "1")->select("id", 'name', 'slug', "price", 'stock', 'systemComissation');


        !empty($request->name) ?  $products = $products->where("name", "like", "%{$request->name}%") : "";

        !empty($request->category_id) ?  $products = $products->whereHas('categories', function ($query) use ($request) {
            $query->where("category_id",  "{$request->category_id}");
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




        $products =   $products->orderBy("id", "desc")->simplePaginate(12);
        $categories = category::orderBy("id", "asc")->get();
        return view('users/products', compact('products', 'categories'));
    }

    public function add_fav($id)
    {
        $user_id = Auth::user()->id;
        $product_id = $id;

        try {
            products_favourite::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ]);

            cache()->forget("products");

            return response()->json(["status" => "success", "message" => "تم الاضافة الي المفضلة", "favCount" => favCount()]);
        } catch (\Throwable $th) {

            return response()->json(["status" => "error", "message" => "خطا اثناء الاضافة للمفضلة"]);
        }
    }


    public function delete_fav($id)
    {
        $user_id = Auth::user()->id;
        $product_id = $id;

        try {
            products_favourite::where([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ])->delete();
            cache()->forget("products");

            return response()->json(["status" => "success", "message" => "تمت الازالة من المفضلة ", "favCount" => favCount()]);
        } catch (\Throwable $th) {

            return response()->json(["status" => "error", "message" => "خطا اثناء الازالة "]);
        }
    }


    function showProductPage($slug)
    {

        $product = Product::where("slug", $slug)->where("deleted_at", null)->where("show", '1')->first();

        if (!$product) {
            abort(404);
        }


        if (!CanAccess($product->id, auth()->user()->id)) {
            abort(404);
        }

        return view('users/showProduct', compact('product'));
    }

    function getVariant($id, Request $request)
    {

        $ids = $request->input('data');

        return json(["status" => "success", "variant" => variantExists($id, $ids)]);
    }
}

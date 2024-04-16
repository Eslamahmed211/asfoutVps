<?php

namespace App\Http\Controllers\admin\products;

use App\Exports\productExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\lib\img;
use App\Http\Requests\products\productStoreRequest;
use App\Http\Requests\products\productUpdateRequest;
use App\Models\category;
use App\Models\product;
use App\Models\productImage;
use App\Models\productOptimize;
use App\Models\User;
use App\Models\variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Milon\Barcode\DNS1D;

class productController extends Controller
{


    function qr(product $product)
    {
        return json(["status" => "success", "qr" => DNS1D::getBarcodeSVG($product->sku, 'C128', 1.5, 32), "product" => $product]);
    }

    function qrVairant($id)
    {

        $variant = variant::with("product")->find($id);

        return json(["status" => "success", "qr" => DNS1D::getBarcodeSVG($variant->sku, 'C128', 1.5, 32), "name" => $variant->product->name . " " . variantName($id)]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = product::with(["imgs"])->withCount("optimizes")->orderBy('id', 'desc')->simplePaginate(25);
        $categories = category::orderBy('order', 'Asc')->get();

        $traders = User::where("role", 'trader')->get();



        return view('admin/products/index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = category::orderBy('order', 'Asc')->get();
        $traders = User::where("role", 'trader')->get();

        return view('admin/products/create', compact("categories", 'traders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(productStoreRequest $request)
    {
        $data = $request->validated();


        // 1-  check SKU ==============================

        if (!isset($data["sku"])) {
            $data["sku"] = generateProductSKU();
        } else {
            if (skuExists($data["sku"])) {
                $data["sku"] = generateProductSKU();
            }
        }

        //  2- check Slug ==============================


        if (!isset($data["Slug"])) {
            $data["slug"] = generateSlug($data["name"]);
        }



        DB::beginTransaction();



        // 3-  store Product ==============================

        try {
            $product = product::create($data);
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في اضافة المنتج")->withInput();
        }


        // 4- store category ==============================

        try {
            $product->categories()->attach($data["categories"]);
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في اضافة التصنيفات")->withInput();
        }

        // 5-  upload imgs ==============================

        try {

            if (isset($data["imgs"])) {
                $Uploads = [];

                foreach ($data["imgs"] as $img) {
                    $data['img'] = img::upload('product', $img, 800);
                    array_push($Uploads, $data['img']);
                }


                foreach ($Uploads as $Upload) {
                    productImage::create([
                        "product_id" => $product->id,
                        "img" => $Upload,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في اضافة صور المنتج")->withInput();;
        }

        //  ==============================

        DB::commit();




        return redirect("admin/products/$product->id/edit")->with("success", "تم الاضافة بنجاح");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        $categories = category::orderBy('order', 'Asc')->get();

        // return json( $product->imgs[0]);
        return view("admin/products/edit", compact("product", 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(productUpdateRequest $request, product $product)
    {
        $data = $request->validated();

        //  2- check Slug ==============================


        if (!isset($data["slug"]) || empty($data["slug"])) {
            $data["slug"] = generateSlug($data["name"]);
        }

        DB::beginTransaction();

        // 3-  update Product ==============================

        try {
            $product->update($data);
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في تعديل المنتج")->withInput();
        }


        // 4- store category ==============================

        try {
            $product->categories()->sync($data["categories"]);
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في اضافة التصنيفات")->withInput();
        }

        DB::commit();


        return Redirect::back()->with("success", "تم التعديل بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->delete != "ازالة") {
            return Redirect::back()->with("error", "قم بكتابة ازالة  ليتم الازالة");
        }

        try {

            $product = product::with("imgs")->find($request->product_id);

            // $imgs = $product->imgs;


            // foreach ($imgs as $img) {
            //   try {
            //     Storage::delete($img->img);
            //   } catch (\Throwable $th) {
            //     //throw $th;
            //   }
            // }




            $product->delete();

            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }
    public function optimize_destroy(Request $request)
    {

        try {

            $optimize = productOptimize::find($request->optimize_id);

            $optimize->delete();

            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }

    function storeImgs(Request $request, product $product)
    {

        $data = $request->validate([
            "imgs" => "required"
        ], [
            "imgs.required" => "يرجي اضافة صور"
        ]);


        try {

            if (isset($data["imgs"])) {
                $Uploads = [];

                foreach ($data["imgs"] as $img) {
                    $data['img'] = img::upload('product', $img, 800);
                    array_push($Uploads, $data['img']);
                }


                foreach ($Uploads as $Upload) {
                    productImage::create([
                        "product_id" => $product->id,
                        "img" => $Upload,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "خطأ في اضافة صور المنتج")->withInput();;
        }

        return Redirect::back()->with("success", "تم الاضافة بنجاح");
    }


    function showHideProduct(Request $request)
    {

        try {

            $product = product::findOrFail($request->id);


            $product->update([
                "show" => "$request->show"
            ]);


            return json(["status" => "done", "show" => $product->show]);
        } catch (\Throwable $th) {
            return json(["status" => "error", "show" => $product->show]);
        }
    }

    function destroyImgs(Request $request)
    {


        try {

            $img = productImage::find($request->img_id);


            $old_path = $img->img;

            $img->delete();

            Storage::delete($old_path);


            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }



    function destroylogs(Request $request)
    {

        try {

            $product = product::with("logs")->find($request->product_id);

            $logsToDelete = $product->logs->filter(function ($log) {
                return Carbon::parse($log->created_at)->diffInDays(Carbon::now()) > 10;
            });

            $logsToDelete->each->delete();

            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }

    function logs(product $product)
    {


        return view('admin/products/logs', compact('product'));
    }



    function search(Request $request)
    {

        $products = product::with(["imgs"])->withCount("optimizes")->orderBy('id', 'Asc');


        !empty($request->id) ? $products = $products->where("id",  "{$request->id}") : "";

        !empty($request->name) ?  $products = $products->where("name", "like", "{$request->name}%") : "";

        !empty($request->trader_id) ? $products =  $products->where("trader_id",  "{$request->trader_id}") : "";

        !empty($request->category_id) ?  $products = $products->whereHas('categories', function ($query) use ($request) {
            $query->where("category_id",  "{$request->category_id}");
        }) : "";


        match ($request->active) {
            "نشط" => $products =  $products->where("show", "1"),
            "غير نشط" => $products = $products->where("show", "0"),
            default  => "",
        };

        match ($request->deleted) {
            "onlyTrashed" => $products =  $products->onlyTrashed(),
            "withTrashed" => $products = $products->withTrashed(),
            default => "",
        };


        $products =  $products->get();


        if ($request->type == "excel") {
            return Excel::download(new productExport($products), 'products.xlsx');
        } else {
            $categories = category::orderBy('order', 'Asc')->get();
            $traders = User::where("role", 'trader')->get();
            return view('admin/products/index', compact("products", "categories", 'traders'));
        }
    }



    function optimize(product $product, Request $request)
    {

        $data = $request->validate([
            "user_id" => "required|integer",
            "action" => "required|in:0,1"
        ], [
            "user_id.required" => "يرجي اختيار مسوق",
            "action.in" => "يرجي اختيار الاجراء بشكل صحيح"
        ]);

        DB::beginTransaction();

        $old = productOptimize::where("product_id", $product->id)->where("user_id", $data['user_id'])->where("action", $data['action'])->first();

        if (isset($old)) {
            return Redirect::back()->with("error", "المستخدم ده موجود من قبل ")->withInput();
        }

        $old = match ($data['action']) {
            "0" => productOptimize::where("action", "1")->delete(),
            "1" => productOptimize::where("action", "0")->delete(),
        };




        $data["product_id"] = $product->id;

        try {

            productOptimize::create($data);

            DB::commit();


            return redirect()->back()->with("success", "تم الاضافة بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "فشل في الاضافة");
        }
    }



    public function changeOrder(Request $request)
    {


        productImage::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;
    }
}

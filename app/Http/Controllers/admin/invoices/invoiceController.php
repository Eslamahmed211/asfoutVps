<?php

namespace App\Http\Controllers\admin\invoices;

use App\Http\Controllers\Controller;
use App\Models\invoice;
use App\Models\invoiceItem;
use App\Models\product;
use App\Models\User;
use App\Models\variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class invoiceController extends Controller
{
  function index()
  {
    $invoices = invoice::with("trader")->orderBy("id", "desc")->simplePaginate(25);

    $traders = User::where("role", "trader")->where("active", "1")->get();


    return view("admin/invoices/index", compact('invoices', 'traders'));
  }

  function search(Request $request)
  {

    $invoices = invoice::with("trader" , 'items')->orderBy("id", "desc");
    !empty($request->InvoiceName) ?  $invoices = $invoices->where("InvoiceName", "like", "%{$request->InvoiceName}%") : "";
    !empty($request->traderId) ? $invoices =  $invoices->where("traderId",  "{$request->traderId}") : "";
    !empty($request->type) ? $invoices =  $invoices->where("type",  "{$request->type}") : "";

    $date = '';

    if (!empty($request->date)) {

      $date = $request->date;

      $dates = explode(" to ",   $date);

      $startDate = $dates[0];
      $endDate = $dates[1] ?? Carbon::now();
    }

    $date != ""  ? $invoices =  $invoices->whereBetween("created_at", [$startDate, $endDate]) : "";


    $all = $invoices->get();


        $qnt = 0;
        $price = 0;



        foreach($all as $invoice)
        {
            foreach ($invoice->items as $item) {

                if ($invoice->type == "مشتريات") {
                    $qnt += $item->qnt;
                    $price += $item->total;
                }else{
                    $qnt -= $item->qnt;
                    $price -= $item->total;
                }

            }
        }




        $total = count($all);





    $invoices = $invoices->simplePaginate(25);
    $traders = User::where("role", "trader")->where("active", "1")->get();
    return view("admin/invoices/index", compact('invoices', 'traders' , "qnt" , "price" , "total"));
  }

  function create(User $user)
  {

    if ($user->role != "trader"  || $user->active != 1) {
      abort(404);
    }

    $products = product::where("trader_id", $user->id)->get();

    return view("admin/invoices/create", compact("user", 'products'));
  }

  function store(Request $request)
  {


    $validator = Validator::make($request->data, [
      'InvoiceName' => 'required|string',
      'traderId' => 'required|integer',
      "rows" => "required",
      "type" => 'required|in:مشتريات,مرتجعات|string',

    ], [
      "InvoiceName.required" => "يرجي  كتابة اسم الفاتورة",
      "traderId.required" =>  "يرجي اضافة التاجر",
      "rows.required" => "يرجي اضافة حقول في القاتورة",
      "type.required" => "يرجي اختيار نوع الفاتورة",
      "type.in" => "يجب ان تكون نوع الفاتورة مشتريات او مرتجعات"
    ]);

    $trader = User::find($request->data["traderId"]);

    if (!isset($trader->id)) {
      return json(["status" => "CoustomErrors", "CoustomErrors" => "التاجر ده غير موجود"]);
    }

    DB::beginTransaction();


    try {

      $invoice = invoice::create([
        "traderId" => $request->data["traderId"],
        "InvoiceName" =>  $request->data["InvoiceName"],
        "type" => $request->data["type"]
      ]);


      $index = 1;

      foreach ($request->data['rows'] as $row) {

        $validator = Validator::make($row, [
          'date' => 'required|date',
          "productId" => "required|integer|exists:products,id",
          "price" => "required|numeric",
          "qnt" => "required|numeric",
          "total" => "required|numeric",
        ], [
          "date.required" => "التاريخ مطلوب في الفاتورة رقم " . $index,
          "date.date" => "صيغة التاريخ خطأ في الفاتورة رقم" . $index,
          "price.numeric" => "يجب ان يكون السعر رقما" . " في الفاتورة رقم " . $index
        ]);


        if ($validator->fails()) {
          return json(["status" => "ValidationError", "Errors" => $validator->errors()]);
        }

        if ($row["total"] !=  $row["price"] * $row["qnt"]) {
          return json(["status" => "CoustomErrors", "CoustomErrors" => "الاجمالي لا يساوي حاصل ضرب الكمية في السعر"]);
        }

        // Work
        $product = product::find($row["productId"]);

        if (count(getVariants($product->id)) > 0 &&  $row["variantId"] == "") {
          return json(["status" => "CoustomErrors", "CoustomErrors" => "يرحي اختيار خصائص المنتج" . " في الفاتورة رقم " . $index]);
        }

        if ($request->data["type"] == "مشتريات") {
          $product->update([
            "stock" => $product->stock + $row["qnt"]
          ]);
        } else {

          if ($product->stock < $row["qnt"]) {
            return json(["status" => "CoustomErrors", "CoustomErrors" => "كمية المنتج المراد تخفيضها اكبر من الكمية المتاحة" . " في الفاتورة رقم " . $index]);
          }

          $product->update([
            "stock" => $product->stock - $row["qnt"]
          ]);
        }




        invoiceItem::create([
          "invoice_id" => $invoice->id,
          "date" => $row["date"],
          "product_name" => $product->name,
          "variants" => $row["variantId"] != "" ? variantName($row["variantId"]) : "",
          "product_id" => $product->id,
          "variant_id" => $row["variantId"] != "" ?  $row["variantId"] : null,
          "price" => $row["price"],
          "qnt" => $row["qnt"],
          "total" => $row["total"],
        ]);

        if ($row["variantId"] != "") {
          $variant =   variant::find($row["variantId"]);


          if ($request->data["type"] == "مشتريات") {
            $variant->update(["stock" => $variant->stock + $row["qnt"]]);
          } else {

            if ($variant->stock < $row["qnt"]) {
              return json(["status" => "CoustomErrors", "CoustomErrors" => "كمية المنتج  الفرعي المراد  تخفيضها اكبر من الكمية المتاحة" . " في الفاتورة رقم " . $index]);
            }

            $variant->update(["stock" => $variant->stock - $row["qnt"]]);
          }
        }
        $index++;
      }

      DB::commit();


      return json(["status" => "success", "invoice" => $invoice]);
    } catch (\Throwable $th) {
      return json(["status" => "CoustomErrors", "CoustomErrors" => "فشل في اضافة الفاتورة"]);
    }
  }

  function show(invoice $invoice)
  {
    return view("admin/invoices/show", compact('invoice'));
  }

  function traders()
  {
    $traders = User::where("role", "trader")->where("active", "1")->get();
    return view("admin/invoices/traders", compact("traders"));
  }
}

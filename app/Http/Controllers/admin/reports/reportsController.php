<?php

namespace App\Http\Controllers\admin\reports;

use App\Exports\reports\marketer_profits_losses;
use App\Exports\reports\products_orders;
use App\Exports\reports\user_commissions;
use App\Http\Controllers\Controller;
use App\Models\expenses_and_commissions;
use App\Models\product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class reportsController extends Controller
{
    function user_commissions_get()
    {
        return view("admin/reports/user_commissions");
    }

    function user_commissions_post(Request $request)
    {

        return Excel::download(new user_commissions($request->input("user_id")), "user_commissions.xlsx");
    }

    function products_stock_in_orders_get()
    {
        $products = product::get();

        return view("admin/reports/products_stock_in_orders", compact("products"));
    }


    function products_stock_in_orders_post(Request $request)
    {

        $data = $request->validate([
            "product_id" => "nullable|exists:products,id",
        ]);

        return Excel::download(new products_orders($data), "products_orders.xlsx");
    }

    function marketer_profits_losses_get()
    {
        return view("admin/reports/marketer_profits_losses");
    }

    function marketer_profits_losses_post(Request $request)
    {
        $data = $request->validate([
            "user_id" => "nullable|exists:users,id",
            "date" => "nullable"
        ], [
            "user_id.required" => "يرجي اختيار مسوق"
        ]);



        return Excel::download(new marketer_profits_losses($data), "marketer_profits_losses.xlsx");
    }


    function get_take_users(Request $request)
    {
        $data = $request->validate([
            "user_id" => "required",
            "amount" => "required",
            "message" => "nullable"
        ]);

        try {

            if ($data["amount"] == 0) {
                return redirect()->back()->with("error", "لا يمكن وضع المبلغ ب 0 ");
            }

            DB::beginTransaction();

            expenses_and_commissions::create([
                "user_id" => $data["user_id"],
                "commission" => $data["amount"],
                "message" => $data["message"],
                "type" => $data["amount"] > 0 ? "اضافة" : "خصم"

            ]);


             $user =  User::findOrFail($data["user_id"]);

             $user->update([
                "wallet" =>  $user->wallet  += (int)$data["amount"]
             ]);

            DB::commit();

            return redirect()->back()->with("success", "تم الاضافة بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "خطأ");

        }
    }

}

<?php

namespace App\Http\Controllers\users\wallet;

use App\Http\Controllers\Controller;
use App\Models\moderatorsWithdraw;
use App\Models\paymentMethod;
use App\Models\withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class walletController extends Controller
{
    function index()
    {

        if (auth()->user()->role == "user" || auth()->user()->role == "trader") {
            $withdrows = auth()->user()->withdraw;
        }

        if (auth()->user()->role == "moderator") {
            $withdrows = auth()->user()->moderators_withdraws;
        }



        return view("users/wallet/index", compact("withdrows"));
    }

    function store(Request $request)
    {

        $data =  $request->validate([
            "amount" => "required|integer|min:1",
            "type" => "required|integer",
        ], [
            "amount.required" => "يرجي كتابة مبلغ السحب",
            "amount.integer" => " يجب ان يكون مبلغ السحب رقما صحيحا ",
            "type.required" => "يرجي اضافة طريقة السحب",
            "amount.min" => "لا يجب ان يقل طلب السحب عن 1 ",
        ]);

        if ($data['amount'] > auth()->user()->wallet) {
            return redirect()->back()->with('error', "مبلغ السحب اكبر من المحفظة المتاحة");
        }

        if (auth()->user()->role != "user"  && auth()->user()->role != "moderator" && auth()->user()->role != "trader") {
            return redirect()->back()->with('error', "انت لست مسوق او موديتور لعمل طلب سحب ");
        }

        try {

            DB::beginTransaction();

            $payment_method =  paymentMethod::where("user_id", auth()->user()->id)->findOrFail($data['type']);

            if (auth()->user()->role == "user" || auth()->user()->role == "trader") {
                withdraw::create([
                    "user_id" => auth()->user()->id,
                    "amount" => $data["amount"],
                    "type" => $payment_method->type,
                    "options" => $payment_method->options
                ]);
            }

            if (auth()->user()->role == "moderator") {
                moderatorsWithdraw::create([
                    "user_id" => auth()->user()->id,
                    "amount" => $data["amount"],
                    "type" => $payment_method->type,
                    "options" => $payment_method->options
                ]);
            }



            $user = auth()->user();
            $user->wallet = $user->wallet -  $data["amount"];
            $user->save();

            admin_send_message("هناك طلب سحب جديد", "user_withdrow");



            DB::commit();

            return redirect()->back()->with('success',  "تم عمل طلب سحب بنجاح");
        } catch (\Throwable $th) {

            return redirect()->back()->with('error',  "خطأ اثناء عمل طلب سحب");
        }
    }
}

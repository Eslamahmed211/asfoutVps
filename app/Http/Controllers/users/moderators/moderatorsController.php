<?php

namespace App\Http\Controllers\users\moderators;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\moderators\storeModeratorsRequest;
use App\Http\Requests\users\moderators\UpdateModeratorsRequest;
use App\Models\moderatorOption;
use App\Models\moderatorsWithdraw;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class moderatorsController extends Controller
{
    function index()
    {
        $moderators = auth()->user()->moderators;
        return view('users/moderators/index', compact('moderators'));
    }

    function withdraws_index()
    {

        $withdrows = moderatorsWithdraw::with("user")->where("user_id",  auth()->user()->moderators->modelKeys())->orderBy("id", "desc")->get();
        return view('users/moderators/withdraws', compact('withdrows'));
    }

    function withdraws_store(Request $request)
    {

        $data = $request->validate([
            "id" => "required|integer"
        ]);


        try {

            $withdraw =  moderatorsWithdraw::findOrFail($data['id']);


            $withdraw->update([
                "status" => "مصروف",
                "paid_at" => Carbon::now()
            ]);

            return redirect()->back()->with("success", "تم تسديد طلب السحب بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "خطأ");
        }
    }


    public function create()
    {
        return view('users/moderators/create');
    }


    public function store(storeModeratorsRequest $request)
    {


        $data = $request->validated();




        if (check_Email_Phone($data['email'], $data['mobile'])) {
            return redirect()->back();
        }

        $data["role"] = "moderator";
        $data["marketer_id"] = auth()->user()->id;




        try {

            $data["password"] = bcrypt($data["password"]);

            DB::beginTransaction();

            $moderator = User::create($data);

            moderatorOption::create([
                "moderator_id" => $moderator->id,
                "commissionType" => $data["commissionType"] == "null" ? "null"  : $data["commissionType"],
                "commission" => $data["commission"],
            ]);

            DB::commit();

            return Redirect::back()->with("success", "تم الاضافة بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
        }
    }

    function edit($id)
    {

        $moderator = user::with("moderatorOptions")->where("marketer_id", auth()->user()->id)->findOrFail($id);

        return view("users/moderators/edit", compact("moderator"));
    }


    public function update(UpdateModeratorsRequest $request, $id)
    {



        $data = $request->validated();


        $moderator = user::with("moderatorOptions")->where("marketer_id", auth()->user()->id)->findOrFail($id);



        if ($data['password'] != null) {
            $data["password"] = bcrypt($data["password"]);
        } else {
            unset($data["password"]);
        }


        // try {

        DB::beginTransaction();
        $moderator->update($data);

        if (!$moderator->moderatorOptions) {
            moderatorOption::create([
                "moderator_id" => $moderator->id,
                "commissionType" => $data["commissionType"] == "null" ? "null" : $data["commissionType"],
                "commission" => $data["commission"],
            ]);
        } else {
            $moderator->moderatorOptions->update([
                "commissionType" => $data["commissionType"],
                "commission" => $data["commission"],
            ]);
        }


        DB::commit();

        return Redirect::back()->with("success", "تم التعديل بنجاح");
        // } catch (\Throwable $th) {

        //   return Redirect::back()->with("error", "لم يتم التعديل");
        // }
    }


    function search(Request $request)
    {



        $id = '';
        $name = '';
        $mobile = '';
        $email = '';
        $active = '';
        $deleted = '';
        $date = '';

        if (!empty($request->id)) {
            $id = $request->id;
        }

        if (!empty($request->name)) {

            $name = $request->name;
        }

        if (!empty($request->mobile)) {

            $mobile = $request->mobile;
        }

        if (!empty($request->email)) {

            $email = $request->email;
        }


        if (!empty($request->role)) {

            $role = $request->role;
        }

        if (!empty($request->active)) {

            $active = $request->active;

            $active = match ($request->active) {
                "نشط" => 1,
                "غير نشط" => 0,
            };
        }

        if (!empty($request->deleted)) {

            $deleted = $request->deleted;
        }



        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ",   $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";
        }




        $moderators = User::where("name", "LIKE", "{$name}%")->where("marketer_id", auth()->user()->id);


        $id != ""  ? $moderators =  $moderators->where("id",  "{$id}") : "";

        match ($deleted) {
            "yes" => $moderators =  $moderators->onlyTrashed(),
            "" => $moderators = $moderators->withTrashed(),
            default  => "",
        };


        $moderators =  $moderators->where("mobile", "LIKE", "%{$mobile}%");
        $moderators =  $moderators->where("email", "LIKE", "%{$email}%");



        $active != ""  ? $moderators =  $moderators->where("active", "{$active}") : "";

        $date != ""  ? $moderators = $moderators->whereDate("created_at", '>=', $startDate) : "";


        isset($endDate) && !empty($endDate)  ? $moderators = $moderators->whereDate("created_at", '<=', $endDate) : "";


        $moderators =   $moderators->orderBy('id', 'desc')->get();


        return view('users/moderators/index', compact('moderators'));
    }



    public function destroy(Request $request)
    {
        if ($request->delete != "ازالة") {
            return Redirect::back()->with("error", "قم بكتابة ازالة  ليتم الازالة");
        }

        try {

            $moderator = user::where("marketer_id", auth()->user()->id)->findOrFail($request->moderator_id);

            $moderator->delete();

            return redirect('users/moderators')->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }


    function restore($id)
    {


        try {

            $moderator = user::withTrashed()->where("marketer_id", auth()->user()->id)->findOrFail($id);

            $moderator->restore();


            return Redirect::back()->with("success", "تم استرجاع الحساب بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الاسترجاع");
        }
    }
}

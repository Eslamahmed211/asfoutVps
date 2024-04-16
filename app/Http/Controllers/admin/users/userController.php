<?php

namespace App\Http\Controllers\admin\users;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\user;
use App\Models\withdraw;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class userController extends Controller
{

    function searchAjax(Request $request)
    {

        $mobile = $request->input("query");

        if (isset($request->all)) {
            $users =  user::whereIn("role", ["user", "trader", "moderator"]);
        } else {
            $users =  user::where("role", "user");
        }

        $users = $users->where("mobile", "LIKE", "%{$mobile}%")->select("id", 'name')->get();

        if (isset($users[0]->id)) {
            return json(["status" => "success", "users" => $users]);
        } else {
            return json(["status" => "error"]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users =  user::orderBy('id', 'desc')->withTrashed()->simplePaginate(25);
        // return json($users);
        // $Users =  user::where('id', '!=', auth()->user()->id)->get();
        return view('admin/users/index', compact('users'));
    }

    function search(Request $request)
    {




        $id = '';
        $name = '';
        $mobile = '';
        $email = '';
        $role = '';
        $active = '';
        $deleted = '';
        $withModetor = '';
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
                "محظور" => 3
            };
        }

        if (!empty($request->deleted)) {

            $deleted = $request->deleted;
        }

        if (!empty($request->withModetor)) {

            $withModetor = $request->withModetor;
        }


        if (!empty($request->date)) {

            $date = $request->date;

            $dates = explode(" to ",   $date);

            $startDate = $dates[0];
            $endDate = $dates[1] ?? "";
        }




        $users = User::where("name", "LIKE", "{$name}%");


        $id != ""  ? $users =  $users->where("id",  "{$id}") : "";

        match ($deleted) {
            "yes" => $users =  $users->onlyTrashed(),
            "" => $users = $users->withTrashed(),
            default  => "",
        };




        $users =  $users->where("mobile", "LIKE", "%{$mobile}%");
        $users =  $users->where("email", "LIKE", "%{$email}%");

        $role != ""  ? $users =  $users->where("role",   "{$role}") : "";
        $active != ""  ? $users =  $users->where("active", "{$active}") : "";

        $date != ""  ? $users = $users->whereDate("created_at", '>=', $startDate) : "";


        isset($endDate) && !empty($endDate)  ? $users = $users->whereDate("created_at", '<=', $endDate) : "";




        if ($request->type == "search") {

            $users =   $users->orderBy('id', 'desc')->simplePaginate(30);


            return view('admin/users/index', compact('users'));
        } else {

            $users =   $users->orderBy('id', 'desc')->get();



            // return Excel::download(new UsersExport($users), 'users.xlsx');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin/users/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {


        $data = $request->validated();



        if (check_Email_Phone($data['email'], $data['mobile'])) {
            return redirect()->back();
        }



        if (!isset($data['permissions'])) {
            $data['permissions'] = '[]';
        } else {

            $data['permissions'] =  json_encode($data['permissions']);
        }




        try {

            $data["password"] = bcrypt($data["password"]);

            User::create($data);

            return Redirect::back()->with("success", "تم الاضافة بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user $user)
    {
        return view('admin/users/edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserEditRequest $request, user $user)
    {

        $data = $request->validated();



        if (!isset($data['permissions'])) {
            $data['permissions'] = '[]';
        } else {


            $data['permissions'] =  json_encode($data['permissions']);
        }

        if ($data['password'] != null) {
            $data["password"] = bcrypt($data["password"]);
        } else {
            unset($data["password"]);
        }


        try {

            $user->update($data);


            return Redirect::back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم التعديل");
        }
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

            $user = user::findOrFail($request->user_id);

            $user->delete();

            return redirect('admin/users')->with("success", "تم الازالة بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }


    function logs(user $user)
    {

        // $logs = UsersLog::where('user_id' ,  $user->id)->with('changer')->get();

        // return json($user->logs[0]->editer->name);

        return view('admin/users/logs', compact('user'));
    }



    function bulkActive(Request $request)
    {


        $data = $request->validate([
            "ids" => "required|string"
        ]);

        $ids = explode(",", $data["ids"]);

        try {

            User::whereIn('id', $ids)->each(function ($user) {
                $user->active = 1;
                $user->save();
            });



            return Redirect::back()->with("success", "تم التفعيل بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم التفعيل");
        }
    }


    function restore($id)
    {



        try {

            $user = user::withTrashed()->find($id);
            $user->restore();



            return Redirect::back()->with("success", "تم استرجاع الحساب بنجاح");
        } catch (\Throwable $th) {
            return Redirect::back()->with("error", "لم يتم الاسترجاع");
        }
    }


    function withdraws_index()
    {

        $withdrows = withdraw::with("user")->orderBy("id", "desc")->get();
        return view('admin/users/withdraws', compact('withdrows'));
    }

    function withdraws_paid(Request $request)
    {
        $data = $request->validate([
            "id" => "required|integer"
        ]);


        try {

            $withdraw =  withdraw::findOrFail($data['id']);


            $withdraw->update([
                "status" => "مصروف",
                "paid_at" => Carbon::now()
            ]);

            $user = user::where("id", $withdraw->user_id)->first();


            if (in_array("العمولات", $user->notification_settings)) {

                $message = " تم  سداد طلب السحب الخاص بك ";

                user_send_message($user, $message, "paid_withdrow");
            }





            return redirect()->back()->with("success", "تم تسديد طلب السحب بنجاح");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "خطأ");
        }
    }
}

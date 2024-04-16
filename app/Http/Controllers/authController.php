<?php

namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class authController extends Controller
{


  public function login(Request $request)
  {
    $data = $request->validate([
      "email" => "required|email",
      "password" => "required|string|min:8",
      // 'g-recaptcha-response' => 'required|captcha'

    ], [
      "g-recaptcha-response.required" => "الكابتشا مطلوبة",
      "password.required" => "كلمة المرور مطلوبة",
      "email.required" => "الايميل مطلوب",
      "email.email" => "هذة ليست صيغة ايميل" ,
      "password.min" => "يجب ان لا يقل كلمة المرور عن 8 ارقام" ,


    ]);

    unset($data["g-recaptcha-response"]);



    if (auth::attempt($data)) {

      $user = User::where('email', $data['email'])->first();



      if ($user->active  == 3) {
        Auth::logout();
        return Redirect("login")->withErrors("حسابك غير نشط")->withInput();
      } elseif ($user->active == 0) {
        Auth::logout();
        return redirect("login")->withErrors("تم تسجيل الحساب انتظر حتي يتم تفعيل الحساب خلال 24 ساعة")->withInput();
      } elseif ($user->active == 1) {
        auth::login(auth::user());

        return redirect("home");
      } else {
        Auth::logout();
        return redirect("login")->withErrors("هناك خطأ ما يرجي التواصل معنا")->withInput();
      }
    } else {

      $user = User::where("email", $data["email"])->first();

      if ($user == null) {
        return redirect("login")->withErrors("هذا البريد ليس موجود")->withInput();
      } else {
        $passwordFromDb =  $user->password;

        if (!password_verify($data["password"], $passwordFromDb)) {
          return redirect("login")->withErrors("كلمة المرور غير صحيحة")->withInput();
        }
      }
    }
  }



  public function register(Request $request)
  {
    $data = $request->validate([
      "name" => "required|min:3|string",
      "email" => "required|email",
      "mobile" => "required|numeric|digits:11",
      "password" => "required|min:8|max:32|confirmed|string",
      "address" => "required|string",
      "city" => "required|string",
      "role" => "required|string",

    ], [
      "name.required" => "يرجي كتابة اسم المستخدم",
      "email.required" => "يرجي كتابة  البريدالالكتروني",
      "mobile.required" => "يرجي كتابة رقم المستخدم",
      "mobile.digits" => "يجب ان لا يقل رقم التليفون عن 11 رقم",
      "mobile.numeric" => "يجب ان يكون رقم التليفون رقما",

      "password.required" => "يرجي كتابة  كلمة المرور",
      "email.email" => "صيغة الايميل غير صحيحة",
      "password.min" => "يجب ان لا تقل كلمة المرور عن 8 ارقام",
      "password.confirmed" => "كلمة المرور غير متطابقة",
      "address.required" => "يرجي كتابة العنوان",
      "city.required" => "يرجي كتابة المحافظة",
      "role.required" => "يرجي اختيار نوع الحساب",

    ]);


    if ($data['role'] != "user" && $data['role'] != "trader") {
      return Redirect::back()->withErrors("يرجي اختيار نوع الحساب بشكل صحيح")->withInput();
    }



    if (check_Email_Phone($data['email'], $data['mobile'])) {
      return redirect()->back() ;
   }


    // $email = User::where("email", $data["email"])->first();


    // if ($email != null) {
    //   return Redirect::back()->withErrors("هذا البريد مسجل به من قبل")->withInput();
    // }


    // $mobile = User::where("mobile", $data["mobile"])->first();

    // if ($mobile != null) {
    //   return Redirect::back()->withErrors("هذا الرقم مسجل به من قبل")->withInput();
    // }


    $data["password"] = bcrypt($data["password"]);


    try {
      User::create($data);
      return Redirect::back()->with("success", "تم التسجيل بنجاح سوف يتم تفعيل حسابك خلال 24 ساعة");
    } catch (\Throwable $th) {
      return Redirect::back()->withErrors("لم يتم التسجيل")->withInput();
    }
  }



  public function logout()
  {
    Auth::logout();

    return redirect('login');
  }

  public function changePasswoed(Request $request)
  {
    $data = $request->validate([
      "old" => "required|string",
      "password" => "required|string|min:8|max:32|confirmed",
    ], [
      "password.min" => "يجب ان لا تقل كلمة السر عن 8 حروف",
      "old.required" => "كلمة السر القديمة مطلوبة",
      "password.required" => "كلمة السر الجديدة مطلوبة",
      "password.confirmed" => "كلمة السر غير متطابقة",
    ]);

    try {

      if (password_verify($data['old'], auth()->user()->password)) {

        User::find(auth()->user()->id)->update([
          "password" => bcrypt($data['password'])
        ]);

        return Redirect::back()->with("success", "تم تغير كلمة السر بنجاح");
      } else {
        return Redirect::back()->with("error", "كلمة السر القديمة غلط");
      }
    } catch (\Throwable $th) {
      return Redirect::back()->with("error", "لا يمكن تغير كلمة السر");
    }
  }

  public function reset_password(Request $request)
  {
    $data = $request->validate([
      "email" => "required|email|exists:users",
      // 'g-recaptcha-response' => 'required|captcha'

    ], [
      "email.required" => "يرجي كتابة البريد الالكتروني",
      "email.exists" => "البريد ده مش  موجود",
      "g-recaptcha-response.required" => "الكابتشا مطلوبة"
    ]);


    $email = $data["email"];
    $token = Str::random(64);


    DB::table("password_reset_tokens")->where("email", $email)->delete();


    DB::table("password_reset_tokens")->insert([
      "email" => $email,
      "token" => $token,
      "created_at" => Carbon::now()
    ]);


    try {
      Mail::send("auth/restTemplate", ["token" => $token], function ($message) use ($email) {
        $message->to($email);
        $message->subject("استعادة كلمة السر");
      });

      return Redirect::back()->with("success", "تم ارسال الكود الي البريد");
    } catch (\Throwable $th) {
      return Redirect::back()->with("error", "فشل ارسال الكود الي البريد")->withInput();;
    }
  }

  public function create_password_form($token)
  {
    return view('auth/create_password', compact("token"));
  }

  public function create_password(Request $request)
  {
    // validation

    $data = $request->validate([
      "email" => "required|email|exists:users",
      "password" => "required|min:8|max:32|confirmed",
      // 'g-recaptcha-response' => 'required|captcha'

    ], [
      "email.exists" => "هذا البريد غير موجود لدينا"
    ]);

    // cheak token


    $tokenRecord = DB::table("password_reset_tokens")
      ->where("email", $data["email"])
      ->where("token", $request->passwordToken)
      ->first();



    if (!$tokenRecord) {
      return Redirect::back()->withErrors("الرمز خطأ !!")->withInput();
    }

    // change pawword


    user::where("email", $data["email"])->update([
      "password" => Hash::make($data["password"])
    ]);


    //delete oldToken


    DB::table("password_reset_tokens")->where("email", $data["email"])->delete();


    return redirect("/login")->with("success", "تم تغير كلمة السر بنجاح");
  }
}

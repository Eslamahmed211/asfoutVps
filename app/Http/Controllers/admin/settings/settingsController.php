<?php

namespace App\Http\Controllers\admin\settings;

use App\Http\Controllers\Controller;
use App\Models\deliveryPrice;
use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class settingsController extends Controller
{
    function index() {
        $deliveryPrices = deliveryPrice::orderBy('order', 'asc')->get();
        return view("admin/settings/index" , get_defined_vars());
    }

    function branding_update(Request $request)
    {

      cache()->flush();


      $data = $request->validate([
        "website_title" => "nullable|string",
        "website_dis" => "nullable|string",
        "website_logo" => "nullable|image",
        "website_fav" => "nullable|image",
      ]);

      try {

        if (isset($data["website_logo"])) {
          $data["website_logo"] = Storage::put("public/logo",  $data['website_logo']);
        } elseif (isset($data["website_fav"])) {
          $data["website_fav"] = Storage::put("public/fav",  $data['website_fav']);
        }
      } catch (\Throwable $th) {
        return Redirect::back()->with("error", "فشل رفع الصورة");
      }


      foreach ($data as $key => $value) {
        try {
          settings::where("key", $key)->first()->update([
            "value" => $value,
          ]);
        } catch (\Throwable $th) {
          return Redirect::back()->with("error", "فشل اثناء تعديل" . $key);
        }
      }

      return Redirect::back()->with("success", "تم التغير  بنجاح");
    }

    function socialMediaUpdate(Request $request)
    {


      cache()->flush();

      $data = $request->validate([
        "facebook" => "nullable|string",
        "youtube" => "nullable|string",
        "instagram" => "nullable|string",
        "whatsapp" => "nullable|string",
        "phone" => "nullable|string",
        "email" => "nullable|string",
      ]);



      foreach ($data as $key => $value) {
        try {
          settings::where("key", $key)->first()->update([
            "value" => $value,
          ]);
        } catch (\Throwable $th) {
          return Redirect::back()->with("error", "فشل اثناء تعديل" . $key);
        }
      }

      return Redirect::back()->with("success", "تم التغير  بنجاح");
    }
}

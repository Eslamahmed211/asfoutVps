<?php

namespace App\Http\Controllers\admin\ads;

use App\Models\ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller ;

class AdsController extends Controller
{

    public $data ;
    public $path ;
    public $Oldpath ;

    function showHideAds(Request $request)  {

    try {

      $ads = ads::findOrFail($request->id);


      $ads->update([
        "show" => "$request->show"
      ]);


      return json(["status" => "done", "show" => $ads->show]);


    } catch (\Throwable $th) {
      return json(["status" => "error", "show" => $ads->show]);

    }
    }

    public function index()
    {


        $ads = ads::get();

        return view("admin/ads/index" ,compact("ads"));



    }

    public function store(Request $request)
    {


          $data = $request->validate([
            "link" => "nullable" ,
            "img" => "required",
            "show" => "required" ,
            "alt" => "required" ,
          ]);



          $this->data = $data ;


          DB::beginTransaction();

          try
          {
              return  $this->upload_image()->add()->run();

          }
          catch(\Exception $e)
          {
              return Redirect::back()->withInput();
          }



    }

    public function upload_image()
    {
        try {

            $path = Storage::put("public/banner" ,$this->data['img']);

            $this->path =  $path ;

            return $this ;


      } catch (\Exception $e) {


     return Redirect::back()->with("error","خطأ في رفع الصورة")->withInput();;

      }
    }

    public function add()
    {
        try {

            ads::create([
              "link"=>$this->data["link"],
              "show"=>$this->data["show"],
              "alt"=>$this->data["alt"],
              "img"=>$this->path,
            ]);

            return $this ;


            } catch (\Exception $e) {

              Storage::delete($this->path);

                return Redirect::back()->with("error","خطأ في الاضافة")->withInput();;

            }
    }

    public function run()
    {
      try {

        DB::commit();

        return Redirect::back()->with("success","تم الاضافة بنجاح");

        } catch (\Exception $e) {

            DB::rollback();
            return Redirect::back()->with("error","لم يتم الاضافة")->withInput();

        }
    }


    public function delete(Request $request)
    {




        $ads = ads::findOrFail($request->ads_id);
        $oldPath =  $ads->img;

        try {
            $ads->delete();
          Storage::delete($oldPath);


          return Redirect::back()->with("success","تم الازالة بنجاح");

        } catch (\Exception $e) {
          return Redirect::back()->with("error","لم يتم الازالة");
        }

    }

    public function edit_page($id)
    {
        $ads = ads::findOrFail($id);
        return view("admin/ads/ads_update" , compact('ads') );

    }

    public function update(Request $request)
    {

      $data = $request->validate([
        "link" => "nullable" ,
        "img" => "nullable",
        "show" => "required" ,
        "alt" => "required" ,
        "id" => "required" ,

      ]);

      $this->data = $data ;

      DB::beginTransaction();

      try
      {

          return  $this->upload_image2()->update2()->delete_image()->run2();

      }
      catch(\Exception $e)
      {
          return Redirect::back();
      }

    }

    public function upload_image2()
    {
     if (isset($this->data['img']) ) {
        try {

          $path = Storage::put("public/banner" ,$this->data['img']);

          $this->path =  $path ;

          return $this ;


          } catch (\Exception $e) {


            return Redirect::back()->with("error","خطأ في رفع الصورة");

          }
      }
      return $this ;
    }

    public function update2()
    {



        try {

            if (isset($this->data['img']) ) {


              $ads = ads::find($this->data["id"]);


              $this->Oldpath = $ads->img;


              ads::where("id" , $this->data["id"])->update([
                "link"=>$this->data["link"],
                "show"=>$this->data["show"],
                "alt"=>$this->data["alt"],
                "img"=>$this->path,
              ]);

              Storage::delete($this->Oldpath);



                return $this ;
            }
            else{
              ads::where("id" , $this->data["id"])->update([
                "link"=>$this->data["link"],
                "alt"=>$this->data["alt"],
                "show"=>$this->data["show"],
              ]);

                return $this ;
            }


          } catch (\Exception $e) {

              Storage::delete($this->path);

              return Redirect::back()->with("error","خطأ في التعديل");

          }

    }

    public function delete_image()
    {
      if (isset($this->data['img']) ) {

        Storage::delete($this->data['img']);

      }

      return $this ;

    }

    public function run2()
    {
        try {

            DB::commit();


            return Redirect::back()->with("success","تم التعديل بنجاح");

        } catch (\Exception $e) {

            DB::rollback();
            return Redirect::back()->with("error","لم يتم التعديل");

        }
    }


}

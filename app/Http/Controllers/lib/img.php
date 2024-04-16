<?php

namespace App\Http\Controllers\lib;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;


use Illuminate\Support\Facades\Storage;

class img
{
     public static function upload($folder_name, $img, $w)
     {


          $image = Image::make($img)->resize($w, null, function ($constraint) {
               $constraint->aspectRatio();
           });
          $image->response('webp');
          $imageData = $image->encode('webp');

          $uniqueId= time().mt_rand();





          Storage::put("public/$folder_name/$uniqueId.webp", $imageData);

          return  "public\\$folder_name\\$uniqueId.webp";








          // $unique_string = uniqid();

          // $name = $img_name;
          
          

          // Storage::put("public/$folder_name/$unique_string/$name.webp", $imageData);

          // return  "public\\$folder_name\\$unique_string\\$name.webp";
     }

     public static function check($folder_name, $name)
     {
          if (Storage::exists("public\\$folder_name\\$name.webp")) {
               return true;
          } else {
               return false;
          }
     }

     public static function resize($file , $w)
     {

          $img = str_replace('public', 'storage', $file);

          $img = asset("$img");
                                  
          $img = str_replace('\\', '/', $img);


  
          $image = Image::make($img)->resize($w, null, function ($constraint) {
              $constraint->aspectRatio();
          });

          // dd( $image->encode('data-url'));
          $imageData = base64_encode($image->encode('jpg'));


  
          return $imageData ;
     }
}

<?php

use App\Models\cart;
use App\Models\product;
use App\Models\productOptimize;
use App\Models\products_favourite;
use App\Models\settings;
use App\Models\User;
use App\Models\variant;
use App\Notifications\adminNofication;
use App\Notifications\public_Nofication;
use Illuminate\Support\Facades\Redirect;
use Ramsey\Uuid\Uuid;


if (!function_exists("check_Email_Phone")) {
    function check_Email_Phone($email, $mobile)
    {
        $email = User::withTrashed()
            ->where("email", $email)
            ->first();
        $mobile = User::withTrashed()
            ->where("mobile", $mobile)
            ->first();

        if ($email != null) {
            return Redirect::back()
                ->withErrors("هذا البريد مسجل به من قبل")
                ->withInput();
        }
        if ($mobile != null) {
            return Redirect::back()
                ->withErrors("هذا الرقم مسجل به من قبل")
                ->withInput();
        }

        return false;
    }
}

if (!function_exists("json")) {
    function json($data)
    {
        return response()->json($data);
    }
}

if (!function_exists("settings")) {
    function settings($column)
    {
        $value = settings::where("key", $column)->first()?->value;

        return $value;
    }
}

if (!function_exists("getLogo")) {
    function getLogo()
    {
        $value = settings::where("key", "website_logo")->first()?->value;

        $img = str_replace("public", "storage", $value);

        $img = asset("$img");

        return $img;
    }
}

if (!function_exists("path")) {
    function path($img)
    {
        $img = str_replace("public", "storage", $img);

        $img = asset("$img");

        return $img;
    }
}



if (!function_exists('userName')) {
    function userName($id)
    {
        return User::find($id) ?? "";
    }
}


if (!function_exists('translateUsers')) {
    function translateUsers($word)
    {


        $translations = [
            'name' => 'اسم المتسخدم',
            'email' => 'البريد الالكتروني',
            'password' => "كلمة السر",
            'mobile' => "رقم التليفون",
            'address' => "العنوان",
            'city' => "المحافظة",
            'role' => "نوع الحساب",
            'active' => "حالة الحساب",
            'permissions' => "الصلاحيات",
        ];


        return $translations[$word] ?? $word;
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($productName)
    {
        $slug = strtolower($productName);
        $slug = preg_replace('/[^a-z0-9\p{L}]+/u', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}



if (!function_exists('generateProductSKU')) {
    function generateProductSKU()
    {

        $UniqueReference =  "SKU" . mt_rand(1000000, 9999999);
        return $UniqueReference;
    }
}



if (!function_exists('variantExists')) {
    function variantExists($product_id, $ids)
    {

        $variant = variant::with("product")->where('product_id', $product_id)
            ->with('values')
            ->whereHas('values', function ($q) use ($ids) {
                $q->whereIn('value_id', $ids);
            }, '=', count($ids))
            ->whereDoesntHave('values', function ($q) use ($ids) {
                $q->whereNotIn('value_id', $ids);
            })
            ->first();


        return $variant;
    }
}


if (!function_exists('variantStock')) {
    function variantStock($product_id)
    {

        $stock = variant::where('product_id', $product_id)->sum("stock");

        return $stock;
    }
}


if (!function_exists('skuExists')) {
    function skuExists($sku)
    {

        $product = product::where("sku", $sku)->first();

        if (is_null($product)) {
            return false;
        }

        return true;
    }
}


if (!function_exists('timeFormat')) {
    function timeFormat($time)
    {

        $timestamp = strtotime($time);


        // Calculate the difference in seconds between the timestamp and the current time
        $diff = time() - $timestamp;

        // Define an array of time intervals in seconds
        $intervals = array(
            86400       => array('singular' => 'يوم', 'plural' => 'أيام'),
            3600        => array('singular' => 'ساعة', 'plural' => 'ساعات'),
            60          => array('singular' => 'دقيقة', 'plural' => 'دقيقة'),
            1           => array('singular' => 'ث', 'plural' => 'ث')
        );

        // Initialize an empty array to store the time ago components
        $time_ago_components = array();

        // Loop through the intervals and calculate the time ago format
        foreach ($intervals as $seconds => $label) {
            $interval = floor($diff / $seconds);

            if ($interval > 0) {
                $time_ago_components[] = $interval . ' ' . ($interval == 1 ? $label['singular'] : $label['plural']);
                $diff -= $interval * $seconds;
            }
        }

        // Output the time ago format
        return (implode(' و ', array_slice($time_ago_components, 0, 2)));
    }
}


if (!function_exists('variantName')) {
    function variantName($id)
    {
        $variant = variant::with("values")->find($id);

        $name = '';
        foreach ($variant->values as $value) {
            $name = $name . ' ' . $value->value;
        }

        return trim($name);
    }
}


if (!function_exists('getVariants')) {
    function getVariants($product_id)
    {

        $variant = variant::where('product_id', $product_id)->with("values")->get();

        return $variant;
    }
}


// users
if (!function_exists('CanAccess')) {
    function CanAccess($product_id, $user_id)
    {

        $action = productOptimize::where("product_id", $product_id)->first()?->action;

        if (!isset($action)) {
            return true;
        }

        if ($action == '0') {

            $data = productOptimize::where("product_id", $product_id)->where("user_id", $user_id)->first();

            if (isset($data->id)) {
                return false;
            } else {
                return true;
            }
        }


        if ($action == '1') {

            $data = productOptimize::where("product_id", $product_id)->where("user_id", $user_id)->first();

            if (isset($data->id)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('favCount')) {
    function favCount()
    {
        return products_favourite::where('user_id', auth()->user()->id)->count();
    }
}

function cartCount()
{
    return cart::where('user_id', auth()->user()->id)->count();
}



if (!function_exists('CanAccessComment')) {
    function CanAccessComment($comment_id)
    {
        return $comment_id == auth()->user()->id;
    }
}





if (!function_exists('ON')) {
    function ON($name)
    {
        return in_array($name, auth()->user()->notification_settings);
    }
}


if (!function_exists('admin_send_message')) {
    function admin_send_message($message, $type)
    {
        $admins = User::where("role", "admin")->get();
        foreach ($admins as $admin) {
            $content = ['message' =>  $message, "type" => $type];
            $admin->notify(new adminNofication($content));
        }
    }
}



if (!function_exists('user_send_message')) {
    function user_send_message($user, $message, $type , $id = "")
    {
        $content = ['message' =>  $message, "type" => $type , "id" => $id];
        $user->notify(new public_Nofication($content));
    }
}

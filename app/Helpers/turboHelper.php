<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
// use Exception;


// require '/home/u747453778/domains/asfour.shop/public_html/affiliate/app/speed/ApiServices.php';

function NEW_ORDER_TERBO($order)
{

    $details = getOrderData($order->details);

    $client = new Client();

    $query = [
        "authentication_key" => "187ad6b58aedfa97d009a88c82f1fa331af24582333a7d31b714b4ad76cd004d",
        "main_client_code" => 25516,
        "second_client" => "عصفور",
        "receiver" => $order->clientName,
        "phone1" => $order->clientPhone,
        "phone2" => $order->clientPhone2 ?? null,
        "api_followup_phone" => "01000262003",
        "government" => $order->city,
        "area" => $order->city,
        "address" => $order->address,
        "notes" => $order->notesBosta,
        "invoice_number" => $order->reference,
        "order_summary" => $details["dis"],
        "amount_to_be_collected" => (int) $details["total"] + $order->delivery_price,
        "return_amount" => $order->return_price,
        "is_order" => 0,
        "return_summary" => $details["dis"],
        "can_open" => 1,
    ];

    $response = $client->post("https://backoffice.turbo-eg.com/external-api/add-order", [
        'headers' => [
            'content-type' => 'application/json',
        ],
        'json' => $query,
    ]);

    $body = json_decode($response->getBody());

    if (!$body->success) {
        return redirect()->back()->with("error", " الاوردر ده " . " " . $order->reference . " " . "مش راضي يترفع علي شركة الشحن");
    }

    $order->update([
        "trackingNumber" => $body->result->bar_code,
        'delivery_at' => Carbon::now(),
        'company' => "terbo",
        'status' => "جاهز للتغليف",
    ]);



}

function NEW_ORDER_SPEED($order)
{
    $api_services = new ApiServices();

    $details = getOrderData($order->details);

    $order_parameters = [
        "acceptAddress" => $order->address,
        "acceptDistrictName" => "",
        "acceptCityName" => $order->city,
        "acceptProvinceName" => $order->city,

        "acceptCountryCode" => "EG",
        "acceptCountryName" => "Egypt",
        "acceptMobile" => $order->clientPhone,
        "acceptName" => $order->clientName,

        "codFee" => (int) $details["total"] + $order->delivery_price,

        "customerCode" => "EG001724",
        "goodsQTY" => $details["qnt"],

        "remark" => $order->notesBosta,
        "isAllowOpen" => 1,
        "piece" => 1,
        "sendAddress" => "دار السلام",
        "sendCityName" => "القاهرة",
        "sendCountryCode" => "EG",
        "sendCountryName" => "Egypt",
        "sendDistrictName" => "القاهرة",
        "sendMobile" => "01000262003",
        "sendName" => "Asfor",
        "sendProvinceName" => "القاهرة",
        "deliveryType" => "DE01",
        "payMethod" => "PA01",
        "parcelType" => "PT01",
        "shipType" => "ST01",
        "transportType" => "TT01",
        "platformSource" => "Asfor",
    ];

    $itemList = [];

    foreach ($order->details as $detail) {

        array_push($itemList, [
            "battery" => 0,
            "blInsure" => 0,
            "goodsName" => $detail->discription,
            "goodsNameDialect" => $detail->discription,
            "goodsQTY" => $detail->qnt,
            "goodsType" => "IT01",
            "goodsValue" => 1,
            "sku" =>   $detail->variant_id != null ?  $detail->variant->sku :  $detail->product->sku,
        ]);
    }
    $order_parameters["itemList"] = $itemList;

    $create_order_result = $api_services->createOrder($order_parameters);



    if (!$create_order_result["success"]) {
        return redirect()->back()->with("error", " الاوردر ده " . " " . $order->reference . " " . "مش راضي يترفع علي شركة الشحن");
    }

    $order->update([
        "trackingNumber" => $create_order_result["billCode"],
        'delivery_at' => Carbon::now(),
        'company' => "speed",
        'status' => "جاهز للتغليف"
    ]);
}

function NEW_ORDER_ADM($order)
{
    $details = getOrderData($order->details);

    $client = new Client();

    $query = [

        "uname" => "Asfor",
        "upass" => "123456",
        "clientref" => $order->reference,
        "name" => $order->clientName,
        "area" => fix_city($order->city),
        "address" =>  $order->address,
        "tel" => $order->clientPhone,
        "amount" => (int) $details["total"] + $order->delivery_price,
        "remarks" => $order->notesBosta,
        "pieces" => $details["qnt"]
    ];

    $response = $client->post("http://system.admexpress-eg.com/api/shipments.asmx/SendShipment", [
        'headers' => [
            'content-type' => 'application/json',
        ],
        'json' => $query,
    ]);

    $body = json_decode($response->getBody());


    try {
        $body = get_object_vars(json_decode($response->getBody()));

        $body = json_decode($body["d"]);

        $body = get_object_vars($body[0]);


        $order->update([
            "trackingNumber" => $body["serial"],
            'delivery_at' => Carbon::now(),
            'company' => "adm",
            'status' => "جاهز للتغليف"
        ]);
    } catch (\Throwable $th) {
        return redirect()->back()->with("error", " الاوردر ده " . " " . $order->reference . " " . "مش راضي يترفع علي شركة الشحن");
    }
}


function fix_city($city)
{
    return match ($city) {
        "الإسكندرية" => "اسكندرية",
        "الغربية" => "الغربيه",
        "الإسماعيلية" => "الاسماعيلية",
        "الأقصر" => "الاقصر",
        "مطروح" => "مرسي مطروح",
        "القليوبية" => "Qalyubia",
        "الوادي الجديد" => "wadi gdid",
        default => $city,
    };
}

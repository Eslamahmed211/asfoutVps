<?php

// namespace App\speed;

error_reporting(0);
header('Content-Type: application/json; charset=utf-8');
require('ApiServices.php');
$api_services = new ApiServices();

$order_parameters = [
  "acceptAddress" => "عزبة شلبي",
  "acceptDistrictName" => "المطربة",
  "acceptCityName" => "القاهرة",
  "acceptProvinceName" => "القاهرة",


  "acceptCountryCode" => "EG",
  "acceptCountryName" => "Egypt",
  "acceptMobile" => "01006541955",
  "acceptName" => "اسلام احمد",

  "codFee" => 415,
  "customerCode" => "EG001724",
  "goodsQTY" => 1,

  "remark" => "الرجاء المعاينه وليس القياس او التجربه مع ضمان ثلاثه ايام فقط ضد عيوب الصناعة شرط الاسترجاع سلامه المنتج والعبوه والفاتوره",
  "isAllowOpen" => 1,

  "itemList" => [
    [
      "battery" => 0,
      "blInsure" => 0,
      "goodsName" => "سرير أطفال",
      "goodsNameDialect" =>  "سرير أطفال",
      "goodsQTY" => 1,
      "goodsType" => "IT01",
      "goodsValue" => 1,
      "sku" => "4630017971152",
    ]
  ],


  "piece" => 1,
  "sendAddress" => "دار السلام",
  "sendCityName" => "القاهرة",
  "sendCountryCode" => "EG",
  "sendCountryName" => "Egypt",
  "sendDistrictName" => "القاهرة",
  "sendMobile" => "012345644444",
  "sendName" => "asfour",
  "sendProvinceName" => "القاهرة",
  "deliveryType" => "DE01",
  "payMethod" => "PA01",
  "parcelType" => "PT01",
  "shipType" => "ST01",
  "transportType" => "TT01",
  "platformSource" => "Asfor",

];

try {
  $create_order_result = $api_services->createOrder($order_parameters);
  echo json_encode($create_order_result);
} catch (Exception $ex) {
  echo $ex->getMessage();
}

<?php
  error_reporting(0);
  header('Content-Type: application/json; charset=utf-8');
  //STEP 1: include the required sdk files
  require('ApiServices.php');
  //STEP 2: Create ann instance of ApiServices
  $api_services = new ApiServices();
  //STEP 3: Perform the desired operation
  //-------------------------CANCEL ORDER EXAMPLE: format your operation like the one below---------------------------------
  $cancel_parameters = [[
    "customerCode"=>"EG000563",
    "billCode"=>"EG020216885647",
    "cancelReason"=>"Customer canceled shipment",
    "cancelBy"=>"Test",
    "cancelTel"=>"01234567890"
  ]];
  try{
    $cancel_order_result = $api_services->cancelOrder($cancel_parameters);
    echo json_encode($cancel_order_result);
  }catch(Exception $ex){
    echo $ex->getMessage();
  }
?>

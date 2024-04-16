<?php
error_reporting(0);
  header('Content-Type: application/json; charset=utf-8');
  //STEP 1: include the required sdk files
  require('ApiServices.php');
  //STEP 2: Create ann instance of ApiServices
  $api_services = new ApiServices();
  //STEP 3: Perform the desired operation
  //-------------------------PRINT ORDER EXAMPLE: format your operation like the one below---------------------------------
  $print_parameters = [
    "waybillNoList"=>[
      "EG020069305568 ",
 


],
    "labelType"=>2,
    "withLogo"=>"true"
  ];
  try{
    $print_result = $api_services->printOrder($print_parameters);
    echo json_encode($print_result);
  }catch(Exception $ex){
    echo $ex->getMessage();
  }
?>
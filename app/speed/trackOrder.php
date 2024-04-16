<?php 
    error_reporting(0);
    header('Content-Type: application/json; charset=utf-8');
    //STEP 1: include the required sdk files
    require('ApiServices.php');     
    //STEP 2: Create ann instance of ApiServices
    $api_services = new ApiServices();
    //To Track a Waybill. Format your parameters like the one below
    $track_parameters =  [
        "mailNo" => "EG020134221353",
        "customerCode" => "EG000627",
        "notifyUrl" => "https://opration.com/subscribe"
    ];
    try{
        $track_result  = $api_services->track($track_parameters);

        echo json_encode($track_result);

    }catch(Exception $ex){
        echo $ex->getMessage();
    }
?>
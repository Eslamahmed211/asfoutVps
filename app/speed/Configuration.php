<?php
    return [
    	// The secretkey and the customer code AppCode are provided by Su Da Fei
    	'app_code' => "EG001724",
        // 880088
        'secret_key' => "FI3YI7mf", // "HrEZWKMg",// "x5TAhlLB", //"K3r7SYQq",
        // 'base_path' => '8.214.27.92:8480', // kinldy replace this with the LIVE PATH like 'api.speedaf.com'
        'base_path' => 'https://apis.speedaf.com',
        /*
        Don't temper with the below settings
        except you know what you are doing
    	*/
      	'sorting_code_by_waybill_path'  => '/open-api/network/threeSectionsCode/getByBillCode',
        'sorting_code_by_address_path' =>  '/open-api/network/threeSectionsCode/getByAddress',
        'create_order_path' => '/open-api/express/order/createOrder',
        'cancel_order_path' => '/open-api/express/order/cancelOrder',
        'track_path' => '/open-api/express/track/subscribe',
        'print_path' => '/open-api/express/order/print',
        'third_party_track' => '/open-api/express/track/arrivePush'
    ]
?>

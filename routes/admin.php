<?php

use App\Http\Controllers\admin\ads\AdsController;
use App\Http\Controllers\admin\attribute\attributeController;
use App\Http\Controllers\admin\category\categoryController;
use App\Http\Controllers\admin\deliveryPrice\deliveryPriceController;
use App\Http\Controllers\admin\invoices\invoiceController;
use App\Http\Controllers\admin\orders\OrderLogicController;
use App\Http\Controllers\admin\orders\ordersActions;
use App\Http\Controllers\admin\orders\OrderViewsController;
use App\Http\Controllers\admin\pages\pageContoller;
use App\Http\Controllers\admin\products\productController;
use App\Http\Controllers\admin\reports\reportsController;
use App\Http\Controllers\admin\settings\settingsController;
use App\Http\Controllers\admin\tools\toolsControler;
use App\Http\Controllers\admin\users\userController;
use App\Http\Controllers\admin\variants\variantsController;
use App\Http\Controllers\users\marketerController;
use App\Models\order;
use App\Models\product;
use Illuminate\Support\Facades\Route;


Route::get('home', function () {
    $all = order::count();
    $pending = Order::CountByStatusIn(["قيد المراجعة"]);
    $reviewedCount = Order::CountByStatusIn(["تم المراجعة"]);
    $retryCount = Order::CountByStatusIn(["محاولة تانية"]);
    $canceled = Order::CountByStatusIn(["تم الالغاء"]);
    $Shipping = Order::CountByStatusIn(["جاري التجهيز للشحن"]);
    $shippedCount = Order::CountByStatusIn(["تم ارسال الشحن"]);
    $deliveryFailureCount = Order::CountByStatusIn(["فشل التوصيل"]);
    $deliveredCount = Order::CountByStatusIn(["تم التوصيل"]);
    $done = Order::CountByStatusIn(["مكتمل"]);
    $waitingCount = Order::CountByStatusIn(["قيد الانتظار"]);
    $testing =  Order::CountByStatusIn(['جاهز للتغليف']);
    $return =  Order::CountByStatusIn(["طلب استرجاع"]);



    if (($deliveredCount + $deliveryFailureCount + $done) != 0) {
        $precent =  number_format((($deliveredCount + $done) / ($deliveredCount + $deliveryFailureCount + $done))  * 100, 2);
    } else {
        $precent = 0;
    }

    return view('admin/home', get_defined_vars());
});


Route::prefix("users")->group(function () {

    Route::middleware('checkRole:users_show')->group(function () {
        Route::get('/', [userController::class, 'index']);
        Route::get('search', [userController::class, 'search']);
    });

    Route::middleware('checkRole:users_action')->group(function () {
        Route::post('bulkActive', [userController::class, 'bulkActive']);
        Route::get('create', [userController::class, 'create']);

        Route::post('/', [userController::class, 'store']);
        Route::get('{user}/edit', [userController::class, 'edit']);
        Route::get('{user}/logs', [userController::class, 'logs']);
        Route::get('restore/{id}', [userController::class, 'restore']);
        Route::get('searchAjax', [userController::class, 'searchAjax']);
        Route::put('{user}', [userController::class, 'update']);
        Route::DELETE('destroy', [userController::class, 'destroy']);
    });

    Route::middleware('checkRole:users_withdraws')->group(function () {
        Route::get('withdraws', [userController::class, 'withdraws_index']);
        Route::post('withdraws', [userController::class, 'withdraws_paid']);
    });
});

Route::prefix("categories")->group(function () {

    Route::middleware('checkRole:products_show')->group(function () {
        Route::get('/', [categoryController::class, 'index']);
    });

    Route::middleware('checkRole:products_action')->group(function () {
        Route::get('create', [categoryController::class, 'create']);
        Route::post('/', [categoryController::class, 'store']);
        Route::get('{category}/edit', [categoryController::class, 'edit']);
        Route::put('{category}', [categoryController::class, 'update']);
        Route::DELETE('destroy', [categoryController::class, 'destroy']);
        Route::get('changeOrder', [categoryController::class, 'changeOrder']);
    });
});


Route::middleware('checkRole:products_action')->group(function () {


    //  attributes
    Route::get('attributes', [attributeController::class, "index"]);
    Route::post('attribute/add', [attributeController::class, "add"]);
    Route::get('attributes/add/{id}', [attributeController::class, "addValue"]);
    Route::post('attribute/addValue/', [attributeController::class, "addValueStore"]);
    Route::delete('attribute/destroy', [attributeController::class, "delete"]);
    Route::post('values/edit', [attributeController::class, "change_value_value"]);
    Route::delete('value/destroy', [attributeController::class, "delete_value"]);
    Route::get('product_images/changeOrder', [productController::class, 'changeOrder']);
    Route::DELETE('product_images/destroy', [productController::class, 'destroyImgs']);
});

Route::prefix("products")->group(function () {


    Route::middleware('checkRole:products_show')->group(function () {
        Route::get('search', [productController::class, 'search']);
        Route::get('/', [productController::class, 'index']);
    });

    Route::middleware('checkRole:products_action')->group(function () {
        Route::get('{product}/logs', [productController::class, 'logs']);
        Route::delete('logs/destroy', [productController::class, 'destroylogs']);
        Route::get('{product}/qr', [productController::class, 'qr']);
        Route::get('{id}/qrVairant', [productController::class, 'qrVairant']);

        Route::get('create', [productController::class, 'create']);
        Route::post('/', [productController::class, 'store']);

        Route::post('showHideProduct', [productController::class, 'showHideProduct']);
        Route::get('{product}/edit', [productController::class, 'edit']);
        Route::get('{product}/optimize', function (product $product) {
            return view("admin/products/optimize", compact("product"));
        });
        Route::put('{product}', [productController::class, 'update']);
        Route::post('{product}/storeImgs', [productController::class, 'storeImgs']);
        Route::post('{product}/optimize', [productController::class, 'optimize']);
        Route::post('{product}/optimize', [productController::class, 'optimize']);

        Route::delete('destroy', [productController::class, 'destroy']);
        Route::delete('optimize/destroy', [productController::class, 'optimize_destroy']);
    });
});


Route::prefix("products")->middleware('checkRole:products_action')->group(function () {


    Route::get('{product}/variants', [variantsController::class, 'index']);
    Route::post('{product}/storeAttributes', [variantsController::class, 'storeAttributes']);
    Route::post('{product}/variants', [variantsController::class, 'store']);

    Route::put('variants/updateStock', [variantsController::class, 'updateStock']);
    Route::delete('variant/destroy', [variantsController::class, 'destroy']);

    Route::delete('variants/bulkDelete', [variantsController::class, 'bulkDelete']);
});


Route::get('products/ajaxVariant', [variantsController::class, 'ajaxVariant']);


Route::prefix("ads")->middleware('checkRole:ads')->group(function () {
    Route::get('/', [AdsController::class, "index"]);
    Route::post('add', [adsController::class, "store"]);
    Route::get('edit/{id}', [adsController::class, 'edit_page']);
    Route::post('update', [adsController::class, 'update']);
    Route::delete('destroy', [adsController::class, 'delete']);

    Route::post('showHideAds', [adsController::class, 'showHideAds']);
});


Route::prefix("pages")->middleware('checkRole:pages')->group(function () {
    Route::get('/', [pageContoller::class, 'index']);
    Route::get('create', [pageContoller::class, 'create']);
    Route::post('/', [pageContoller::class, 'store']);
    Route::DELETE('destroy', [pageContoller::class, 'destroy']);
    Route::get('changeOrder', [pageContoller::class, 'changeOrder']);

    Route::get('{page}/edit', [pageContoller::class, 'edit']);
    Route::put('{page}', [pageContoller::class, 'update']);
});



Route::prefix("settings")->middleware('checkRole:settings')->group(function () {
    Route::get('/', [settingsController::class, 'index']);
    Route::put('branding', [settingsController::class, "branding_update"]);
    Route::put('social-media', [settingsController::class, "socialMediaUpdate"]);
});



Route::prefix("deliveryPrice")->middleware('checkRole:settings')->group(function () {
    Route::get('changeOrder', [deliveryPriceController::class, 'changeOrder']);
    Route::DELETE('destroy', [deliveryPriceController::class, 'destroy']);
    Route::put('edit', [deliveryPriceController::class, 'edit']);
    Route::post('/', [deliveryPriceController::class, 'store']);
});



Route::prefix("orders")->group(function () {
    Route::get('GetOrderDetailsAjax/{id}', [OrderLogicController::class, "GetOrderDetailsAjax"]);
    Route::get('GetOrderDetailsAjaxReference/{id}', [OrderLogicController::class, "GetOrderDetailsAjaxReference"]);

    Route::middleware('checkRole:orders_show')->group(function () {
        Route::get('/', [OrderViewsController::class, "AllOrders"]);
        Route::get('search', [OrderViewsController::class, "search"]);
    });


    Route::middleware('checkRole:orders_confrim')->group(function () {
        Route::get('{order}/show', [OrderViewsController::class, "show"]);
        Route::get('{order}/statusLogs', [OrderViewsController::class, "statusLogs"]);
        Route::get('details/{id}/edit',  [OrderViewsController::class, 'edit']);

        Route::get('print/{references}',  [OrderViewsController::class, 'print']);



        Route::delete('details/destroy',  [OrderLogicController::class, 'destroyDetails']);
        Route::get('{order}/tryAgian', [OrderLogicController::class, "tryAgian"]);
        Route::post('{order}/logs', [OrderLogicController::class, "logs_Store"]);
        Route::get('rollback/{reference}', [OrderLogicController::class, "rollback"]);
        Route::put('details/{id}',  [OrderLogicController::class, 'replace']);
        Route::put('{order}',  [OrderLogicController::class, 'update']);

        Route::prefix("notes")->group(function () {
            Route::post('/', [OrderLogicController::class, "store_notes"]);
            Route::get('{id}/delete', [OrderLogicController::class, "delete_notes"]);
            Route::put('update', [OrderLogicController::class, "update_notes"]);
        });
    });


    Route::middleware('checkRole:order_commissions')->group(function () {
        Route::get("order_commissions", [OrderViewsController::class, "order_commissions"]);
        Route::get("order_commissions/search", [OrderViewsController::class, "order_commissions_search"]);
        Route::get("ExpensesAndCommissionsHistory", [OrderViewsController::class, "ExpensesAndCommissionsHistory"]);
    });
});


Route::middleware('checkRole:orders_confrim')->group(function () {
    Route::get('getVariant/{id}', [marketerController::class, 'getVariant']);
    Route::post('cart',  [OrderLogicController::class, 'addMoreToOrder']);


    Route::prefix("toUser/products")->group(function () {
        Route::get('/',  [OrderViewsController::class, 'productsPage']);
        Route::get('search',  [OrderViewsController::class, 'productsSearch']);
        Route::get('{slug}',  [OrderViewsController::class, 'showProductPage']);
    });
});


Route::prefix("orders/actions")->middleware('checkRole:order_action')->group(function () {
    Route::post('to_turbo', [ordersActions::class, "to_turbo"]);
    Route::post('to_return', [ordersActions::class, "to_return"]);
    Route::get('{reference}/toReady', [ordersActions::class, "to_ready"]);
    Route::get('{references}/required_producst', [ordersActions::class, "required_producst"]);
    Route::get('{reference}/to_failed ', [ordersActions::class, "toFailed"]);
    Route::post('change_bulk_orders_status', [ordersActions::class, "change_bulk_orders_status"]);
});

Route::prefix("tools")->middleware('checkRole:order_action')->group(function () {
    Route::get('waitingOrders', [toolsControler::class, 'waitingOrders']);
    Route::post('change_order_status', [toolsControler::class, 'change_order_status']);
});


Route::prefix("invoices")->middleware('checkRole:invoices')->group(function () {
    Route::get('traders', [invoiceController::class, 'traders']);
    Route::get('/', [invoiceController::class, 'index']);
    Route::get('search', [invoiceController::class, 'search']);
    Route::get('{user}', [invoiceController::class, 'create']);
    Route::post('/', [invoiceController::class, 'store']);
    Route::get('{invoice}/show', [invoiceController::class, 'show']);
});


Route::prefix("reports")->middleware('checkRole:reports')->group(function () {
    Route::get('user_commissions', [reportsController::class, 'user_commissions_get']);
    Route::post('user_commissions_post', [reportsController::class, 'user_commissions_post']);


    Route::get('products_stock_in_orders', [reportsController::class, 'products_stock_in_orders_get']);
    Route::post('products_stock_in_orders_post', [reportsController::class, 'products_stock_in_orders_post']);


    Route::get('marketer_profits_losses', [reportsController::class, 'marketer_profits_losses_get']);
    Route::post('marketer_profits_losses_post', [reportsController::class, 'marketer_profits_losses_post']);
});


Route::post("get_take_users", [reportsController::class, "get_take_users"])->middleware('checkRole:order_action');


Route::get('notifications', function () {
    return view("users/notifications");
});


Route::get('/mark-notification-as-read/{id}', function ($id) {

    $notification = auth()->user()->notifications->find($id);


    if ($notification) {
        $notification->markAsRead();
    }

    return response()->json(['message' => 'Notification marked as read']);
});

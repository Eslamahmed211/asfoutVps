<?php

return [
    'permissions' => [
        "المستخدمين" => ["عرض" => "users_show",   "الاجراءات" => "users_action" , 'طلبات السحب' => "users_withdraws"],
        "المنتجات" =>  ["عرض" => "products_show",   "الاجراءات" => "products_action"],
        "الفواتير" => ["كل الصلاحيات" => "invoices"],
        "التقارير" => ["كل الصلاحيات" => "reports"],
        "البنرات الاعلانية" => ["كل الصلاحيات" => "ads"],
        "الصفحات" => ["كل الصلاحيات" => "pages"],
        "اعدادات الموقع" => ["كل الصلاحيات" => "settings"],

        "الاوردرات" => [
            "عرض" => 'orders_show',
            "مراجعة الاوردرات" => 'orders_confrim',
            "عمولات الاوردرات" => "order_commissions",
            "شحن وتغير حالة الاوردرات" => "order_action",
        ],


    ]
];

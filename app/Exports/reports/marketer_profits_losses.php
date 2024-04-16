<?php

namespace App\Exports\reports;

use App\Models\order;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class marketer_profits_losses implements FromArray, WithHeadings
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'كود المستخدم',
            'الاسم',
            'رقم التليفون',
            'اجمالي التسليمات',
            'اجمالي المرتجعات',
            'الصافي',
        ];
    }
    // فشل التوصيل يدوي

    public function array(): array
    {

        $all = [];
        set_time_limit(0);


        if ($this->data["user_id"] != null) {
            $users = [];
            array_push($users, User::find($this->data["user_id"]));
        } else {
            $users = user::whereIn("role", ["user"])->get();
        }


        foreach ($users as $user) {


            $ordersDeliverd = order::whereIn("status", ["تم التوصيل", "مكتمل"])->IdAndModerators($user->id);

            if (!empty($this->data["date"])) {

                $date = $this->data["date"];

                $dates = explode(" to ", $date);

                $startDate = $dates[0];
                $endDate = $dates[1] ?? "";

                $date != "" ? $ordersDeliverd = $ordersDeliverd->whereDate("created_at", '>=', $startDate) : "";
                isset($endDate) && !empty($endDate) ? $ordersDeliverd = $ordersDeliverd->whereDate("created_at", '<=', $endDate) : $ordersDeliverd = $ordersDeliverd->whereDate("created_at", '<=', $startDate);
            }


            $ordersDeliverd = $ordersDeliverd->get();
            $totalDeliverd = SUM_IN_ORDER_DETAILS_USING_COLUMNS($ordersDeliverd, "systemComissation", "qnt") - SUM_IN_ORDER_DETAILS_USING_COLUMNS($ordersDeliverd, "ponus", "qnt");

            $failed1 = order::whereIn("status", ["فشل التوصيل"])->IdAndModerators($user->id);


            if (!empty($this->data["date"])) {

                $date = $this->data["date"];

                $dates = explode(" to ", $date);

                $startDate = $dates[0];
                $endDate = $dates[1] ?? "";

                $date != "" ? $failed1 = $failed1->whereDate("created_at", '>=', $startDate) : "";
                isset($endDate) && !empty($endDate) ? $failed1 = $failed1->whereDate("created_at", '<=', $endDate) : $failed1 = $failed1->whereDate("created_at", '<=', $startDate);
            }

            $failed1 = $failed1->sum("delivery_price") * 20 /100 ;


            array_push($all, [
                $user->id,
                $user->name,
                $user->mobile,
                $totalDeliverd,
                $failed1,
                $totalDeliverd - $failed1,
            ]);
        }
        return $all;
    }
}

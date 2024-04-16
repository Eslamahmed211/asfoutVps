<?php

namespace App\Exports\reports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromArray, WithHeadings
{
    protected $orders;
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    protected $headings =  [
        "كود الاوردر",
        "كود الشحنة",
        "اسم المسوق",
        "رقم المسوق",
        "المودريتور",
        "رقم المودريتور",
        "حالة الطلب",
        "اجمالي الاوردر",
        "عمولة المسوق",
        "خصم",
        "اضافة",
        "بونص",
        "صافي العمولة",
        "المحافظة",
        "سعر الشحن",
        "عمولة الموقع",
        "الصافي بعد البونص",
        "تاريخ اضافة الاوردر",
        "اسم العميل",
        "رقم العميل",
        "عنوان العميل",
        "محتوي الاوردر",
    ];

    public function headings(): array
    {
        return $this->headings;
    }

    public function array(): array
    {
        $all = [];

        if ($this->orders == null) {
            array_push($all, []);
        }


        foreach ($this->orders as $order) {

            $array = [];
            $data = ORDER_ALL_DETAILS($order);

            foreach ($this->headings as $heading) {
                array_push($array, $data[$heading]);
            }
            array_push($all, $array);
        }


        return $all;
    }
}

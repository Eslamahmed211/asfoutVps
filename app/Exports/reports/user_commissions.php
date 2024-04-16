<?php

namespace App\Exports\reports;

use App\Models\commission_history;
use App\Models\expenses_and_commissions;
use App\Models\User;
use App\Models\withdraw;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class user_commissions implements FromArray, WithHeadings
{

    protected  $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function headings(): array
    {
        return [
            'كود المستخدم',
            'الاسم',
            'رقم التليفون',
            'اجمالي العمولات',
            'اجمالي المصروفات',
            'تم اضافة',
            'تم خصم',
            'لديه بالمحفظة',
            'الصافي',
        ];
    }

    public function array(): array
    {

        if ($this->user != null) {

            $user = User::find($this->user);

            $ch = commission_history::where("user_id", $user->id)->sum("commission");
            $wd = withdraw::where("user_id", $user->id)->sum("amount");
            $w = $user->wallet;


            $plus = expenses_and_commissions::where("user_id", $user->id)->where("type", "اضافة")->sum("commission");
            $minus = expenses_and_commissions::where("user_id", $user->id)->where("type", "خصم")->sum("commission");



            return [[
                $user->id,
                $user->name,
                $user->mobile,
                $ch,
                $wd,
                $plus,
                $minus,
                $w,
                $ch - ($wd + $w) + ($plus + ($minus))


            ]];
        } else {
            $all = [];

            $users =  user::whereIn("role", ["user", "trader", "moderator"])->get();

            foreach ($users as $user) {

                $ch = commission_history::where("user_id", $user->id)->sum("commission");
                $wd = withdraw::where("user_id", $user->id)->sum("amount");
                $w = $user->wallet;

                $plus = expenses_and_commissions::where("user_id", $user->id)->where("type", "اضافة")->sum("commission");
                $minus = expenses_and_commissions::where("user_id", $user->id)->where("type", "خصم")->sum("commission");



                array_push($all, [
                    $user->id,
                    $user->name,
                    $user->mobile,
                    $ch,
                    $wd,
                    $plus,
                    $minus,
                    $w,
                    $ch - ($wd + $w) + ($plus + ($minus))
                ]);
            }
            return $all;
        }
    }
}

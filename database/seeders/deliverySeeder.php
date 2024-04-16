<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class deliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                "id" => 1,
                "name" => "القاهرة",
            ],
            [
                "id" => 2,
                "name" => "الجيزة",
            ],
            [
                "id" => 3,
                "name" => "الشرقية",
            ],
            [
                "id" => 4,
                "name" => "الدقهلية",
            ],
            [
                "id" => 5,
                "name" => "البحيرة",
            ],
            [
                "id" => 6,
                "name" => "المنيا",
            ],
            [
                "id" => 7,
                "name" => "القليوبية",
            ],
            [
                "id" => 8,
                "name" => "الإسكندرية",
            ],
            [
                "id" => 9,
                "name" => "الغربية",
            ],
            [
                "id" => 10,
                "name" => "سوهاج",
            ],
            [
                "id" => 11,
                "name" => "أسيوط",
            ],
            [
                "id" => 12,
                "name" => "المنوفية",
            ],
            [
                "id" => 13,
                "name" => "كفر الشيخ",
            ],
            [
                "id" => 14,
                "name" => "الفيوم",
            ],
            [
                "id" => 15,
                "name" => "قنا",
            ],
            [
                "id" => 16,
                "name" => "بني سويف",
            ],
            [
                "id" => 17,
                "name" => "أسوان",
            ],
            [
                "id" => 18,
                "name" => "دمياط",
            ],
            [
                "id" => 19,
                "name" => "الإسماعيلية",
            ],
            [
                "id" => 20,
                "name" => "الأقصر",
            ],
            [
                "id" => 21,
                "name" => "بورسعيد",
            ],
            [
                "id" => 22,
                "name" => "السويس",
            ],
            [
                "id" => 23,
                "name" => "مطروح",
            ],
            [
                "id" => 24,
                "name" => "شمال سيناء",
            ],
            [
                "id" => 25,
                "name" => "البحر الأحمر",
            ],
            [
                "id" => 26,
                "name" => "الوادي الجديد",
            ],
            [
                "id" => 27,
                "name" => "جنوب سيناء",
            ],

        ];


        DB::table('delivery_prices')->insert($data);
    }
}

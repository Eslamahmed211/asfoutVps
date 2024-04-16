<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class settingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $columns = [
            [
                "key" => "website_title",
                "value" => "عصفور"
            ],
            [
                "key" => "website_dis",
                "value" => "افلييت عصفور"
            ],
            [
                "key" => "website_logo",
                "value" => null
            ],
            [
                "key" => "website_fav",
                "value" => null
            ],


            [
                "key" => "facebook",
                "value" => null
            ],
            [
                "key" => "youtube",
                "value" => null
            ],
            [
                "key" => "instagram",
                "value" => null
            ],
            [
                "key" => "whatsapp",
                "value" => null
            ],
            [
                "key" => "phone",
                "value" => null
            ],
            [
                "key" => "email",
                "value" => null
            ],

            [
                "key" => "opening",
                "value" => "1"
            ],

            [
                "key" => "waiting_orders",
                "value" => "48"
            ]
        ];



        DB::table('settings')->insert($columns);
    }
}

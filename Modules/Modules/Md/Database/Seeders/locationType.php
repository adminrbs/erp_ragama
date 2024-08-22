<?php

namespace Modules\Md\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class locationType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /*  DB::table('districts')->insert([

            'district_name' => 'Not Applicable',
            'is_active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]); */

        DB::table('location_types')->insert([
            'location_type_name' => 'Not Applicable',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        DB::table('location_types')->insert([
            'location_type_name' => 'Return Item Store',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        DB::table('location_types')->insert([
            'location_type_name' => 'Main Store',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        DB::table('location_types')->insert([
            'location_type_name' => 'Damadge and sort expired',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);


    }
}

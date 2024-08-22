<?php

namespace Modules\Md\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VehicleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicles')->insert([
            'vehicle_no' =>'AAA-0001',
            'vehicle_name' => 'DODGE',
            'description' => 'PERFORMANCE VEHICLES',
            'vehicle_type_id'=> 1,
            'licence_expire_date' => '2023-01-01',
            'insurance_expire_date' => '2023-01-01',
            'remarks' => 'VEHICLES',
            'status_id'=> 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

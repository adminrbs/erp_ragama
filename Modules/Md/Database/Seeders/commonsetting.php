<?php

namespace Modules\Md\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class commonsetting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('districts')->insert([
            'district_name' => 'Not Applicable',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Ampara',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Anuradhapura',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Badulla',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Batticaloa',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('districts')->insert([
            'district_name' => 'Colombo',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Galle',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Gampaha',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Hambantota',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Jaffna',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Kalutara',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Kandy',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Kegalle',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Kilinochchi',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Kurunegala',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Mannar',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Matale',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Matara',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Monaragala',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Mullaitivu',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Nuwara Eliya',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Polonnaruwa',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Puttalam',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Ratnapura',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('districts')->insert([
            'district_name' => 'Trincomalee',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
         DB::table('districts')->insert([
            'district_name' => 'Vavuniya',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        DB::table('towns')->insert([
            'district_id'=>'1',
            'town_name' => 'Not Applicable',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customer_groups')->insert([

            'group' => 'Not Applicable',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customer_grades')->insert([

            'grade' => 'Not Applicable',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('item_category_level_1s')->insert([

            'category_level_1' => 'Not Applicable',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('item_category_level_2s')->insert([
            'Item_category_level_1_id'   => 1,
            'category_level_2' => 'Not Applicable',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('item_category_level_3s')->insert([
            'Item_category_level_2_id'   => 1,
            'category_level_3' => 'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('employee_designations')->insert([
            'employee_designation' => 'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_statuses')->insert([
            'employee_status' => 'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('supplier_groups')->insert([
            'supplier_group_name' => 'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        

        DB::table('item_altenative_names')->insert([
            'item_altenative_name' => 'Not Applicable',
            'status_id' => '1',
            //'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('vehicle_types')->insert([
            'vehicle_type' => 'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
       DB::table('employees')->insert([
            'employee_code' => '01',
            'employee_name' => 'Not Applicable',
            'office_mobile' => '1',
            'office_email' => '1',
            'persional_mobile' => '1',
            'persional_fixed' => '1',
            'persional_email' => '1',
            'address' => '1',
            'desgination_id' => '1',
            'report_to' => '1',
            'date_of_joined' => '2023-05-19 09',
            'date_of_resign' => '2023-05-19 09',
            'note' => '1',
            'status_id' => '1',
            'mobile_user_name' => 'kfd',
            'mobile_app_password' => '123',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sales_return_resons')->insert([
            'sales_return_resons' =>'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('supplier_payment_methods')->insert([
            'supplier_payment_method' =>'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('payment_terms')->insert([
            'payment_term_name' =>'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customer_payment_modes')->insert([
            'customer_payment_method' =>'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('delivery_types')->insert([
            'delivery_type_name' =>'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

<?php

namespace Modules\Md\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeDesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Employee disignation

        DB::table('employee_designations')->insert([
            'employee_designation' => 'Not Applicable',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'General',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Branch Manager',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Marketing Manager',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Sales Manager',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Area Manager',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Sales Representative',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Cash collector – cash /cheque',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Cashier',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('employee_designations')->insert([
            'employee_designation' => 'Driver',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employee_designations')->insert([
            'employee_designation' => 'Delivery Helper',
            'is_active' => '1',
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
    }
}

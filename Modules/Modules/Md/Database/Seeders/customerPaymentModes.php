<?php

namespace Modules\Md\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class customerPaymentModes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_payment_modes')->insert([
            'customer_payment_method' => 'Cash',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('customer_payment_modes')->insert([
            'customer_payment_method' => 'Cheque',
            'is_active' => 1,
            'system' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

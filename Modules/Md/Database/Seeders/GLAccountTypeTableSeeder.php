<?php

namespace Modules\Md\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GLAccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $glAccountTypes = [
            'Revenue',
            'Cost Of Sales',
            'Expenses',
            'Current Assets',
            'Non Current Assets',
            'Bank Account',
            'Current Liabilities',
            'Non Current Liabilities',
            'Capital Account',
            'Profit and Loss Brought Forward',
            'Suspense',
            'Petty Cash',
            'Other Income',
        ];

        foreach ($glAccountTypes as $type) {
            DB::table('gl_account_types')->insert([
                'gl_account_type' => $type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

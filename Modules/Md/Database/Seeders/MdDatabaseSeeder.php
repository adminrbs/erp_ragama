<?php

namespace Modules\Md\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MdDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call([
           /*  commonsetting::class,
            locationType::class,
            SupplyGroupTableSeeder::class,
            TownTableSeeder::class,
            VehicleTableSeeder::class,  */
            /*  EmployeeDesignationsTableSeeder::class, */
           /*  customerPaymentModes::class */
            
           GLAccountTypeTableSeeder::class

        ]);

        // $this->call("OthersTableSeeder");
    }
}

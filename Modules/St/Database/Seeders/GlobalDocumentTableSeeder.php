<?php

namespace Modules\St\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GlobalDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('global_documents')->insert([
            ['document_number' => '210','prefix' => 'INV-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['document_number' => '120','prefix' => 'GRN-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['document_number' => '220','prefix' => 'SRT-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()], 
            ['document_number' => '130','prefix' => 'GRT-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['document_number' => '100','prefix' => 'PRQ-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['document_number' => '110','prefix' => 'POR-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['document_number' => '200','prefix' => 'SOR-','prefix_enable'=>'1','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            

        ]);
    }
}

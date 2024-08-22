<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Md\Entities\supply_group;

class salesAnalyst extends Controller
{
    //load supply groups as sales analysts to sales invoice and sales return
    public function loadSupplyGroupsAsSalesAnalyst(){
        $supplyGroups = supply_group::all();
        if ($supplyGroups) {
            return response()->json($supplyGroups);
        } else {
            return response()->json(['status' => false]);
        }
    }
}

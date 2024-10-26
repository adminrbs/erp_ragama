<?php

namespace App\Http\Controllers;

use App\Models\global_setting;
use Illuminate\Http\Request;

class ChequeReferenceNumberController extends Controller
{
    public static function customerChequeReferenceGenerator(){
        $new_reference_number = global_setting::first()->increment('customer_cheque_reference_number');
        return $new_reference_number; 
    }

    public static function ChequeSupplierReferenceGenerator(){
        $new_reference_number = global_setting::first()->increment('supplier_cheque_reference_number');
        return $new_reference_number;
    }
}

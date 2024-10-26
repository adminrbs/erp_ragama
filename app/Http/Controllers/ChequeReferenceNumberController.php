<?php

namespace App\Http\Controllers;

use App\Models\global_setting;
use Illuminate\Http\Request;

class ChequeReferenceNumberController extends Controller
{
    public static function customerChequeReferenceGenerator()
    {
        $global_setting = global_setting::first();
        $global_setting->increment('customer_cheque_reference_number'); // Increment the value by 1
        return $global_setting->customer_cheque_reference_number; // Return the updated value
    }
    

    public static function ChequeSupplierReferenceGenerator(){
        $global_setting = global_setting::first();
        $global_setting->increment('supplier_cheque_reference_number');
        return $global_setting->supplier_cheque_reference_number;;
    }
}

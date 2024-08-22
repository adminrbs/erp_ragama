<?php

namespace App\Http\Controllers;

use App\Models\global_setting;
use Illuminate\Http\Request;

class CompanyDetailsController extends Controller
{
    
    
    
    public static function CompanyName(){
       
        return global_setting::first()->company_name; 
    }

    public static function CompanyNumber(){
        return global_setting::first()->contact_details;  
    }
    public static function CompanyAddress(){
        return global_setting::first()->company_address;    
    }

    public static function CompanyContactDetails(){
        return global_setting::first()->contact_details;
    }
    public static function companyimage(){

    return "images/icon.svg";
    }
    

}

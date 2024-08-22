<?php

namespace Modules\Cb\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class CustomerReceiptReportController extends Controller
{ public function printCustomerReceiptReport()
    {
         try{
             $query = 'SELECT items.Item_code, items.item_Name, SUM(item_historys.quantity) AS quantity, items.unit_of_measure
             FROM item_historys
             LEFT JOIN items ON item_historys.item_id = items.item_id
             GROUP BY item_historys.item_id, items.item_Name, items.unit_of_measure';
             $result = DB::select($query);
            
                 $reportViwer = new ReportViewer();
                 $reportViwer->addParameter("CustomerReceipt_tabel", $result);
                 $reportViwer->addParameter('companyName',CompanyDetailsController::CompanyName());
                 $reportViwer->addParameter('companyAddress',CompanyDetailsController::CompanyAddress());
                 $reportViwer->addParameter('companyNumber',CompanyDetailsController::CompanyNumber());
                 return $reportViwer->viewReport('customer_receipt.json');
            
             
         }catch(Exception $ex){
             return $ex;
         }
     }
}

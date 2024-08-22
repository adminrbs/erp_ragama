<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Expectation;
use RepoEldo\ELD\ReportViewer;

class SalesreturndetailsController extends Controller
{
    public function printSalesReturnDetailsReport()
    {
        try {

            $query = "SELECT
            
            sr.order_date,
            sri.external_number,
            l.location_name,
            sr.sales_invoice_id,
            c.customer_name,
            sri.item_name,
            sri.price * sri.quantity AS amount,  
            sr.return_reason_id,
            sri.quantity,
            sri.free_quantity,
            emp.employee_name
            
            
            
        FROM
            sales_return_items AS sri
        LEFT JOIN
            sales_returns AS sr ON sri.sales_return_Id = sr.sales_return_Id
        LEFT JOIN
            customers AS c ON sr.customer_id = c.customer_id
        LEFT JOIN
        	locations AS l ON sr.location_id = l.location_id
        LEFT JOIN
        	employees AS emp ON sr.employee_id=emp.employee_id";
            $result = DB::select($query);
           
                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("SalesReturn_tabaledata", $result);
                $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
                $reportViwer->addParameter('companyName',CompanyDetailsController::CompanyName());
                $reportViwer->addParameter('companyAddress',CompanyDetailsController::CompanyAddress());
                $reportViwer->addParameter('companyNumber',CompanyDetailsController::CompanyNumber());
                return $reportViwer->viewReport('salesreturnDetails.json');
           
            
        }catch(Expectation $ex){
            return $ex;
        }
    }


}

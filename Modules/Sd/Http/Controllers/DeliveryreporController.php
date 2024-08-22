<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class DeliveryreporController extends Controller

{
    public function delivery_report($id)
    {

        try {

            $quary = "SELECT T.town_id,T.townName,H.external_number AS Invoice_number , C.customer_name,C.primary_address,employees.employee_name,employees.office_mobile,H.total_amount
            FROM sales_invoices H
            INNER JOIN customers C ON C.customer_id = H.customer_id 
            INNER JOIN delivery_plans  DP ON H.delivery_plan_id=DP.delivery_plan_id   
            INNER JOIN town_non_administratives  T ON T.town_id=C.town 
            INNER JOIN employees ON H.employee_id=employees.employee_id
            WHERE H.delivery_plan_id='" . $id . "'
           ";

            $result = DB::select($quary);

            $resulstownid = DB::select('select town_id,townName from town_non_administratives');
            $towntablearray = [];


            $titel = [];

            $reportViwer = new ReportViewer();
            foreach ($resulstownid as $townid) {
                $table = [];


                foreach ($result as $towondata) {

                    if ($towondata->town_id == $townid->town_id) {

                        array_push($table, $towondata);
                    }
                }



                if (count($table) > 0) {

                    array_push($towntablearray, $table);

                    array_push($titel, $townid->townName);
                    $reportViwer->addParameter('abc', $titel);
                } //dd($table);
            }

            //$reportViwer->addParameter("Picking_list_tabaledata", $result);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('deliveryPlanNo', $this->getDeliveryNo($id));
            $reportViwer->addParameter('vehicleData', $this->vehicleData($id));

            $reportViwer->addParameter('group_data', [$towntablearray]);
            //$reportViwer->addParameter('abc',[1,2,3]);

            // $reportViwer->addParameter('townname', [[$townNames]]);
            return $reportViwer->viewReport('delivery_report.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }


    private function getDeliveryNo($id)
    {
        //dd($id);
        $query = "SELECT CONCAT('Delivery Plan No :',delivery_plans.external_number) AS delivery_no
        FROM delivery_plans WHERE delivery_plans.delivery_plan_id = '" . $id . "'";
        //dd($query);
        $result = DB::select($query);
        //dd($result);
        if (count($result) > 0) {
            return $result[0]->delivery_no;
        }
        return "";
    }

    private function vehicleData($id){
        $qry = 'SELECT 
    CONCAT(
        "Vehicle No: ", V.vehicle_no, " / ",
        "Driver: ", E_driver.employee_name, " / ",
        "Helper: ", E_helper.employee_name
    ) AS delivery_info
FROM 
    delivery_plans DP
    INNER JOIN vehicles V ON DP.vehicle_id = V.vehicle_id
    INNER JOIN employees E_driver ON DP.driver_id = E_driver.employee_id
    INNER JOIN employees E_helper ON DP.helper_id = E_helper.employee_id
	WHERE
		DP.delivery_plan_id = '.$id;

$result = DB::select($qry);
//dd($result);
if (count($result) > 0) {
    return $result[0]->delivery_info;
}

    }
}

<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\DeliveryPlan;
use RepoEldo\ELD\ReportViewer;

class PickingListController extends Controller
{

    public function pickingList($id)
    {
        try {

            $quary = "SELECT I.supply_group_id, I.Item_code  , I.item_Name , I.package_unit 
            , SUM(D.quantity+D.free_quantity)*-1 AS quantity,H.delivery_plan_id FROM   sales_invoices H 
            
            INNER JOIN sales_invoice_items D ON H.sales_invoice_Id=D.sales_invoice_Id 
            INNER JOIN items I ON  I.item_id=D.item_id 
          
            INNER JOIN supply_groups SG ON SG.supply_group_id=I.supply_group_id 
            WHERE H.picking_list_id=$id
            GROUP BY I.item_id";

            $result = DB::select($quary);

            $resulsgroupid = DB::select('select supply_group_id,supply_group from supply_groups');
            $grouptablearray = [];

            $reportViwer = new ReportViewer();
            $titel = [];
            $deliery_plan_id ='';
            foreach ($resulsgroupid as $groupid) {
                $table = [];


                foreach ($result as $groupdata) {


                    $deliery_plan_id = $groupdata->delivery_plan_id;
                    


                    if ($groupdata->supply_group_id == $groupid->supply_group_id) {
                        array_push($table, $groupdata);
                    }
                }
                //dd($titel);
                if (count($table) > 0) {

                    array_push($grouptablearray, $table);
                    array_push($titel, $groupid->supply_group);
                    $reportViwer->addParameter('grouptitel', $titel);
                }
            }

            //$supplygroup = array_column($result, 'supply_group');

            //dd($supplygroup);


            //$reportViwer->addParameter("Picking_list_tabaledata", $result);
            $plan = DeliveryPlan::find($deliery_plan_id);
            $ref_num = $plan->external_number;
            $picking_qry = DB::Select("SELECT LPAD($id, 5, '0') as id");
            $picking_list_id_ = $picking_qry[0]->id;
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('pickingListInvoice', $this->getPickingListInvoice($id));
            $reportViwer->addParameter('pickingListInvoiceCount', $this->getPickingListInvoiceCount($id));
            $reportViwer->addParameter('pickingDeliveryNo', $this->getPickingDeliveryNo($id));
            $reportViwer->addParameter('pickingListRoute', $this->getPickingListRoute($id));

            $reportViwer->addParameter('group_data', [$grouptablearray]);
             $reportViwer->addParameter('picking_title', 'Picking List :'.$ref_num.'-'.$picking_list_id_);
            return $reportViwer->viewReport('picking_list.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }


    private function getPickingListInvoice($id)
    {
        $query = "SELECT CONCAT('Invoice List : ',GROUP_CONCAT(manual_number SEPARATOR ', ')) AS invoice
        FROM sales_invoices WHERE sales_invoices.picking_list_id = '" . $id . "'";
        $result = DB::select($query);
        if (count($result) > 0) {
            return $result[0]->invoice;
        }
        return "";
    }

    private function getPickingListRoute($id)
    {
        $query = "SELECT CONCAT('Route List : ',GROUP_CONCAT(DISTINCT routes.route_name SEPARATOR ', ')) AS routes
        FROM sales_invoices
        INNER JOIN delivery_plan_route_lists ON sales_invoices.delivery_plan_id = delivery_plan_route_lists.delivery_plan_id
        INNER JOIN routes ON delivery_plan_route_lists.route_id = routes.route_id  WHERE sales_invoices.picking_list_id  = '" . $id . "'";
        $result = DB::select($query);
        if (count($result) > 0) {
            return $result[0]->routes;
        }
        return "";
    }

    private function getPickingListInvoiceCount($id)
    {
        $query = "SELECT  CONCAT('No of invoice : ',COUNT(*)) AS invoiceCount
        FROM sales_invoices WHERE sales_invoices.picking_list_id = '" . $id . "'";
        $result = DB::select($query);
        if (count($result) > 0) {
            return $result[0]->invoiceCount;
        }
        return 0;
    }

    private function getPickingDeliveryNo($id)
    {
        $query = "SELECT CONCAT('Picking List No :',LPAD(sales_invoices.picking_list_id,5,'0'),' ','Delivery Plan No :',delivery_plans.delivery_ref_no) AS picking_delivery_no
        FROM sales_invoices INNER JOIN delivery_plans ON sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id WHERE sales_invoices.picking_list_id = '" . $id . "'";
        $result = DB::select($query);
        if (count($result) > 0) {
            return $result[0]->picking_delivery_no;
        }
        return "";
    }
}

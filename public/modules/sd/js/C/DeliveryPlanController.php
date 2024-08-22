<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\delivery_status;
use Modules\Sd\Entities\DeliveryPlan;
use Modules\Sd\Entities\DeliveryPlanPackingList;
use Modules\Sd\Entities\DeliveryPlanRouteList;
use Modules\Sd\Entities\DeliveryPlanTownList;
use Modules\Sd\Entities\District;
use Modules\Sd\Entities\employee;
use Modules\Sd\Entities\route;
use Modules\Sd\Entities\sales_invoice;
use Modules\Sd\Entities\TownNonAdministrative;
use Modules\Sd\Entities\vehicle;

class DeliveryPlanController extends Controller
{

    public function loadDeliveryPlanSelect2()
    {
        try {
            $vehicle = vehicle::all();
            $salserep = employee::where('desgination_id', '=', '7')->get();
            $driver = employee::where('desgination_id', '=', '10')->get();
            $helper = employee::where('desgination_id', '=', '11')->orWhere('desgination_id', '=', '10')->get();
            $route = route::all();
            $district = District::all();
            $delivery_statuses = delivery_status::where('statuse_id','!=',4)->get();

            return response()->json([
                "status" => true, "data" => [
                    "vehicle" => $vehicle,
                    "salserep" => $salserep,
                    "driver" => $driver,
                    "helper" => $helper,
                    "route" => $route,
                    "district" => $district,
                    "delviery_statuses" => $delivery_statuses
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    public function loadLownsSelect2($district)
    {
        try {
            $towns = TownNonAdministrative::where('district_id', '=', $district)->get();
            return response()->json([
                "status" => true, "data" => $towns
            ]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    public function saveDeliveryPlan(Request $request)
    {

        try {
            $deliveryPlan = new DeliveryPlan();
            $deliveryPlan->delivery_ref_no = $request->get('delivery_ref_no');
            $deliveryPlan->internal_number = IntenelNumberController::getNextID();
            $deliveryPlan->external_number = $request->get('delivery_ref_no');
            $deliveryPlan->document_number = 510;
            $deliveryPlan->vehicle_id = $request->get('vehicle_id');
            //$deliveryPlan->sales_rep_id = $request->get('sales_rep_id');
            $deliveryPlan->driver_id = $request->get('driver_id');
            $deliveryPlan->helper_id = $request->get('helper_id');
            //$deliveryPlan->route_id = $request->get('route_id');
            $deliveryPlan->date_from = $request->get('date_from');
            $deliveryPlan->date_to = $request->get('date_to');
            $deliveryPlan->status = $request->get('status');
            $deliveryPlan->created_by = Auth::user()->id;
            if ($deliveryPlan->save()) {
                $routes = json_decode($request->get('routes'));
                $this->saveDeliveryPlanRoutes($routes, $deliveryPlan->delivery_plan_id);
                $towns = json_decode($request->get('towns'));
                $this->saveDeliveryPlanTowns($towns, $request->get('district_id'), $deliveryPlan->delivery_plan_id);
                return response()->json(["status" => true]);
            }
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    private function saveDeliveryPlanRoutes($routes, $delivery_plan_id)
    {

        try {
            DeliveryPlanRouteList::where('delivery_plan_id', '=', $delivery_plan_id)->delete();
            foreach ($routes as $json_route) {
                $route = json_decode($json_route);
                $deliveryRoute = new DeliveryPlanRouteList();
                $deliveryRoute->delivery_plan_id = $delivery_plan_id;
                $deliveryRoute->route_id = $route;
                $deliveryRoute->save();
            }
            return true;
        } catch (Exception $ex) {
            return $ex;
        }
    }


    private function saveDeliveryPlanTowns($towns, $district_id, $delivery_plan_id)
    {

        try {
            DeliveryPlanTownList::where('delivery_plan_id', '=', $delivery_plan_id)->delete();
            foreach ($towns as $json_town) {
                $town = json_decode($json_town);
                $deliveryTown = new DeliveryPlanTownList();
                $deliveryTown->delivery_plan_id = $delivery_plan_id;
                $deliveryTown->district_id = 0;
                $deliveryTown->town_id = $town->town_id;
                $deliveryTown->order = $town->order;
                $deliveryTown->save();
            }
            return true;
        } catch (Exception $ex) {
            return $ex;
        }
    }



    public function getDeliveryPlansDeliverd()
    {

        $query = "SELECT 
        delivery_plans.external_number,
        delivery_plans.delivery_plan_id,
        delivery_plans.external_number AS delivery_ref_no,
        (SELECT employees.employee_name FROM employees WHERE employees.employee_id = delivery_plans.driver_id) AS driver,
        (SELECT employees.employee_name FROM employees WHERE employees.employee_id = delivery_plans.helper_id) AS helper,
        (SELECT vehicles.vehicle_name FROM vehicles WHERE vehicles.vehicle_id = delivery_plans.vehicle_id) AS vehicle_name,
        (SELECT  COUNT(*) AS invoiceCount
        FROM sales_invoices WHERE sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id) AS invoice_count,
        (SELECT GROUP_CONCAT(DISTINCT sales_invoices.manual_number SEPARATOR ', ') AS invoice
        FROM sales_invoices WHERE sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id) AS invoice,
		    (SELECT GROUP_CONCAT(DISTINCT routes.route_name SEPARATOR ', ') AS routes
        FROM routes
        INNER JOIN delivery_plan_route_lists ON routes.route_id = delivery_plan_route_lists.route_id
        INNER JOIN delivery_plans AS DPL ON delivery_plan_route_lists.delivery_plan_id = DPL.delivery_plan_id  WHERE DPL.delivery_plan_id  = delivery_plans.delivery_plan_id) AS route,
		delivery_plans.date_from,
        delivery_plans.date_to,
        delivery_plans.`status` FROM delivery_plans WHERE delivery_plans.status = '4' ORDER BY delivery_plans.delivery_plan_id DESC";

        return response()->json(["data" => DB::select($query)]);
    }

    public function getVehicleOutDeliveryPlans()
    {

        $query = "SELECT 
        delivery_plans.external_number,
        delivery_plans.delivery_plan_id,
        delivery_plans.external_number AS delivery_ref_no,
        (SELECT employees.employee_name FROM employees WHERE employees.employee_id = delivery_plans.driver_id) AS driver,
        (SELECT employees.employee_name FROM employees WHERE employees.employee_id = delivery_plans.helper_id) AS helper,
        (SELECT vehicles.vehicle_name FROM vehicles WHERE vehicles.vehicle_id = delivery_plans.vehicle_id) AS vehicle_name,
        (SELECT  COUNT(*) AS invoiceCount
        FROM sales_invoices WHERE sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id) AS invoice_count,
        (SELECT GROUP_CONCAT(DISTINCT sales_invoices.manual_number SEPARATOR ', ') AS invoice
        FROM sales_invoices WHERE sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id) AS invoice,
		    (SELECT GROUP_CONCAT(DISTINCT routes.route_name SEPARATOR ', ') AS routes
        FROM routes
        INNER JOIN delivery_plan_route_lists ON routes.route_id = delivery_plan_route_lists.route_id
        INNER JOIN delivery_plans AS DPL ON delivery_plan_route_lists.delivery_plan_id = DPL.delivery_plan_id  WHERE DPL.delivery_plan_id  = delivery_plans.delivery_plan_id) AS route,
		delivery_plans.date_from,
        delivery_plans.date_to,
        delivery_plans.`status` FROM delivery_plans WHERE delivery_plans.status = '3' ORDER BY delivery_plans.delivery_plan_id DESC";

        return response()->json(["data" => DB::select($query)]);
    }

    public function getDeliveryPlansNoneDeliverd()
    {

        $query = "SELECT 
        delivery_plans.external_number,
        delivery_plans.delivery_plan_id,
        delivery_plans.external_number AS delivery_ref_no,
        (SELECT employees.employee_name FROM employees WHERE employees.employee_id = delivery_plans.driver_id) AS driver,
        (SELECT employees.employee_name FROM employees WHERE employees.employee_id = delivery_plans.helper_id) AS helper,
        (SELECT vehicles.vehicle_name FROM vehicles WHERE vehicles.vehicle_id = delivery_plans.vehicle_id) AS vehicle_name,
        (SELECT  COUNT(*) AS invoiceCount
        FROM sales_invoices WHERE sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id) AS invoice_count,
        (SELECT GROUP_CONCAT(DISTINCT sales_invoices.manual_number SEPARATOR ', ') AS invoice
        FROM sales_invoices WHERE sales_invoices.delivery_plan_id = delivery_plans.delivery_plan_id) AS invoice,
		    (SELECT GROUP_CONCAT(DISTINCT routes.route_name SEPARATOR ', ') AS routes
        FROM routes
        INNER JOIN delivery_plan_route_lists ON routes.route_id = delivery_plan_route_lists.route_id
        INNER JOIN delivery_plans AS DPL ON delivery_plan_route_lists.delivery_plan_id = DPL.delivery_plan_id  WHERE DPL.delivery_plan_id  = delivery_plans.delivery_plan_id) AS route,
		delivery_plans.date_from,
        delivery_plans.date_to,
        delivery_plans.`status` FROM delivery_plans WHERE delivery_plans.status != '4' ORDER BY delivery_plans.delivery_plan_id DESC";

        return response()->json(["data" => DB::select($query)]);
    }


    public function getTownsFromRoute($route_id)
    {
        $query = 'SELECT DISTINCT
        town_non_administratives.town_id,
        town_non_administratives.townName
        FROM town_non_administratives INNER JOIN customers
        ON town_non_administratives.town_id = customers.town
        WHERE customers.route_id = "' . $route_id . '"';

        return response()->json(["data" => DB::select($query)]);
    }



    public function getDeliveryPlan($delivery_plan_id)
    {
        try {
            $deliveryPlan =  DeliveryPlan::find($delivery_plan_id);
            if ($deliveryPlan) {
                $query = "SELECT routes.route_id,
                routes.route_name,
                delivery_plan_route_lists.delivery_plan_route_list_id
                FROM routes 
                INNER JOIN delivery_plan_route_lists
                ON routes.route_id = delivery_plan_route_lists.route_id
                WHERE delivery_plan_route_lists.delivery_plan_id = '" . $delivery_plan_id . "'";
                $deliveryPlan->delivery_plan_route_list = DB::select($query);
            }

            if ($deliveryPlan) {
                $query = "SELECT town_non_administratives.town_id,
                town_non_administratives.townName,
                delivery_plan_town_lists.`order`
                FROM town_non_administratives INNER JOIN delivery_plan_town_lists
                ON town_non_administratives.town_id = delivery_plan_town_lists.town_id
                WHERE delivery_plan_town_lists.delivery_plan_id =  '" . $delivery_plan_id . "'";
                $deliveryPlan->delivery_plan_town_list = DB::select($query);
            }
            return response()->json(["status" => true, "data" => $deliveryPlan]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }

    public function updateDeliveryPlan(Request $request, $delivery_plan_id)
    {
        try {
            $deliveryPlan =  DeliveryPlan::find($delivery_plan_id);
            $deliveryPlan->delivery_ref_no = $request->get('delivery_ref_no');
            $deliveryPlan->vehicle_id = $request->get('vehicle_id');
            //$deliveryPlan->sales_rep_id = $request->get('sales_rep_id');
            $deliveryPlan->driver_id = $request->get('driver_id');
            $deliveryPlan->helper_id = $request->get('helper_id');
            //$deliveryPlan->route_id = $request->get('route_id');
            $deliveryPlan->date_from = $request->get('date_from');
            $deliveryPlan->date_to = $request->get('date_to');
            $deliveryPlan->status = $request->get('status');
            if ($deliveryPlan->update()) {
                $routes = json_decode($request->get('routes'));
                $this->saveDeliveryPlanRoutes($routes, $deliveryPlan->delivery_plan_id);
                $towns = json_decode($request->get('towns'));
                $this->saveDeliveryPlanTowns($towns, $request->get('district_id'), $deliveryPlan->delivery_plan_id);
                return response()->json(["status" => true]);
            }
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    public function getNonAllocateInvoice($delivery_plan_id)
    {
        try {
            $invoice =  [];
            $query = "SELECT 
            sales_invoices.sales_invoice_Id,
            sales_invoices.manual_number,
            sales_invoices.order_date_time AS date,
            sales_invoices.external_number,
            customers.customer_name,
            town_non_administratives.townName,
            sales_invoices.total_amount,
            sales_invoices.order_date_time,
            0 AS order_no
            FROM sales_invoices
            INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
			INNER JOIN delivery_plan_route_lists ON customers.route_id = delivery_plan_route_lists.route_id
            LEFT JOIN town_non_administratives ON customers.town_id = town_non_administratives.town_id
            WHERE delivery_plan_route_lists.delivery_plan_id = '" . $delivery_plan_id . "' AND (sales_invoices.is_delivery_planned='0' OR sales_invoices.is_postpone_delivery = '1') AND NOT EXISTS (
    SELECT 1 
    FROM sales_returns 
    WHERE sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
);";
            $invoice = DB::select($query);
            return response()->json(["status" => true, "data" => $invoice]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function getAllocatedInvoice($delivery_plan_id)
    {
        try {
            $invoice =  [];
            $query = "SELECT 
            sales_invoices.sales_invoice_Id,
            sales_invoices.manual_number,
            sales_invoices.order_date_time AS date,
            sales_invoices.external_number,
            customers.customer_name,
            town_non_administratives.townName,
            sales_invoices.total_amount,
            sales_invoices.order_date_time,
            sales_invoices.delivery_instruction,
            0 AS order_no
            FROM sales_invoices
            INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
            LEFT JOIN town_non_administratives ON customers.town_id = town_non_administratives.town_id
            WHERE sales_invoices.delivery_plan_id = '" . $delivery_plan_id . "' AND sales_invoices.is_delivery_planned ='1' AND sales_invoices.is_postpone_delivery = '0' AND NOT EXISTS (
    SELECT 1 
    FROM sales_returns 
    WHERE sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
);
";
            $invoice = DB::select($query);
            return response()->json(["status" => true, "data" => $invoice]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function getDeliveryplanPostpone($delivery_plan_id)
    {
        try {
            $invoice =  [];
            $query = "SELECT 
            sales_invoices.sales_invoice_Id,
            sales_invoices.manual_number,
            sales_invoices.order_date_time AS date,
            sales_invoices.external_number,
            customers.customer_name,
            town_non_administratives.townName,
            sales_invoices.total_amount,
            sales_invoices.order_date_time,
            sales_invoices.delivery_instruction,
            0 AS order_no
            FROM sales_invoices
            INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
            LEFT JOIN town_non_administratives ON customers.town_id = town_non_administratives.town_id
            WHERE sales_invoices.delivery_plan_id = '" . $delivery_plan_id . "' AND sales_invoices.is_delivery_planned ='1' AND sales_invoices.is_postpone_delivery = '0'";
            $invoice = DB::select($query);
            return response()->json(["status" => true, "data" => $invoice]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function getNonPickingList($delivery_plan_id, $route_id)
    {
        try {
            $invoice =  [];
            $query = "SELECT 
            sales_invoices.sales_invoice_Id,
            sales_invoices.order_date_time AS date,
            sales_invoices.external_number,
            customers.customer_name,
            town_non_administratives.townName,
            sales_invoices.total_amount,
            sales_invoices.order_date_time,
            0 AS order_no
            FROM sales_invoices
            INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
            LEFT JOIN town_non_administratives ON customers.town_id = town_non_administratives.town_id
            WHERE sales_invoices.delivery_plan_id = '" . $delivery_plan_id . "' AND sales_invoices.is_delivery_planned='1' AND sales_invoices.is_picking_list='0'";
            $invoice = DB::select($query);
            return response()->json(["status" => true, "data" => $invoice]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }




    public function getPickingList($delivery_plan_id, $route_id)
    {
        try {
            $picking_list =  [];
            $query = "SELECT DISTINCT delivery_plan_packing_lists.delivery_plan_packing_list_id,
            LPAD(sales_invoices.picking_list_id,5,'0') AS external_number
            FROM delivery_plan_packing_lists 
            INNER JOIN sales_invoices ON delivery_plan_packing_lists.delivery_plan_packing_list_id = sales_invoices.picking_list_id
            WHERE sales_invoices.delivery_plan_id = '" . $delivery_plan_id . "'";
            $picking_list = DB::select($query);
            return response()->json(["status" => true, "data" => $picking_list]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }





    public function saveDeliveryPlanInvoice(Request $request)
    {



        try {
            $invoices = json_decode($request->get('invoice'));
            foreach ($invoices as $invoice_data) {
                $data = $invoice_data;
                $sales_invoice = sales_Invoice::find($data->sales_invoice_id);
                if ($sales_invoice) {
                    $sales_invoice->delivery_plan_id = $data->delivery_plan_id;
                    $sales_invoice->is_delivery_planned = true;
                    $sales_invoice->is_postpone_delivery = false;
                    $sales_invoice->update();
                }
            }
            return response()->json(["status" => true, "data" => null]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }




    public function saveDeliveryPlanNonPickingInvoice(Request $request)
    {



        try {
            $deliveryPlanPackingList = new DeliveryPlanPackingList();
            $deliveryPlanPackingList->internal_number = IntenelNumberController::getNextID();
            $deliveryPlanPackingList->external_number =  $request->get("external_no");
            if ($deliveryPlanPackingList->save()) {

                $invoices = json_decode($request->get('invoice'));
                foreach ($invoices as $invoice_data) {
                    $data = $invoice_data;
                    $sales_invoice = sales_Invoice::find($data->sales_invoice_id);
                    if ($sales_invoice) {
                        $sales_invoice->delivery_plan_id = $data->delivery_plan_id;
                        $sales_invoice->is_picking_list = true;
                        $sales_invoice->picking_list_id = $deliveryPlanPackingList->delivery_plan_packing_list_id;
                        $sales_invoice->update();
                    }
                }
            }
            return response()->json(["status" => true, "data" => null]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }



    public function updateAllocatedRemark(Request $request)
    {



        try {
            $formData = json_decode($request->get("data"));
            foreach ($formData as $data) {
                $remark_json = json_decode($data);
                $sales_invoice = sales_invoice::find($remark_json->id);
                if ($sales_invoice) {
                    $sales_invoice->delivery_instruction = $remark_json->remark;
                    if (!$sales_invoice->update()) {
                        return response()->json(["status" => false, "data" => null]);
                    }
                }
            }
            return response()->json(["status" => true, "data" => null]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    public function updatePostponeDelivery(Request $request)
    {



        try {
            $formData = json_decode($request->get("data"));
            foreach ($formData as $data) {
                $remark_json = json_decode($data);
                $sales_invoice = sales_invoice::find($remark_json->sales_invoice_id);
                if ($sales_invoice) {
                    $sales_invoice->postpone_reason = $remark_json->reason;
                    $sales_invoice->delivery_plan_id = null;
                    $sales_invoice->is_delivery_planned = 0;
                    $sales_invoice->is_postpone_delivery = 1;
                    $sales_invoice->postpone_by = Auth::user()->id;
                    $sales_invoice->postpone_date_time = date("Y-m-d") . " " . date("h:i:s");
                    $sales_invoice->postponed += 1;
                    if (!$sales_invoice->update()) {
                        return response()->json(["status" => false, "data" => null]);
                    }
                }
            }
            return response()->json(["status" => true, "data" => null]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }

    public function removeRouteFromDeliveryPlan($id)
    {
        try {
            $route = DeliveryPlanRouteList::find($id);
            $status =  $route->delete();
            return response()->json(["status" => $status, "data" => null]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }

    public function isInvoiceToRoute($delivery_plan_id, $route_id)
    {

        try {
            $isInvoice = false;
            $query = "SELECT 
            sales_invoices.sales_invoice_Id
            FROM sales_invoices
            INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
			INNER JOIN delivery_plan_route_lists ON sales_invoices.delivery_plan_id = delivery_plan_route_lists.delivery_plan_id
            LEFT JOIN town_non_administratives ON customers.town_id = town_non_administratives.town_id
            WHERE sales_invoices.delivery_plan_id = '" . $delivery_plan_id . "' AND delivery_plan_route_lists.delivery_plan_route_list_id = '" . $route_id . "' AND sales_invoices.is_delivery_planned ='1' AND sales_invoices.is_postpone_delivery = '0'";
            $result = DB::select($query);
            if(count($result) > 0){
                $isInvoice = true;
            }
            
            return response()->json(["status" =>  $isInvoice, "data" => null]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    //to new model
    public function loadNonAllocatedInvoice_all()
    {
        try {
            $invoice =  [];
            $query = "SELECT 
            sales_invoices.sales_invoice_Id,
            sales_invoices.manual_number,
            sales_invoices.order_date_time AS date,
            sales_invoices.external_number,
            customers.customer_name,
            town_non_administratives.townName,
            sales_invoices.total_amount,
            sales_invoices.order_date_time,
            0 AS order_no
        FROM sales_invoices
        INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
        LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
        WHERE sales_invoices.is_delivery_planned = '0'
        AND NOT EXISTS (
            SELECT 1 
            FROM sales_returns 
            WHERE sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
        )
        ";
            $invoice = DB::select($query);
            return response()->json(["status" => true, "data" => $invoice]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }

    //to new model
    public function showPostponeDeliveryAll()
    {
        try {

            $query = "SELECT 
            sales_invoices.sales_invoice_Id,
            sales_invoices.manual_number,
            sales_invoices.order_date_time AS date,
            sales_invoices.external_number,
            customers.customer_name,
            town_non_administratives.townName,
            sales_invoices.total_amount,
            sales_invoices.order_date_time,
            sales_invoices.remarks ,
            users.`name` as   postpone_by   
            FROM sales_invoices
            INNER JOIN customers ON sales_invoices.customer_id = customers.customer_id
            INNER JOIN users ON users.id=sales_invoices.postpone_by   
            LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id
            WHERE  sales_invoices.is_postpone_delivery = '1' AND NOT EXISTS (
    SELECT 1 
    FROM sales_returns 
    WHERE sales_returns.sales_invoice_id = sales_invoices.sales_invoice_Id
);";
            $invoice = DB::select($query);
            return response()->json(["status" => true, "data" => $invoice]);
        } catch (Exception $ex) {
            return response()->json(["status" => false, "exception" => $ex]);
        }
    }


    //update finish delivery plan
    public function finish_plan($id){
        try{
            $dp = DeliveryPlan::find($id);
            $dp->status = 4;
            if($dp->update()){
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }

            

        }catch(Exception $ex){
            return $ex;
        }
    }
}

<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Prc\Entities\branch;
use Modules\Prc\Entities\location;
use Modules\Prc\Entities\Purchase_request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Prc\Entities\item;
use Modules\Prc\Entities\PurchaseRequestDraft;
use Modules\Prc\Entities\purchase_request_item;
use Modules\Prc\Entities\purchase_request_item_draft;
use Modules\Prc\Entities\purchase_request_other;
use Modules\Prc\Entities\purchase_request_other_draft;


class PurchaseRequestController extends Controller
{
    public function getBranches_view()
    {
        try {
            $branches = branch::all();
            if ($branches) {
                return response()->json($branches);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //getting locations
    public function getLocation($id)
    {
        try {
            $locations = location::where('branch_id', '=', $id)
            ->where('location_type_id', '=', 3)
            ->where('Status','=',1)
            ->get();
            if ($locations) {
                return response()->json($locations);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //addng purchase request
    public function addPurchaseRequest(Request $request, $id)
    {
        try {
            if ($id != "null") {

                $requestDraft = Purchaserequestdraft::find($id)->delete();
                $itemDraft =  purchase_request_item_draft::where("purchase_request_Id", "=", $id)->delete();
                $otherDraft =  purchase_request_other_draft::where("purchase_request_Id", "=", $id)->delete();
            }
            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');
           
            $data = DB::table('branches')->where('branch_id', $bR_id)->get();
            
            $EXPLODE_ID = explode("-",$referencenumber);
            $externalNumber  = '';
        if ($data->count() > 0) {
            $documentPrefix = $data[0]->prefix;
            $externalNumber  =$documentPrefix."-".$EXPLODE_ID[0]."-".$EXPLODE_ID[1];
        }
            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $collectionOther = json_decode($request->input('collectionOther'));
            $purchaseRequest = new purchase_request();
            $purchaseRequest->internal_number = IntenelNumberController::getNextID();
            $purchaseRequest->external_number = $externalNumber; // need to change 
            $purchaseRequest->purchase_request_date_time = $request->input('purchasee_request_date');
            $purchaseRequest->branch_id = $bR_id;
            $purchaseRequest->location_id = $request->input('cmbLocation');
            $purchaseRequest->expected_date = $request->input('DtexpectedDate');
            /* $purchaseRequest->approval_status = $request->input('approval_status'); */
            $purchaseRequest->remarks = $request->input('txtRemarks');
            $purchaseRequest->prepaired_by = $PreparedBy;
            $purchaseRequest->document_number = 100;

            if ($purchaseRequest->save()) {
                $length_firstArray = count($collection);
                $length_secondArray = count($collectionOther);
               
                   
                    //looping first array
                    foreach ($collection as $i) {
                        $item = json_decode($i);
                        foreach ($item as $key => $value) {
                            if (is_string($value) && empty($value)) {
                                $item->$key = null;
                            }
                        }
                        $request_item = new purchase_request_item();
                        $request_item->purchase_request_Id = $purchaseRequest->purchase_request_Id;
                        $request_item->internal_number = $purchaseRequest->internal_number;
                        $request_item->external_number = $purchaseRequest->external_number; // need to change
                        $request_item->item_id = $item->item_id;
                        $request_item->item_name = $item->item_name;
                        $request_item->quantity = $item->qty;
                        $request_item->unit_of_measure = $item->uom;
                        $request_item->package_unit = $item->PackUnit;
                        $request_item->package_size = $item->PackSize;
                        $request_item->save();
                    }
               

                    //looping second array
                    foreach ($collectionOther as $j) {
                        $item = json_decode($j);
                        $request_other = new purchase_request_other();
                        $request_other->purchase_request_Id = $purchaseRequest->purchase_request_Id;
                        $request_other->internal_number = $purchaseRequest->internal_number;
                        $request_other->external_number = $purchaseRequest->external_number;
                        $request_other->description = $item->description;
                        $request_other->quantity = $item->qty;
                        $request_other->save();
                    }
              
            
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //add purchase request draft
    public function addPurchaseRequestDraft(Request $request)
    {
        try {
            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $collectionOther = json_decode($request->input('collectionOther'));
            $purchaseRequest = new Purchaserequestdraft();
            $purchaseRequest->internal_number = 0000;
            $purchaseRequest->external_number =  $request->input('LblexternalNumber'); // need to change 
            $purchaseRequest->purchase_request_date_time = $request->input('purchasee_request_date');
            $purchaseRequest->branch_id = $request->input('cmbBranch');
            $purchaseRequest->location_id = $request->input('cmbLocation');
            $purchaseRequest->expected_date = $request->input('DtexpectedDate');
            /* $purchaseRequest->approval_status = $request->input('approval_status'); */
            $purchaseRequest->remarks = $request->input('txtRemarks');
            $purchaseRequest->prepaired_by = $PreparedBy;
            $purchaseRequest->document_number = 100;
            $purchaseRequest->your_reference_number = $request->input('txtYourReference');

            if ($purchaseRequest->save()) {
                //looping first array
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $request_item = new purchase_request_item_draft();
                    $request_item->purchase_request_Id = $purchaseRequest->purchase_request_Id;
                    $request_item->internal_number = 0000;
                    $request_item->external_number = $purchaseRequest->external_number; // need to change
                    $request_item->item_id = $item->item_id;
                    $request_item->item_name = $item->item_name;
                    $request_item->quantity = $item->qty;
                    $request_item->unit_of_measure = $item->uom;
                    $request_item->package_unit = $item->PackUnit;
                    $request_item->package_size = $item->PackSize;
                    $request_item->save();
                }

                //looping second array
                foreach ($collectionOther as $j) {
                    $item = json_decode($j);
                    $request_other = new purchase_request_other_draft();
                    $request_other->purchase_request_Id = $purchaseRequest->purchase_request_Id;
                    $request_other->internal_number = 0000;
                    $request_other->external_number = $purchaseRequest->external_number; 
                    $request_other->description = $item->description;
                    $request_other->quantity = $item->qty;
                    $request_other->save();
                }

                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //load item names to bs modal

    public function loadItems()
    {
        $val = 1;
        try {
            
            $items = DB::table('items')
            ->select('item_id', 'item_Name','Item_code')
            ->where('is_active','=', $val)
            ->get();
            $collection = [];
            foreach ($items as $item) {
                array_push($collection, ["hidden_id" => $item->item_id, "id" =>  $item->item_Name, "value" =>  $item->Item_code, "collection" => [$item->item_id, $item->item_Name, $item->Item_code]]);
            }
            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getItemInfo($Item_id)
    {
        try {
            /* $query = "SELECT item_id, item_Name, unit_of_measure, CASE WHEN balance < 0 THEN 0 ELSE balance END AS balance
            FROM (
                SELECT item_id, item_Name, unit_of_measure, NULL AS balance
                FROM items WHERE items.item_id = '".$Item_id."'
                UNION
                SELECT item_historys.item_id, items.item_Name, items.unit_of_measure, SUM(item_historys.quantity) AS balance
                FROM item_historys
                INNER JOIN items ON item_historys.item_id = items.item_id WHERE item_historys.item_id = '".$Item_id."'
                GROUP BY item_historys.item_id, items.item_Name, items.unit_of_measure
            ) AS combined_data
            "; */
            /* $result = DB::select($query); */
            $info = item::find($Item_id);
            if ($info) {
                return response()->json([$info]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //getting data to list
    public function getPurchaseReuqestData()
    {
        try {
            /* $query = "SELECT prd.*, 'Draft' AS status, b.branch_name
            FROM purchase_request_drafts prd
            LEFT JOIN branches b ON prd.branch_id = b.branch_id
            UNION
            SELECT pr.*, 
                CASE
                    WHEN DATABASE() = 'purchase_request_drafts' THEN 'Draft'
                    ELSE 'Original'
                END AS status,
                b.branch_name
            FROM purchase_requests pr
            LEFT JOIN branches b ON pr.branch_id = b.branch_id"; */

            $query = 'SELECT purchase_requests.*, branches.branch_name,"Original" AS status
            FROM purchase_requests
            INNER JOIN branches ON purchase_requests.branch_id = branches.branch_id
            ';

            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //getting each purchase_request data
    public function getEachPurchasingOrder($id, $status)
    {
        try {


            if ($status == "Draft") {
                $purchaseRequest = Purchaserequestdraft::find($id);
                if ($purchaseRequest) {
                    return response()->json((['success' => 'Data loaded', 'data' => $purchaseRequest]));
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            } else {
                $purchaseRequest = purchase_request::find($id);
                if ($purchaseRequest) {
                    return response()->json((['success' => 'Data loaded', 'data' => $purchaseRequest]));
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each product of purchase request
    public function getEachproduct($id, $status)
    {
        try {
            if ($status == "Original") {
                $query = 'SELECT *, items.Item_code
            FROM purchase_request_items
            INNER JOIN items ON purchase_request_items.item_id = items.item_id
            WHERE purchase_request_items.purchase_request_Id = "' . $id . '"';
                $item = DB::select($query);
                if ($item) {
                    return response()->json($item);
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            } else {
                $query = 'SELECT *, items.Item_code
            FROM purchase_request_item_drafts
            INNER JOIN items ON purchase_request_item_drafts.item_id = items.item_id
            WHERE purchase_request_item_drafts.purchase_request_Id = "' . $id . '"';
                $item = DB::select($query);
                if ($item) {
                    return response()->json($item);
                } else {
                    return response()->json((['error' => 'Data not loaded', 'data' => []]));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get each other
    public function getEachOther($id, $status)
    {
        if ($status == "Original") {
            /* $purchase_rqst_other = purchase_request_other::where("purchase_request_Id","=",$id); */
            $Query = 'SELECT * FROM purchase_request_others WHERE purchase_request_Id ="' . $id . '"';
            $other = DB::select($Query);
            if ($other) {
                return response()->json(['success' => 'Data loaded', 'data' => $other]);
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } else {
            /* $purchase_rqst_other_draft = purchase_request_other_draft::where("purchase_request_Id","=",$id); */
            $Query = 'SELECT * FROM purchase_request_other_drafts WHERE purchase_request_Id ="' . $id . '"';
            $other = DB::select($Query);
            if ($other) {
                return response()->json(['success' => 'Data loaded', 'data' => $other]);
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        }
    }

    //delete purchase request
    public function deletePurhcaseRetqest($id, $status)
    {
        try {
            if ($status == "Original") {
                $rqst = purchase_request::find($id);
                if ($rqst->delete()) {
                    $rqst_item = purchase_request_item::where('purchase_request_Id', '=', $id)->delete();;
                    $rqst_other = purchase_request_other::where('purchase_request_Id', '=', $id)->delete();
                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            } else {
                $rqst_draft = Purchaserequestdraft::find($id);
                if ($rqst_draft->delete()) {
                    $rqst_item_draft = purchase_request_item_draft::where('purchase_request_Id', '=', $id)->delete();
                    $rqst_other_draft = purchase_request_other_draft::where('purchase_request_Id', '=', $id)->delete();
                    return response()->json(["message" => "Deleted"]);
                } else {
                    return response()->json(["message" => "Not Deleted"]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //purchase rqst update - perment table
    public function updatePurchaserequestPermenet(Request $request, $id)
    {
        try {
            /*  $PreparedBy = Auth::user()->id; */
            $collection = json_decode($request->input('collection'));
            $collectionOther = json_decode($request->input('collectionOther'));
            $purchaseRequest = purchase_request::find($id);
           /*  $purchaseRequest->internal_number = 0000;
            $purchaseRequest->external_number = 0000; */ // need to change 
            $purchaseRequest->purchase_request_date_time = $request->input('purchasee_request_date');
            $purchaseRequest->branch_id = $request->input('cmbBranch');
            $purchaseRequest->location_id = $request->input('cmbLocation');
            $purchaseRequest->expected_date = $request->input('DtexpectedDate');
            /* $purchaseRequest->approval_status = $request->input('approval_status'); */
            $purchaseRequest->remarks = $request->input('txtRemarks');
            /*  $purchaseRequest->prepaired_by = $PreparedBy; */
            $purchaseRequest->your_reference_number = $request->input('txtYourReference');

            if ($purchaseRequest->update()) {
                //delete existing data
                $deleteRequestItem = purchase_request_item::where("purchase_request_Id", "=", $id)->delete();
                $deleteItemOther = purchase_request_other::where("purchase_request_Id", "=", $id)->delete();
                //looping first array
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $request_item = new purchase_request_item();
                    $request_item->purchase_request_Id = $id;
                    $request_item->internal_number = 0000;
                    $request_item->external_number = 0000; // need to change
                    $request_item->item_id = $item->item_id;
                    $request_item->item_name = $item->item_name;
                    $request_item->quantity = $item->qty;
                    $request_item->unit_of_measure = $item->uom;
                    $request_item->package_unit = $item->PackUnit;
                    $request_item->package_size = $item->PackSize;
                    $request_item->save();
                }
                //delete existing data

                //looping second array
                foreach ($collectionOther as $j) {
                    $item = json_decode($j);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $request_other = new purchase_request_other();
                    $request_other->purchase_request_Id = $id;
                    /*  $request_other->internal_number = 0000;
                    $request_other->external_number = 0000; */
                    $request_other->description = $item->description;
                    $request_other->quantity = $item->qty;
                    $request_other->save();
                }

                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //purchase rqst update - draft
    public function updatePurchaserequestDraft(Request $request, $id)
    {
        try {
            /* $PreparedBy = Auth::user()->id; */
            $collection = json_decode($request->input('collection'));
            $collectionOther = json_decode($request->input('collectionOther'));
            $purchaseRequest = Purchaserequestdraft::find($id);
            $purchaseRequest->internal_number = 0000;
            $purchaseRequest->external_number = 0000; // need to change 
            $purchaseRequest->purchase_request_date_time = $request->input('purchasee_request_date');
            $purchaseRequest->branch_id = $request->input('cmbBranch');
            $purchaseRequest->location_id = $request->input('cmbLocation');
            $purchaseRequest->expected_date = $request->input('DtexpectedDate');
            /* $purchaseRequest->approval_status = $request->input('approval_status'); */
            $purchaseRequest->remarks = $request->input('txtRemarks');
            /* $purchaseRequest->prepaired_by = $PreparedBy; */

            if ($purchaseRequest->Update()) {
                $itemDraft = purchase_request_item_draft::where("purchase_request_Id", "=", $id)->delete();
                $otherDraft = purchase_request_other_draft::where("purchase_request_Id", "=", $id)->delete();
                //looping first array
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $request_item = new purchase_request_item_draft();
                    $request_item->purchase_request_Id = $id;
                    $request_item->internal_number = 0000;
                    $request_item->external_number = 0000; // need to change
                    $request_item->item_id = $item->item_id;
                    $request_item->item_name = $item->item_name;
                    $request_item->quantity = $item->qty;
                    $request_item->unit_of_measure = $item->uom;
                    $request_item->package_unit = $item->PackUnit;
                    $request_item->package_size = $item->PackSize;
                    $request_item->save();
                }

                //looping second array
                foreach ($collectionOther as $j) {
                    $item = json_decode($j);
                    foreach ($item as $key => $value) {
                        if (is_string($value) && empty($value)) {
                            $item->$key = null;
                        }
                    }
                    $request_other = new purchase_request_other_draft();
                    $request_other->purchase_request_Id = $id;
                    /*  $request_other->internal_number = 0000;
                    $request_other->external_number = 0000; */
                    $request_other->description = $item->description;
                    $request_other->quantity = $item->qty;
                    $request_other->save();
                }

                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //getting pending approval to table
    public function getPendingapprovals()
    {
        try {
            $query = 'SELECT *,branches.branch_name FROM purchase_requests INNER JOIN branches on purchase_requests.branch_id = branches.branch_id WHERE purchase_requests.approval_status = "Pending"';
            /* $pendingApprovals = purchase_request::where("approval_status","=","Pending")->get(); */
            $pendingApprovals = DB::select($query);
            if ($pendingApprovals) {
                return response()->json((['success' => 'Data loaded', 'data' => $pendingApprovals]));
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //approve request
    public function approveRequest($id)
    {
        $approvedBy = Auth::user()->id;
        try {
            $request = purchase_request::find($id);
            $request->approval_status = "Approved";
            $request->approved_by = $approvedBy;
            if ($request->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //reject request
    public function rejectRequest($id)
    {
        $approvedBy = Auth::user()->id;
        try {
            $request = purchase_request::find($id);
            $request->approval_status = "Rejected";
            $request->approved_by = $approvedBy;
            if ($request->update()) {
                return response()->json((['status' => true]));
            } else {
                return response()->json((['status' => false]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load data for report - p_rqst
    public function getRequestDataForRPT($id)
    {
        try {
            /*  $query = 'SELECT purchase_requests.*, branches.branch_name, locations.location_name, prepared_by_user.name AS prepared_by_name, approved_by_user.name AS approved_by_name,purchase_request_items.*,purchase_request_others.*
            FROM purchase_requests
            INNER JOIN branches ON purchase_requests.branch_id = branches.branch_id
            INNER JOIN locations ON purchase_requests.location_id = locations.location_id
            INNER JOIN users AS prepared_by_user ON purchase_requests.prepaired_by = prepared_by_user.id
            INNER JOIN purchase_request_items on purchase_requests.purchase_request_Id = purchase_request_items.purchase_request_Id
            INNER JOIN purchase_request_others on purchase_requests.purchase_request_Id = purchase_request_others.purchase_request_Id
            LEFT JOIN users AS approved_by_user ON purchase_requests.approved_by = approved_by_user.id 
            WHERE purchase_requests.purchase_request_Id = "' . $id . '"';

            $results = DB::select($query);
            
                $collection = []; */
            /* foreach($results as $data){
                    array_push($collection,["purchase_request_Id"=>$data->purchase_request_Id,"internal_number"=>$data->internal_number,
                    "external_number"=>$data->external_number,"purchase_request_date_time"=>$data->purchase_request_date_time,"branch_id"=>$data->branch_name,
                "location_id"=>$data->location_name,"expected_date"=>$data->expected_date,"approval_status"=>$data->approval_status,"remarks"=>$data->remarks,
            "prepaired_by"=>$data->prepaired_by,"approved_by"=>$data->approved_by]);
        } */

            return response()->json(['success' => true, 'data' => [
                'purchaseRequests' => $this->getDataPurchaseRqst($id),
                'purchaseReqestItems' => $this->getDataPurchaseRqstItem($id),
                'purchaseRequestOthers' => $this->getDataPurchaseRqstOther($id),
            ]]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    private function getDataPurchaseRqst($id)
    {
        $qry = 'SELECT purchase_requests.*, branches.branch_name, locations.location_name, prepared_by_user.name AS prepared_by_name, approved_by_user.name AS approved_by_name  FROM purchase_requests
        INNER JOIN branches ON purchase_requests.branch_id = branches.branch_id
        INNER JOIN locations ON purchase_requests.location_id = locations.location_id
        INNER JOIN users AS prepared_by_user ON purchase_requests.prepaired_by = prepared_by_user.id LEFT JOIN users AS approved_by_user ON purchase_requests.approved_by = approved_by_user.id 
        WHERE purchase_requests.purchase_request_Id = "' . $id . '"';
        return DB::select($qry);
    }
    private function getDataPurchaseRqstItem($id)
    {
        $qry = 'SELECT purchase_request_items.item_name,purchase_request_items.quantity,purchase_request_items.unit_of_measure,purchase_request_items.package_unit,items.Item_code FROM purchase_request_items INNER JOIN items ON purchase_request_items.item_id = items.item_id WHERE purchase_request_items.purchase_request_Id= "' . $id . '"';
        return DB::select($qry);
    }
    private function getDataPurchaseRqstOther($id)
    {
        $qry = 'SELECT * FROM purchase_request_others WHERE purchase_request_Id ="' . $id . '"';
        return DB::select($qry);
    }


    // loard view page location
    public function getviewLocation()
    {
        try {
            $branches = location::all();
            if ($branches) {
                return response()->json($branches);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
// End
}

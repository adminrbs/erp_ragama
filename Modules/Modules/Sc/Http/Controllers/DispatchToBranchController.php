<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use DateTime;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Prc\Entities\location;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\dispatch_recieve;
use Modules\Sc\Entities\dispatch_recieve_item;
use Modules\Sc\Entities\dispatch_to_branch;
use Modules\Sc\Entities\dispatch_to_branch_items;
use Modules\Sc\Entities\dispatch_to_branch_setoff;
use Modules\Sc\Entities\goods_transfer;
use Modules\Sc\Entities\goods_transfer_items;
use Modules\Sc\Entities\internal_order;
use Modules\Sc\Entities\item;
use Modules\Sc\Entities\item_history;
use Modules\Sc\Entities\item_history_setOff;
use Modules\Sd\Entities\location as EntitiesLocation;

class DispatchToBranchController extends Controller
{
    //add dispatch to branch
    public function add_dispatch_to_branch(Request $request,$order_id)
    {
        //dd($request->input('dispatch_Date_time'));
        DB::beginTransaction();
        try {
            $setOffArray = json_decode($request->input('setOffArray'));
            // dd($setOffArray);
            $collection = json_decode($request->input('collection'));
            // dd($setOffArray);
            $branch_id_ = $request->input('cmbBranch');
            $location_id = $request->input('cmbLocation');

            
            $from_date = DateTime::createFromFormat('d/m/Y', $request->input('from_date'));
            $formatted_from_date = $from_date->format('Y-m-d');
            $to_date = DateTime::createFromFormat('d/m/Y',$request->input('to_date'));
            $formatted_to_date = $to_date->format('Y-m-d');
            
            
           
            
            foreach ($setOffArray as $i) {
                $item = json_decode($i);
                $itemID = $item->item_id;
                $setOffqty = $item->setoff_quantity;
                $wholeSalePrice = $item->wholesale_price;
                $retail_price = $item->retail_price;
                $cost_price = $item->cost_price;
                $query = "SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance 
                FROM item_history_set_offs 
                WHERE whole_sale_price = '" . $wholeSalePrice . "' 
                  AND item_id = '" . $itemID . "' 
                  AND branch_id = '" . $branch_id_ . "' 
                  AND location_id = " . $location_id . "
                  AND price_status = 0
                  AND quantity> 0";




                $balance = DB::select($query);
                if ($balance) {
                    $stockBalance = $balance[0]->balance;

                    $formatted_stockBalance = floatval(str_replace(',', '', $stockBalance));
                    $formatted_qty = floatval(str_replace(',', '', $setOffqty));

                    if ($formatted_stockBalance < $formatted_qty) {
                        $status = false;
                        return response()->json(["message" => "insuficent"]);
                    }
                }
            }


            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }

            $PreparedBy = Auth::user()->id;
            $collection = json_decode($request->input('collection'));
            $DP = new dispatch_to_branch();
            $DP->internal_number = IntenelNumberController::getNextID();
            $DP->external_number = $externalNumber;
            $DP->document_number = 1800;
            $DP->trans_date = $request->input('dispatch_Date_time');
            $DP->from_branch_id = $request->input('cmbBranch'); //stock sending branch
            $DP->from_location_id = $request->input('cmbLocation');
            $DP->to_branch_id = $request->input('cmb_to_Branch'); //requesting branch / receiving branch
            $DP->to_location_id = $request->input('cmb_to_Location');
            $DP->remarks = $request->input('txtRemarks');
            $DP->your_reference_number = $request->input('your_reference_number');
            $DP->prepaired_by = $PreparedBy;
            $DP->total_amount = $request->input('lblNetTotal');
            $DP->from_date = $formatted_from_date;
            $DP->to_date = $formatted_to_date;

           
           

            $locations_to = location::find($DP->to_location_id);
            $locations_from = location::find($DP->from_location_id);

            if ($DP->save()) {
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $dp_item = new dispatch_to_branch_items();
                    $dp_item->dispatch_to_branch_id = $DP->dispatch_to_branch_id;
                    $dp_item->internal_number =  $DP->internal_number;
                    $dp_item->external_number =  $DP->external_number;
                    $dp_item->item_id =  $item->item_id;
                    $dp_item->quantity = $item->qty;
                    $dp_item->package_unit =  $item->PackUnit;
                    $dp_item->price =  $item->price;
                    $dp_item->whole_sale_price =  $item->whole_sale_price;
                    $dp_item->retial_price =  $item->retial_price;
                    $dp_item->cost_price =  $item->cost_price;
                  //  $dp_item->batch_number =  $item->batch_number;
                    $dp_item->from_loc_rd_sale =  $item->from_loc_rd_sale;
                    $dp_item->from_loc_qoh =  $item->from_loc_qoh;
                    $dp_item->to_loc_rd_sale =  $item->to_loc_rd_sale;
                    $dp_item->to_loc_qoh =  $item->to_loc_qoh;
                    if ($dp_item->save()) {
                        //item history minus
                        $item_history_minus = new item_history();
                        $item_history_minus->internal_number = $dp_item->internal_number;
                        $item_history_minus->external_number = $dp_item->external_number;
                        $item_history_minus->external_number = $dp_item->external_number;
                        $item_history_minus->branch_id =  $DP->from_branch_id;
                        $item_history_minus->location_id = $DP->from_location_id;
                        $item_history_minus->document_number = $DP->document_number;
                        $item_history_minus->transaction_date = $DP->trans_date;
                        $item_history_minus->description = "Dispatch to " . $locations_to->location_name;
                        $item_history_minus->item_id =  $dp_item->item_id;
                        $item_history_minus->quantity = -floatVal($dp_item->quantity);
                        // $item_history->free_quantity = $dp_item->free_quantity;
                        $item_history_minus->whole_sale_price = $item->whole_sale_price;
                        $item_history_minus->retial_price = $dp_item->retial_price;
                        $item_history_minus->cost_price = $dp_item->cost_price;
                        $item_history_minus->save();
                    }

                    foreach ($setOffArray as $j) {

                        $SetOff_item = json_decode($j);
                        if ($SetOff_item->item_id == $item->item_id) {

                            $setOff = new dispatch_to_branch_setoff();
                            $setOff->internal_number = $DP->internal_number;
                            $setOff->external_number = $DP->external_number;
                            $setOff->dispatch_to_branch_item_id = $dp_item->dispatch_to_branch_item_id;
                            $setOff->item_history_setoff_id = $SetOff_item->history_id;
                            $setOff->item_id = $SetOff_item->item_id;
                            $setOff->set_off_qty = $SetOff_item->setoff_quantity;
                            $setOff->cost_price = $SetOff_item->cost_price;
                            $setOff->whole_sale_price = $SetOff_item->wholesale_price;
                            $setOff->retail_price = $SetOff_item->retail_price;
                            $setOff->batch_number = $SetOff_item->batch_no;
                            if ($setOff->save()) {

                                $reference_item_history = item_history_setOff::find($SetOff_item->history_id);
                                $reference_item_history->setoff_quantity = $reference_item_history->setoff_quantity + $setOff->set_off_qty;
                                $reference_item_history->update();

                                //  $reference_item_history = item_history_setOff::find($SetOff_item->history_id); // plus

                                $item_history_set_off_minus = new item_history_setOff();
                                $item_history_set_off_minus->internal_number = $setOff->internal_number;
                                $item_history_set_off_minus->external_number = $setOff->external_number;
                                $item_history_set_off_minus->document_number = $DP->document_number;
                                $item_history_set_off_minus->batch_number = $setOff->batch_number;
                                $item_history_set_off_minus->branch_id = $DP->from_branch_id;
                                $item_history_set_off_minus->location_id = $DP->from_location_id;
                                $item_history_set_off_minus->transaction_date = $DP->trans_date;
                                $item_history_set_off_minus->item_id =  $setOff->item_id;
                                $item_history_set_off_minus->whole_sale_price = $setOff->whole_sale_price;
                                $item_history_set_off_minus->retial_price = $setOff->retail_price;
                                $item_history_set_off_minus->cost_price = $setOff->cost_price;
                                $item_history_set_off_minus->quantity = -$setOff->set_off_qty;
                                $item_history_set_off_minus->setoff_quantity = -$setOff->set_off_qty;
                                $item_history_set_off_minus->reference_internal_number = $reference_item_history->internal_number;
                                $item_history_set_off_minus->reference_external_number = $reference_item_history->external_number;
                                $item_history_set_off_minus->reference_document_number = $reference_item_history->document_number;
                                /* $item_history_set_off_minus->setoff_id = $item_history_set_off->item_history_setoff_id; */
                                $item_history_set_off_minus->save();
                            }
                        }
                    }
                }

               $order = internal_order::find($order_id);
               if($order){
                $order->status = 1;
                $order->update();
               }
              
            }
            DB::commit();
            return response()->json(["status" => true, "message" => "success"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    //receive dispatch to branch
    public function receive_dispatch(Request $request, $id, $br_id, $loc_id)
    {
        try {
           // dd($request->input('lblNetTotal'));
            DB::beginTransaction();


            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $br_id;

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }
            
            
            $PreparedBy = Auth::user()->id;
            $DP = new dispatch_recieve();
            $DP->internal_number = IntenelNumberController::getNextID();
            $DP->external_number = $externalNumber;
            $DP->document_number = 2000;
            $DP->trans_date = date('Y-m-d');
            $DP->from_branch_id = $request->input('cmbBranch');
            $DP->from_location_id = $request->input('cmbLocation');
            $DP->to_branch_id = $br_id;
            $DP->to_location_id = $loc_id;
            $DP->remarks = $request->input('txtRemarks');
            $DP->your_reference_number = $request->input('your_reference_number');
            $DP->prepaired_by = $PreparedBy;
            $DP->total_amount = $request->input('lblNetTotal');
            $DP->dispatch_to_branch_id = $id;
            if ($DP->save()) {

                $collection = json_decode($request->input('collection'));
                $total_qty = 0;
                foreach ($collection as $i) {

                    $item = json_decode($i);
                    $dpr_item = new dispatch_recieve_item();
                    $dpr_item->	dispatch_recieve_id = $DP->dispatch_recieve_id;
                    $dpr_item->internal_number = $DP->internal_number;
                    $dpr_item->external_number = $DP->external_number;
                    $dpr_item->item_id =  $item->item_id;
                    $dpr_item->quantity = $item->qty;
                    $dpr_item->package_unit =  $item->PackUnit;
                    $dpr_item->price =  $item->price;
                    $dpr_item->whole_sale_price =  $item->whole_sale_price;
                    $dpr_item->retial_price =  $item->retial_price;
                    $dpr_item->cost_price =  $item->cost_price;
                    $dpr_item->save();

                    $dp_item = dispatch_to_branch_items::find($item->dispatch_item_id);
                    $dp_item->received_qty = $dp_item->received_qty + $item->qty;
                    if ($dp_item->update()) {

                        $dp_setoff = dispatch_to_branch_setoff::where("dispatch_to_branch_item_id", "=", $item->dispatch_item_id)->get();
                        $user_received_qty = (int)$item->qty;

                        foreach ($dp_setoff as $row) {

                            $set_off_qty = $row->set_off_qty;
                            $set_off = dispatch_to_branch_setoff::find($row->dispatch_to_branch_setoffs_id);
                            if ((int)$set_off_qty >= $user_received_qty) {
                                $set_off->received_qty = $user_received_qty;
                                $user_received_qty = (int)$user_received_qty - (int)$user_received_qty;
                            } else {
                                $set_off->received_qty = $set_off_qty;
                                $user_received_qty = (int)$user_received_qty - (int)$set_off_qty;
                            }
                            $set_off->update();
                            $total_qty = $total_qty + $set_off->received_qty;
                            $this->add_item_history_setoff($set_off, $set_off->received_qty, $br_id, $loc_id,$dpr_item);
                        }
                    }



                    //inserting item_history
                    $br_name = branch::find($DP->from_branch_id);
                    // $loc_name = location::find($loc_id);
                    $item_history = new item_history();
                    $item_ = item::find($dp_item->item_id);
                    $item_history->internal_number = $dpr_item->internal_number;
                    $item_history->external_number = $dpr_item->external_number;
                    $item_history->branch_id = $br_id;
                    $item_history->location_id = $loc_id;
                    $item_history->document_number = 2000;
                    $item_history->transaction_date = date('Y-m-d');
                    $item_history->description = "Goods Dispatched From " . $br_name->branch_name;
                    $item_history->item_id = $set_off->item_id;
                    $item_history->quantity = $dpr_item->quantity;
                    $item_history->batch_number = $item_->Item_code;
                    $item_history->whole_sale_price = $dpr_item->whole_sale_price;
                    $item_history->retial_price = $dpr_item->retial_price;
                    $item_history->cost_price = $dpr_item->cost_price;
                    $item_history->save();
                }
                $dp_to_branch = dispatch_to_branch::find($id); //dispatch to branch object
                if($DP->total_amount != $dp_to_branch->total_amount){
                    $dp_to_branch->shortage = 1;
                    $dp_to_branch->update();
                }
            }

            DB::commit();
            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }





    //use for receive stock (dispached stock from another branch)

    private function add_item_history_setoff($obj, $qty, $br_id, $loc_id,$dpr_item)
    {
         $reference_ih = item_history_setOff::find($obj->item_history_setoff_id);
       
        
        $ih_set_off = new item_history_setOff();
        $ih_set_off->internal_number = $dpr_item->internal_number;
        $ih_set_off->external_number = $dpr_item->external_number;
        $ih_set_off->document_number = 2000;
        $ih_set_off->batch_number = $obj->batch_number;
        $ih_set_off->branch_id = $br_id;
        $ih_set_off->location_id = $loc_id;
         $ih_set_off->transaction_date = date('Y-m-d');
        $ih_set_off->item_id = $obj->item_id;
        $ih_set_off->whole_sale_price = $reference_ih->whole_sale_price;
        $ih_set_off->retial_price = $reference_ih->retial_price;
        $ih_set_off->cost_price = $reference_ih->cost_price;
        $ih_set_off->quantity = $qty;
        $ih_set_off->reference_internal_number = $reference_ih->internal_number;
        $ih_set_off->reference_external_number = $reference_ih->external_number;
        $ih_set_off->reference_document_number = $reference_ih->document_number;
        $ih_set_off->save();
    }

    // get dispatch to list
    public function get_dispatch_list(Request $request)
    {
        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('dispatch_to_branches')
                ->select(
                    'dispatch_to_branches.dispatch_to_branch_id',
                    'dispatch_to_branches.trans_date',
                    'dispatch_to_branches.external_number',
                    DB::raw("FORMAT(total_amount, 0) as total_amount"),

                    'from_branch.branch_name as from_branch',
                    'to_branch.branch_name as to_branch',
                    'from_location.location_name as from_location',
                    'to_location.location_name as to_location'
                )
                ->join('branches as from_branch', 'dispatch_to_branches.from_branch_id', '=', 'from_branch.branch_id')
                ->join('branches as to_branch', 'dispatch_to_branches.to_branch_id', '=', 'to_branch.branch_id')
                ->join('locations as from_location', 'dispatch_to_branches.from_location_id', '=', 'from_location.location_id')
                ->join('locations as to_location', 'dispatch_to_branches.to_location_id', '=', 'to_location.location_id')
                ->orderBy('dispatch_to_branches.external_number', 'DESC');


            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('trans_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('total_amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('from_branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('to_branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('from_location.location_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('to_location.location_name', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
                // $status = "Original";
                //  $disabled = "disabled";
                /* $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->dispatch_to_branch_id . '" onclick="btnEdit_(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160'; */
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                //$buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

                $item->buttons = $buttons;

                return $item;
            });

            return response()->json([
                'data' => $results,
                'draw' => request('draw'),
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load dispatches
    public function get_dispatches($branch_id, $location_id)
    {
        try {
            $qry = "SELECT DISTINCT D.dispatch_to_branch_id,D.external_number,D.total_amount,D.trans_date,
                 B.branch_name,L.location_name FROM dispatch_to_branches D INNER JOIN branches B ON 
                 D.from_branch_id = B.branch_id INNER JOIN locations L ON D.from_location_id = L.location_id 
                 INNER JOIN dispatch_to_branch_items DI ON D.dispatch_to_branch_id = DI.dispatch_to_branch_id
                WHERE (DI.quantity - (DI.received_qty + DI.reversed_qty)) > 0 AND D.shortage = 0  AND D.to_branch_id = $branch_id AND D.to_location_id = $location_id";

            $result = DB::select($qry);
            // dd($qry);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => true, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load dispatch items
    public function get_dispatch_items($id)
    {
        try {
            $qry = "SELECT DI.dispatch_to_branch_item_id,DI.quantity, DI.package_unit,DI.price,(DI.quantity - (DI.received_qty + DI.reversed_qty)) AS remain_qty,I.item_Name,I.Item_code FROM dispatch_to_branch_items DI INNER JOIN items I ON DI.item_id = I.item_id WHERE (DI.quantity - DI.received_qty > 0) AND DI.dispatch_to_branch_id = $id";
            $result = DB::select($qry);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => true, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function load_dispatch_items(Request $request, $id)
    {
        try {
            //load header details
            $dispatch = dispatch_to_branch::find($id);
            //dd($dispatch);
            $resultsArray = [];
            $collection = json_decode($request->get('Item_ids'));

            // Prepare an array to store the item IDs
            $itemIDs = [];
            foreach ($collection as $i) {
                $id = json_decode($i);
                $itemIDs[] = $id;
            }

            $itemIDsString = implode(',', $itemIDs);

            $qry = "SELECT DI.dispatch_to_branch_item_id, DI.quantity, DI.package_unit, DI.price,
             DI.quantity - DI.received_qty AS remain_qty, DI.whole_sale_price, DI.retial_price, 
             DI.cost_price, I.item_Name, I.Item_code,DI.item_id FROM dispatch_to_branch_items DI 
             INNER JOIN items I ON DI.item_id = I.item_id WHERE (DI.quantity - DI.received_qty > 0) 
              AND DI.dispatch_to_branch_item_id IN (" . $itemIDsString . ")";

            $result = DB::select($qry);
         //   dd($qry);
            if ($result) {
                return response()->json(["status" => true, "data" => $result, "dispatch" => $dispatch]);
            } else {
                return response()->json(["status" => true, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load records for view
    public function load_dispatch_items_view($id)
    {
        try {
            //load header details
            $dispatch = dispatch_to_branch::find($id);
            $qry = "SELECT DI.dispatch_to_branch_item_id, DI.quantity, DI.package_unit, DI.price, DI.quantity - DI.received_qty AS remain_qty, DI.whole_sale_price, DI.retial_price, DI.cost_price,DI.from_loc_rd_sale,DI.to_loc_rd_sale,DI.from_loc_qoh,DI.to_loc_qoh, I.item_Name, I.Item_code FROM dispatch_to_branch_items DI INNER JOIN items I ON DI.item_id = I.item_id WHERE DI.dispatch_to_branch_id =$id";


            $result = DB::select($qry);
            if ($result) {
                return response()->json(["status" => true, "data" => $result, "dispatch" => $dispatch]);
            } else {
                return response()->json(["status" => true, "data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //dispatch receive list
    public function dispatch_receive_list(Request $request){
        try{

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('dispatch_recieves')
                ->select(
                    'dispatch_recieves.dispatch_recieve_id',
                    'dispatch_recieves.trans_date',
                    'dispatch_recieves.external_number',
                    DB::raw("FORMAT(dispatch_recieves.total_amount, 0) as total_amount"),

                    'from_branch.branch_name as from_branch',
                    'to_branch.branch_name as to_branch',
                    'from_location.location_name as from_location',
                    'to_location.location_name as to_location'
                )
                ->join('branches as from_branch', 'dispatch_recieves.from_branch_id', '=', 'from_branch.branch_id')
                ->join('branches as to_branch', 'dispatch_recieves.to_branch_id', '=', 'to_branch.branch_id')
                ->join('locations as from_location', 'dispatch_recieves.from_location_id', '=', 'from_location.location_id')
                ->join('locations as to_location', 'dispatch_recieves.to_location_id', '=', 'to_location.location_id')
                ->orderBy('dispatch_recieves.external_number', 'DESC');


            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('trans_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('total_amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('from_branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('to_branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('from_location.location_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('to_location.location_name', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
                // $status = "Original";
                //  $disabled = "disabled";
                /* $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->dispatch_to_branch_id . '" onclick="btnEdit_(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160'; */
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->dispatch_recieve_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                //$buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';

                $item->buttons = $buttons;

                return $item;
            });

            return response()->json([
                'data' => $results,
                'draw' => request('draw'),
            ]);

        }catch(Exception $ex){
            return $ex;
        }   
    }

    public function load_dispatch_receive_items_view($id){
        try{

            $dispatch = dispatch_recieve::find($id);
            $qry = "SELECT DI.dispatch_recieve_item_id, DI.quantity, DI.package_unit, DI.price, DI.quantity, DI.whole_sale_price, DI.retial_price, DI.cost_price, I.item_Name, I.Item_code FROM dispatch_recieve_items DI LEFT JOIN items I ON DI.item_id = I.item_id WHERE DI.dispatch_recieve_id = $id";
            $result = DB::select($qry);
            if ($result) {
                return response()->json(["status" => true, "data" => $result, "dispatch" => $dispatch]);
            } else {
                return response()->json(["status" => true, "data" => []]);
            }
            

        }catch(Exception $ex){
            return $ex;
        }
    }


    public function getItemInfotodivisiontransferentry(Request $request,$frombranch_id, $item_id, $fromlocation_id,$tobranch,$tolocation)
    {
        try {
            $fromDt = $request->input('fromD');
            $toDt = $request->input('toD');

            $fromD = DateTime::createFromFormat('d/m/Y', $fromDt)->format('Y-m-d');
            $toD = DateTime::createFromFormat('d/m/Y', $toDt)->format('Y-m-d');
           // dd($request);
           $query = "SELECT DISTINCT
           IT.unit_of_measure,
           IT.item_Name,
           IT.average_cost_price,
           IT.package_size,
           IT.package_unit,
           IT.previouse_purchase_price,
           (
               SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
               FROM item_history_set_offs
               WHERE item_id = '".$item_id."' AND branch_id = '".$frombranch_id."' AND location_id = '".$fromlocation_id."' AND quantity > 0 AND price_status = 0
           ) AS from_balance,
           (
               SELECT IF(ISNULL(SUM(quantity - setoff_quantity)), 0, SUM(quantity - setoff_quantity))
               FROM item_history_set_offs
               WHERE item_id = '".$item_id."' AND branch_id = '".$tobranch."' AND location_id = '".$tolocation."' AND quantity > 0 AND price_status = 0
           ) AS to_balance,
           (
               SELECT SUM(quantity)
               FROM item_historys IH_FROM
               WHERE (document_number = 210 OR document_number = 220) AND item_id = '".$item_id."' AND IH_FROM.branch_id = '".$frombranch_id."' AND IH_FROM.location_id = '".$fromlocation_id."'
               AND
               IH_FROM.transaction_date BETWEEN '".$fromD."' AND '".$toD."'
           ) AS from_sales,
           (
               SELECT SUM(quantity)
               FROM item_historys IH_TO
               WHERE (document_number = 210 OR document_number = 220) AND item_id = '".$item_id."' AND IH_TO.branch_id = '".$tobranch."' AND IH_TO.location_id = '".$tolocation."'
               AND
               IH_TO.transaction_date BETWEEN '".$fromD."' AND '".$toD."'
           ) AS to_sales
       FROM
           items IT
       LEFT JOIN
           item_historys IH_FROM ON IT.item_id = IH_FROM.item_id
       LEFT JOIN
           item_historys IH_TO ON IT.item_id = IH_TO.item_id
       WHERE
           IT.item_id = '".$item_id."'";

       // dd($query);
            $result = DB::select($query);

            if ($result) {
                return response()->json($result);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load internal orders
    public function loadinternalOrders($from_branch,$to_br){
        try{
            $results = DB::table('internal_orders as IO')
    ->select('IO.internal_orders_id', 'IO.external_number', 'IO.order_date_time','IO.from_branch_id', 'B.branch_name')
    ->join('branches as B', 'IO.from_branch_id', '=', 'B.branch_id')
    ->where('IO.to_branch_id', $from_branch)
    ->where('IO.from_branch_id', $to_br)
    ->where('IO.status','=', 0)
    ->get();
    

    if ($results) {
        return response()->json(["status" => true, "data" => $results]);
    } else {
        return response()->json(["status" => true, "data" => []]);
    }

        }catch(Exception $ex){
            return $ex;
        }
    }


    //load items
    public function loadOrderItems($id,$branch,$location){
        try {
            $order = internal_order::find($id);
            $query = "SELECT internal_order_items.internal_order_items_id,internal_order_items.item_name,internal_order_items.item_id,internal_order_items.quantity,
            internal_order_items.package_unit,items.Item_code,
            (
                SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance
                   FROM item_history_set_offs
                   WHERE item_id = internal_order_items.item_id AND branch_id = $branch AND location_id = $location AND price_status = 0 AND quantity > 0
               ) AS Balance
               
            FROM internal_order_items INNER JOIN items ON internal_order_items.item_id = items.item_id WHERE internal_order_items.	internal_orders_id ='" . $id . "'";

            $result = DB::select($query);

           
            if ($result) {
                return response()->json(['success' => 'Data loaded', 'data' => $result]);
            } else {
                return response()->json(['success' => 'Data loaded', 'data' => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getItemsFordispatchtable(Request $request, $branchID_,$location_id, $orderID, $to_branch, $to_location)
    {
        try {
            $resultsArray = [];
            $collection = json_decode($request->get('Item_ids'));
           // dd($collection);
            $fromDt = $request->input('from_date');
            $toDt = $request->input('to_date');

            $fromD = DateTime::createFromFormat('d/m/Y', $fromDt)->format('Y-m-d');
            $toD = DateTime::createFromFormat('d/m/Y', $toDt)->format('Y-m-d');
            $order_ID = $orderID;

            // Prepare an array to store the item IDs
            $itemIDs = [];
            foreach ($collection as $i) {
                $id = json_decode($i);
                $itemIDs[] = $id;
            }

            // Create a comma-separated string of item IDs for the IN clause
            $itemIDsString = implode(',', $itemIDs);

            ini_set('max_execution_time', '0'); // for infinite time of execution
            
            $query = "SELECT 
           SOI.internal_order_items_id, 
           SOI.item_name,
           SOI.item_id,
           SOI.quantity,
          
           SOI.package_unit,
         
          
           IT.Item_code,
    
        
           (
            SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance
               FROM item_history_set_offs
               WHERE item_id = SOI.item_id AND branch_id = '" . $branchID_ . "' AND location_id = '" . $location_id . "' AND price_status = 0 AND item_history_set_offs.quantity > 0 
           ) AS from_balance,
           (
            SELECT IF(ISNULL(SUM(quantity-setoff_quantity)), 0, SUM(quantity-setoff_quantity)) AS balance
               FROM item_history_set_offs
               WHERE item_id = SOI.item_id AND branch_id = '" . $to_branch . "' AND location_id = '" . $to_location . "' AND price_status = 0 AND item_history_set_offs.quantity > 0 
           ) AS to_balance,
           (
            SELECT SUM(quantity* -1) AS from_sales
            FROM item_historys IH_FROM
            WHERE (document_number = 210 OR document_number = 220) AND item_id = SOI.item_id AND IH_FROM.branch_id = '".$branchID_."' AND IH_FROM.location_id = '".$location_id."'
            AND
            IH_FROM.transaction_date BETWEEN '".$fromD."' AND '".$toD."'
        ) AS from_sales,
        (
            SELECT SUM(quantity * -1) AS to_sales
            FROM item_historys IH_TO
            WHERE (document_number = 210 OR document_number = 220) AND item_id = SOI.item_id AND IH_TO.branch_id = '".$to_branch."' AND IH_TO.location_id = '".$to_location."'
            AND
            IH_TO.transaction_date BETWEEN '".$fromD."' AND '".$toD."'
        ) AS to_sales
       FROM 
       internal_order_items SOI
           INNER JOIN items IT ON SOI.item_id = IT.item_id
           INNER JOIN internal_orders SO ON SO.internal_orders_id = SOI.internal_orders_id 
       WHERE 
           SO.internal_orders_id = '" . $order_ID . "'
           AND SOI.internal_order_items_id IN (" . $itemIDsString . ");
       ";

//dd($query);
            $result = DB::select($query);
            foreach ($result as $res) {
                $res->setOffData = $this->getItemHistorySetoffBatch01($branchID_, $res->item_id, $location_id);
            }
            if ($result) {
                $resultsArray = $result; // No need to loop through and append to $resultsArray
            }

            return response()->json(['success' => 'Data loaded', 'data' => $resultsArray]);
        } catch (Exception $ex) {

            return $ex;
        }
    }


    public function getItemHistorySetoffBatch01($branchID, $item_id, $location_id)
    {

        ini_set('max_execution_time', '0'); // for infinite time of execution
        $query = "SELECT
            item_history_setoff_id,
            batch_number,
            item_id,
            quantity - setoff_quantity AS AvlQty,
            cost_price,
            whole_sale_price,
            retial_price
        FROM
            item_history_set_offs
        WHERE
            branch_id = '" . $branchID . "'
            AND item_id = '" . $item_id . "'
            AND location_id = '" . $location_id . "'
            AND price_status = 0
            AND quantity - setoff_quantity > 0 AND quantity > 0  ORDER BY item_history_setoff_id ASC";
        $result = DB::select($query);
        return $result;
    }


    //reject order
    public function reject_internal_Order($order_id){
        try{
                $order = internal_order::find($order_id);
                if($order->status == 0){
                    $order->status = 2;
                    $order->update();
                    return response()->json(['success' => true]);
                }else{
                    return response()->json(['success' => false]);  
                }
               
        }catch(Exception $ex){
            return $ex;
        }
    }
    

    //get transfer shortages
    public function get_transfer_shortages(Request $request,){
        try{
           
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $from = $request->get('from');
            $to = $request->get('to');
            
            $query = DB::table('dispatch_to_branches')
                ->select(
                    
                    'DI.external_number',
                    'DI.quantity',
                    'DI.received_qty',
                    DB::raw('DI.quantity - DI.received_qty as balance'),
                    DB::raw("LEFT(I.item_Name, 25) as item_Name"),
                    'I.Item_code',
                    'DI.package_unit',
                    'dispatch_to_branches.trans_date',

                    'from_branch.branch_name as from_branch',
                    'to_branch.branch_name as to_branch',
                    'from_location.location_name as from_location',
                    'to_location.location_name as to_location',
                    'U.name',
                    'DR.trans_date'
                )
                ->join('dispatch_to_branch_items as DI','dispatch_to_branches.dispatch_to_branch_id','=','DI.dispatch_to_branch_id')
                ->join('items as I','DI.item_id','=','I.item_id')
                ->join('branches as from_branch', 'dispatch_to_branches.from_branch_id', '=', 'from_branch.branch_id')
                ->join('branches as to_branch', 'dispatch_to_branches.to_branch_id', '=', 'to_branch.branch_id')
                ->join('locations as from_location', 'dispatch_to_branches.from_location_id', '=', 'from_location.location_id')
                ->join('locations as to_location', 'dispatch_to_branches.to_location_id', '=', 'to_location.location_id')
                ->join('dispatch_recieves as DR','dispatch_to_branches.dispatch_to_branch_id','=','DR.dispatch_to_branch_id')
                ->join('users as U','DR.prepaired_by','=','U.id');

                if($from != 0){
                    $query->where('dispatch_to_branches.from_branch_id','=',$from);
                }
                if($to != 0){
                    $query->where('dispatch_to_branches.to_branch_id','=',$to);
                }

                $query->where('dispatch_to_branches.shortage','=',1)
                ->having('balance', '!=', 0)
                ->orderBy('DI.external_number', 'DESC');
                
            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                   // $search_amount = str_replace(',', '', $searchValue);
                    $query->where('DI.external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('dispatch_to_branches.trans_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('item_Name', 'like', '%' . $searchValue . '%')
                        ->orWhere('Item_code', 'like', '%' . $searchValue . '%')
                        ->orWhere('from_branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('to_branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('from_location.location_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('to_location.location_name', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


           

            return response()->json([
                'data' => $results,
                'draw' => request('draw'),
            ]);
        }catch(Exception $ex){
            return $ex;
        }
    }

    //load branches
    public function getBranches(){
        $branches = branch::all();
        if ($branches) {
            return response()->json($branches);
        } else {
            return response()->json(['status' => false]);
        }
    }
}



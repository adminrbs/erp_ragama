<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\IntenelNumberController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\dispatch_to_branch;
use Modules\Sc\Entities\dispatch_to_branch_items;
use Modules\Sc\Entities\dispatch_to_branch_setoff;
use Modules\Sc\Entities\item_history;
use Modules\Sc\Entities\item_history_setOff;
use Modules\Sc\Entities\reverse_devision_transfer;
use Modules\Sc\Entities\reverse_devision_transfer_items;

class ReverseDevisionTransferController extends Controller
{
    //load remaining dispatches to the model
    public function get_dispatches_to_reverse_devision_model($branch_id, $location_id)
    {
        try {
            $qry = "SELECT DISTINCT D.dispatch_to_branch_id,D.external_number,D.total_amount,D.trans_date,
            B.branch_name,L.location_name FROM dispatch_to_branches D INNER JOIN branches B ON 
            D.from_branch_id = B.branch_id INNER JOIN locations L ON D.from_location_id = L.location_id 
            INNER JOIN dispatch_to_branch_items DI ON D.dispatch_to_branch_id = DI.dispatch_to_branch_id
           WHERE (DI.quantity - (DI.received_qty+DI.reversed_qty)) > 0 AND D.to_branch_id = $branch_id AND D.to_location_id = $location_id";

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

    //get dispatch items to model with reverse qty
    public function get_dispatch_items_to_reverse($id)
    {
        try {
            $qry = "SELECT DI.dispatch_to_branch_item_id,DI.quantity, DI.package_unit,DI.price,DI.quantity - (DI.received_qty + DI.reversed_qty) AS remain_qty,I.item_Name,I.Item_code FROM dispatch_to_branch_items DI INNER JOIN items I ON DI.item_id = I.item_id WHERE (DI.quantity - DI.received_qty > 0) AND DI.dispatch_to_branch_id = $id";
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

    //load revesable dispatch items
    public function load_dispatch_items_for_reverse(Request $request, $id)
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
             INNER JOIN items I ON DI.item_id = I.item_id WHERE (DI.quantity - (DI.received_qty + DI.reversed_qty) > 0) 
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

    //reverse devision tramsfer
    public function reverse_dispatch(Request $request,$dispatch_id){
        try{
            DB::beginTransaction();

            $collection = json_decode($request->input('collection'));
           
           

            $referencenumber = $request->input('LblexternalNumber');
            $bR_id = $request->input('cmbBranch');

            $data = DB::table('branches')->where('branch_id', $bR_id)->get();

            $EXPLODE_ID = explode("-", $referencenumber);
            $externalNumber  = '';
            if ($data->count() > 0) {
                $documentPrefix = $data[0]->prefix;
                $externalNumber  = $documentPrefix . "-" . $EXPLODE_ID[0] . "-" . $EXPLODE_ID[1];
            }

           // dd($externalNumber);
            $PreparedBy = Auth::user()->id;
            $reverse_dispatch = new reverse_devision_transfer();
            $reverse_dispatch->internal_number = IntenelNumberController::getNextID();
            $reverse_dispatch->external_number = $externalNumber;
            $reverse_dispatch->document_number = 2000;
            $reverse_dispatch->trans_date = date('Y-m-d');
            $reverse_dispatch->branch_id = $request->input('cmbBranch');
            $reverse_dispatch->location_id = $request->input('cmbLocation');
            $reverse_dispatch->remarks = $request->input('txtRemarks');
            $reverse_dispatch->your_reference_number = $request->input('your_reference_number');
            $reverse_dispatch->prepaired_by = $PreparedBy;
            $reverse_dispatch->total_amount = $request->input('lblNetTotal');
            $reverse_dispatch->dispatch_to_branch_id = $dispatch_id;
            
            if ($reverse_dispatch->save()) {
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $reverse_item = new reverse_devision_transfer_items();
                    $reverse_item->	reverse_devision_transfer_id = $reverse_dispatch->reverse_devision_transfer_id;
                    $reverse_item->internal_number = $reverse_dispatch->internal_number;
                    $reverse_item->external_number = $reverse_dispatch->external_number;
                    $reverse_item->item_id =  $item->item_id;
                    $reverse_item->quantity = $item->qty;
                    $reverse_item->package_unit =  $item->PackUnit;
                    $reverse_item->price =  $item->price;
                    $reverse_item->whole_sale_price =  $item->whole_sale_price;
                    $reverse_item->retial_price =  $item->retial_price;
                    $reverse_item->cost_price = $item->cost_price;
                    $reverse_item->dispatch_to_branch_item_id = $item->dispatch_item_id;
                    $reverse_item->save();

                    /* $dp_item = dispatch_to_branch_items::find($item->dispatch_item_id);
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
                            $this->add_item_history_setoff($set_off, $set_off->received_qty, $br_id, $loc_id,$reverse_item);
                        }
                    }



                    //inserting item_history
                    $br_name = branch::find($DP->from_branch_id);
                    $item_history = new item_history();
                    $item_ = item::find($dp_item->item_id);
                    $item_history->internal_number = $reverse_item->internal_number;
                    $item_history->external_number = $reverse_item->external_number;
                    $item_history->branch_id = $br_id;
                    $item_history->location_id = $loc_id;
                    $item_history->document_number = 2000;
                    $item_history->transaction_date = date('Y-m-d');
                    $item_history->description = "Goods Dispatched From " . $br_name->branch_name;
                    $item_history->item_id = $set_off->item_id;
                    $item_history->quantity = $reverse_item->quantity;
                    $item_history->batch_number = $item_->Item_code;
                    $item_history->whole_sale_price = $reverse_item->whole_sale_price;
                    $item_history->retial_price = $reverse_item->retial_price;
                    $item_history->cost_price = $reverse_item->cost_price;
                    $item_history->save(); */
                }
            }

            DB::commit();
            return response()->json(["status" => true]);
            
        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }


    //get data to approve
    public function get_revese_division_transfer($id){
        try{
            $header = reverse_devision_transfer::find($id);
            $items = DB::select('SELECT reverse_devision_transfer_items.*,items.Item_code,items.item_Name FROM reverse_devision_transfer_items INNER JOIN items ON reverse_devision_transfer_items.item_id = items.item_id WHERE reverse_devision_transfer_items.reverse_devision_transfer_id = '.$id);
            return response()->json(["header" => $header,"items"=>$items]);
        }catch(Exception $ex){
            return $ex;
        }

    }

    //get data to approval list
    public function get_pending_reverse_trasfers(Request $request){
        try{

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('reverse_devision_transfers')
                ->select(
                    'reverse_devision_transfers.reverse_devision_transfer_id',
                    'reverse_devision_transfers.external_number',
                    'reverse_devision_transfers.trans_date',
                    'DB.external_number as dispatch_ref',
                    'reverse_devision_transfers.status',
                    DB::raw("FORMAT(reverse_devision_transfers.total_amount, 0) as total_amount"),
                    'branch.branch_name',
                )
                ->join('branches as branch', 'reverse_devision_transfers.branch_id', '=', 'branch.branch_id')
                ->join('dispatch_to_branches as DB','reverse_devision_transfers.dispatch_to_branch_id','=','DB.dispatch_to_branch_id')
                ->where('reverse_devision_transfers.status', '=', 0)
                ->orderBy('reverse_devision_transfers.external_number', 'DESC');


            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('total_amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('DB.dispatch_ref', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
                 $status = "Pending";
                //  $disabled = "disabled";
                /* $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->dispatch_to_branch_id . '" onclick="btnEdit_(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160'; */
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->reverse_devision_transfer_id . ')" title="view"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-info btn-sm" onclick="approve(' . $item->reverse_devision_transfer_id . ')" title="Approve"><i class="fa fa-check" aria-hidden="true"></i></button>&#160';
                //$buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                $item->buttons = $buttons;
                $statusLabel = '<label class="badge badge-pill bg-warning">' . $status . '</label>';
                $item->statusLabel = $statusLabel;

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


    //approve - reject
    public function approval_request($id,$type){
        try{
            DB::beginTransaction();
            $reverse_transfer = reverse_devision_transfer::find($id);
            $dispatch_to_branch_id = $reverse_transfer->dispatch_to_branch_id;
            $dispach_id = $reverse_transfer->dispatch_to_branch_id;
            $branch_qry = DB::select("SELECT B.branch_name FROM dispatch_to_branches DB INNER JOIN branches B ON DB.to_branch_id = B.branch_id WHERE DB.dispatch_to_branch_id = ".$dispach_id);
            
            if($type == "Approve"){
              if($reverse_transfer->status == 0){
                $reverse_transfer->status = 1;
                $reverse_transfer->approved_by = Auth::user()->id;
                $reverse_transfer->update();
                //updating dispatch to branch shortage status
                $DPB = dispatch_to_branch::find($dispatch_to_branch_id);
                $DPB->shortage = 2;
                $DPB->update();

                $reverse_transfer_items = reverse_devision_transfer_items::where("reverse_devision_transfer_id","=",$id)->get();

                foreach($reverse_transfer_items as $item){
                    $ih = new item_history();
                    $ih->internal_number = $reverse_transfer->internal_number;
                    $ih->external_number = $reverse_transfer->external_number;
                    $ih->document_number = $reverse_transfer->document_number;
                    $ih->branch_id = $reverse_transfer->branch_id;
                    $ih->location_id = $reverse_transfer->location_id;
                    $ih->transaction_date = $reverse_transfer->trans_date;
                    $ih->description = "Dispatched reversed from". $branch_qry[0]->branch_name;
                    $ih->item_id = $item->item_id;
                    $ih->quantity = $item->quantity;
                    /* $ih->quantity = $reverse_transfer->document_number; */
                    $ih->whole_sale_price = $item->whole_sale_price;
                    $ih->retial_price = $item->retial_price;
                    $ih->cost_price = $item->cost_price;
                    
                    $ih->save();


                    //getting dispatch set off data
                    $dp_item= dispatch_to_branch_items::where("dispatch_to_branch_id","=",$dispach_id)->get();
                    $total_qty = $ih->quantity;
                    //dd($dp_item);
                    foreach($dp_item as $i){
                       
                        if($i->item_id == $ih->item_id){
                            $dp_setoff = dispatch_to_branch_setoff::where("dispatch_to_branch_item_id","=",$i->dispatch_to_branch_item_id)->first();
                            //dd($dp_setoff);
                            //reference ih set off
                            $reference_ih = item_history_setOff::find($dp_setoff->item_history_setoff_id);
                            $ih_setoff = new item_history_setOff();
                            $ih_setoff->internal_number = $reverse_transfer->internal_number;
                            $ih_setoff->external_number = $reverse_transfer->external_number;
                            $ih_setoff->document_number = $reverse_transfer->document_number;
                             $ih_setoff->branch_id = $reverse_transfer->branch_id;
                            $ih_setoff->location_id = $reverse_transfer->location_id;
                            $ih_setoff->transaction_date = $reverse_transfer->trans_date;
                           // $ih_setoff->description = "Dispatched reversed from". $branch_qry[0]->branch_name;
                            $ih_setoff->item_id = $item->item_id;
                            if($total_qty > $reference_ih->quantity){
                                $ih_setoff->quantity = $reference_ih->quantity;
                                $total_qty = $total_qty - $reference_ih->quantity;
                            }else{
                                $ih_setoff->quantity = $total_qty;
                                $total_qty = $total_qty - $total_qty;
                            }
                            
                            /* $ih->quantity = $reverse_transfer->document_number; */
                            $ih_setoff->whole_sale_price = $reference_ih->whole_sale_price;
                            $ih_setoff->retial_price = $reference_ih->retial_price;
                            $ih_setoff->cost_price = $reference_ih->cost_price;
                            $ih_setoff->reference_internal_number = $reference_ih->internal_number;
                            $ih_setoff->reference_external_number = $reference_ih->external_number;
                            $ih_setoff->reference_document_number = $reference_ih->document_number;
                            $ih_setoff->save();

                            //update dispatch item reversed qty
                            $i->reversed_qty = $i->reversed_qty + $ih_setoff->quantity;
                            $i->update();
                            
                        }
                    }
                    
                }
                DB::commit();
                return response()->json(["status" => true,"msg"=>"approved"]);
              }else{
                return response()->json(["status" => false,"msg"=>"used"]);
              }
              
            }else{
                $reverse_transfer->status = 0;
                $reverse_transfer->update();
                DB::commit();
                return response()->json(["status" => true,"msg"=>"rejected"]);

            }

            
        }catch(Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }


    //load all datat to list
    public function get_all_reverse_trasfers(Request $request){
        try{

            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');
            $query = DB::table('reverse_devision_transfers')
                ->select(
                    'reverse_devision_transfers.reverse_devision_transfer_id',
                    'reverse_devision_transfers.external_number',
                    'reverse_devision_transfers.trans_date',
                    'DB.external_number as dispatch_ref',
                    'reverse_devision_transfers.status',
                    DB::raw("FORMAT(reverse_devision_transfers.total_amount, 0) as total_amount"),
                    'branch.branch_name',
                )
                ->join('branches as branch', 'reverse_devision_transfers.branch_id', '=', 'branch.branch_id')
                ->join('dispatch_to_branches as DB','reverse_devision_transfers.dispatch_to_branch_id','=','DB.dispatch_to_branch_id')
                ->orderBy('reverse_devision_transfers.external_number', 'DESC');


            if (!empty($searchValue)) {

                $query->where(function ($query) use ($searchValue) {
                    $search_amount = str_replace(',', '', $searchValue);
                    $query->where('external_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('total_amount', 'like', '%' . $search_amount . '%')
                        ->orWhere('branch.branch_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('DB.dispatch_ref', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();


            $results->transform(function ($item) {
                 $status = "Pending";
                //  $disabled = "disabled";
                /* $buttons = '<button class="btn btn-primary btn-sm" id="btnEdit_' . $item->dispatch_to_branch_id . '" onclick="btnEdit_(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160'; */
                $buttons = '<button class="btn btn-success btn-sm" onclick="view(' . $item->reverse_devision_transfer_id . ')" title="view"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
               // $buttons = '<button class="btn btn-info btn-sm" onclick="approve(' . $item->reverse_devision_transfer_id . ')" title="Approve"><i class="fa fa-check" aria-hidden="true"></i></button>&#160';
                //$buttons .= '<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' . $item->dispatch_to_branch_id . ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                $item->buttons = $buttons;
                if($item->status == 0){
                    $statusLabel = '<label class="badge badge-pill bg-warning">' . $status . '</label>';
                    $item->statusLabel = $statusLabel;
                }else if($item->status == 1){
                    $statusLabel = '<label class="badge badge-pill bg-success">Approved</label>';
                    $item->statusLabel = $statusLabel;
                }else{
                    $statusLabel = '<label class="badge badge-pill bg-danger">Rejected</label>';
                    $item->statusLabel = $statusLabel;
                }
                

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
}

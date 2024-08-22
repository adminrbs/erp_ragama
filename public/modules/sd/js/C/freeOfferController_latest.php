<?php

namespace Modules\Sd\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;
use Modules\Sd\Entities\Customer;
use Modules\Sd\Entities\free_offer_customer_grade;
use Exception;
use Illuminate\Http\Request;
use Modules\Sd\Entities\free_offer;
use Modules\Sd\Entities\free_offer_data;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\free_offer_thresholds;
use Modules\Sd\Entities\free_offer_range;
use Modules\Sd\Entities\location;
use Modules\Sd\Entities\Customer_grade;
use Modules\Sd\Entities\Customer_group;
use Modules\Sd\Entities\free_offer_customer;
use Modules\Sd\Entities\free_offer_location;
use Modules\Sd\Entities\free_offer_customer_group;
use Modules\Sd\Entities\supply_group;
use Modules\Sd\Entities\item;

class freeOfferController_latest extends Controller
{

    //add new offer
    public function addFreeOffer(Request $request)
    {
        try {

            $request->validate([

                'txtOfferName' => 'required'

            ]);


            $item_id_array = json_decode($request->input('item_id_array'));
            /*  $collection = json_decode($request->input('collection')); */
            $free_qty_hashMap = json_decode($request->input('free_qty_hashMap'));





            $cus_id_array = "";
            $grp_id_array = "";
            $start = $request->input('dtStartDate');
            $end = $request->input('dtEndDate');

            if (count($item_id_array) < 1) {
                return response()->json(["status" => false, "message" => "item_empty"]);
            }

            if ($request->input('cmbApplyTo') == 2) {
                $cus_id_array = json_decode($request->input('cus_id_array'));
                if (count($cus_id_array) < 1) {
                    return response()->json(["status" => false, "message" => "customer_empty"]);
                } else {
                    foreach ($item_id_array as $item_id) {
                        foreach ($cus_id_array as $cus_id) {
                            /* $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                           WHERE ((('$start' BETWEEN FO.start_date AND FO.end_date) 
                           OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                           AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') OR (FO.end_date BETWEEN '$start' AND '$end')) AND FO.is_active = 1"; */
                            $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            INNER JOIN free_offer_customers FOC ON FO.offer_id = FOC.offer_id 
                            WHERE (
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                                OR (
                                    (FO.start_date BETWEEN '$start' AND '$end') 
                                    OR (FO.end_date BETWEEN '$start' AND '$end')
                                )
                            )
                            AND FO.is_active = 1 
                            AND FOD.item_id = $item_id 
                            AND FOC.customer_id = $cus_id;
                            ";

                            $validate_result = DB::select($validate_qry);

                            if ($validate_result) {

                                if ($validate_result[0]->count > 0) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $cus_id]);
                                }
                            }
                        }
                    }
                }
            }else if($request->input('cmbApplyTo') == 3){
                $grp_id_array = json_decode($request->input('grp_id_array'));
                if (count($grp_id_array) < 1) {
                    return response()->json(["status" => false, "message" => "customer_empty"]);
                }else{
                    foreach ($item_id_array as $item_id) {
                        foreach ($grp_id_array as $grp_id) {
                            /* $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                           WHERE ((('$start' BETWEEN FO.start_date AND FO.end_date) 
                           OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                           AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') OR (FO.end_date BETWEEN '$start' AND '$end')) AND FO.is_active = 1"; */
                            $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            INNER JOIN free_offer_customer_groups FOC ON FO.offer_id = FOC.offer_id 
                            WHERE (
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                                OR (
                                    (FO.start_date BETWEEN '$start' AND '$end') 
                                    OR (FO.end_date BETWEEN '$start' AND '$end')
                                )
                            )
                            AND FO.is_active = 1 
                            AND FOD.item_id = $item_id 
                            AND FOC.customer_group_id = $grp_id;
                            ";

                            $validate_result = DB::select($validate_qry);

                            if ($validate_result) {

                                if ($validate_result[0]->count > 0) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $grp_id]);
                                }
                            }
                        }
                    }

                } 

            } else {
                foreach ($item_id_array as $item_id) {

                    $validate_qry = "SELECT COUNT(*) as count
                        FROM free_offers FO 
                        INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                        WHERE
                         FO.is_active = 1 
                            AND FO.apply_to = 1
                            AND FOD.item_id = $item_id
                            AND ((
                            (
                                ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                            )
                           
                        ) 
                        OR (
                            (
                                FO.start_date BETWEEN '$start' AND '$end'
                                OR FO.end_date BETWEEN '$start' AND '$end'
                            ) 
                            
                        ))
                       
                        ";

                    $validate_result = DB::select($validate_qry);
                    if ($validate_result) {
                        if ($validate_result[0]->count > 0) {
                            return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                        }
                    }
                }
            }

            //validating dates with item



            $offer = new free_offer();

            $offer->name = $request->input('txtOfferName');
            $offer->description = $request->input('txtDescription');
            $offer->start_date = $request->input('dtStartDate');
            $offer->end_date = $request->input('dtEndDate');
            $offer->apply_to = $request->input('cmbApplyTo');
            $offer->is_active = $request->input('isActive');


            if ($offer->save()) {
                $primaryId = $offer->offer_id;
                foreach ($item_id_array as $id) {
                    $offer_data = new free_offer_data();
                    $offer_data->offer_id = $primaryId;
                    $offer_data->item_id = $id;
                    $offer_data->offer_type = 1;
                    $offer_data->offer_redeem_as = 1;
                    $offer_data->is_active = 1;
                    if ($offer_data->save()) {


                        foreach ($free_qty_hashMap as $key => $freeQtyArray) {
                            foreach ($freeQtyArray as $element) {
                                if ($key == $offer_data->item_id) {
                                    $free_offer_threshold = new free_offer_thresholds();
                                    $free_offer_threshold->offer_data_id = $offer_data->offer_data_id;
                                    $free_offer_threshold->quantity = $element->qty;
                                    $free_offer_threshold->free_offer_quantity = $element->foc;
                                    $free_offer_threshold->save();
                                }
                            }
                        }
                    }
                }

                //save free offer customers
                if ($offer->apply_to == 2) {
                    foreach ($cus_id_array as $cus) {
                        $free_offer_customer = new free_offer_customer();
                        $free_offer_customer->offer_id = $offer->offer_id;
                        $free_offer_customer->customer_id = $cus;
                        $free_offer_customer->save();
                    }
                } else if($offer->apply_to == 3){
                    foreach ($grp_id_array as $grp) {
                        $free_offer_customer_group = new free_offer_customer_group();
                        $free_offer_customer_group->offer_id = $offer->offer_id;
                        $free_offer_customer_group->customer_group_id = $grp;
                        $free_offer_customer_group->save();
                    }
                }
            } else {
                return response()->json(["status" => false]);
            }

            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //add new offer
    public function update_free_offer_new(Request $request, $id)
    {
        
        try {
            $request->validate([

                'txtOfferName' => 'required'

            ]);


            $item_id_array = json_decode($request->input('item_id_array'));
            /*  $collection = json_decode($request->input('collection')); */
            $free_qty_hashMap = json_decode($request->input('free_qty_hashMap'));
            $start = $request->input('dtStartDate');
            $end = $request->input('dtEndDate');




            $cus_id_array = "";
            $grp_id_array = "";

            if (count($item_id_array) < 1) {
                return response()->json(["status" => false, "message" => "item_empty"]);
            }

            if ($request->input('cmbApplyTo') == 2) {
                $cus_id_array = json_decode($request->input('cus_id_array'));
                if (count($cus_id_array) < 1) {
                    return response()->json(["status" => false, "message" => "customer_empty"]);
                } else {
                    foreach ($item_id_array as $item_id) {
                        foreach ($cus_id_array as $cus_id) {

                            $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            INNER JOIN free_offer_customers FOC ON FO.offer_id = FOC.offer_id 
                            WHERE (
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                                OR (
                                    (FO.start_date BETWEEN '$start' AND '$end') 
                                    OR (FO.end_date BETWEEN '$start' AND '$end')
                                )
                            )
                            AND FO.is_active = 1 
                            AND FOD.item_id = $item_id 
                            AND FOC.customer_id = $cus_id;
                            ";
                           
                            $validate_result = DB::select($validate_qry);

                            if ($validate_result) {



                                if ($validate_result[0]->count == 1) {
                                    $qry_id = DB::select("SELECT FO.offer_id
                                    FROM free_offers FO 
                                    INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                                   WHERE (((('$start' BETWEEN FO.start_date AND FO.end_date) 
                                   OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                                   AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') 
                                   OR (FO.end_date BETWEEN '$start' AND '$end'))) AND FO.is_active = 1 AND FO.apply_to = 2
                                   ");
                                  
                                    if ($qry_id[0]->offer_id != $id) {
                                        return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $cus_id]);
                                    }
                                } else if ($validate_result[0]->count > 1) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $cus_id]);
                                }
                            }
                        }
                    }
                }
            }else if($request->input('cmbApplyTo') == 3){
                $grp_id_array = json_decode($request->input('grp_id_array'));
                if (count($grp_id_array) < 1) {
                    return response()->json(["status" => false, "message" => "customer_empty"]);
                }else{
                    foreach ($item_id_array as $item_id) {
                        foreach ($grp_id_array as $grp_id) {

                            $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            INNER JOIN free_offer_customer_groups FOC ON FO.offer_id = FOC.offer_id 
                            WHERE (
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                                OR (
                                    (FO.start_date BETWEEN '$start' AND '$end') 
                                    OR (FO.end_date BETWEEN '$start' AND '$end')
                                )
                            )
                            AND FO.is_active = 1 
                            AND FOD.item_id = $item_id 
                            AND FOC.customer_group_id = $grp_id;
                            ";
                           
                            $validate_result = DB::select($validate_qry);

                            if ($validate_result) {



                                if ($validate_result[0]->count == 1) {
                                    $qry_id = DB::select("SELECT FO.offer_id
                                    FROM free_offers FO 
                                    INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                                   WHERE (((('$start' BETWEEN FO.start_date AND FO.end_date) 
                                   OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                                   AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') 
                                   OR (FO.end_date BETWEEN '$start' AND '$end'))) AND FO.is_active = 1 AND FO.apply_to = 3
                                   ");
                                    
                                    if ($qry_id[0]->offer_id != $id) {
                                        return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $grp_id]);
                                    }
                                } else if ($validate_result[0]->count > 1) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $grp_id]);
                                }
                            }
                        }
                    }

                }
            } else {
                foreach ($item_id_array as $item_id) {

                    $validate_qry = "SELECT COUNT(*) as count
                        FROM free_offers FO 
                        INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                        WHERE
                         FO.is_active = 1 
                            AND FO.apply_to = 1
                            AND FOD.item_id = $item_id
                            AND ((
                            (
                                ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                            )
                           
                        ) 
                        OR (
                            (
                                FO.start_date BETWEEN '$start' AND '$end'
                                OR FO.end_date BETWEEN '$start' AND '$end'
                            ) 
                            
                        ))
                       
                        ";
                       
                   
                    $validate_result = DB::select($validate_qry);
                    if ($validate_result) {

                        if ($validate_result[0]->count == 1) {
                            $qry_id = DB::select("SELECT FO.offer_id
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                            WHERE
                                FO.is_active = 1 AND FO.apply_to = 1 AND FOD.item_id = $item_id AND
                                (
                                    (
                                        $start BETWEEN FO.start_date AND FO.end_date
                                        OR $end BETWEEN FO.start_date AND FO.end_date
                                    )
                                    OR 
                                    (
                                        FO.start_date BETWEEN '$start' AND '$end'
                                        OR FO.end_date BETWEEN '$start' AND '$end'
                                    )
                                );
                            ");
                                 
                            if ($qry_id[0]->offer_id != $id) {
                                return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                            }
                        } else if ($validate_result[0]->count > 1) {
                            return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                        }
                    }
                }
            }

            //validating dates with item
            /*  $start = $request->input('dtStartDate');
            $end = $request->input('dtEndDate');
            if ($request->input('isActive') == 1) {
                foreach ($item_id_array as $item_id) {
                    $validate_qry = "SELECT COUNT(*) as count
                    FROM free_offers FO 
                    INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                   WHERE ((('$start' BETWEEN FO.start_date AND FO.end_date) 
                   OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                   AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') 
                   OR (FO.end_date BETWEEN '$start' AND '$end')) AND FO.is_active = 1";

                    $validate_result = DB::select($validate_qry);
                    if ($validate_result) {
                        if ($validate_result[0]->count == 1) {
                            $qry_id = DB::select("SELECT FO.offer_id
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                           WHERE ((('$start' BETWEEN FO.start_date AND FO.end_date) 
                           OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                           AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') 
                           OR (FO.end_date BETWEEN '$start' AND '$end')) AND FO.is_active = 1
                           ");
                            if ($qry_id[0]->offer_id != $id) {
                                return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                            }
                        } else if ($validate_result[0]->count > 1) {
                            return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                        }
                    }
                }
            }
 */

            $offer_data_id_array = [];
            $offer_threshold_array = [];
            $offer =  free_offer::find($id);
            $offer->name = $request->input('txtOfferName');
            $offer->description = $request->input('txtDescription');
            $offer->start_date = $request->input('dtStartDate');
            $offer->end_date = $request->input('dtEndDate');
            $offer->apply_to = $request->input('cmbApplyTo');
            $offer->is_active = $request->input('isActive');

            if ($offer->update()) {
                $primaryId = $offer->offer_id;
                $old_offer_data = free_offer_data::where("offer_id", "=", $primaryId)->get();
                foreach ($old_offer_data as $data_id) {
                    array_push($offer_data_id_array, $data_id->offer_data_id);
                }
                free_offer_data::where("offer_id", "=", $primaryId)->delete();

                foreach ($offer_data_id_array as $offer_data_id_) {
                    $old_threshold_data = free_offer_thresholds::where("offer_data_id", "=", $offer_data_id_)->delete();
                }

                foreach ($item_id_array as $id) {


                    $offer_data = new free_offer_data();
                    $offer_data->offer_id = $primaryId;
                    $offer_data->item_id = $id;
                    $offer_data->offer_type = 1;
                    $offer_data->offer_redeem_as = 1;
                    $offer_data->is_active = 1;
                    if ($offer_data->save()) {
                        foreach ($free_qty_hashMap as $key => $freeQtyArray) {
                            foreach ($freeQtyArray as $element) {
                                if ($key == $offer_data->item_id) {

                                    $free_offer_threshold = new free_offer_thresholds();
                                    $free_offer_threshold->offer_data_id = $offer_data->offer_data_id;
                                    $free_offer_threshold->quantity = $element->qty;
                                    $free_offer_threshold->free_offer_quantity = $element->foc;
                                    $free_offer_threshold->save();
                                }
                            }
                        }
                    }
                }

                //save free offer customers
                if ($offer->apply_to == 2) {
                    $old_cus_data = free_offer_customer::where('offer_id', '=', $primaryId)->delete();
                    foreach ($cus_id_array as $cus) {
                        $free_offer_customer = new free_offer_customer();
                        $free_offer_customer->offer_id = $offer->offer_id;
                        $free_offer_customer->customer_id = $cus;
                        $free_offer_customer->save();
                    }
                }else if($offer->apply_to == 3){
                    $old_grp_data = free_offer_customer_group::where('offer_id', '=', $primaryId)->delete();
                    foreach ($grp_id_array as $grp) {
                        $free_offer_customer_grp = new free_offer_customer_group();
                        $free_offer_customer_grp->offer_id = $offer->offer_id;
                        $free_offer_customer_grp->customer_group_id = $grp;
                        $free_offer_customer_grp->save();
                    }
                }
            } else {
                return response()->json(["status" => false]);
            }

            return response()->json(["status" => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //validate date overlap
    private function validate_dates($type, $apply_to, $item_array, $cus_array, $start, $end, $id)
    {
        try {
            //type 0 is insert
            if ($type == 0) {
                if ($apply_to == 2) {
                    foreach ($item_array as $item_id) {
                        foreach ($cus_array as $cus_id) {

                            $validate_qry = "SELECT COUNT(*) as count
                        FROM free_offers FO 
                        INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                        INNER JOIN free_offer_customers FOC ON FO.offer_id = FOC.offer_id 
                        WHERE (
                            (
                                ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                            )
                            OR (
                                (FO.start_date BETWEEN '$start' AND '$end') 
                                OR (FO.end_date BETWEEN '$start' AND '$end')
                            )
                        )
                        AND FO.is_active = 1 
                        AND FOD.item_id = $item_id 
                        AND FOC.customer_id = $cus_id;
                        ";

                            $validate_result = DB::select($validate_qry);

                            if ($validate_result) {

                                if ($validate_result[0]->count > 0) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $cus_id]);
                                }
                            }
                        }
                    }
                }else{
                    //all
                    foreach ($item_array as $item_id) {

                        $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            WHERE
                             FO.is_active = 1 
                                AND FO.apply_to = 1
                                AND FOD.item_id = $item_id
                                AND ((
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                               
                            ) 
                            OR (
                                (
                                    FO.start_date BETWEEN '$start' AND '$end'
                                    OR FO.end_date BETWEEN '$start' AND '$end'
                                ) 
                                
                            ))
                           
                            ";
    
                        $validate_result = DB::select($validate_qry);
                        if ($validate_result) {
                            if ($validate_result[0]->count > 0) {
                                return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                            }
                        }
                    }

                }
                 //type != 0 is update
            }else{
                //apply to customer
                if ($apply_to == 2) {
                   
                    foreach ($item_array as $item_id) {
                        foreach ($cus_array as $cus_id) {

                            $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            INNER JOIN free_offer_customers FOC ON FO.offer_id = FOC.offer_id 
                            WHERE (
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                                OR (
                                    (FO.start_date BETWEEN '$start' AND '$end') 
                                    OR (FO.end_date BETWEEN '$start' AND '$end')
                                )
                            )
                            AND FO.is_active = 1 
                            AND FOD.item_id = $item_id 
                            AND FOC.customer_id = $cus_id;
                            ";

                            $validate_result = DB::select($validate_qry);

                            if ($validate_result) {

                                if ($validate_result[0]->count == 1) {
                                    $qry_id = DB::select("SELECT FO.offer_id
                                    FROM free_offers FO 
                                    INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                                   WHERE ((('$start' BETWEEN FO.start_date AND FO.end_date) 
                                   OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                                   AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') 
                                   OR (FO.end_date BETWEEN '$start' AND '$end')) AND FO.is_active = 1
                                   ");
                                    if ($qry_id[0]->offer_id != $id) {
                                        return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $cus_id]);
                                    }
                                } else if ($validate_result[0]->count > 1) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id, "cus_id" => $cus_id]);
                                }
                            }
                        }
                    }

                    

                }else{

                    foreach ($item_array as $item_id) {

                        $validate_qry = "SELECT COUNT(*) as count
                            FROM free_offers FO 
                            INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id
                            WHERE
                             FO.is_active = 1 
                                AND FO.apply_to = 1
                                AND FOD.item_id = $item_id
                                AND ((
                                (
                                    ('$start' BETWEEN FO.start_date AND FO.end_date) 
                                    OR ('$end' BETWEEN FO.start_date AND FO.end_date)
                                )
                               
                            ) 
                            OR (
                                (
                                    FO.start_date BETWEEN '$start' AND '$end'
                                    OR FO.end_date BETWEEN '$start' AND '$end'
                                ) 
                                
                            ))
                           
                            ";
    
                        $validate_result = DB::select($validate_qry);
                        if ($validate_result) {
    
                            if ($validate_result[0]->count == 1) {
                                $qry_id = DB::select("SELECT FO.offer_id
                                        FROM free_offers FO 
                                        INNER JOIN free_offer_datas FOD ON FO.offer_id = FOD.offer_id 
                                       WHERE ((('$start' BETWEEN FO.start_date AND FO.end_date) 
                                       OR ('$end' BETWEEN FO.start_date AND FO.end_date))
                                       AND FOD.item_id = $item_id) OR ((FO.start_date BETWEEN '$start' AND '$end') 
                                       OR (FO.end_date BETWEEN '$start' AND '$end')) AND FO.is_active = 1
                                       ");
                                if ($qry_id[0]->offer_id != $id) {
                                    return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                                }
                            } else if ($validate_result[0]->count > 1) {
                                return response()->json(["status" => false, "message" => "date overlap", "item_id" => $item_id]);
                            }
                        }
                    }

                }

            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //laod all offer data to the table
    public function getAllOffers()
    {
        try {
            /* $allOffers = free_offer::all(); */
            $query = 'SELECT
            offer_id,
            name,
            description,
            start_date,
            end_date,
            IF(free_offers.apply_to = 1, "All",
              IF(free_offers.apply_to = 2, "Locations",
                IF(free_offers.apply_to = 3, "Customer",
                  IF(free_offers.apply_to = 4, "Customer grade",
                    IF(free_offers.apply_to = 5, "Customer group", NULL)
                  )
                )
              )
            ) AS apply_to,
            IF(free_offers.is_active = 1, "Yes", "No") AS is_active
          FROM
            free_offers;';
            $allOffers = DB::select($query);

            if ($allOffers) {
                return response()->json((['success' => 'Data loaded', 'data' => $allOffers]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete offer

    public function deleteoffer($id)
    {
        try {
            $deletingOffer = free_offer::find($id);
            if ($deletingOffer->delete()) {
                return response()->json((["status" => true, 'message' => 'Deleted']));
            } else {
                return response()->json((["status" => false, 'message' => 'Error']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete offer data
    public function deleteOfferData($id)
    {
        try {
            $deletingOfferData = free_offer_data::find($id);
            if ($deletingOfferData->delete()) {
                return response()->json((["status" => true, 'message' => 'Deleted']));
            } else {
                return response()->json((["status" => false, 'message' => 'Error']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }





    //add new offer data with supply group
    public function addOfferDatawithSupplyGroup(Request $request, $id)
    {
        try {
            $primaryKeys = [];
            $supplyGroupID = $request->input('cmbSupplyGroup');
            $SG_items = item::where('supply_group_id', '=', $supplyGroupID)
                ->where('allowed_free_quantity', '=', 1)
                ->get();
            if ($SG_items) {
                foreach ($SG_items as $j) {
                    $item_id = $j->item_id;
                    $item_name = $j->item_Name;
                    $freeOffers = free_offer_data::where('item_id', '=', $item_id)
                        ->where('offer_id', '=', $id)
                        ->get();
                    $count = $freeOffers->count();
                    if ($count > 0) {
                        return response()->json(["status" => true, "message" => "duplicate", "item" => $item_name]);
                    } else {
                        $offerData = new free_offer_data();
                        $offerData->offer_id = $id;
                        $offerData->item_id = $item_id;
                        $offerData->offer_type = $request->input('cmbofferType');
                        $offerData->offer_redeem_as = $request->input('cmbRedeemas');
                        $offerData->is_active = $request->input('chkActivate_offerData');

                        if ($offerData->save()) {
                            $primaryId = $offerData->offer_data_id;
                            array_push($primaryKeys, $primaryId);
                        } else {
                            return response()->json(["status" => false]);
                        }
                    }
                }
                return response()->json(["status" => true, "primaryKey" => $primaryKeys]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get offer data
    public function getAllofferData($id)
    {
        try {
            /* $offerData = free_offer_data::where('offer_id',$id)->get(); */
            $query = 'SELECT 
            items.item_Name,
            items.Item_code,
            free_offer_datas.offer_id,
            free_offer_datas.offer_data_id,
            IF(free_offer_datas.offer_type = 1,"Free offer for given a quantity","Free offer for given a quantity range") AS offer_type,
            IF(free_offer_datas.offer_redeem_as = 1,"Free offer by quantity",
            IF(free_offer_datas.offer_redeem_as = 2,"Free offer by given value",
            IF(free_offer_datas.offer_redeem_as = 3,"Free offer by price",
            IF(free_offer_datas.offer_redeem_as = 4,"Free offer by another item","")))) AS offer_redeem_as,
            IF(free_offer_datas.is_active = 1,"Yes","No")AS is_active
            FROM free_offer_datas
            INNER JOIN items ON free_offer_datas.item_id = items.item_id WHERE offer_id= "' . $id . '"';
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

    //update offer data
    public function updateOfferData(Request $request, $id, $offer_id)
    {
        try {
            $itm_id = $request->input('cmbItem');
            $freeOffers = free_offer_data::where('item_id', '=', $itm_id)
                ->where('offer_id', '=', $offer_id)->get();
            $count = $freeOffers->count();
            if ($count > 0) {
                return response()->json(["status" => true, "message" => "duplicate"]);
            } else {
                $offerData = free_offer_data::find($id);
                $offerData->item_id = $request->input('cmbItem');
                $offerData->offer_type = $request->input('cmbofferType');
                $offerData->offer_redeem_as = $request->input('cmbRedeemas');
                $offerData->is_active = $request->input('chkActivate_offerData');

                if ($offerData->update()) {
                    return response()->json(["status" => true]);
                } else {
                    return response()->json(["status" => false]);
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //add threshold
    public function addThreshold(Request $request)
    {
        try {

            $selectedIds = json_decode($request->input('selectedIDs'));
            $collection = json_decode($request->input('collection'));

            //validate
            foreach ($selectedIds as $j) {
                $val = json_decode($j);
                $count = 0;
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    if ($item->qty) {
                        $enterd_qty = $item->qty;
                    } else {
                        $enterd_qty = 0;
                    }
                    $count = DB::table('free_offer_thresholds')
                        ->where('quantity', floatval($enterd_qty))
                        ->where('offer_data_id', $val)
                        ->count();
                    if ($count > 0) {
                        return response()->json((["status" => false, 'message' => 'exist']));
                    }
                }
            }

            //inserting
            foreach ($selectedIds as $j) {
                $val = json_decode($j);
                foreach ($collection as $i) {
                    $item = json_decode($i);

                    $threshold = new free_offer_thresholds();
                    $threshold->offer_data_id = $val;
                    if ($item->qty) {
                        $threshold->quantity = $item->qty;
                    } else {
                        $threshold->quantity = 0;
                    }

                    if ($item->foc) {
                        $threshold->free_offer_quantity = $item->foc;
                    } else {
                        $threshold->free_offer_quantity = 0;
                    }

                    if ($item->mxQty) {
                        $threshold->maximum_quantity = $item->mxQty;
                    } else {
                        $threshold->maximum_quantity = 0;
                    }
                    if ($item->fov) {
                        $threshold->free_offer_value = $item->fov;
                    } else {
                        $threshold->free_offer_value = 0;
                    }

                    if ($item->mxVlv) {
                        $threshold->maximum_value = $item->mxVlv;
                    } else {
                        $threshold->maximum_value = 0;
                    }

                    if ($item->toq) {
                        $threshold->total_offer_quantity = $item->toq;
                    } else {
                        $threshold->total_offer_quantity = 0;
                    }

                    if ($item->tov) {
                        $threshold->total_offer_value = $item->tov;
                    } else {
                        $threshold->total_offer_value = 0;
                    }

                    if (!$item->free_offer_another_item_id) {
                        $threshold->free_offer_another_item_id = 0;
                    } else if ($item->free_offer_another_item_id == "undefined") {
                        $threshold->free_offer_another_item_id = 0;
                    } else {
                        $threshold->free_offer_another_item_id = $item->free_offer_another_item_id;
                    }

                    $threshold->save();
                }
            }

            return response()->json((["status" => true, 'message' => 'Added']));
        } catch (Exception $ex) {

            return $ex;
        }
    }
    //add threshold modal
    public function addThreshold_modal(Request $request, $id)
    {
        try {
            $threshold = new free_offer_thresholds();
            $threshold->offer_data_id = $id;
            $threshold->quantity = $request->input('txtQuantity');
            $threshold->free_offer_quantity = $request->input('txtFreeOfferQuantity');
            $threshold->maximum_quantity = $request->input('txtMaximumQuantity');
            $threshold->free_offer_value = $request->input('txtFreeofferValue');
            $threshold->maximum_value = $request->input('txtMaximumValue');
            $threshold->total_offer_quantity = $request->input('txtTotalOfferQuantity');
            $threshold->total_offer_value = $request->input('txtTotalOfferValue');
            $threshold->free_offer_another_item_id = $request->input('cmbFreeofferAnotherItem');
            if ($threshold->save()) {
                return response()->json((["status" => true, 'message' => 'Added']));
            } else {
                return response()->json((["status" => false, 'message' => 'Not Added']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get threshold data to data table
    public function getallthresholds($id)
    {
        try {

            $query = 'SELECT items.item_Name AS free_offer_another_item_id,free_offer_thresholds.free_offer_thresholds_id,free_offer_thresholds.offer_data_id,free_offer_thresholds.quantity,
            free_offer_thresholds.free_offer_quantity,free_offer_thresholds.maximum_quantity,free_offer_thresholds.free_offer_value,
            free_offer_thresholds.maximum_value FROM free_offer_thresholds LEFT JOIN items ON free_offer_thresholds.free_offer_another_item_id = items.item_id  WHERE offer_data_id="' . $id . '"';


            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // get each threshold to update
    public function geteachThreshold($id)
    {
        try {
            $eachThreshold = free_offer_thresholds::find($id);
            if ($eachThreshold) {
                return response()->json((['success' => 'Data loaded', 'data' => $eachThreshold]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    // updaet threshold data
    public function updateThresholdData(Request $request, $id)
    {
        try {
            $thresholdData = free_offer_thresholds::find($id);
            $thresholdData->quantity = $request->input('txtQuantity');
            $thresholdData->free_offer_quantity = $request->input('txtFreeOfferQuantity');
            $thresholdData->maximum_quantity = $request->input('txtMaximumQuantity');
            $thresholdData->free_offer_value = $request->input('txtFreeofferValue');
            $thresholdData->maximum_value = $request->input('txtMaximumValue');
            $thresholdData->free_offer_another_item_id = $request->input('cmbFreeofferAnotherItem');
            $thresholdData->total_offer_quantity = $request->input('txtTotalOfferQuantity');
            $thresholdData->total_offer_value = $request->input('txtTotalOfferValue');

            if ($thresholdData->update()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function deleteThresholdData($id)
    {
        try {
            $thresholdData = free_offer_thresholds::find($id);
            if ($thresholdData->delete()) {
                return response()->json((["status" => true, 'message' => 'Deleted']));
            } else {
                return response()->json((["status" => false, 'message' => 'Error']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function addRange(Request $request)
    {
        try {

            $SelectedID = json_decode($request->input('selectedIDs'));
            $collection = json_decode($request->input('collection'));
            foreach ($SelectedID as $j) {
                $val = json_decode($j);
                foreach ($collection as $i) {
                    $item = json_decode($i);
                    $range = new free_offer_range();
                    $range->offer_data_id = $val;

                    /*    if ($item->qty) {
                        $threshold->quantity = $item->qty;
                    }else{
                        $threshold->quantity = 0;
                    } */
                    if ($item->from) {
                        $range->from = $item->from;
                    } else {
                        $range->from = 0;
                    }

                    if ($item->to) {
                        $range->to = $item->to;
                    } else {
                        $range->to = 0;
                    }

                    if ($item->foc) {
                        $range->free_offer_quantity = $item->foc;
                    } else {
                        $range->free_offer_quantity = 0;
                    }

                    if ($item->mxQty) {
                        $range->maximum_quantity = $item->mxQty;
                    } else {
                        $range->maximum_quantity = 0;
                    }

                    if ($item->fov) {
                        $range->free_offer_value = $item->fov;
                    } else {
                        $range->free_offer_value = 0;
                    }

                    if ($item->mxVlv) {
                        $range->maximum_value = $item->mxVlv;
                    } else {
                        $range->maximum_value = 0;
                    }


                    if ($item->toq) {
                        $range->total_offer_quantity = $item->toq;
                    } else {
                        $range->total_offer_quantity = 0;
                    }

                    if ($item->tov) {
                        $range->total_offer_value = $item->tov;
                    } else {
                        $range->total_offer_value = 0;
                    }


                    if (!$item->free_offer_another_item_id) {
                        $range->free_offer_another_item_id = 0;
                    } else if ($item->free_offer_another_item_id == "undefined") {
                        $range->free_offer_another_item_id = 0;
                    } else {
                        $range->free_offer_another_item_id = $item->free_offer_another_item_id;
                    }


                    $range->save();
                }
            }

            return response()->json((["status" => true, 'message' => 'Added']));
        } catch (Exception $ex) {

            return $ex;
        }
    }

    //add range model
    public function addRange_modal(Request $request, $id)
    {
        try {

            $range = new free_offer_range();
            $range->offer_data_id = $id;
            $range->from = $request->input('txtFromRange');
            $range->to = $request->input('txtToRange');
            $range->free_offer_quantity = $request->input('txtFreeOfferQuantityRange');
            $range->maximum_quantity = $request->input('txtMaximumquantityRange');
            $range->free_offer_value = $request->input('txtFreeOfferValueRange');
            $range->maximum_value = $request->input('txtMaximumValueRange');
            $range->total_offer_quantity = $request->input('txtTotalOfferQuantityRange');
            $range->total_offer_value = $request->input('txtTotalOfferValueRange');
            $range->free_offer_another_item_id = $request->input('cmbFreeOfferAnotherItemIDRange');

            if ($range->save()) {
                return response()->json((["status" => true, 'message' => 'Added']));
            } else {
                return response()->json((["status" => false, 'message' => 'Not Added']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get range data to table
    public function GetRangeData($id)
    {
        try {
            $rangeData = 'SELECT items.item_Name as free_offer_another_item_id, free_offer_ranges.free_offer_range_id,
            free_offer_ranges.offer_data_id,free_offer_ranges.from,free_offer_ranges.to,
            free_offer_ranges.free_offer_quantity,free_offer_ranges.maximum_quantity,
            free_offer_ranges.free_offer_value,free_offer_ranges.maximum_value FROM free_offer_ranges LEFT JOIN items ON free_offer_ranges.free_offer_another_item_id = items.item_id WHERE offer_data_id="' . $id . '"';

            $result = DB::select($rangeData);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //get range data to update
    public function getEachRangeData($id)
    {
        try {
            $rangeData = free_offer_range::find($id);
            if ($rangeData) {
                return response()->json((['success' => 'Data loaded', 'data' => $rangeData]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function updateRangeData(Request $request, $id)
    {
        try {
            $rangeData = free_offer_range::find($id);
            $rangeData->from = $request->input('txtFromRange');
            $rangeData->to = $request->input('txtToRange');
            $rangeData->free_offer_quantity = $request->input('txtFreeOfferQuantityRange');
            $rangeData->maximum_quantity = $request->input('txtMaximumquantityRange');
            $rangeData->free_offer_value = $request->input('txtFreeOfferValueRange');
            $rangeData->maximum_value = $request->input('txtMaximumValueRange');
            $rangeData->free_offer_another_item_id = $request->input('cmbFreeOfferAnotherItemIDRange');
            $rangeData->total_offer_quantity = $request->input('txtTotalOfferQuantityRange');
            $rangeData->total_offer_value = $request->input('txtTotalOfferValueRange');
            if ($rangeData->update()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function deleteRange($id)
    {
        try {
            $rangeData = free_offer_range::find($id);
            if ($rangeData->delete()) {
                return response()->json((["status" => true, 'message' => 'Deleted']));
            } else {
                return response()->json((["status" => false, 'message' => 'Error']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getOptions($filterBy)
    {
        try {
            if ($filterBy == 1) {
                $options = location::all();
                if ($options) {
                    return response()->json($options);
                } else {
                    return response()->json((['error' => 'Data is not loaded', 'data' => []]));
                }
            } else if ($filterBy == 2) {
                $options = Customer::all();
                if ($options) {
                    return response()->json($options);
                } else {
                    return response()->json((['error' => 'Data is not loaded', 'data' => []]));
                }
            } else if ($filterBy == 3) {
                $options = Customer_grade::all();
                if ($options) {
                    return response()->json($options);
                } else {
                    return response()->json((['error' => 'Data is not loaded', 'data' => []]));
                }
            } else if ($filterBy == 4) {
                $options = Customer_group::all();
                if ($options) {
                    return response()->json($options);
                } else {
                    return response()->json((['error' => 'Data is not loaded', 'data' => []]));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function addApplyTo(Request $request, $id)
    {
        try {
            $option_array = json_decode($request->input('option_array'), true);
            $ApplyTotext_value_id = $request->input('ApplyTotext_value_id');
            if ($ApplyTotext_value_id == 1) {

                foreach ($option_array as $val) {
                    $offerLocation = new free_offer_location();
                    $offerLocation->offer_id = $id;
                    $offerLocation->location_id = $val;
                    $offerLocation->save();
                }
                return response()->json(["status" => true]);
            } else if ($ApplyTotext_value_id == 2) {

                foreach ($option_array as $val) {
                    $offerCustomer = new free_offer_customer();
                    $offerCustomer->offer_id = $id;
                    $offerCustomer->customer_id = $val;
                    $offerCustomer->save();
                }
                return response()->json(["status" => true]);
            } else if ($ApplyTotext_value_id == 3) {

                foreach ($option_array as $val) {

                    $offerCustomerGrade = new free_offer_customer_grade();
                    $offerCustomerGrade->offer_id = $id;
                    $offerCustomerGrade->customer_grade_id = $val;
                    $offerCustomerGrade->save();
                }
                return response()->json(["status" => true]);
            } else if ($ApplyTotext_value_id == 4) {

                foreach ($option_array as $val) {

                    $offerCustomerGrade = new free_offer_customer_group();
                    $offerCustomerGrade->offer_id = $id;
                    $offerCustomerGrade->customer_group_id = $val;
                    $offerCustomerGrade->save();
                }
                return response()->json(["status" => true]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getAllOfferCustomerSData($id)
    {
        try {
            $query = 'SELECT free_offer_customers.free_offer_customer_id, free_offers.name,
            customers.customer_name FROM free_offer_customers INNER JOIN free_offers ON free_offer_customers.offer_id = free_offers.offer_id
            INNER JOIN customers ON free_offer_customers.customer_id  = customers.customer_id WHERE free_offer_customers.offer_id = "' . $id . '"';

            $customerOffer = DB::select($query);
            if ($customerOffer) {
                return response()->json((['success' => 'Data loaded', 'data' => $customerOffer]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function deleteofferCustomer(Request $request)
    {
        try {
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {

                DB::table('free_offer_customers')
                    ->where('free_offer_customer_id', $record)
                    ->delete();
            }

            return response()->json(['message' => 'Records deleted successfully', 'status' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getAllOfferLocationData($id)
    {
        try {
            $query = 'SELECT free_offer_locations.free_offer_location_id, free_offers.name,
            locations.location_name FROM free_offer_locations INNER JOIN free_offers ON free_offer_locations.offer_id = free_offers.offer_id
            INNER JOIN locations ON free_offer_locations.location_id  = locations.location_id WHERE free_offer_locations.offer_id = "' . $id . '"';

            $offerLocation = DB::select($query);
            if ($offerLocation) {
                return response()->json((['success' => 'Data loaded', 'data' => $offerLocation]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function deleteOfferLocation(Request $request)
    {
        try {
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {

                DB::table('free_offer_locations')
                    ->where('free_offer_location_id', $record)
                    ->delete();
            }

            return response()->json(['message' => 'Records deleted successfully', 'status' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getAllCustomerGradeOfferData($id)
    {
        try {
            $query = 'SELECT free_offer_customer_grades.free_offer_customer_grade_id, free_offers.name,
                customer_grades.grade FROM free_offer_customer_grades INNER JOIN free_offers ON free_offer_customer_grades.offer_id = free_offers.offer_id
                INNER JOIN customer_grades ON free_offer_customer_grades.customer_grade_id  = customer_grades.customer_grade_id WHERE free_offer_customer_grades.offer_id = "' . $id . '"';

            $offerCustomerGrade = DB::select($query);
            if ($offerCustomerGrade) {
                return response()->json((['success' => 'Data loaded', 'data' => $offerCustomerGrade]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function deleteOfferCusGrade(Request $request)
    {
        try {
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {

                DB::table('free_offer_customer_grades')
                    ->where('free_offer_customer_grade_id', $record)
                    ->delete();
            }

            return response()->json(['message' => 'Records deleted successfully', 'status' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    // get offer customer group to data table
    public function getAllCustomerGroupOfferData($id)
    {
        try {
            $query = 'SELECT free_offer_customer_groups.free_offer_customer_group_id, free_offers.name,
            customer_groups.group FROM free_offer_customer_groups INNER JOIN free_offers ON free_offer_customer_groups.offer_id = free_offers.offer_id
            INNER JOIN customer_groups ON free_offer_customer_groups.customer_group_id  = customer_groups.customer_group_id WHERE free_offer_customer_groups.offer_id = "' . $id . '"';

            $offerCustomerGrade = DB::select($query);
            if ($offerCustomerGrade) {
                return response()->json((['success' => 'Data loaded', 'data' => $offerCustomerGrade]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function DeleteOfferCusGroup(Request $request)
    {
        try {
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {

                DB::table('free_offer_customer_groups')
                    ->where('free_offer_customer_group_id', $record)
                    ->delete();
            }

            return response()->json(['message' => 'Records deleted successfully', 'status' => true]);
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //get newly added offer
    public function getAddedOffers($id)
    {
        try {
            $query = 'SELECT
                offer_id,
                name,
                description,
                start_date,
                end_date,
                IF(free_offers.apply_to = 1, "All",
                  IF(free_offers.apply_to = 2, "Locations",
                    IF(free_offers.apply_to = 3, "Customer",
                      IF(free_offers.apply_to = 4, "Customer grade",
                        IF(free_offers.apply_to = 5, "Customer group", NULL)
                      )
                    )
                  )
                ) AS apply_to,
                IF(free_offers.is_active = 1, "Yes", "No") AS is_active
              FROM
                free_offers WHERE offer_id = :id';

            $allOffers = DB::select($query, ['id' => $id]);

            if ($allOffers) {
                return response()->json(['success' => 'Data loaded', 'data' => $allOffers]);
            } else {
                return response()->json(['error' => 'Data is not loaded']);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get newly added offer data
    public function getNewAddedofferData($id)
    {
        try {

            $query = 'SELECT 
            items.item_Name,
            items.item_id,
            items.Item_code,
            free_offer_datas.offer_id,
            free_offer_datas.offer_data_id,
            IF(free_offer_datas.offer_type = 1,"Free offer for given a quantity","Free offer for given a quantity range") AS offer_type,
            IF(free_offer_datas.offer_redeem_as = 1,"Free offer by quantity",
            IF(free_offer_datas.offer_redeem_as = 2,"Free offer by given value",
            IF(free_offer_datas.offer_redeem_as = 3,"Free offer by price",
            IF(free_offer_datas.offer_redeem_as = 4,"Free offer by another item","")))) AS offer_redeem_as,
            IF(free_offer_datas.is_active = 1,"Yes","No")AS is_active
            FROM free_offer_datas
            INNER JOIN items ON free_offer_datas.item_id = items.item_id WHERE offer_data_id= "' . $id . '"';
            $result = DB::select($query);

            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }



    // get newly added threshold

    public function getaddedthresholds($id)
    {
        try {

            $query = 'SELECT items.item_Name AS free_offer_another_item_id,free_offer_thresholds.free_offer_thresholds_id,free_offer_thresholds.offer_data_id,free_offer_thresholds.quantity,
            free_offer_thresholds.free_offer_quantity,free_offer_thresholds.maximum_quantity,free_offer_thresholds.free_offer_value,
            free_offer_thresholds.maximum_value FROM free_offer_thresholds INNER JOIN items ON free_offer_thresholds.free_offer_another_item_id = items.item_id  WHERE free_offer_thresholds_id="' . $id . '"';


            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //get newly added range data
    public function GetaddedRangeData($id)
    {
        try {
            $rangeData = 'SELECT items.item_Name as free_offer_another_item_id, free_offer_ranges.free_offer_range_id,
            free_offer_ranges.offer_data_id,free_offer_ranges.from,free_offer_ranges.to,
            free_offer_ranges.free_offer_quantity,free_offer_ranges.maximum_quantity,
            free_offer_ranges.free_offer_value,free_offer_ranges.maximum_value FROM free_offer_ranges INNER JOIN items ON free_offer_ranges.free_offer_another_item_id = items.item_id WHERE free_offer_range_id="' . $id . '"';

            $result = DB::select($rangeData);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getSupllyGroup()
    {
        try {
            $supplyGRP = supply_group::all();
            return response()->json($supplyGRP);
        } catch (Exception $ex) {

            return $ex;
        }
    }

    public function getItemsForSupGRP($id)
    {
        try {

            $items = item::where('supply_group_id', '=', $id)
                ->where('allowed_free_quantity', '=', 1)
                ->where('is_active', '=', 1)
                ->get();

            return response()->json($items);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function loadItems_freeOffer()
    {
        try {

            $items = item::where('allowed_free_quantity', '=', 1)->where('is_active', '=', 1)->get();

            return response()->json($items);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //delete offer data with checkbox (new)
    public function deleteSelectedOfferData(Request $request)
    {
        try {
            $deleting_Ids = json_decode($request->input('deleteList'));
            foreach ($deleting_Ids as $i) {
                $id = json_decode($i);
                $offer_data = free_offer_data::find($id)->delete();
            }
            return response()->json((['success' => 'Data loaded', 'status' => 'true']));
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //filter and load dala to offer list
    public function filterOffers(Request $request)
    {
        try {
            $from_date = $request->input('from_date');
            $input_date = DateTime::createFromFormat('m/d/Y', $from_date);
            $converted_from_date = $input_date->format('Y-m-d');
            $to_date = $request->input('to_date');
            $input_date_to = DateTime::createFromFormat('m/d/Y', $to_date);
            $converted_to_date = $input_date_to->format('Y-m-d');
            $filterBy = $request->input('cmbAny');
            $supplyGroup = $request->input('cmbSupplyGroup');
            $status = $request->input('cmbStatus');


            $query = "SELECT
                 free_offer_datas.offer_id,
                 free_offer_datas.offer_data_id,
                 free_offers.name,
                 free_offers.start_date,
                 free_offers.end_date,
                 free_offer_datas.item_id,
                 items.item_Name,
                 items.Item_code,
                 free_offer_thresholds.quantity,
                 free_offer_thresholds.free_offer_quantity,
                 CASE 
                     WHEN free_offer_datas.offer_type = 1 THEN 'Free offer for given a quantity'
                     WHEN free_offer_datas.offer_type = 2 THEN 'Free offer for given a quantity range'
                     ELSE 'Unknown'   
                 END AS offer_type
             FROM
                 free_offer_datas
             INNER JOIN items ON free_offer_datas.item_id = items.item_id
             INNER JOIN free_offers ON free_offer_datas.offer_id = free_offers.offer_id
             LEFT JOIN supply_groups ON items.supply_group_id = supply_groups.supply_group_id
             LEFT JOIN free_offer_thresholds ON free_offer_datas.offer_data_id = free_offer_thresholds.offer_data_id
             WHERE (free_offer_datas.offer_type <> 2) AND free_offer_thresholds.quantity > 0 AND (free_offer_datas.offer_redeem_as = 1 OR free_offer_datas.offer_redeem_as = 3)";

            if ($filterBy == 1 && $supplyGroup == 0 && $status == 0) {
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }


            if ($filterBy == 1 && $supplyGroup == 0 && $status != 0) {
                $query .= "AND free_offer_datas.is_active ='" . $status . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }

            if ($filterBy == 1 && $supplyGroup != 0 && $status == 0) {
                $query .= "AND items.supply_group_id ='" . $supplyGroup . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }

            if ($filterBy == 1 && $supplyGroup != 0 && $status != 0) {
                $query .= "AND free_offer_datas.is_active ='" . $status . "' AND items.supply_group_id ='" . $supplyGroup . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }

            if ($filterBy == 0 && $supplyGroup == 0 && $status == 0) {
                $query .= "AND free_offers.start_date >='" . $converted_from_date . "' AND free_offers.end_date <='" . $converted_to_date . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }

            if ($filterBy == 0 && $supplyGroup == 0 && $status != 0) {
                $query .= "AND free_offers.start_date >='" . $converted_from_date . "' AND free_offers.end_date <='" . $converted_to_date . "' AND free_offer_datas.is_active ='" . $status . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }

            if ($filterBy == 0 && $supplyGroup != 0 && $status == 0) {
                $query .= "AND free_offers.start_date >='" . $converted_from_date . "' AND free_offers.end_date <='" . $converted_to_date . "' AND items.supply_group_id ='" . $supplyGroup . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }

            if ($filterBy == 0 && $supplyGroup != 0 && $status != 0) {
                $query .= "AND free_offers.start_date >='" . $converted_from_date . "' AND free_offers.end_date <='" . $converted_to_date . "' AND items.supply_group_id ='" . $supplyGroup . "' AND free_offer_datas.is_active ='" . $status . "'";
                $result =  DB::select($query);
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //load supply group to list - filter (with 'Any' option)
    public function getSupllyGroupToOfferList()
    {
        try {
            $query = "SELECT 0 AS supply_group_id, 'Any' AS supply_group
            UNION
            SELECT supply_groups.supply_group_id,supply_groups.supply_group FROM supply_groups";
            $result = DB::select($query);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getthreshold_data($offerDataid)
    {
        try {
            $query = "SELECT free_offer_thresholds.*, items.item_Name, items.Item_code
            FROM free_offer_thresholds
            LEFT JOIN items ON free_offer_thresholds.free_offer_another_item_id = items.item_id
            WHERE free_offer_thresholds.offer_data_id = " . $offerDataid;
            $result = DB::select($query);
            if ($result) {
                return response()->json($result);
            } else {
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update threshold new
    public function updateNewThreshold(Request $request)
    {
        try {

            $selectedIds = json_decode($request->input('selectedIDs'));
            $collection = json_decode($request->input('collection'));
            foreach ($selectedIds as $j) {
                $val = json_decode($j);
                $deleingItem = free_offer_thresholds::where('offer_data_id', '=', $val)->delete();
            }

            foreach ($selectedIds as $j) {
                $val = json_decode($j);
                foreach ($collection as $i) {
                    $item = json_decode($i);

                    $threshold = new free_offer_thresholds();
                    $threshold->offer_data_id = $val;
                    if ($item->qty) {
                        $threshold->quantity = $item->qty;
                    } else {
                        $threshold->quantity = 0;
                    }

                    if ($item->foc) {
                        $threshold->free_offer_quantity = $item->foc;
                    } else {
                        $threshold->free_offer_quantity = 0;
                    }

                    if ($item->mxQty) {
                        $threshold->maximum_quantity = $item->mxQty;
                    } else {
                        $threshold->maximum_quantity = 0;
                    }
                    if ($item->fov) {
                        $threshold->free_offer_value = $item->fov;
                    } else {
                        $threshold->free_offer_value = 0;
                    }

                    if ($item->mxVlv) {
                        $threshold->maximum_value = $item->mxVlv;
                    } else {
                        $threshold->maximum_value = 0;
                    }

                    if ($item->toq) {
                        $threshold->total_offer_quantity = $item->toq;
                    } else {
                        $threshold->total_offer_quantity = 0;
                    }

                    if ($item->tov) {
                        $threshold->total_offer_value = $item->tov;
                    } else {
                        $threshold->total_offer_value = 0;
                    }

                    if (!$item->free_offer_another_item_id) {
                        $threshold->free_offer_another_item_id = 0;
                    } else if ($item->free_offer_another_item_id == "undefined") {
                        $threshold->free_offer_another_item_id = 0;
                    } else {
                        $threshold->free_offer_another_item_id = $item->free_offer_another_item_id;
                    }

                    $threshold->save();
                }
            }

            return response()->json((["status" => true, 'message' => 'Added']));
        } catch (Exception $ex) {

            return $ex;
        }
    }

    //check threshold exist
    public function checkThresholExist(Request $request)
    {
        try {
            $selectedIds = json_decode($request->input('selectedIDs'));

            //validate
            foreach ($selectedIds as $j) {
                $val = json_decode($j);
                $count = 0;

                $count = DB::table('free_offer_thresholds')
                    ->where('offer_data_id', $val)
                    ->count();
                if ($count > 0) {
                    return response()->json((["status" => false, 'message' => 'exist']));
                } else {
                    return response()->json((["status" => false, 'message' => 'no']));
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    /*********free offer modified latest ********************/
    //get data to dual list box - customers,group,grade
    public function get_offer_data_apply_to($type)
    {
        try {
            if ($type == 2) {
                $customer = DB::select("SELECT customer_id as id,CONCAT(customer_code,' - ',customer_name,' - ',town_non_administratives.townName) as name FROM customers INNER JOIN town_non_administratives ON customers.town = town_non_administratives.town_id");
                return response()->json($customer);
            } else if ($type == 3) {
                $customer_group = DB::select("SELECT customer_group_id as id,customer_groups.group as name FROM customer_groups");
                return response()->json($customer_group);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load selected custoemrs to html table
    public function load_selected_customers(Request $request)
    {
        try {
            $cus_data = [];
            $cus_id_array = $request->get('option_array');
            foreach ($cus_id_array as $id) {
                $qry = DB::select('SELECT customer_id, customer_code, customer_name,townName FROM customers LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id WHERE customers.customer_id = ' . $id);
                if ($qry) {
                    array_push($cus_data, $qry);
                }
            }

            return response()->json(["data" => $cus_data]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load selected customers accrdign to cus grp to html table
    public function load_grp_customers(Request $request)
    {
        try {
            $cus_data = [];
            $cus_grp_array = $request->get('option_array');
            foreach ($cus_grp_array as $id) {
                $qry = DB::select('SELECT customer_id, customer_code, customer_name,townName FROM customers LEFT JOIN town_non_administratives ON customers.town = town_non_administratives.town_id WHERE customers.customer_group_id = ' . $id);
                if ($qry) {
                    array_push($cus_data, $qry);
                }
            }

            return response()->json(["data" => $cus_data]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function load_selected_groups(Request $request)
    {
        try {
            $grp_data = [];
            $cus_grp_array = $request->get('option_array');
            foreach ($cus_grp_array as $id) {
                $qry = DB::select('SELECT customer_groups.customer_group_id,customer_groups.group  FROM customer_groups WHERE customer_groups.customer_group_id = ' . $id);
                if ($qry) {
                    array_push($grp_data, $qry);
                }
            }

            return response()->json(["data" => $grp_data]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    //get each function - latest view
    public function get_each_offer($id)
    {
        try {
            $item_array = [];
            $offer_qry = DB::select("SELECT * FROM free_offers WHERE free_offers.offer_id = $id");
            $offer_data_qry = DB::select("SELECT free_offer_datas.*,items.Item_code,items.item_Name,items.is_active FROM free_offer_datas INNER JOIN items ON free_offer_datas.item_id = items.item_id WHERE free_offer_datas.offer_id = $id");
            if ($offer_data_qry) {
                foreach ($offer_data_qry as $data) {
                    $offer_data_id = $data->offer_data_id;
                    $offer_item_qry = DB::select("SELECT free_offer_thresholds.*, free_offer_datas.item_id AS key_value
                    FROM free_offer_thresholds
                    INNER JOIN free_offer_datas ON free_offer_datas.offer_data_id = free_offer_thresholds.offer_data_id
                    WHERE free_offer_thresholds.offer_data_id = $offer_data_id;
                    ");
                    if ($offer_item_qry) {
                        array_push($item_array, $offer_item_qry);
                    }
                }
            }

            $offer_customer_qry = DB::select("SELECT free_offer_customers.*,customers.customer_id,customers.customer_name,customers.customer_code FROM free_offer_customers INNER JOIN customers ON free_offer_customers.customer_id = customers.customer_id WHERE free_offer_customers.offer_id = $id");
            $offer_cus_group = DB::select("SELECT FCG.*,CG.customer_group_id,CG.`group` FROM free_offer_customer_groups FCG INNER JOIN customer_groups CG ON FCG.customer_group_id = CG.customer_group_id WHERE FCG.offer_id = $id");
            return response()->json(["offer" => $offer_qry, "offer_Data" => $offer_data_qry, "item" => $item_array, "customers" => $offer_customer_qry,"supply_group" => $offer_cus_group]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}

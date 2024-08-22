<?php

namespace Modules\Md\Http\Controllers;

use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Md\Entities\Customer;
use Modules\Md\Entities\Customer_grade;
use Modules\Md\Entities\Customer_group;
use Modules\Md\Entities\free_offer;
use Modules\Md\Entities\free_offer_customer;
use Modules\Md\Entities\free_offer_customer_grade;
use Modules\Md\Entities\free_offer_customer_group;
use Modules\Md\Entities\free_offer_data;
use Modules\Md\Entities\free_offer_location;
use Modules\Md\Entities\free_offer_range;
use Modules\Md\Entities\free_offer_thresholds;
use Modules\Md\Entities\location;

class freeOfferController extends Controller
{

    // add new offer
    public function addFreeOffer(Request $request)
    {
        try {
            $request->validate([

                'txtOfferName' => 'required'

            ]);
            $offer = new free_offer();
            $offer->name = $request->input('txtOfferName');
            $offer->description = $request->input('txtDescription');
            $offer->start_date = $request->input('dtStartDate');
            $offer->end_date = $request->input('dtEndDate');
            $offer->apply_to = $request->input('cmbApplyTo');
            $offer->is_active = $request->input('isActive');

            if ($offer->save()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //update offer
    public function updateOffer(Request $request, $id)
    {
        try {
            $request->validate([

                'txtOfferName' => 'required'

            ]);
            $offer = free_offer::find($id);
            $offer->name = $request->input('txtOfferName');
            $offer->description = $request->input('txtDescription');
            $offer->start_date = $request->input('dtStartDate');
            $offer->end_date = $request->input('dtEndDate');
            $offer->apply_to = $request->input('cmbApplyTo');
            $offer->is_active = $request->input('isActive');

            if ($offer->update()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
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
                return response()->json((['error' => 'Data is not loaded']));
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
    public function deleteOfferData($id){
        try{
            $deletingOfferData = free_offer_data::find($id);
            if($deletingOfferData->delete()){
                return response()->json((["status" => true, 'message' => 'Deleted']));
            }else{
                return response()->json((["status" => false, 'message' => 'Error']));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    //get offer (details)
    public function getEachOfferData($id)
    {
        try {
            $searchOffer = free_offer::find($id);
            if ($searchOffer) {
                return response()->json((['success' => 'Data loaded', 'data' => $searchOffer]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
    
    //get each offer data
    public function getEachOfferDataDetails($id){
        try{
            $searcOFferData = free_offer_data::find($id);
            if ($searcOFferData) {
                return response()->json((['success' => 'Data loaded', 'data' => $searcOFferData]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    //add offer data
    public function addOfferData(Request $request, $id)
    {
        try {
            $offerData = new free_offer_data();
            $offerData->offer_id = $id;
            $offerData->item_id = $request->input('cmbItem');
            $offerData->offer_type = $request->input('cmbofferType');
            $offerData->offer_redeem_as = $request->input('cmbRedeemas');
            $offerData->is_active = $request->input('chkActivate_offerData');

            if ($offerData->save()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
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
            free_offer_datas.offer_id,
            free_offer_datas.offer_data_id,
            IF(free_offer_datas.offer_type = 1,"Free Offering Thresholds","Free offering range") AS offer_type,
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
    public function updateOfferData(Request $request, $id){
        try{
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


        }catch(Exception $ex){
            return $ex;
        }

    }

    //add threshold
    public function addThreshold(Request $request, $id){
        try{
            $threshold = new free_offer_thresholds();
            $threshold-> offer_data_id = $id;
            $threshold->quantity = $request->input('txtQuantity');
            $threshold->free_offer_quantity = $request-> input('txtFreeOfferQuantity');
            $threshold->maximum_quantity = $request-> input('txtMaximumQuantity');
            $threshold->free_offer_value = $request->input('txtFreeofferValue');
            $threshold->maximum_value = $request ->input('txtMaximumValue');
            $threshold->free_offer_another_item_id = $request->input('cmbFreeofferAnotherItem');

            if ($threshold->save()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            return $ex;

        }
    }

    //get threshold data to data table
    public function getallthresholds($id){
        try{
            
            $query = 'SELECT items.item_Name AS free_offer_another_item_id,free_offer_thresholds.free_offer_thresholds_id,free_offer_thresholds.offer_data_id,free_offer_thresholds.quantity,
            free_offer_thresholds.free_offer_quantity,free_offer_thresholds.maximum_quantity,free_offer_thresholds.free_offer_value,
            free_offer_thresholds.maximum_value FROM free_offer_thresholds INNER JOIN items ON free_offer_thresholds.free_offer_another_item_id = items.item_id  WHERE offer_data_id="'.$id.'"';
             
            
            $result = DB::select($query);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded','data' =>[]]));
            }


        }catch(Exception $ex){
            return $ex;
        }
    }

    // get each threshold to update
    public function geteachThreshold($id){
        try{
            $eachThreshold = free_offer_thresholds::find($id);
            if ($eachThreshold) {
                return response()->json((['success' => 'Data loaded', 'data' => $eachThreshold]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }


        }catch(Exception $ex){
            return $ex;
        }
    }


    // updaet threshold data
    public function updateThresholdData(Request $request, $id){
        try{
            $thresholdData = free_offer_thresholds::find($id);
            $thresholdData->quantity = $request->input('txtQuantity');
            $thresholdData->free_offer_quantity = $request-> input('txtFreeOfferQuantity');
            $thresholdData->maximum_quantity = $request-> input('txtMaximumQuantity');
            $thresholdData->free_offer_value = $request->input('txtFreeofferValue');
            $thresholdData->maximum_value = $request ->input('txtMaximumValue');
            $thresholdData->free_offer_another_item_id = $request->input('cmbFreeofferAnotherItem');

            if ($thresholdData->update()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }


        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteThresholdData($id){
        try{
            $thresholdData = free_offer_thresholds::find($id);
            if ($thresholdData->delete()) {
                return response()->json((["status" => true, 'message' => 'Deleted']));
            } else {
                return response()->json((["status" => false, 'message' => 'Error']));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }


    public function addRange(Request $request,$id){
        try{
            $rangeData = new free_offer_range();
            $rangeData->offer_data_id = $id;
            $rangeData->from = $request->input('dtFromRange');
            $rangeData->to = $request->input('dtToRange');
            $rangeData->free_offer_quantity = $request->input('txtFreeOfferQuantityRange');
            $rangeData->maximum_quantity = $request->input('txtMaximumquantityRange');
            $rangeData->free_offer_value = $request-> input('txtFreeOfferValueRange');
            $rangeData->maximum_value = $request->input('txtMaximumValueRange');
            $rangeData->free_offer_another_item_id = $request->input('cmbFreeOfferAnotherItemIDRange');

            if ($rangeData->save()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    //get range data to table
    public function GetRangeData($id){
        try{
            $rangeData = 'SELECT items.item_Name as free_offer_another_item_id, free_offer_ranges.free_offer_range_id,
            free_offer_ranges.offer_data_id,free_offer_ranges.from,free_offer_ranges.to,
            free_offer_ranges.free_offer_quantity,free_offer_ranges.maximum_quantity,
            free_offer_ranges.free_offer_value,free_offer_ranges.maximum_value FROM free_offer_ranges INNER JOIN items ON free_offer_ranges.free_offer_another_item_id = items.item_id WHERE offer_data_id="'.$id.'"';

            $result = DB::select($rangeData);
            if ($result) {
                return response()->json((['success' => 'Data loaded', 'data' => $result]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }


        }catch(Exception $ex){
            return $ex;
        }
    }

    //get range data to update
    public function getEachRangeData($id){
        try{
            $rangeData = free_offer_range::find($id);
            if ($rangeData) {
                return response()->json((['success' => 'Data loaded', 'data' => $rangeData]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function updateRangeData(Request $request,$id){
        try{
            $rangeData = free_offer_range::find($id);
            $rangeData->from = $request->input('dtFromRange');
            $rangeData->to = $request->input('dtToRange');
            $rangeData->free_offer_quantity = $request->input('txtFreeOfferQuantityRange');
            $rangeData->maximum_quantity = $request->input('txtMaximumquantityRange');
            $rangeData->free_offer_value = $request-> input('txtFreeOfferValueRange');
            $rangeData->maximum_value = $request->input('txtMaximumValueRange');
            $rangeData->free_offer_another_item_id = $request->input('cmbFreeOfferAnotherItemIDRange');
            if ($rangeData->update()) {
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteRange($id){
        try{
            $rangeData = free_offer_range::find($id);
            if ($rangeData->delete()) {
                return response()->json((["status" => true, 'message' => 'Deleted']));
            } else {
                return response()->json((["status" => false, 'message' => 'Error']));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function getOptions($filterBy){
         try{
            if($filterBy == 1){
                $options = location::all();
                if ($options) {
                    return response()->json($options);
                } else {
                    return response()->json((['error' => 'Data is not loaded','data'=>[]]));
                }
            }else if($filterBy == 2){
                 $options = Customer::all();
                 if($options){
                    return response()->json($options);
                 }else{
                    return response()->json((['error' => 'Data is not loaded','data'=>[]]));
                 }
            }else if($filterBy == 3){
                $options = Customer_grade::all();
                if($options){
                    return response()->json($options);
                 }else{
                    return response()->json((['error' => 'Data is not loaded','data'=>[]]));
                 }
            }else if($filterBy == 4){
                $options = Customer_group::all();
                if($options){
                    return response()->json($options);
                 }else{
                    return response()->json((['error' => 'Data is not loaded','data'=>[]]));
                 }
            }

        }catch(Exception $ex){
            return $ex;
        } 
    }

    public function addApplyTo(Request $request,$id){
        try{
            $option_array = json_decode($request->input('option_array'), true);
            $ApplyTotext_value_id = $request->input('ApplyTotext_value_id');
            if($ApplyTotext_value_id == 1){
               
                foreach ($option_array as $val) {
                    $offerLocation = new free_offer_location();
                    $offerLocation->offer_id = $id;
                    $offerLocation->location_id = $val;
                    $offerLocation->save();
                }
                return response()->json(["status" => true]);
            }else if($ApplyTotext_value_id == 2){

                foreach ($option_array as $val) {
                    $offerCustomer = new free_offer_customer();
                    $offerCustomer->offer_id = $id;
                    $offerCustomer->customer_id = $val;
                    $offerCustomer->save();
                }
                return response()->json(["status" => true]);
            }else if($ApplyTotext_value_id == 3){

                foreach ($option_array as $val) {

                    $offerCustomerGrade = new free_offer_customer_grade();
                    $offerCustomerGrade->offer_id = $id;
                    $offerCustomerGrade->customer_grade_id = $val;
                    $offerCustomerGrade->save();
                }
                return response()->json(["status" => true]);

            }else if($ApplyTotext_value_id == 4){

                foreach ($option_array as $val) {

                    $offerCustomerGrade = new free_offer_customer_group();
                    $offerCustomerGrade->offer_id = $id;
                    $offerCustomerGrade->customer_group_id = $val;
                    $offerCustomerGrade->save();
                }
                return response()->json(["status" => true]);

            }

        
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function getAllOfferCustomerSData($id){
        try{
            $query = 'SELECT free_offer_customers.free_offer_customer_id, free_offers.name,
            customers.customer_name FROM free_offer_customers INNER JOIN free_offers ON free_offer_customers.offer_id = free_offers.offer_id
            INNER JOIN customers ON free_offer_customers.customer_id  = customers.customer_id WHERE free_offer_customers.offer_id = "'.$id.'"';

             $customerOffer = DB::select($query);
             if ($customerOffer) {
                return response()->json((['success' => 'Data loaded', 'data' => $customerOffer]));
            } else {
                return response()->json((['error' => 'Data is not loaded','data'=>[]]));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteofferCustomer(Request $request){
        try{
            $selectedRecords = $request->input('records');

        foreach ($selectedRecords as $record) {
            
            DB::table('free_offer_customers')
                ->where('free_offer_customer_id', $record)
                ->delete();
        }
    
        return response()->json(['message' => 'Records deleted successfully','status'=>true]);

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function getAllOfferLocationData($id){
        try{
            $query = 'SELECT free_offer_locations.free_offer_location_id, free_offers.name,
            locations.location_name FROM free_offer_locations INNER JOIN free_offers ON free_offer_locations.offer_id = free_offers.offer_id
            INNER JOIN locations ON free_offer_locations.location_id  = locations.location_id WHERE free_offer_locations.offer_id = "'.$id.'"';

            $offerLocation = DB::select($query);
            if ($offerLocation) {
                return response()->json((['success' => 'Data loaded', 'data' => $offerLocation]));
            } else {
                return response()->json((['error' => 'Data is not loaded','data'=>[]]));
            }

        }catch(Exception $ex){
            return $ex;
        }
    }

    public function deleteOfferLocation(Request $request){
        try{
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {
                
                DB::table('free_offer_locations')
                    ->where('free_offer_location_id', $record)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully','status'=>true]);


        }catch(Exception $ex){
            return $ex;
        }


    }

    public function getAllCustomerGradeOfferData($id){
            try{
                $query = 'SELECT free_offer_customer_grades.free_offer_customer_grade_id, free_offers.name,
                customer_grades.grade FROM free_offer_customer_grades INNER JOIN free_offers ON free_offer_customer_grades.offer_id = free_offers.offer_id
                INNER JOIN customer_grades ON free_offer_customer_grades.customer_grade_id  = customer_grades.customer_grade_id WHERE free_offer_customer_grades.offer_id = "'.$id.'"';
    
                $offerCustomerGrade = DB::select($query);
                if ($offerCustomerGrade) {
                    return response()->json((['success' => 'Data loaded', 'data' => $offerCustomerGrade]));
                } else {
                    return response()->json((['error' => 'Data is not loaded','data'=>[]]));
                }
    
            }catch(Exception $ex){
                return $ex;
            }
        
    }

    public function deleteOfferCusGrade(Request $request){
        try{
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {
                
                DB::table('free_offer_customer_grades')
                    ->where('free_offer_customer_grade_id', $record)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully','status'=>true]);


        }catch(Exception $ex){
            return $ex;
        }

    }

    // get offer customer group to data table
    public function getAllCustomerGroupOfferData($id){
        try{
            $query = 'SELECT free_offer_customer_groups.free_offer_customer_group_id, free_offers.name,
            customer_groups.group FROM free_offer_customer_groups INNER JOIN free_offers ON free_offer_customer_groups.offer_id = free_offers.offer_id
            INNER JOIN customer_groups ON free_offer_customer_groups.customer_group_id  = customer_groups.customer_group_id WHERE free_offer_customer_groups.offer_id = "'.$id.'"';

            $offerCustomerGrade = DB::select($query);
            if ($offerCustomerGrade) {
                return response()->json((['success' => 'Data loaded', 'data' => $offerCustomerGrade]));
            } else {
                return response()->json((['error' => 'Data is not loaded','data'=>[]]));
            }

        }catch(Exception $ex){
            return $ex;
        }
        

    }

    public function DeleteOfferCusGroup(Request $request){
          try{
            $selectedRecords = $request->input('records');

            foreach ($selectedRecords as $record) {
                
                DB::table('free_offer_customer_groups')
                    ->where('free_offer_customer_group_id', $record)
                    ->delete();
            }
        
            return response()->json(['message' => 'Records deleted successfully','status'=>true]);


        }catch(Exception $ex){
            return $ex;
        }

    }



}

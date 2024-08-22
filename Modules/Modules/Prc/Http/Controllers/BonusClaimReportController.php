<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BonusClaimReportController extends Controller
{


    public function printBonusClaimReportPdf($id){
        try {

           
               return response()->json(['success' => true, 'data' => [
                 'bonusClaim' => $this->getDatagrnRqst($id),
                 'bonusClaim_item' => $this->getDatagrnItem($id),
                 'company_name' => CompanyDetailsController::CompanyName(),
                 'company_address' => CompanyDetailsController::CompanyAddress(),
                 'contact_details' => CompanyDetailsController::CompanyNumber()
             ]]);


             } catch (\Exception $ex) {
             return response()->json(['status' => false, 'error' => $ex->getMessage()]);
             }

     }

                     private function getDatagrnRqst($id)
                 {
                     $qry = 'SELECT
                     BC.*,
                     branches.branch_name,
                     users.name AS userName,
                     users.email
                    
                 FROM
                     bonus_claims BC
                 LEFT JOIN
                     locations ON BC.location_id = locations.location_id
                 LEFT JOIN
                     users ON BC.prepaired_by = users.id
                     LEFT JOIN
                     branches ON BC.branch_id=branches.branch_id
                    
                     WHERE
                     BC.bonus_claim_Id ="' . $id . '"';
                     return DB::select($qry);
                 }
                 private function getDatagrnItem($id)
                 {
                     $qry = 'SELECT BS.*, 
                     items.*,BS.whole_sale_price AS whprice,BS.retial_price AS retprice,
                     ROUND(BS.price * BS.quantity, 2) AS amount
              FROM bonus_claim_items BS
              INNER JOIN items ON BS.item_id = items.item_id
              WHERE BS.bonus_claim_Id="' . $id . '"';

                     return DB::select($qry);
                 }
}

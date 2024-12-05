<?php

namespace Modules\Cb\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SfaChequeCollectionListController extends Controller
{
    public function load_sfa_cheque_collection_list(){
        try{

            $query = "SELECT
       DATE(CC.created_at) AS created_date,
    CC.cheque_collection_id,
    CC.external_number,
    SUM(CR.amount) AS amount
FROM
    cheque_collections CC
INNER JOIN
    sfa_receipts CR ON CC.cheque_collection_id = CR.cheque_collection_id
GROUP BY
    CC.internal_number
ORDER BY
    CC.created_at DESC;";
            $result = DB::select($query);
            if ($result) {
                return response()->json(["status" => true, "data" => $result]);
            } else {
                return response()->json(["status" => false, "data" => []]);
            }
        }catch(Exception $ex){
            return $ex;
        }
    }
}

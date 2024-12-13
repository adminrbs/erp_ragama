<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Modules\St\Entities\GlobalDocument;

class ReferenceIdController extends Controller
{
    public function newReferenceId($table, $doc_number) //purchase request
    {
        /*  try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(MAX(external_number)),0,MAX(external_number)) AS id FROM " . $table . "  WHERE document_number = '".$doc_number."' AND external_number LIKE '%".$prefix."%'";
            $result = explode("-", DB::select($query)[0]->id);
            $id = 0;
            if (count($result) == 2) {
                $id =  ($result[1] + 1);
            }else{
                $id =  ($result[0] + 1); 
            }
            //$prefix = str_replace("-","",GlobalDocument::where('document_number','=','210')->get()[0]->prefix);
            
            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        } */

        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY purchase_request_Id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //PO reference
    public function PO_rferenceId(Request $request, $table, $doc_number)
    {


        try {

            $branch_id = $request->input('id');

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY purchase_order_Id  DESC LIMIT 1";
            $query = "
    SELECT 
        IF(ISNULL(external_number), 0, external_number) AS id 
    FROM 
        " . $table . " 
    WHERE 
        document_number = '" . $doc_number . "' 
        AND external_number LIKE '%" . $prefix . "%' 
        AND branch_id = '" . $branch_id . "' 
    ORDER BY 
        purchase_order_Id DESC 
    LIMIT 1
";

            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //GRN reference and RTN reference
    public function GRN_referenceId(Request $request, $table, $doc_number)
    {


        try {
            $branch_id = $request->input('id');
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            //$query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY goods_received_Id  DESC LIMIT 1";
            $query = "
            SELECT 
                IF(ISNULL(external_number), 0, external_number) AS id 
            FROM 
                " . $table . " 
            WHERE 
                document_number = '" . $doc_number . "' 
                AND external_number LIKE '%" . $prefix . "%' 
                AND branch_id = '" . $branch_id . "' 
            ORDER BY 
                goods_received_Id DESC 
            LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //gr return
    public function GRN_return_referenceId(Request $request, $table, $doc_number)
    {

        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY goods_received_return_Id  DESC LIMIT 1";
            $branch_id = $request->input('id');
            $query = "
            SELECT 
                IF(ISNULL(external_number), 0, external_number) AS id 
            FROM 
                " . $table . " 
            WHERE 
                document_number = '" . $doc_number . "' 
                AND external_number LIKE '%" . $prefix . "%' 
                AND branch_id = '" . $branch_id . "' 
            ORDER BY 
                goods_received_return_Id DESC 
            LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //SO reference
    public function SO_referenceId_gen($table, $doc_number)
    {

        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY sales_order_Id  DESC LIMIT 1";
            $result = explode("-", DB::select($query)[0]->id);
            $id = 0;
            if (count($result) == 2) {
                $id =  ($result[1] + 1);
            } else {
                $id =  ($result[0] + 1);
            }
            //$prefix = str_replace("-","",GlobalDocument::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //sales invoice refernece

    public function SI_referenceId(Request $request, $table, $doc_number)
    {


        try {
            $branch_id = $request->input('id');

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            /*  $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "% AND branch_id ='".$branch_id." ' ORDER BY sales_invoice_Id  DESC LIMIT 1"; */
            $query = "
    SELECT 
        IF(ISNULL(external_number), 0, external_number) AS id 
    FROM 
        " . $table . " 
    WHERE 
        document_number = '" . $doc_number . "' 
        AND external_number LIKE '%" . $prefix . "%' 
        AND branch_id = '" . $branch_id . "' 
    ORDER BY 
        sales_invoice_Id DESC 
    LIMIT 1
";

            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //sales return id
    public function SR_referenceId($table, $doc_number)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY sales_return_Id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function CustomerReceipt_referenceID(Request $request, $table, $doc_number)
    {
        //dd()
        //dd($request->input('id'));
        try {
            $branch_id = $request->input('id');
            // dd($branch_id);
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . " WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' AND branch_id = '".$branch_id."' ORDER BY customer_receipt_id  DESC LIMIT 1";
            /*  $query = "SELECT IF(ISNULL(external_number), 0, external_number) AS id 
FROM " . $table . " 
WHERE document_number = '" . $doc_number . "' 
  AND external_number LIKE '%" . $prefix . "%' 
  AND branch_id = '" . $branch_id . "' 
ORDER BY customer_receipt_id DESC 
LIMIT 1";  */



            $query = "
            SELECT 
                IF(ISNULL(external_number), 0, external_number) AS id 
            FROM 
                " . $table . " 
            WHERE 
                document_number = '" . $doc_number . "' 
                AND external_number LIKE '%" . $prefix . "%' 
                AND branch_id = '" . $branch_id . "' 
            ORDER BY 
                customer_receipt_id DESC 
            LIMIT 1";
            //dd($query);
            $result = DB::select($query);
            $id = 1;
            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                if (count($result) >= 3) {
                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[1] + 1;
                }
            }
            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public static function CustomerReceipt_referenceID_cash_bundle($table, $doc_number)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY customer_receipt_id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;
            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                if (count($result) >= 3) {
                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[1] + 1;
                }
            }
            return ["id" => $id, "prefix" => $prefix];
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function SO_REF_id($table, $doc_number)
    {
        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY sales_order_Id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function DeliveryPlan_referenceID($table, $doc_number)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY delivery_plan_id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;
            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                if (count($result) >= 3) {
                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[1] + 1;
                }
            }
            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function cash_bundle_referenceID($table, $doc_number)
    {
        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY cash_bundles_id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public static function SR_trans_referenceId($table, $doc_number)
    {
        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY return_transfer_id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;
            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                if (count($result) >= 3) {
                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[1] + 1;
                }
            }
            return ["id" => $id, "prefix" => $prefix];
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_Goods_transfer($table, $doc_number)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // dd($prefix);
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY goods_transfer_id  DESC LIMIT 1";

            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_cheque_collection($table, $doc_number)
    {
        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY cheque_collection_id  DESC LIMIT 1";
            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            //dd($id);
            //$prefix = str_replace("-","",global_document::where('document_number','=','210')->get()[0]->prefix);

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_sample_dispatch($table, $doc_number)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // dd($prefix);
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY sample_dispatch_id  DESC LIMIT 1";

            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_customer_transaction_allocation($table, $doc_number)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // dd($prefix);
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY customer_transaction_alocation_id  DESC LIMIT 1";

            $result = DB::select($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    /* dd($id); */
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_stockadjustment($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            //dd($prefix);
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY stock_adjustment_id  DESC LIMIT 1";

            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }



    public function newReferenceNumber_debit_note($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY debit_notes_id  DESC LIMIT 1";

            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_credit_note($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY credit_notes_id  DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_dispatch_to_branch($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY dispatch_to_branch_id  DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_chqReturn($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY cheque_returns_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);
            //dd($result);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public static function newReferenceNumber_chqReturn_new($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY debit_notes_id DESC LIMIT 1";

            $result = DB::select($query);
            // dd($query);
            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return ["id" => $id, "prefix" => $prefix];
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_dispatch_receive($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY dispatch_recieve_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_reverse_dispatch($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY reverse_devision_transfer_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function newReferenceNumber_creditor_debit_note($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY creditor_debit_notes_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function newReferenceNumber_creditor_credit_note($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY creditor_credit_notes_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    //internal orders
    public function newReferenceNumber_InternalOrders($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY internal_orders_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //supplier payment
    public function newReferenceNumber_supplierPayment($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY supplier_payment_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_supplier_transaction_allocation($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY supplier_transaction_alocation_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_BonusClaim_referenceId(Request $request, $table, $doc_number)
    {
        try {
            $branch_id = $request->input('id');
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // dd($prefix);
            $query = "
            SELECT 
                IF(ISNULL(external_number), 0, external_number) AS id 
            FROM 
                " . $table . " 
            WHERE 
                document_number = '" . $doc_number . "' 
                AND external_number LIKE '%" . $prefix . "%' 
                AND branch_id = '" . $branch_id . "' 
            ORDER BY 
                bonus_claim_Id DESC 
            LIMIT 1";
            //$query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' ORDER BY bonus_claim_Id  DESC LIMIT 1";

            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }


            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function newReferenceNumber_direct_cash_bundles(Request $request, $table, $doc_number)
    {
        try {
            $branch_id = $request->input('id');
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // dd($prefix);
            $query = "
           SELECT 
               IF(ISNULL(external_number), 0, external_number) AS id 
           FROM 
               " . $table . " 
           WHERE 
               document_number = '" . $doc_number . "' 
               AND external_number LIKE '%" . $prefix . "%' 
               AND branch_id = '" . $branch_id . "' 
           ORDER BY 
               direct_cash_bundle_id DESC 
           LIMIT 1
       ";
            //dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[0] + 1;
                }
            }

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //direct cheque collection
    public function newReferenceNumber_direct_cheque_bundles(Request $request, $table, $doc_number)
    {
        try {
            $branch_id = $request->input('id');

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            // dd($prefix);
            $query = "
           SELECT 
               IF(ISNULL(external_number), 0, external_number) AS id 
           FROM 
               " . $table . " 
           WHERE 
               document_number = '" . $doc_number . "' 
               AND external_number LIKE '%" . $prefix . "%' 
               AND branch_id = '" . $branch_id . "' 
           ORDER BY 
               direct_cheque_collection_id DESC 
           LIMIT 1
       ";
            //  dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);

                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[0] + 1;
                }
            }

            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function newReferenceNumber_sales_invoice_copy_issued($table, $doc_number)
    {
        try {

            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%'  ORDER BY sales_invoice_copy_issued_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function newReferenceNumber_paymentVoucher_referenceId(Request $request, $table, $doc_number)
    {
        try {

            $branch_id = $request->input('id');
            //dd($doc_number);
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;

            $query = "SELECT IF(ISNULL(external_number),0,external_number) AS id FROM " . $table . "  WHERE document_number = '" . $doc_number . "' AND external_number LIKE '%" . $prefix . "%' AND branch_id = '" . $branch_id . "'  ORDER BY payment_voucher_id DESC LIMIT 1";
            // dd($query);
            $result = DB::select($query);

            $id = 1;

            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                //dd($result);
                if (count($result) >= 3) {

                    $id = (int) $result[2] + 1;
                    //dd($id); 
                } else {
                    $id = (int) $result[0] + 1;
                }
            }



            return response()->json(["id" => $id, "prefix" => $prefix]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public static function CustomerReceipt_referenceID_sales_invoice_cash($table, $doc_number, $branch)
    {


        try {
            $prefix = GlobalDocument::where('document_number', '=', $doc_number)->get()[0]->prefix;
            $query = "
            SELECT 
                IF(ISNULL(external_number), 0, external_number) AS id 
            FROM 
                " . $table . " 
            WHERE 
                document_number = '" . $doc_number . "' 
                AND external_number LIKE '%" . $prefix . "%' 
                AND branch_id = '" . $branch . "' 
            ORDER BY 
                customer_receipt_id DESC 
            LIMIT 1";
            $result = DB::select($query);
            $id = 1;
            if (count($result) == 1) {

                $result = explode("-", $result[0]->id);
                if (count($result) >= 3) {
                    $id = (int) $result[2] + 1;
                } else {
                    $id = (int) $result[1] + 1;
                }
            }
            return ["id" => $id, "prefix" => $prefix];
        } catch (Exception $ex) {
            return $ex;
        }
    }
}

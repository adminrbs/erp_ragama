<?php

namespace Modules\Sd\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class FreeissuedReportController extends Controller
{
    public function freeissuedreport($search)
    {
        try {

            $searchOption = json_decode($search);


            // dd($searchOption );



            $selectecategory1 = $searchOption[11]->selectecategory1;
            $selectecategory2 = null; //$searchOption[1]->selectecategory2;
            $cmbProduct = $searchOption[10]->cmbProduct;
            $selecteBranch = $searchOption[4]->selecteBranch;


            // $user_id = auth()->id();
            // $userrole = "SELECT users_roles.role_id FROM users_roles WHERE users_roles.user_id=$user_id";
            // $alluserrol = DB::select($userrole);
            // if (!empty($alluserrol) && $alluserrol[0]->role_id !== 1) {
            //     if (count($selecteBranch) <= 0) {
            //         return;
            //     }
            // }
            $fromdate = $searchOption[5]->fromdate;
            $todate = $searchOption[6]->todate;




            $nonNullCount = 0;

            if ($searchOption !== null) {

                if ($searchOption[11]->selectecategory1 !== null) {
                    $nonNullCount++;
                }
                /*if ($searchOption[1]->selectecategory2 !== null) {
                    $nonNullCount++;
                }*/
                if ($searchOption[10]->cmbProduct !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[4]->selecteBranch !== null) {
                    $nonNullCount++;
                }


                if ($searchOption[5]->fromdate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[6]->todate !== null) {
                    $nonNullCount++;
                }
            }
            if ($nonNullCount > 1) {
                $query = "SELECT
                items1.item_Name,
                items1.package_unit,
                sales_with_sales_returns1.transaction_date,
                sales_with_sales_returns1.external_number,
                customers1.customer_name,
                (sales_with_sales_returns1.quantity)*-1 AS qty,
                (sales_with_sales_returns1.free_quantity) * -1 AS freqty,
                sales_with_sales_returns1.price,
                (sales_with_sales_returns1.quantity * sales_with_sales_returns1.price)*-1 AS amount,
                (sales_with_sales_returns1.free_quantity * sales_with_sales_returns1.price)*-1 AS freeamount,
                items1.item_id,
                items1.category_level_1_id,
                items1.category_level_2_id,
                sales_with_sales_returns1.branch_id
            FROM
            erp_kfd_rmg.sales_with_sales_returns sales_with_sales_returns1
                INNER JOIN erp_kfd_rmg.customers customers1 ON sales_with_sales_returns1.customer_id = customers1.customer_id
                INNER JOIN erp_kfd_rmg.items items1 ON sales_with_sales_returns1.item_id = items1.item_id
            
            
";

                // WHERE
                //                 (sales_with_sales_returns1.transaction_date >= {d '2024-03-01'} AND sales_with_sales_returns1.transaction_date <= {d '2024-03-31'})
                //                 AND sales_with_sales_returns1.branch_id = 6

                $quryModify = "";

                if ($fromdate != null && $todate != null) {
                    if ($nonNullCount > 2) {
                        $quryModify .= " sales_with_sales_returns1.transaction_date >= {d '" . $fromdate . "'} AND sales_with_sales_returns1.transaction_date <= {d '" . $todate . "'} AND";
                    } else {
                        $quryModify .= " sales_with_sales_returns1.transaction_date >= {d '" . $fromdate . "'} AND sales_with_sales_returns1.transaction_date <= {d '" . $todate . "'} AND";
                    }
                }
                if ($selectecategory1 != null) {

                    if (count($selectecategory1) > 1) {
                        $quryModify .= " items1.category_level_1_id IN ('" . implode("', '", $selectecategory1) . "') AND";
                    } else {
                        $quryModify .= " items1.category_level_1_id ='" . $selectecategory1[0] . "' AND";
                    }
                }
                if ($selectecategory2 != null) {

                    if (count($selectecategory2) > 1) {
                        $quryModify .= " items1.category_level_2_id IN ('" . implode("', '", $selectecategory2) . "') AND";
                    } else {
                        $quryModify .= " items1.category_level_2_id ='" . $selectecategory2[0] . "' AND";
                    }
                }
                if ($cmbProduct != null) {

                    if (count($cmbProduct) > 1) {
                        $quryModify .= " sales_with_sales_returns1.item_id IN ('" . implode("', '", $cmbProduct) . "') AND";
                    } else {
                        $quryModify .= " sales_with_sales_returns1.item_id ='" . $cmbProduct[0] . "' AND";
                    }
                }




                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " sales_with_sales_returns1.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " sales_with_sales_returns1.branch_id ='" . $selecteBranch[0] . "' AND";
                    }
                }

                if ($quryModify !== "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " where " . $quryModify;
                }

                $query .= 'AND (sales_with_sales_returns1.free_quantity <> 0)  ORDER BY
               items1.item_id;';

                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);
                /*$itemcategory = DB::select('SELECT I.item_id,I.Item_code,ICL2.Item_category_level_2_id,ICL2.category_level_2 FROM item_category_level_2s ICL2
                INNER JOIN items I ON I.category_level_2_id=ICL2.Item_category_level_2_id');*/

                $itemcategory = DB::select('SELECT I.item_id,I.Item_code,ICL1.Item_category_level_1_id,ICL1.category_level_1 FROM item_category_level_1s ICL1
                INNER JOIN items I ON I.category_level_1_id=ICL1.Item_category_level_1_id');

                $categoryitemlearray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                $EXISTING_CATEGORY_LEVEL1 = "";
                $BOOL_CHANGE_TITLE = false;
                foreach ($itemcategory as $itemid) {
                    $table = [];


                    foreach ($result as $queryesult) {
                        //dd($result);
                        // if ($queryesult->item_id == $itemid->item_id) {


                        //     array_push($table, $queryesult);
                        // }

                        if ($queryesult->category_level_1_id == $itemid->Item_category_level_1_id && $queryesult->item_id == $itemid->item_id) {
                            array_push($table, $queryesult);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($categoryitemlearray, $table);
                        if ($EXISTING_CATEGORY_LEVEL1 == "" && !$BOOL_CHANGE_TITLE) {
                            // $EXISTING_CATEGORY_LEVEL1 = $itemid->Item_code;
                            array_push($titel, '<strong>' . $EXISTING_CATEGORY_LEVEL1 . '</strong><br><strong>' . $itemid->category_level_1 . '</strong>');
                        } else {
                            if ($EXISTING_CATEGORY_LEVEL1 != $itemid->Item_code) {
                                //$EXISTING_CATEGORY_LEVEL1 = $itemid->Item_code;
                                array_push($titel, '<strong>' . $EXISTING_CATEGORY_LEVEL1 . '</strong><br><strong>' . $itemid->category_level_1 . '</strong>');
                            } else {
                                array_push($titel, '<strong>' . $itemid->category_level_1 . '</strong>');
                            }
                        }

                        // array_push($titel, $itemid->Item_code);


                        $reportViwer->addParameter('abc', $titel);
                    }
                }
                $reportViwer->addParameter("sales_summary_tabaledata", [$categoryitemlearray]);


                // $category_levels = DB::select('SELECT I.Item_code, ICL2.item_category_level_1_id,ICL2.item_category_level_2_id,ICL2.category_level_2 FROM items I 

                // INNER JOIN item_category_level_2s ICL2 ON  ICL2.Item_category_level_2_id=I.category_level_2_id');

                // $supplygrouparray = [];
                // $table = [];
                // $titel = [];
                // $reportViwer = new ReportViewer();
                // $EXISTING_CATEGORY_LEVEL1 = "";
                // $BOOL_CHANGE_TITLE = false;
                // foreach ($category_levels as $category_level) {

                //     $table = [];




                //     foreach ($result as $supplygroupdata) {


                //         if ($supplygroupdata->category_level_1_id == $category_level->item_category_level_1_id && $supplygroupdata->category_level_2_id == $category_level->item_category_level_2_id) {

                //             array_push($table, $supplygroupdata);
                //         }
                //     }



                //     if (count($table) > 0) {

                //         array_push($supplygrouparray, $table);

                //         if ($EXISTING_CATEGORY_LEVEL1 == "" && !$BOOL_CHANGE_TITLE) {
                //             $EXISTING_CATEGORY_LEVEL1 = $category_level->category_level_1;
                //             array_push($titel, '<strong>' . $EXISTING_CATEGORY_LEVEL1 . '</strong><br><strong>' . $category_level->category_level_2 . '</strong>');
                //         } else {
                //             if ($EXISTING_CATEGORY_LEVEL1 != $category_level->category_level_1) {
                //                 $EXISTING_CATEGORY_LEVEL1 = $category_level->category_level_1;
                //                 array_push($titel, '<strong>' . $EXISTING_CATEGORY_LEVEL1 . '</strong><br><strong>' . $category_level->category_level_2 . '</strong>');
                //             } else {
                //                 array_push($titel, '<strong>' . $category_level->category_level_2 . '</strong>');
                //             }
                //         }

                //         $reportViwer->addParameter('abc', $titel);
                //     }
                // }



                //  $reportViwer->addParameter("sales_summary_tabaledata", [$supplygrouparray]);
                // $result = DB::select($query);


                // $reportViwer = new ReportViewer();
                // $reportViwer->addParameter("sales_summary_tabaledata", $result);
            } else {

                $query = "SELECT DISTINCT SI.external_number AS invoice_number ,
                SI.order_date_time AS Date ,
                C.customer_code , 
                CONCAT(C.customer_name ,' ', T.townName ) AS customer_name  , 
                E.employee_name as sales_rep,
                SI.total_amount as amount 
                 
              
               FROM sales_invoices  SI 
              INNER JOIN sales_invoice_items SII  ON SI.sales_invoice_Id=SII.sales_invoice_Id 
              INNER JOIN items I ON SII.item_id=I.item_id 
              LEFT JOIN customers C ON C.customer_id=SI.customer_id 
              LEFT JOIN town_non_administratives T ON T.town_id=C.town 
              LEFT JOIN employees E ON E.employee_id=SI.employee_id
              INNER JOIN branches D ON D.branch_id = SI.branch_id
";


                $quryModify = "";
                if ($fromdate != null && $todate != null) {
                    $quryModify .= "SI.order_date_time between '" . $fromdate . "' AND '" . $todate . "'";
                }

                if ($selectecategory1 != null) {

                    if (count($selectecategory1) > 1) {
                        $quryModify .= " SI.customer_id IN ('" . implode("', '", $selectecategory1) . "')";
                    } else {
                        $quryModify .= " SI.customer_id ='" . $selectecategory1[0] . "'";
                    }
                }

                if ($selectecategory2 != null) {
                    if (count($selectecategory2) > 1) {
                        $quryModify .= " C.customer_group_id IN ('" . implode("', '", $selectecategory2) . "')";
                    } else {
                        $quryModify .= " C.customer_group_id ='" . $selectecategory2[0] . "'";
                    }
                }


                if ($cmbProduct != null) {
                    if (count($cmbProduct) > 1) {
                        $quryModify .= " C.customer_grade_id IN ('" . implode("', '", $cmbProduct) . "')";
                    } else {
                        $quryModify .= " C.customer_grade_id ='" . $cmbProduct[0] . "'";
                    }
                }


                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " C.route_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " C.route_id ='" . $selecteBranch[0] . "'";
                    }
                }
                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " SI.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    } else {
                        $quryModify .= " SI.branch_id ='" . $selecteBranch[0] . "'";
                    }
                }

                if ($quryModify !== "") {

                    $query = $query . " WHERE " . $quryModify;
                }
                if ($quryModify == "") {

                    $query = $query;
                }



                //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                // dd($query);
                $result = DB::select($query);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("sales_summary_tabaledata", $result);
            }

            $total = 0;
            foreach ($result as $row) {
                $total += $row->amount;
            }
            $formattedTotal = number_format($total, 2, '.', ',');
            $concatenatedTotal = 'Grand Total' . ' ' . ' ' . $formattedTotal;
            $reportViwer->addParameter('total', $concatenatedTotal);
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter("filter", "");

            $user_id = auth()->id();
            /*$query = "SELECT branches.branch_id, branches.branch_name,branches.address FROM branches LEFT JOIN
            user_distributor ON user_distributor.user_id = $user_id WHERE user_distributor.user_id = $user_id
            AND user_distributor.distributor_id = branches.branch_id";
            $reuslt = DB::select($query);
            $branchIds = array_column($reuslt, 'branch_name');
            $distributorAddress = array_column($reuslt, 'address');

            $userrole = "SELECT users_roles.role_id FROM users_roles WHERE users_roles.user_id=$user_id";
            $alluserrol = DB::select($userrole);*/


            if ($selecteBranch != null) {

                if (count($selecteBranch) > 1) {
                    $height = 0;
                    $reportViwer->addParameter('height', $height);
                    $reportViwer->addParameter('distributor', "");
                    $reportViwer->addParameter('distributoraddress', "");
                } else {
                    $query = "SELECT branches.branch_id, branches.branch_name,branches.address FROM branches
                    WHERE branches.branch_id IN ('" . implode("', '", $selecteBranch) . "')";
                    $result = DB::select($query);
                    $height = 30;
                    $reportViwer->addParameter('height', $height);
                    $reportViwer->addParameter('distributor', $result[0]->branch_name);
                    $reportViwer->addParameter('distributoraddress', $result[0]->address);
                }
            } else {
                $height = 0;
                $reportViwer->addParameter('height', $height);
                $reportViwer->addParameter('distributor', "");
                $reportViwer->addParameter('distributoraddress', "");
            }

            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
            $reportViwer->addParameter('dateRange', "From " . $fromdate . " To " . $todate);
            $length =  5;

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $length);


            return $reportViwer->viewReport('free_issue.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }



    //hiddn filter

    public function hidefilter(Request $request, $id)
    {
        try {

            $jsonData = json_decode($request->getContent(), true);

            $branch = $jsonData['branch'];
            $customer = $jsonData['customer'];
            $customergroup = $jsonData['customergroup'];
            $customerGrade = $jsonData['customerGrade'];
            $route = $jsonData['route'];

            $frodate = $jsonData['frodate'];
            $todate = $jsonData['todate'];
            $salesrep = $jsonData['salesrep'];

            if ($id == "salesreturnReport") {
                return response()->json([
                    //'branch' => $branch,
                    //'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //  'todate' => $todate,
                    //  'salesrep'=>$salesrep,


                ]);
            } elseif ($id == "salesReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    //'salesrep'=>$salesrep,


                ]);
            } elseif ($id == "salesdetailsReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    //'customergroup' => $customergroup,
                    // 'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    //'salesrep'=>$salesrep,


                ]);
            } elseif ($id == "itemCustomerReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    'salesrep' => $salesrep,


                ]);
            } elseif ($id == "freeSummaryReport") {

                return response()->json([
                    // 'branch' => $branch,
                    // 'customer' => $customer,
                    'customergroup' => $customergroup,
                    'customerGrade' => $customerGrade,
                    // 'route' => $route,
                    // 'frodate' => $frodate,
                    //'todate' => $todate,
                    'salesrep' => $salesrep,


                ]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getSalesrepfor_report()
    {
        try {

            $data = DB::select("SELECT employees.employee_name,employees.employee_id FROM employees WHERE employees.desgination_id = 7;");

            return response()->json($data);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}

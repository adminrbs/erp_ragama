<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\supply_group;

class BranchwiseStockReportController extends Controller
{
    protected $grand_total = 0;
    public function branchwiseStockReport($search)
    {
        try {
            $searchOption = json_decode($search);

            $selected1 = $searchOption[1]->selected1;
            $selected2 = $searchOption[2]->selected2;
            $selected3 = $searchOption[3]->selected3;
            $selected4 = $searchOption[4]->selected4;
            $selected5 = $searchOption[5]->selected5;
            //$selected6 = $searchOption[6]->selected6;


            $selectedproduct = $searchOption[6]->selectedproduct;
            $selectecategory1 = $searchOption[7]->selectecategory1;
            $selectecategory2 = $searchOption[8]->selectecategory2;
            $selectecategory3 = $searchOption[9]->selectecategory3;
            $selectSupplygroup = $searchOption[10]->selectSupplygroup;


            $fromdate = $searchOption[11]->fromdate;
            $todate = $searchOption[12]->todate;
            $selecteBranch = $searchOption[13]->selecteBranch;
            $selecteLocation = $searchOption[14]->selecteLocation;

            $query = 'SELECT DISTINCT branches.prefix AS branch_name,branches.branch_id,GROUP_CONCAT(DISTINCT items.supply_group_id SEPARATOR ",") AS supply_group_ids FROM branches LEFT JOIN 
            item_historys IHS ON branches.branch_id = IHS.branch_id LEFT JOIN items ON IHS.item_id = items.item_id 
            LEFT JOIN locations ON IHS.location_id = locations.location_id ';

            $qury_Modify = "";
            if ($fromdate && $todate) {
                $qury_Modify .= " IHS.transaction_date <= '" . $todate . "' AND";
            }

            if ($selecteBranch != null) {
                if (count($selecteBranch) > 1) {
                    $qury_Modify .= " IHS.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                } else {
                    $qury_Modify .= " IHS.branch_id ='" . $selecteBranch[0] . "' AND";
                }
            }

            if ($selecteLocation != null) {
                if (count($selecteLocation) > 1) {
                    $qury_Modify .= " IHS.location_id IN ('" . implode("', '", $selecteLocation) . "') AND";
                } else {
                    $qury_Modify .= " IHS.location_id ='" . $selecteLocation[0] . "'AND";
                }
            }

            if ($selectedproduct != null) {
                if (count($selectedproduct) > 1) {
                    $qury_Modify .= " IHS.item_id IN ('" . implode("', '", $selectedproduct) . "') AND";
                } else {
                    $qury_Modify .= " IHS.item_id ='" . $selectedproduct[0] . "' AND";
                }
            }

            if ($selectecategory1 != null) {
                if (count($selectecategory1) > 1) {
                    $qury_Modify .= " items.category_level_1_id IN ('" . implode("', '", $selectecategory1) . "') AND";
                } else {
                    $qury_Modify .= " items.category_level_1_id ='" . $selectecategory1[0] . "'AND";
                }
            }

            if ($selectecategory2 != null) {
                if (count($selectecategory2) > 1) {
                    $qury_Modify .= " items.category_level_2_id IN ('" . implode("', '", $selectecategory2) . "') AND";
                } else {
                    $qury_Modify .= " items.category_level_2_id ='" . $selectecategory2[0] . "'AND";
                }
            }

            if ($selectecategory3 != null) {
                if (count($selectecategory3) > 1) {
                    $qury_Modify .= " items.category_level_3_id IN ('" . implode("', '", $selectecategory3) . "') AND";
                } else {
                    $qury_Modify .= " items.category_level_3_id ='" . $selectecategory3[0] . "'AND";
                }
            }

            if ($selectSupplygroup != null) {
                if (count($selectSupplygroup) > 1) {
                    $qury_Modify .= " items.supply_group_id  IN ('" . implode("', '", $selectSupplygroup) . "') AND";
                } else {
                    $qury_Modify .= " items.supply_group_id  = " . $selectSupplygroup[0] . " AND ";
                }
            }
            if ($qury_Modify !== "") {

                $query = $query . " where " . $qury_Modify;
                //$query = $query . ' GROUP BY items.Item_code';
            }
            if ($qury_Modify != "") {
                $query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
            }
            $query .= ' GROUP BY
        branches.prefix,
        branches.branch_id';
            //dd($query);
            $result = DB::select($query);
            $sup_group_array = [];
            foreach ($result as $sup) {
                $ids_ = $sup->supply_group_ids;
                $temp_array = [];

                if (strpos($ids_, ',') !== false) {

                    $temp_array = explode(",", $ids_);
                } else {

                    $temp_array[] = $ids_;
                }


                $sup_group_array = array_merge($sup_group_array, $temp_array);


                $sup_group_array = array_unique($sup_group_array);
            }
            // dd($sup_group_array);
            //$table_array = [];

            //foreach ($sup_group_array as $table_id) {
                //$table = '<table id="tblRDSalesSummary'.$table_id.'" style="width:100%;border:1px solid black;border-collapse: collapse;">';
                $table = $this->createTable($sup_group_array, $result, $query, $searchOption);
                //$table .= '</table>';
                //dd($table);
                //array_push($table_array, $table);
                // dd($table_array);
            //}

           // $table_html = '';

            /*foreach ($table_array as $table) {
                $table_html .= '<div>' . $table . '</div>';
            }*/

           // $table .= '<div><h2>Grand Total: '.$this->grand_total.'</h2></div>';
           $table.='<table style="width:100%;"><tr><td  style="width:100%;"><h2  style="text-align:right;">Grand Total: '.$this->grand_total.'</h2></td></tr></table>';
            // dd($table_array);


            return '<h2 style="text-align:center;">' . CompanyDetailsController::CompanyName() . '</h2><h2 style="text-align:center;">Branch Wise Stock Report</h2><h4 style="text-align:center;">As at ' . $searchOption[12]->todate . '</h4>' . $table;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function createTable($sup_group_array, $result, $query, $searchOption)
    {

        //dd($query);
        // $result = DB::select($query);




        //dd($sup_group_array);

        $table_list = "";
        foreach ($sup_group_array as $sup_id) {
            $supply_grp = supply_group::find($sup_id);
            //dd($supply_grp->supply_group);

            $table_header = '<table id="tbl" style="width:100%;border:1px solid black;border-collapse: collapse;"><thead><tr><th style="border:1px solid black;text-align:center;width:120px;">Item Code</th style="border:1px solid black;text-align:center"><th style="border:1px solid black;text-align:center">Item Name</th><th style="border:1px solid black;text-align:center;width:100px;">Pack Size</th>';
            foreach ($result as $data) {
                $table_header .= '<th style="border:1px solid black;text-align:center;width:100px;">' . $data->branch_name . '</th>';
            }
            $table_header .= '<th style="width:100px;">Qty</th></tr></thead>';
            $table_header .= $this->createBody($sup_id, $result, $searchOption);
            $table_header . '</table><br>';
            $table_list.=$table_header.'<div><h3>'.$supply_grp->supply_group.'</h3></div>';
        }

        return  $table_list;
    }


    private function createBody($sup_id, $columns, $searchOption)
    {
        $selected1 = $searchOption[1]->selected1;
        $selected2 = $searchOption[2]->selected2;
        $selected3 = $searchOption[3]->selected3;
        $selected4 = $searchOption[4]->selected4;
        $selected5 = $searchOption[5]->selected5;
        //$selected6 = $searchOption[6]->selected6;


        $selectedproduct = $searchOption[6]->selectedproduct;
        $selectecategory1 = $searchOption[7]->selectecategory1;
        $selectecategory2 = $searchOption[8]->selectecategory2;
        $selectecategory3 = $searchOption[9]->selectecategory3;
        $selectSupplygroup = $searchOption[10]->selectSupplygroup;


        $fromdate = $searchOption[11]->fromdate;
        $todate = $searchOption[12]->todate;
        $selecteBranch = $searchOption[13]->selecteBranch;
        $selecteLocation = $searchOption[14]->selecteLocation;
        $qury_Modify = "";


        $qury_Modify = "";
        if ($fromdate && $todate) {
            $qury_Modify .= " item_historys.transaction_date <= '" . $todate . "' AND";
        }

        if ($selecteBranch != null) {
            if (count($selecteBranch) > 1) {
                $qury_Modify .= " item_historys.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
            } else {
                $qury_Modify .= " item_historys.branch_id ='" . $selecteBranch[0] . "' AND";
            }
        }

        if ($selecteLocation != null) {
            if (count($selecteLocation) > 1) {
                $qury_Modify .= " item_historys.location_id IN ('" . implode("', '", $selecteLocation) . "') AND";
            } else {
                $qury_Modify .= " item_historys.location_id ='" . $selecteLocation[0] . "'AND";
            }
        }

        if ($selectedproduct != null) {
            if (count($selectedproduct) > 1) {
                $qury_Modify .= " item_historys.item_id IN ('" . implode("', '", $selectedproduct) . "') AND";
            } else {
                $qury_Modify .= " item_historys.item_id =" . $selectedproduct[0] . " AND";
            }
        }

        if ($selectecategory1 != null) {
            if (count($selectecategory1) > 1) {
                $qury_Modify .= " items.category_level_1_id IN ('" . implode("', '", $selectecategory1) . "') AND";
            } else {
                $qury_Modify .= " items.category_level_1_id ='" . $selectecategory1[0] . "'AND";
            }
        }

        if ($selectecategory2 != null) {
            if (count($selectecategory2) > 1) {
                $qury_Modify .= " items.category_level_2_id IN ('" . implode("', '", $selectecategory2) . "') AND";
            } else {
                $qury_Modify .= " items.category_level_2_id ='" . $selectecategory2[0] . "'AND";
            }
        }

        if ($selectecategory3 != null) {
            if (count($selectecategory3) > 1) {
                $qury_Modify .= " items.category_level_3_id IN ('" . implode("', '", $selectecategory3) . "') AND";
            } else {
                $qury_Modify .= " items.category_level_3_id ='" . $selectecategory3[0] . "'AND";
            }
        }

        if ($selectSupplygroup != null) {
            if (count($selectSupplygroup) > 1) {
                $qury_Modify .= " items.supply_group_id  IN ('" . implode("', '", $selectSupplygroup) . "') AND";
            } else {
                $qury_Modify .= " items.supply_group_id  = " . $selectSupplygroup[0] . " AND";
            }
        }
        $qury_Modify_2 = '';
        /*  foreach ($columns as $col) {
            $qury_Modify_2 .= 'IF(branches.branch_id = ' . $col->branch_id . ',IFNULL(SUM(item_historys.quantity),0),0) AS ' . $col->branch_name . ', ';
            $qury_Modify_2 .= 'IF(branches.branch_id = ' . $col->branch_id . ',IFNULL(SUM(item_historys.quantity),0),0) AS qty_'.$col->branch_name . ',';
        } */

        foreach ($columns as $col) {
            $qury_Modify_2 .= "SUM(IF(branches.branch_id = " . $col->branch_id . ", IFNULL(item_historys.quantity, 0), 0)) AS " . $col->branch_name . ",
            SUM(IF(branches.branch_id = " . $col->branch_id . ", IFNULL(item_historys.quantity, 0), 0)) AS qty_" . $col->branch_name . ",";
        }


        $query = 'SELECT items.Item_code, items.item_Name,items.package_unit,items.supply_group_id, ';
        /*  if ($selecteBranch != null) {
           
            $qury_Modify_2 = trim($qury_Modify_2, ',');
            $query = trim($query, ',');
        }

        if ($selecteBranch == null) {
           
            $qury_Modify_2 = trim($qury_Modify_2, ',');
            $query = trim($query, ',');
        } */
        $qury_Modify_2 = trim($qury_Modify_2, ',');
        $query = trim($query, ',');
        $query .= $qury_Modify_2 . '
            FROM
            item_historys
            LEFT JOIN branches ON item_historys.branch_id = branches.branch_id
            LEFT JOIN items ON item_historys.item_id = items.item_id
            LEFT JOIN locations ON item_historys.location_id = locations.location_id
            
            WHERE ' . $qury_Modify;
        $query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);

       // $query .="'";
        $query .= ' GROUP BY item_historys.item_id';
         //dd($query);
        $result = DB::select($query);

        //dd($result);



        $quantity_array = [];
        for ($count = 0; $count < count($columns); $count++) {
            $key = $columns[$count]->branch_name;
            array_push($quantity_array, ['branch' => $key, 'quantity' => 0]);
            // $quantity_array[$key] = 0;
        }
        //dd($quantity_array);
        $table_body = '<tbody id="' . $sup_id . '">';

        foreach ($result as $data) {
            $row = (array) $data;
            if ($sup_id == $row['supply_group_id']) {
                $table_body .= '<tr><td style="border:1px solid black;text-align:left">';
                $table_body .= $row['Item_code'] . '</td>';
                $table_body .= '<td style="border:1px solid black;text-align:left">' . $row['item_Name'] . '</td>';
                $table_body .= '<td style="border:1px solid black;text-align:center">' . $row['package_unit'] . '</td>';
                $total_qty = 0;
                for ($count = 0; $count < count($columns); $count++) {
                    $key = $columns[$count]->branch_name;
                    $total_qty += floatval($row['qty_' . $key]);
                    $table_body .= '<td style="border:1px solid black;text-align:right">' . intval($row[$key]) . '</td>';
                    for ($i = 0; $i < count($quantity_array); $i++) {
                        if ($quantity_array[$i]['branch'] == $key) {
                            $quantity_array[$i]['quantity'] += intval($row[$key]);
                            break;
                        }
                    }
                    //$quantity_array[$key] += intval($row[$key]);
                }
                $table_body .= '<td style="border:1px solid black;text-align:right">' . $total_qty . '</td></tr>';
            }
        }
        //dd($quantity_array);
        $table_body .= '<tr><td style="text-align:left"><b>Sub Total</b></td><td></td><td></td>';
        $final_total = 0;
        foreach ($quantity_array as $j) {
            $final_total += floatval($j['quantity']);
            $table_body .= '<td style="border:1px solid black;text-align:right">' . $j['quantity'] . '</td>';
        }

        $table_body .= '<td style="border:1px solid black;text-align:right">' . $final_total . '</td></tr></tbody>';
        $this->grand_total += $final_total;
        return $table_body;
    }
}

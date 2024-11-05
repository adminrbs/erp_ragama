<?php

namespace Modules\Prc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use RepoEldo\ELD\ReportViewer;

class PoHelpNewReport extends Controller
{
   public function poHelpReport($search){
try{

    $searchOption = json_decode($search);
    // dd($searchOption );

    $fromdate = $searchOption[0]->fromdate;
    $todate = $searchOption[1]->todate;
    $selecteBranch = $searchOption[2]->selecteBranch;
    $selectSupplygroup = $searchOption[3]->selectSupplygroup;

    $mainLocations = DB::select("SELECT location_id FROM locations WHERE locations.location_type_id = 3 AND locations.branch_id IN ('" . implode("', '", $selecteBranch) . "')");

    // Extract location IDs into a simple array
    $locationIds = array_column($mainLocations, 'location_id');
    
    // Build the location query as a comma-separated list
    $locatin_query = "";
    if (!empty($locationIds)) {
        $locatin_query .= "location_id IN ('" . implode("', '", $locationIds) . "')";
    }
    



    $nonNullCount = 0;

    if ($searchOption !== null) {

        if ($searchOption[0]->fromdate !== null) {
            $nonNullCount++;
        }
        if ($searchOption[1]->todate !== null) {
            $nonNullCount++;
        }
       
        if ($searchOption[2]->selecteBranch !== null) {
            $nonNullCount++;
        }
      
        if ($searchOption[3]->selectSupplygroup !== null) {
            $nonNullCount++;
        }
        
    }

    if ($nonNullCount > 1) {
        $quryModify = "";
        $quryModify2 = " AND ";
        $quryModify3 =" Where I.is_active=1";
        if ( $todate != null) {
            $quryModify .= " transaction_date<= '" . $todate . "'  AND ";
          //  $quryModify2 .= " '" . $todate . "' AND";

        }

        if ($selecteBranch != null) {
            if (count($selecteBranch) > 1) {
                $quryModify .= " branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                $quryModify2 .= "  branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";

            } else {
                $quryModify .= " branch_id ='" . $selecteBranch[0] . "' AND";
                $quryModify2 .= " branch_id ='" . $selecteBranch[0] . "' AND";

            }

        }  

      /*   if ($selectedproduct != null) {
            if (count($selectedproduct) > 1) {
                $quryModify .= " item_id IN ('" . implode("', '", $selectedproduct) . "') AND";
                $quryModify2 .= " item_id IN ('" . implode("', '", $selectedproduct) . "') AND";
            } else {
                $quryModify .= " item_id ='" . $selectedproduct[0] . "' AND";
                $quryModify2 .= " item_id ='" . $selectedproduct[0] . "' AND";
            }

        }      */

         

        if ($selectSupplygroup != null) {
            if (count($selectSupplygroup) > 1) {
                $quryModify3 .= "  WHERE I.supply_group_id  IN ('" . implode("', '", $selectSupplygroup) . "')  ";
              
            } else {
                $quryModify3 .= "  WHERE I.supply_group_id  ='" . $selectSupplygroup[0] . "' ";
               
            }

        }               
         if ($quryModify != "" || $quryModify2 != "") {
            $quryModify = rtrim($quryModify, 'AND OR ');
            $quryModify2 = rtrim($quryModify2, 'AND OR ');
        }




        $query = "  SELECT  I.Item_code, I.item_Name ,I.package_unit, S.avg_sales , ROUND((S.avg_sales * 80) / 100, 0) AS required, L.qty_in_hand ,  
        CASE 
        WHEN (ROUND((S.avg_sales * 80) / 100, 0) - L.qty_in_hand) <= 0 THEN NULL 
        ELSE ROUND((ROUND((S.avg_sales * 80) / 100, 0) - L.qty_in_hand), 0) 
    END AS reoder,  I.supply_group_id , SG.supply_group,L.branch_id , B.branch_name , I.item_id
                    FROM 
                    ( SELECT branch_id , item_id , SUM(quantity) AS  qty_in_hand  From item_historys IH WHERE  $quryModify AND $locatin_query
                    GROUP BY branch_id , item_id   
                    )  L   
                    INNER JOIN items I ON I.item_id = L.item_id 
                    INNER JOIN  supply_groups  SG ON  SG.supply_group_id =I.supply_group_id 
                    INNER JOIN  branches B ON L.branch_id=B.branch_id 
                   
                    LEFT JOIN 
                    (SELECT branch_id  , item_id  ,  ROUND(SUM(quantity*-1)/3 ) AS avg_sales FROM  sales_with_sales_returns    
                    WHERE transaction_date  BETWEEN  DATE_ADD('" . $todate . "',INTERVAL -90 DAY )  AND  '" . $todate . "'    $quryModify2
                    GROUP BY branch_id , item_id  ) S   ON  S.branch_id=L.branch_id AND S.item_id=L.item_id
                    $quryModify3 ";


       
        


        //$query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
        //dd($query);
        $result = DB::select($query);
        //dd($result);
        // $reportViwer = new ReportViewer();
        // $reportViwer->addParameter("StockBalance_tabaledata", $result);
        $resulsupplygroup = DB::select('select supply_groups.supply_group_id ,supply_groups.supply_group from supply_groups');

                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {

                    $table = [];


                    foreach ($result as $supplygroupdata) {


                        if ($supplygroupdata->supply_group_id == $supplygroupid->supply_group_id) {

                            array_push($table, $supplygroupdata);
                        }
                    }



                    if (count($table) > 0) {

                        array_push($supplygrouparray, $table);


                        array_push($titel, $supplygroupid->supply_group);

                        $reportViwer->addParameter('abc', $titel);
                    }
                }

               

                $reportViwer->addParameter("tabale_data", [$supplygrouparray]);

    } 

    if ($searchOption !== null) {


        /* $selectedproduct = $searchOption[3]->selectedproduct; */
       /*  $fromdate = $searchOption[0]->fromdate;
        $todate = $searchOption[1]->todate;
        $selecteBranch = $searchOption[2]->selecteBranch;
      
        $selectSupplygroup = $searchOption[3]->selectSupplygroup; */

        $selectSupplygroup = $searchOption[3]->selectSupplygroup;

        $fromdate = $searchOption[0]->fromdate;
        $todate = $searchOption[1]->todate;
        $selecteBranch = $searchOption[2]->selecteBranch;
       

        // Set parameters for selectedproduct, selectecategory1, selectecategory2, and selectecategory3


        // Set the "filter" parameter using $fromdate and $todate

       

       



       

            if (
                $selecteBranch  == null  && $fromdate == null && $todate == null && $selectSupplygroup == null 
            ) {
                $filterLabel = "";
            } elseif ($selecteBranch !== null) {
               
           
            } elseif ($fromdate !== null && $todate !== null) {
               
            }  elseif ($selectSupplygroup !== null) {
               
            }



            //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $category1";

           
    }
    $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());

    $reuslt = DB::select($query);
   
   
  

        $height=30;
        $reportViwer->addParameter('height',$height);
        $reportViwer->addParameter('distributor', "");
        $reportViwer->addParameter('distributoraddress', "");
    
    $reportname = " Reorder Status AS At  "." " . $todate ." " ;
    $reportViwer->addParameter('nameandtime', $reportname);
    $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
    $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
    $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());
    
    $reportViwer->addParameter('hight', 30);

    return $reportViwer->viewReport('salesorderstatus.json');

}catch(Exception $ex){
return $ex;
}

}
}

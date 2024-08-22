<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Expectation;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\category_level_1;
use Modules\Sc\Entities\category_level_2;
use Modules\Sc\Entities\category_level_3;
use Modules\Sc\Entities\item;
use Modules\Sc\Entities\location;
use Modules\Sc\Entities\supply_group;
use RepoEldo\ELD\ReportViewer;

class ItemMovemetHistoryController extends Controller
{
    public function printItemMovementHistoryReport($search)
    {
        // return $search;
        //dd($search);
        try {

            $searchOption = json_decode($search);
          //  dd($searchOption );
            $select = $searchOption[0]->selected;
            //dd($select == null);
            $selected1 = $searchOption[1]->selected1;
            $selected2 = $searchOption[2]->selected2;
            $selected3 = $searchOption[3]->selected3;
            $selected4 = $searchOption[4]->selected4;
            $selected5 = $searchOption[5]->selected5;

            $selectedproduct = $searchOption[6]->selectedproduct;
            $selectecategory1 = $searchOption[7]->selectecategory1;
            $selectecategory2 = $searchOption[8]->selectecategory2;
            $selectecategory3 = $searchOption[9]->selectecategory3;
            $selectSupplygroup = $searchOption[10]->selectSupplygroup;


            $fromdate = $searchOption[11]->fromdate;
            $todate = $searchOption[12]->todate;
            $selecteBranch = $searchOption[13]->selecteBranch;
            //dd($selecteBranch[0]);
            $selecteLocation = $searchOption[14]->selecteLocation;

            $nonNullCount = 0;


            if ($searchOption !== null) {

                if ($searchOption[0]->selected !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[1]->selected1 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[2]->selected2 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[3]->selected3 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[4]->selected4 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[5]->selected5 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[6]->selectedproduct !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[7]->selectecategory1 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[8]->selectecategory2 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[9]->selectecategory3 !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[10]->selectSupplygroup !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[11]->fromdate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[12]->todate !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[13]->selecteBranch !== null) {
                    $nonNullCount++;
                }
                if ($searchOption[14]->selecteLocation !== null) {
                    $nonNullCount++;
                }
            }

            if ($nonNullCount > 1) {

                DB::select("SET @running_total := 0;");

                 /* $query = "SELECT DISTINCT
                D.transaction_date,
                D.referance_number,
                D.item_name,
                'Opening Balance' AS description,
								D.package_unit,
                TRUNCATE(IF(D.quantity > 0, D.quantity, 0),0) AS in_quantity,
                TRUNCATE(ABS(IF(D.quantity < 0, D.quantity, 0)),0) AS out_quantity,
                (@running_total := @running_total + IF(D.quantity > 0, D.quantity, 0) - ABS(IF(D.quantity < 0, D.quantity, 0))) AS running_total,
                IFNULL(D.whole_sale_price,0.00) AS whole_sale_price,
                IFNULL(D.retial_price,0.00) AS retial_price,
                D.user_name AS user_name,
                IFNULL(D.cost_price,0.00) AS cost_price
                
            FROM (
                SELECT IFNULL(SUM(IH.quantity), 0) AS quantity,
                IH.transaction_date,
                IH.description,
                I.package_unit,
IFNULL(SI.your_reference_number,'') AS referance_number,
                (SELECT items.item_Name FROM items WHERE items.item_id = IH.item_id) AS item_name,
                IFNULL(IH.whole_sale_price,0.00) AS whole_sale_price,
                IFNULL(IH.retial_price,0.00) AS retial_price,
                users.name AS user_name,
                IFNULL(IH.cost_price,0.00) AS cost_price
                FROM item_historys IH
INNER JOIN sales_invoice_items SII ON IH.item_id = SII.item_id
INNER JOIN sales_invoices SI ON SII.sales_invoice_id = SI.sales_invoice_id
                INNER JOIN items I ON I.item_id = IH.item_id
                INNER JOIN users ON I.created_by = users.id
                WHERE IH.transaction_date <= '" . $fromdate . "' AND IH.item_id = '" . $selectedproduct[0] . "'
            ) D
            UNION ALL
            SELECT DISTINCT
            IH.transaction_date,
            IFNULL(SI.your_reference_number,'') AS referance_number,
            items.item_Name AS item_name,
            LEFT(IH.description,20) AS description,
			items.package_unit,
            TRUNCATE(IF(IH.quantity > 0, IH.quantity, 0),0) AS in_quantity,
            TRUNCATE(ABS(IF(IH.quantity < 0, IH.quantity, 0)),0) AS out_quantity,
            (@running_total := @running_total + IF(IH.quantity > 0, IH.quantity, 0) - ABS(IF(IH.quantity < 0, IH.quantity, 0))) AS running_total,
            IFNULL(IH.whole_sale_price,0.00) AS whole_sale_price,
            IFNULL(IH.retial_price,0.00) AS retial_price,
            users.name AS user_name,
            IFNULL(IH.cost_price,0.00) AS cost_price
            FROM item_historys IH
            INNER JOIN sales_invoice_items SII ON IH.item_id = SII.item_id
            INNER JOIN sales_invoices SI ON SII.sales_invoice_id = SI.sales_invoice_id
						INNER JOIN items ON IH.item_id = items.item_id
						INNER JOIN users ON items.created_by = users.id "; */

                        /*************************************** */
                       // dd($fromdate);
                        $query = " SELECT
                        '" . $fromdate . "' AS transaction_date,
                        D.reference_number,
                        
                        '' AS item_name,
                        'Opening Balance' AS description,
                       
                        D.cost_price,
                       
                        
                        TRUNCATE(IF(D.quantity > 0, D.quantity, 0), 0) AS in_quantity,
                        TRUNCATE(ABS(IF(D.quantity < 0, D.quantity, 0)), 0) AS out_quantity,
                        TRUNCATE(@running_total := @running_total + IF(D.quantity > 0, D.quantity, 0) - ABS(IF(D.quantity < 0, D.quantity, 0)), 0) AS running_total,
                        D.whole_sale_price,
                        D.retial_price,
                       
                        '' AS user_name
                    FROM (
                        SELECT IFNULL(SUM(quantity), 0) AS quantity,
                        IFNULL(IH.manual_number, IH.external_number) AS reference_number,
                        IH.whole_sale_price,
                        IH.retial_price,
                        IH.cost_price
                        FROM item_historys IH
                        LEFT JOIN items I ON I.item_id = IH.item_id
                        WHERE IH.transaction_date < '" . $fromdate . "' AND IH.item_id = '" . $selectedproduct[0] . "'
                    ) D
                    UNION ALL
                    SELECT
                        IH.transaction_date,
                        IFNULL(IH.manual_number, IH.external_number) AS reference_number,
                        
                        '' AS item_name,
                        IH.description AS description,
                       
                        IH.cost_price,
                        
                        TRUNCATE(IF(IH.quantity > 0, IH.quantity, 0), 0) AS in_quantity,
                        TRUNCATE(ABS(IF(IH.quantity < 0, IH.quantity, 0)), 0) AS out_quantity,
                        TRUNCATE(@running_total := @running_total + IF(IH.quantity > 0, IH.quantity, 0) - ABS(IF(IH.quantity < 0, IH.quantity, 0)), 0) AS running_total,

                        IH.whole_sale_price,
                        IH.retial_price,
                        
                        '' AS user_name
                    FROM item_historys IH
                    WHERE  IH.item_id = '" . $selectedproduct[0] . "' AND IH.transaction_date BETWEEN '" . $fromdate . "' AND '" . $todate . "'";
                            
                $quryModify = "";


                if ($fromdate != null && $todate != null) {
                    $quryModify .= "IH.transaction_date between '" . $fromdate . "' AND '" . $todate . "' AND";
                }

                if ($selectedproduct != null) {
                    if (count($selectedproduct) > 1) {
                        $quryModify .= " IH.item_id IN ('" . implode("', '", $selectedproduct) . "') AND";
                    } else {
                        $quryModify .= " IH.item_id ='" . $selectedproduct[0] . "'AND";
                    }
                }

                if ($selectecategory1 != null) {
                    if (count($selectecategory1) > 1) {
                        $quryModify .= " items.category_level_1_id IN ('" . implode("', '", $selectecategory1) . "') AND";
                    } else {
                        $quryModify .= " items.category_level_1_id ='" . $selectecategory1[0] . "'AND";
                    }
                }

                if ($selectecategory2 != null) {
                    if (count($selectecategory2) > 1) {
                        $quryModify .= " items.category_level_2_id IN ('" . implode("', '", $selectecategory2) . "') AND";
                    } else {
                        $quryModify .= " items.category_level_2_id ='" . $selectecategory2[0] . "'AND";
                    }
                }

                if ($selectecategory3 != null) {
                    if (count($selectecategory3) > 1) {
                        $quryModify .= " items.category_level_3_id IN ('" . implode("', '", $selectecategory3) . " ') AND";
                    } else {
                        $quryModify .= " items.category_level_3_id ='" . $selectecategory3[0] . "'AND";
                    }
                }

                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " IH.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " IH.branch_id ='" . $selecteBranch[0] . "'AND";
                    }
                }
                if ($selecteLocation != null) {
                    if (count($selecteLocation) > 1) {
                        $quryModify .= " IH.location_id IN ('" . implode("', '", $selecteLocation) . "') AND";
                    } else {
                        $quryModify .= " IH.location_id ='" . $selecteLocation[0] . "'AND ";
                    }
                }

                if ($quryModify != "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " AND " . $quryModify;
                }



                // $query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
              // dd($query);
                $result = DB::select($query);
              //  dd($result);

                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("itemmovementhistory_tabaledata", $result);
            } else {


                DB::select("SET @running_total := 0;");

                $query = " SELECT DISTINCT
                            IH.transaction_date, 
                            IFNULL(IH.manual_number,'') AS external_number, 
                            IH.description, 
                            TRUNCATE(IF(IH.quantity > 0, IH.quantity, 0),0) AS in_quantity, 
                            TRUNCATE(ABS(IF(IH.quantity < 0, IH.quantity, 0)),0) AS out_quantity,
                            '0.00' AS opening_balance,
                            (@running_total := @running_total + IF(IH.quantity > 0, IH.quantity, 0) - ABS(IF(IH.quantity < 0, IH.quantity, 0))) AS running_total
                        FROM item_historys IH
                        LEFT JOIN branches ON IH.branch_id  = branches.branch_id 
                        LEFT JOIN items ON IH.item_id = items.item_id
                        LEFT JOIN locations ON IH.location_id = locations.location_id
                        ";
                $quryModify = "";


                if ($fromdate != null && $todate != null) {
                    $quryModify .= "IH.transaction_date between '" . $fromdate . "' AND '" . $todate . "'";
                }


                if ($selectedproduct != null) {
                    if (count($selectedproduct) > 1) {
                        $quryModify .= " IH.item_id IN ('" . implode("', '", $selectedproduct) . "') ";
                    } else {
                        $quryModify .= " IH.item_id ='" . $selectedproduct[0] . "'";
                    }
                }

                if ($selectecategory1 != null) {
                    if (count($selectecategory1) > 1) {
                        $quryModify .= " items.category_level_1_id IN ('" . implode("', '", $selectecategory1) . "') ";
                    } else {
                        $quryModify .= " items.category_level_1_id ='" . $selectecategory1[0] . "'";
                    }
                }

                if ($selectecategory2 != null) {
                    if (count($selectecategory2) > 1) {
                        $quryModify .= " items.category_level_2_id IN ('" . implode("', '", $selectecategory2) . "') ";
                    } else {
                        $quryModify .= " items.category_level_2_id ='" . $selectecategory2[0] . "'";
                    }
                }

                if ($selectecategory3 != null) {
                    if (count($selectecategory3) > 1) {
                        $quryModify .= " items.category_level_3_id IN ('" . implode("', '", $selectecategory3) . "') ";
                    } else {
                        $quryModify .= " items.category_level_3_id ='" . $selectecategory3[0] . "'";
                    }
                }

                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " IH.branch_id IN ('" . implode("', '", $selecteBranch) . "') ";
                    } else {
                        $quryModify .= " IH.branch_id ='" . $selecteBranch[0] . "'";
                    }
                }
                if ($selecteLocation != null) {
                    if (count($selecteLocation) > 1) {
                        $quryModify .= " IH.location_id IN ('" . implode("', '", $selecteLocation) . "') ";
                    } else {
                        $quryModify .= " IH.location_id ='" . $selecteLocation[0] . "'";
                    }
                }


                if ($quryModify != "") {
                    $query = $query . " where " . $quryModify;
                }



                // $query = preg_replace('/\W\w+\s*(\W*)$/', '$1', $query);
                //dd($query);
                $result = DB::select($query);


                $reportViwer = new ReportViewer();
                $reportViwer->addParameter("itemmovementhistory_tabaledata", $result);
            }
            if ($searchOption !== null) {


                $selectedproduct = $searchOption[6]->selectedproduct;
                $selectecategory1 = $searchOption[7]->selectecategory1;
                $selectecategory2 = $searchOption[8]->selectecategory2;
                $selectecategory3 = $searchOption[9]->selectecategory3;
                $selectSupplygroup = $searchOption[10]->selectSupplygroup;

                $fromdate = $searchOption[11]->fromdate;
                $todate = $searchOption[12]->todate;
                $selecteBranch = $searchOption[13]->selecteBranch;
                $selecteLocation = $searchOption[14]->selecteLocation;

                // Set parameters for selectedproduct, selectecategory1, selectecategory2, and selectecategory3


                // Set the "filter" parameter using $fromdate and $todate

                $branch = $this->getBranch($selecteBranch);

                $branchname = '';
                if ($branch) {
                    // Process the data
                    $branchname = $branch->pluck('branch_name')->implode(', ');
                }

                $location = $this->getLocation($selecteLocation);

                $locationname = '';
                if ($location) {
                    // Process the data
                    $locationname = $location->pluck('location_name')->implode(', ');
                }

                $item = $this->getitem($selectedproduct);

                $itemname = '';
                if ($item) {
                    // Process the data
                    $itemname = $item->pluck('item_Name')->implode(', ');
                }
                $categoryL1 = $this->getcategory1($selectecategory1);

                $category1 = '';
                if ($categoryL1) {
                    // Process the data
                    $category1 = $categoryL1->pluck('category_level_1')->implode(', ');
                }
                $categoryL2 = $this->getcategory2($selectecategory2);

                $category2 = '';
                if ($categoryL2) {
                    // Process the data
                    $category2 = $categoryL2->pluck('category_level_2')->implode(', ');
                }

                $categoryL3 = $this->getcategory3($selectecategory3);

                $category3 = '';
                if ($categoryL3) {
                    // Process the data
                    $category3 = $categoryL3->pluck('category_level_3')->implode(', ');
                }
                $selectSupplygroup1 = $this->getsupplygroup($selectSupplygroup);

                $selectSupplygroup = '';
                if ($selectSupplygroup1) {
                    // Process the data
                    $selectSupplygroup = $selectSupplygroup1->pluck('supply_group')->implode(', ');
                }

                if ($nonNullCount > 1) {

                    $filterLabel = '';
                    if ($nonNullCount > 1) {
                        //$filterLabel .= "For";
                    }
                    if ($selecteBranch !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Branch: $branchname and";
                    }
                    if ($selecteLocation !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " Location: $locationname and";
                    }


                    if ($fromdate !== null) {
                        $filterLabel .= " $fromdate ";
                    }

                    if ($todate !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " To: $todate";
                    }

                    if ($selectedproduct !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " product: $itemname ";
                    }

                    if ($selectecategory1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " category level 1: $category1 and";
                    }

                    if ($selectecategory2 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " category level 2: $category2  and";
                    }

                    if ($selectecategory3 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " category level 3: $category3 and";
                    }

                    if ($selectSupplygroup1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        //$filterLabel .= " category level 3: $selectSupplygroup and";
                    }



                    // Check if the filter label is not empty and then print it
                    if (!empty($filterLabel)) {
                        $filterLabel = rtrim($filterLabel, 'and ');
                    }
                    $date_range = "";

                    if ($todate != null) {
                        $date_range = $todate;
                    }

                    $reportViwer->addParameter("sub_title", 'Bin Card as at ' . $date_range);
                    $reportViwer->addParameter("item_name", "Item Name : " . $itemname);
                    //
                } else {

                    if (
                        $selecteBranch == null && $selecteLocation == null && $fromdate == null && $todate == null && $selectedproduct == null && $selectecategory1 == null
                        && $selectecategory2 == null && $selectecategory3 == null && $selectSupplygroup == null
                    ) {
                        $filterLabel = "";
                    } elseif ($selecteBranch !== null) {
                        $filterLabel = "For: $branchname branch";
                    } elseif ($selecteLocation !== null) {
                        $filterLabel = "For: $locationname";
                    } elseif ($fromdate !== null && $todate !== null) {
                        $filterLabel = "For From: $fromdate  To: $todate";
                    } elseif ($selectedproduct !== null) {
                        $filterLabel = "For: product: $itemname";
                    } elseif ($selectecategory1 !== null) {
                        $filterLabel = "For: category level 1: $category1";
                    } elseif ($selectecategory2 !== null) {
                        $filterLabel = "For: category level 2: $category2";
                    } elseif ($selectecategory3 !== null) {
                        $filterLabel = "For: category level 3: $category3";
                    } elseif ($selectSupplygroup !== null) {
                        $filterLabel = "For: Supply Group : $selectSupplygroup";
                    }




                    //$filterLabel = "From: $fromdate  To: $todate  and product: $itemname  and categorylevel1: $category1";

                    $reportViwer->addParameter("filter", $filterLabel);
                }
            }
            $reportViwer->addParameter('companylogo', CompanyDetailsController::companyimage());
            $reportViwer->addParameter('companyName', CompanyDetailsController::CompanyName());
            $reportViwer->addParameter('companyAddress', CompanyDetailsController::CompanyAddress());
            $reportViwer->addParameter('companyNumber', CompanyDetailsController::CompanyNumber());

            $length =  (strlen($filterLabel) / 90);
            $i = floor($length);
            $i2 = 0;
            if (($length - $i) > 0) {
                $i2++;
            }
            $label_height = (($i + $i2) * 20);

            //dd($length . " " . (strlen($filterLabel)));
            $reportViwer->addParameter('hight', $label_height);
            return $reportViwer->viewReport('item_movemet_history.json');
        } catch (Exception $ex) {
            return $ex;
        }
    }
    public function getBranch($selecteBranch)
    {
        if ($selecteBranch != null) {
            $branch = branch::whereIn('branch_id', $selecteBranch)
                ->select('branch_id', 'branch_name')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $branch;
        }
    }

    public function getLocation($selecteLocation)
    {
        if ($selecteLocation != null) {
            $location = location::whereIn('location_id', $selecteLocation)
                ->select('location_id', 'location_name')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $location;
        }
    }
    public function getitem($selectedproduct)
    {
        if ($selectedproduct != null) {
            $item = item::whereIn('item_id', $selectedproduct)
                ->select('item_id', 'item_Name')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $item;
        }
    }

    public function getcategory1($selectecategory1)
    {
        if ($selectecategory1 != null) {
            $categoryL1 = category_level_1::whereIn('item_category_level_1_id', $selectecategory1)
                ->select('item_category_level_1_id', 'category_level_1')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $categoryL1;
        }
    }

    public function getcategory2($selectecategory2)
    {
        if ($selectecategory2 != null) {
            $categoryL2 = category_level_2::whereIn('item_category_level_2_id', $selectecategory2)
                ->select('item_category_level_2_id', 'category_level_2')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $categoryL2;
        }
    }
    public function getcategory3($selectecategory3)
    {
        if ($selectecategory3 != null) {
            $categoryL3 = category_level_3::whereIn('item_category_level_3_id', $selectecategory3)
                ->select('item_category_level_2_id', 'category_level_3')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $categoryL3;
        }
    }

    public function getsupplygroup($selectSupplygroup)
    {
        if ($selectSupplygroup != null) {
            $supplygroup = supply_group::whereIn('supply_group_id', $selectSupplygroup)
                ->select('supply_group_id', 'supply_group')
                ->get();
            //dd($branch->pluck('branch_name', 'branch_id'));
            return $supplygroup;
        }
    }


    public function stockcontrol(Request $request, $id)
    {
        try {
            $jsonData = json_decode($request->getContent(), true);
            $branch = $jsonData['branch'];
            $product = $jsonData['product'];
            $caregory1 = $jsonData['caregory1'];
            $caregory2 = $jsonData['caregory2'];
            $caregory3 = $jsonData['caregory3'];
            $supplygroup = $jsonData['supplygroup'];
            $frodate = $jsonData['frodate'];
            $todate = $jsonData['todate'];
            $location = $jsonData['location'];

            if ($id == "stock-balance") {
                return response()->json([
                    // 'branch' => $branch,
                    // 'product' => $product,
                    // 'caregory1' => $caregory1,
                    //'caregory2' => $caregory2,
                    //'caregory3' => $caregory3,
                    // 'supplygroup' => $supplygroup,
                    'frodate' => $frodate,
                    /// 'todate' => $todate
                ]);
            } elseif ($id == "item-movement") {

                return response()->json([
                    //'branch' => $branch,
                    // 'product' => $product,
                    'caregory1' => $caregory1,
                    'caregory2' => $caregory2,
                    'caregory3' => $caregory3,
                    //'supplygroup' => $supplygroup,
                    //'frodate' => $frodate,
                    //'todate' => $todate
                ]);
            } elseif ($id == "valuations") {

                return response()->json([
                    //'branch' => $branch,
                    // 'product' => $product,
                    //'caregory1' => $caregory1,
                    // 'caregory2' => $caregory2,
                    // 'caregory3' => $caregory3,
                    // 'supplygroup' => $supplygroup,
                    'frodate' => $frodate,
                    //'todate' => $todate
                ]);
            } elseif ($id == "rdStock") {

                return response()->json([
                    //'branch' => $branch,
                    'product' => $product,
                    'caregory1' => $caregory1,
                    'caregory2' => $caregory2,
                    'caregory3' => $caregory3,
                    // 'supplygroup' => $supplygroup,
                    //'frodate' => $frodate,
                    //'todate' => $todate
                    //'location' => $location,
                ]);
            } else if ($id == "rdStockWithFree") {
                return response()->json([
                    //'branch' => $branch,
                    'product' => $product,
                    'caregory1' => $caregory1,
                    'caregory2' => $caregory2,
                    'caregory3' => $caregory3,
                    // 'supplygroup' => $supplygroup,
                    //'frodate' => $frodate,
                    //'todate' => $todate
                    //'location' => $location,
                ]);
            }else if($id == "branchwiseStockReport"){
                return response()->json([
                   // 'branch' => $branch,
                   // 'product' => $product,
                   // 'caregory1' => $caregory1,
                   // 'caregory2' => $caregory2,
                   // 'caregory3' => $caregory3,
                   //  'supplygroup' => $supplygroup,
                    'frodate' => $frodate,
                   // 'todate' => $todate,
                   // 'location' => $location,
                ]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}

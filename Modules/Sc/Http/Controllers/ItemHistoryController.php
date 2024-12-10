<?php

namespace Modules\Sc\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use App\Http\Controllers\CompanyNameController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sc\Entities\branch;
use Modules\Sc\Entities\category_level_1;
use Modules\Sc\Entities\category_level_2;
use Modules\Sc\Entities\category_level_3;
use Modules\Sc\Entities\item;
use Modules\Sc\Entities\item_history;
use Modules\Sc\Entities\location;
use Modules\Sc\Entities\supply_group;
use RepoEldo\ELD\ReportViewer;

class ItemHistoryController extends Controller
{
    //load data to item history tabe
    public function getItemHistory()
    {
        try {



            $itemHistorydetails = item_history::all();
            if ($itemHistorydetails) {
                return response()->json((['success' => 'Data loaded', 'data' => $itemHistorydetails]));
            } else {
                return response()->json((['error' => 'Data not loaded', 'data' => []]));
            }
        } catch (Exception $ex) {
            return $ex;
        }
     }

     //get stock balance
     public function stockBalanceReport($search)
     {

        try {

            $searchOption = json_decode($search);
            // dd($searchOption );
            $select = $searchOption[0]->selected;
            //dd($select == null);
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

            // dd($searchOption);


            $nonNullCount = 0;

            if ($searchOption !== null) {

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
               /*  $query = ' SELECT items.Item_code, items.item_Name, SUM(item_history_set_offs.quantity) AS quantity, items.unit_of_measure,items.category_level_1_id
            FROM item_history_set_offs
            LEFT JOIN branches ON item_history_set_offs.branch_id  = branches.branch_id 
            LEFT JOIN items ON item_history_set_offs.item_id = items.item_id
            LEFT JOIN locations ON item_history_set_offs.location_id = locations.location_id'; */
            $query = ' SELECT
	items.Item_code,
	items.item_Name,
	SUM(
		item_history_set_offs.quantity - item_history_set_offs.setoff_quantity 
	) AS quantity,
	items.unit_of_measure,
	items.category_level_1_id
FROM
	items
            LEFT JOIN item_history_set_offs ON items.item_id = item_history_set_offs.item_id
            LEFT JOIN branches ON item_history_set_offs.branch_id  = branches.branch_id 
            LEFT JOIN locations ON item_history_set_offs.location_id = locations.location_id';


                $quryModify = "";
                if ( $todate != null) {
                    $quryModify .= " item_history_set_offs.transaction_date <= '" . $todate . "' AND";
                    //$quryModify .= "D.trans_date BETWEEN '" . min($fromdate) . "' AND '" . max($todate) . "'";

                }

                if ($selecteBranch != null) {
                    if (count($selecteBranch) > 1) {
                        $quryModify .= " item_history_set_offs.branch_id IN ('" . implode("', '", $selecteBranch) . "') AND";
                    } else {
                        $quryModify .= " item_history_set_offs.branch_id ='" . $selecteBranch[0] . "'AND";
                    }

                }  

                if ($selecteLocation != null) {
                    if (count($selecteLocation) > 1) {
                        $quryModify .= " item_history_set_offs.location_id IN ('" . implode("', '", $selecteLocation) . "') AND";
                    } else {
                        $quryModify .= " item_history_set_offs.location_id ='" . $selecteLocation[0] . "'AND";
                    }

                }    

                if ($selectedproduct != null) {
                    if (count($selectedproduct) > 1) {
                        $quryModify .= " item_history_set_offs.item_id IN ('" . implode("', '", $selectedproduct) . "') AND";
                    } else {
                        $quryModify .= " item_history_set_offs.item_id ='" . $selectedproduct[0] . "'AND";
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
                        $quryModify .= " items.category_level_3_id IN ('" . implode("', '", $selectecategory3) . "') AND";
                    } else {
                        $quryModify .= " items.category_level_3_id ='" . $selectecategory3[0] . "'AND";
                    }

                }    

                if ($selectSupplygroup != null) {
                    if (count($selectSupplygroup) > 1) {
                        $quryModify .= " items.supply_group_id  IN ('" . implode("', '", $selectSupplygroup) . "') AND";
                    } else {
                        $quryModify .= " items.supply_group_id  ='" . $selectSupplygroup[0] . "'AND";
                    }

                }               
                if ($quryModify != "") {
                    $quryModify = rtrim($quryModify, 'AND OR ');
                    $query = $query . " where item_history_set_offs.quantity>0 AND " . $quryModify . ' GROUP BY items.Item_id ORDER BY items.Item_code ASC';
                }
                //dd($query);
                $result = DB::select($query);
                $resulsupplygroup = DB::select('SELECT IC.item_category_level_1_id,IC.category_level_1 FROM item_category_level_1s IC');
                $supplygrouparray = [];
                $table = [];
                $titel = [];
                $reportViwer = new ReportViewer();
                foreach ($resulsupplygroup as $supplygroupid) {

                    $table = [];

                    foreach ($result as $supplygroupdata) {
                       
                        
                        if ($supplygroupdata->category_level_1_id == $supplygroupid->item_category_level_1_id) {
                            array_push($table, $supplygroupdata);
                        }
                    }

                    if (count($table) > 0) {
                        array_push($supplygrouparray, $table);
                        array_push($titel, "<strong>Category Level : " . $supplygroupid->category_level_1 . "</strong>");
                        $reportViwer->addParameter('abc', $titel);
                    }
                }

                
                $reportViwer->addParameter("StockBalance_tabaledata", [$supplygrouparray]);
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
                $selecteLocation= $searchOption[14]->selecteLocation;

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
                        $filterLabel .= " For";
                    }

                    if ($selecteBranch !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Branch: $branchname and";
                    }

                    if ($selecteLocation !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " Location: $locationname and";
                    }



                    /* if ($fromdate !== null) {
                        $filterLabel .= " From: $fromdate ";
                    }*/

                    if ($todate !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " To: $todate and";
                    }

                    if ($selectedproduct !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " product: $itemname ";
                    }

                    if ($selectecategory1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " category level 1: $category1 and";
                    }

                    if ($selectecategory2 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " category level 2: $category2  and";
                    }

                    if ($selectecategory3 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " category level 3: $category3 and";
                    }

                    if ($selectSupplygroup1 !== null) {
                        // Add a separator if needed
                        if ($filterLabel !== '') {
                            $filterLabel .= ' ';
                        }
                        $filterLabel .= " category level 3: $selectSupplygroup and";
                    }


                    // Check if the filter label is not empty and then print it
                    if (!empty($filterLabel)) {
                        $filterLabel = rtrim($filterLabel, 'and ');

                        $reportViwer->addParameter("filter", $filterLabel);
                    }
                    //
                } else {

                    if (
                        $selecteBranch  == null && $selecteLocation == null && $fromdate == null && $todate == null && $selectedproduct == null && $selectecategory1 == null
                        && $selectecategory2 == null && $selectecategory3 == null && $selectSupplygroup == null 
                    ) {
                        $filterLabel = "";
                    } elseif ($selecteBranch !== null) {
                        $filterLabel = "For: $branchname branch";
                    }elseif($selecteLocation !== null){
                        $filterLabel = "For: $locationname";
                    } elseif ($fromdate !== null && $todate !== null) {
                        $filterLabel = "For   To: $todate";
                    } elseif ($selectedproduct !== null) {
                        $filterLabel = "For: $itemname";
                    } elseif ($selectecategory1 !== null) {
                        $filterLabel = "For: $category1";
                    } elseif ($selectecategory2 !== null) {
                        $filterLabel = "For: $category2";
                    } elseif ($selectecategory3 !== null) {
                        $filterLabel = "For: $category3";
                    } elseif ($selectSupplygroup !== null) {
                        $filterLabel = "For: $selectSupplygroup";
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
            //dd($reportViwer);
            return $reportViwer->viewReport('stockBalanceReport.json');
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

}
/*

*/
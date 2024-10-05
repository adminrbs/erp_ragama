<?php

namespace Modules\Sd\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CommisionReportController extends Controller
{
    protected $finalTotal;
    protected $finalEarnings;
    protected $finalDeduction;
    protected $finalchqReturns;
    protected $finalsalesReturns;



    //Generate report
    public function generatecommisionReport($filters)
    {

        try {

            $searchOption = json_decode($filters);
            $branch = $searchOption[0]->selecteBranch;
            $fromDate = $searchOption[1]->fromdate;
            $toDate = $searchOption[2]->todate;
            $collector = $searchOption[3]->selectCollector;

            if ($branch == null) {

                $branch = DB::table('branches')->pluck('branch_id')->toArray();
            }

            if ($collector == null) {

                $collectorIds = DB::table('employees')
                ->whereIn('desgination_id', [7, 8]) 
                ->pluck('employee_id')
                ->toArray();
            
            



                $collector = implode(',', $collectorIds);
            } else {

                $collector = is_array($collector) ? implode(',', $collector) : $collector;
            }
            //dd($fromDate);
            $html = '<html>';
            $html .= '<div style="text-align: center;">';
            $html .= '<h2>Commision Report</h2>';
            $html .= '<h4> From :' . $fromDate . ' To :' . $toDate . '</h4>';
            $html .= '</div>';

            foreach ($branch as $b_id) {

                $result = $this->getData($fromDate, $toDate, $b_id, $collector);
                //dd($result);

                $html .= $this->generateTable($b_id, $result);
                $html .= '<br>';
            }

            $html .= '<table style="border-collapse: collapse; width: 100%; max-width: 800px; margin-top: 5px; border-top: 1px solid black; border-bottom: 3px double black;">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th colspan="2" style="text-align: center;width:300px;">Total</th>';
            $html .= '<th style="width:100px;">' . number_format($this->finalTotal, 2, '.', ',') . '</th>' .
            '<th style="width:100px;">' . number_format($this->finalEarnings, 2, '.', ',') . '</th>' .
            '<th style="width:120px;">' . number_format($this->finalDeduction, 2, '.', ',') . '</th>' .
            '<th style="width:120px;">' . number_format($this->finalchqReturns, 2, '.', ',') . '</th>' .
            '<th style="width:120px;">' . number_format($this->finalsalesReturns, 2, '.', ',') . '</th>';
   
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '</table>';
            $html .= '</html>';


            return $html;
        } catch (Exception $ex) {
            return $ex;
        }
    }


    public function getData($from, $to, $b_id, $collectorArray)
    {
        $commissionPrecentage = [
            1 => [
                0,
                72,
                73,
                78,
                79,
                85,
                76,
                0.01,
                0.0075,
                0.005

            ],
            2 => [
                0,
                72,
                73,
                78,
                79,
                85,
                76,
                0.01,
                0.0075,
                0.005
            ],
            4 => [
                0,
                62,
                63,
                68,
                69,
                75,
                76,
                0.01,
                0.0075,
                0.005
            ],
            5 => [
                0,
                46,
                47,
                52,
                53,
                58,
                59,
                0.005,
                0.003,
                0.0025
            ],
            6 => [
                0,
                38,
                39,
                45,
                46,
                55,
                56,
                0.0075,
                0.0035,
                0.0035
            ],
            8 => [
                0,
                62,
                63,
                68,
                69,
                75,
                76,
                0.007,
                0.0035,
                0.0035
            ]
        ];
        $rangeArray = [];
        if (isset($commissionPrecentage[$b_id])) {
            $rangeArray = $commissionPrecentage[$b_id];
            $qry = "
            SELECT
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[0] . " AND " . $rangeArray[1] . " THEN CR.amount ELSE 0 END), 0) AS first_range,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[2] . " AND " . $rangeArray[3] . " THEN CR.amount ELSE 0 END), 0) AS second_range,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[4] . " AND " . $rangeArray[5] . " THEN CR.amount ELSE 0 END), 0) AS third_range,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) > " . $rangeArray[6] . " THEN CR.amount ELSE 0 END), 0) AS forth_range,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[0] . " AND " . $rangeArray[1] . " THEN CR.amount * " . $rangeArray[7] . " ELSE 0 END), 0) AS commison_for_first,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[2] . " AND " . $rangeArray[3] . " THEN CR.amount * " . $rangeArray[8] . " ELSE 0 END), 0) AS commison_for_second,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[4] . " AND " . $rangeArray[5] . " THEN 0 ELSE 0 END), 0) AS commison_for_third,
                IFNULL(SUM(CASE WHEN DATEDIFF(D.trans_date, CR.receipt_date) > " . $rangeArray[6] . " THEN -CR.amount * " . $rangeArray[9] . " ELSE 0 END), 0) AS deductive_commision,
        
        (
    SELECT 
        IFNULL(
            SUM(
                CASE 
                    WHEN DATEDIFF(DB.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[0] . " AND " . $rangeArray[1] . " THEN CR.amount * " . $rangeArray[7] . "
                    WHEN DATEDIFF(DB.trans_date, CR.receipt_date) BETWEEN " . $rangeArray[2] . " AND " . $rangeArray[3] . " THEN CR.amount * " . $rangeArray[8] . "
                    ELSE 0
                END
    ), 
    0) AS total_cheque_return_commission_sum
            FROM customer_receipts CR
            LEFT JOIN customer_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
            LEFT JOIN cheque_returns CRT ON CRC.customer_receipt_cheque_id = CRT.customer_receipt_cheque_id
            LEFT JOIN debtors_ledgers DB ON CRT.external_number = DB.external_number
            WHERE DB.paidamount > 0 
            AND CR.receipt_date > CRT.returned_date 
            AND DB.document_number = 1000
     ) AS cheque_return_commission_sum,
    (
        SELECT IFNULL(SUM(CR.amount), 0.00)
        FROM customer_receipts CR
        LEFT JOIN customer_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
        LEFT JOIN cheque_returns CRT ON CRC.customer_receipt_cheque_id = CRT.customer_receipt_cheque_id
        LEFT JOIN debtors_ledgers DB ON CRT.external_number = DB.external_number
        WHERE DB.paidamount > 0 
        AND CR.receipt_date > CRT.returned_date 
        AND DB.document_number = 1000
    ) AS total_return,
    IFNULL(
    (
        SELECT SUM(sales_returns.total_amount) AS sales_return_sum
        FROM sales_returns
        LEFT JOIN customer_collectors CC ON sales_returns.customer_id = CC.customer_id
        WHERE CC.employee_id = E.employee_id AND sales_returns.branch_id = D.branch_id
    ), 0.00
) AS total_sales_return,


            
                B.branch_name,
                COALESCE(D.branch_id, $b_id) AS branch_id, -- Default to $b_id if branch_id is null
                D.customer_id,
                E.employee_name AS collector_name
            FROM
                employees E
            LEFT JOIN customer_receipts CR ON E.employee_id = CR.collector_id 
                AND CR.receipt_date BETWEEN '" . $from . "' AND '" . $to . "'
            LEFT JOIN customer_receipt_setoff_data CRSD ON CR.customer_receipt_id = CRSD.customer_receipt_id
            LEFT JOIN debtors_ledgers D ON CRSD.reference_external_number = D.external_number
            LEFT JOIN branches B ON D.branch_id = B.branch_id
            LEFT JOIN customer_receipt_cheques CRC ON CR.customer_receipt_id = CRC.customer_receipt_id
            LEFT JOIN cheque_returns CRT ON CRC.customer_receipt_cheque_id = CRT.customer_receipt_cheque_id
            LEFT JOIN debtors_ledgers DB ON CRT.external_number = DB.external_number
            
            
        
            WHERE
                E.employee_id IN (" . $collectorArray . ")
            AND
                (D.branch_id = $b_id OR D.branch_id IS NULL)
            GROUP BY
                E.employee_id;
        ";



            //dd($qry);
            $result = DB::select($qry);
            return $result;
        }
    }


    public function generateTable($b_id, $result)
    {
        // Check if $result is null or empty
        if (!$result || empty($result)) {
            return;
        }

        $commissionFormulas = [
            1 => ['0-72 Days', '73-78 Days', '49-85 Days', 'After 86 days'],
            2 => ['0-72 Days', '73-78 Days', '49-85 Days', 'After 86 days'],
            4 => ['0-62 Days', '63-68 Days', '69-75 Days', 'After 76 days'],
            5 => ['0-46 Days', '47-52 Days', '53-58 Days', 'After 59 days'],
            6 => ['0-38 Days', '39-45 Days', '46-55 Days', 'After 56 days'],
            8 => ['0-62 Days', '63-68 Days', '69-75 Days', 'After 76 days']
        ];

        $table = '';

        // Variable to track the current branch to avoid duplicate headers
        $currentBranch = null;

        foreach ($result as $row) {
            // Skip invalid rows
            if (is_null($row) || is_null($row->branch_id) || !isset($commissionFormulas[$row->branch_id])) {
                continue;
            }

            // If this row belongs to a new branch, add the branch header once
            if ($currentBranch !== $row->branch_name) {
                if ($currentBranch !== null) {
                    // Close the previous table body if it's not the first branch
                    $table .= '</tbody></table>';
                }

                // Start a new table for the new branch
                $table .= '<table border="1" style="border-collapse: collapse; border: 1px solid black; width: 100%; max-width: 800px;">';

                // Add the branch name as a header
                $table .= '<thead>
                <tr>
                    <th colspan="7" style="text-align: center;">' . htmlspecialchars($row->branch_name) . '</th>
                </tr>';

                // Add the table header for columns
                $table .= '<tr>
                <th>Collector</th>
                <th>Description</th>
                <th style="width: 100px;">Total</th>
                <th>Earnings</th>
                <th>Deduction</th>
                <th>Chq Return</th>
                <th>Sales Return</th>
            </tr>
            </thead>
            <tbody>';

                // Update the current branch
                $currentBranch = $row->branch_name;
            }

            // Get the commission ranges for this branch
            $rangeArray = $commissionFormulas[(int)$row->branch_id] ?? [];

            // Format the necessary data with number_format
            $collector = htmlspecialchars($row->collector_name ?? '');
            $firstRange = number_format($row->first_range ?? 0, 2, '.', ',');
            $secondRange = number_format($row->second_range ?? 0, 2, '.', ',');
            $thirdRange = number_format($row->third_range ?? 0, 2, '.', ',');
            $commFirst = number_format($row->commison_for_first ?? 0, 2, '.', ',');
            $commSecond = number_format($row->commison_for_second ?? 0, 2, '.', ',');
            $commThird = number_format($row->commison_for_third ?? 0, 2, '.', ',');
            $deductiveComm = number_format($row->deductive_commision ?? 0, 2, '.', ',');
            $chqReturnCom = number_format($row->cheque_return_commission_sum ?? 0, 2, '.', ',');
            $chqRtn = number_format($row->total_return ?? 0, 2, '.', ',');
            $salesRtn = number_format($row->total_sales_return ?? 0, 2, '.', ',');

            $this->finalTotal += (is_numeric($firstRangeCleaned = str_replace(',', '', $firstRange)) ? $firstRangeCleaned : 0) +
                     (is_numeric($secondRangeCleaned = str_replace(',', '', $secondRange)) ? $secondRangeCleaned : 0) +
                     (is_numeric($thirdRangeCleaned = str_replace(',', '', $thirdRange)) ? $thirdRangeCleaned : 0);

            $this->finalEarnings += (is_numeric($commFirstCleaned = str_replace(',', '', $commFirst)) ? $commFirstCleaned : 0) +
                        (is_numeric($commSecondCleaned = str_replace(',', '', $commSecond)) ? $commSecondCleaned : 0) +
                        (is_numeric($commThirdCleaned = str_replace(',', '', $commThird)) ? $commThirdCleaned : 0);

            $this->finalDeduction += (is_numeric($chqReturnComCleaned = str_replace(',', '', $chqReturnCom)) ? $chqReturnComCleaned : 0) +
                         (is_numeric($deductiveCommCleaned = str_replace(',', '', $deductiveComm)) ? $deductiveCommCleaned : 0);

            $this->finalchqReturns += (is_numeric($chqRtnCleaned = str_replace(',', '', $chqRtn)) ? $chqRtnCleaned : 0);

            $this->finalsalesReturns += (is_numeric($salesRtnCleaned = str_replace(',', '', $salesRtn)) ? $salesRtnCleaned : 0);
            //$this->finalsalesReturns += is_numeric($salesRtn);


            // Add rows for this collector under the current branch
            $table .= '<tr>';
            $table .= '<td rowspan="6">' . $collector . '</td>';
            $table .= '<td>' . htmlspecialchars($rangeArray[0]) . '</td>';
            $table .= '<td style="text-align: right;">' . $firstRange . '</td>';
            $table .= '<td style="text-align: right;">' . $commFirst . '</td>';
            $table .= '<td></td><td></td><td></td></tr>';

            $table .= '<tr>';
            $table .= '<td>' . htmlspecialchars($rangeArray[1]) . '</td>';
            $table .= '<td style="text-align: right;">' . $secondRange . '</td>';
            $table .= '<td style="text-align: right;">' . $commSecond . '</td>';
            $table .= '<td></td><td></td><td></td></tr>';

            $table .= '<tr>';
            $table .= '<td>' . htmlspecialchars($rangeArray[2]) . '</td>';
            $table .= '<td style="text-align: right;">' . $thirdRange . '</td>';
            $table .= '<td style="text-align: right;">' . $commThird . '</td>';
            $table .= '<td></td><td></td><td></td></tr>';

            $table .= '<tr>';
            $table .= '<td>' . htmlspecialchars($rangeArray[3]) . '</td>';
            $table .= '<td></td><td></td>';
            $table .= '<td style="text-align: right;">' . $deductiveComm . '</td>';
            $table .= '<td></td><td></td></tr>';

            // Handle Chq Returns row
            $table .= '<tr>';
            $table .= '<td>Chq returns</td>';
            $table .= '<td></td><td></td>';
            $table .= '<td style="text-align: right;">' .$chqReturnCom . '</td>';
            $table .= '<td style="text-align: right;">' . $chqRtn . '</td>';
            $table .= '<td></td></tr>';

            // Handle Sales Return row
            $table .= '<tr>';
            $table .= '<td>Sales return</td>';
            $table .= '<td></td><td></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td style="text-align: right;">' . $salesRtn . '</td></tr>';
        }

        // Close the final table
        if ($currentBranch !== null) {
            $table .= '</tbody></table>';
        }

        return $table;
    }
}

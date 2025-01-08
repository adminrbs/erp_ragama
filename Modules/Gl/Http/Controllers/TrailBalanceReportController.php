<?php

namespace Modules\Gl\Http\Controllers;

use App\Http\Controllers\CompanyDetailsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class TrailBalanceReportController extends Controller
{
    public function trial_balance($filters)
    {
        $filter_options = json_decode($filters);
        $selectfromdate = $filter_options[1]->selectfromdate ?? null;
        $selecttodate = $filter_options[2]->selecttodate ?? null;
        $selectAccount = $filter_options[0]->selectAccount ?? [];

        // Build query conditions securely
        $conditions = [];
        $params = [];

        if ($selecttodate) {
            $conditions[] = 'GL.transaction_date <= ?';
            $params[] = $selecttodate;
        }

        if (!empty($selectAccount)) {
            $conditions[] = 'GL.gl_account_id = ?';
            $params[] = $selectAccount[0];
        }

        $query_modify = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Base query
        $query = "
            SELECT
                GA.account_code,
                GA.account_title,
                SUM(COALESCE(GL.amount, 0)) AS total_amount,
                SUM(COALESCE(GL.paid_amount, 0)) AS total_paid_amount,
                GAT.gl_account_type_id
            FROM
                general_ledger GL
            INNER JOIN gl_accounts GA ON GL.gl_account_id = GA.account_id
            INNER JOIN gl_account_types GAT ON GA.account_type_id = GAT.gl_account_type_id
            {$query_modify}
            GROUP BY
                GA.account_code, GA.account_title, GAT.gl_account_type_id
        ";

        // Execute query with parameters
        $results = DB::select($query, $params);

        // Group results by `gl_account_type_id`
        $groupedResults = collect($results)->groupBy('gl_account_type_id');
        $grandTotalDebit = 0;
        $grandTotalCredit = 0;

        // Generate the HTML report
        /* $htmlReport = "<h1 style='text-align: center; font-family: Arial, sans-serif;'>".CompanyDetailsController::CompanyName()."</h1>";
        $htmlReport = "<h2 style='text-align: center; font-family: Arial, sans-serif;'>".CompanyDetailsController::CompanyAddress()."</h2>";
        $htmlReport = "<h3 style='text-align: center; font-family: Arial, sans-serif;'>".CompanyDetailsController::CompanyNumber()."</h3>";
        $htmlReport = "<h4 style='text-align: center; font-family: Arial, sans-serif;'>Trial Balance Report As At ".$selecttodate."</h4>"; */
        $htmlReport = "<h1 style='text-align: center; font-family: Arial, sans-serif;'>".CompanyDetailsController::CompanyName()."</h1>";
$htmlReport .= "<h3 style='text-align: center; font-family: Arial, sans-serif;'>".CompanyDetailsController::CompanyAddress()."</h3>";
$htmlReport .= "<h4 style='text-align: center; font-family: Arial, sans-serif;'>".CompanyDetailsController::CompanyNumber()."</h4>";
$htmlReport .= "<h4 style='text-align: center; font-family: Arial, sans-serif;'>Trial Balance Report As At ".$selecttodate."</h4>";


        foreach ($groupedResults as $typeId => $accounts) {
            $htmlReport .= "
                <table style='width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin-left: 5px; margin-right: 5px;'>

                    <thead>
    <tr>
        <th style='border-top: 2px solid black; border-bottom: 2px solid black; padding: 8px;border-left: 2px solid black; padding: 5px; text-align: left; width: 20%;'>Account Code</th>
        <th style='border-top: 2px solid black; border-bottom: 2px solid black; padding: 8px; text-align: left; width: 40%;'>Account Title</th>
        <th style='border-top: 2px solid black; border-bottom: 2px solid black; padding: 8px; text-align: right; width: 20%;'>Debit</th>
        <th style='border-top: 2px solid black; border-bottom: 2px solid black; padding: 8px;border-right: 2px solid black; padding: 5px; text-align: right; width: 20%;'>Credit</th>
    </tr>
</thead>

                    <tbody>
            ";

            $subtotalDebit = 0;
            $subtotalCredit = 0;
            
            foreach ($accounts as $account) {
                $total = $account->total_amount + $account->total_paid_amount;
                $debit = $total > 0 ? abs($total) : 0;
                $credit = $total < 0 ? abs($total) : 0;

                $subtotalDebit += $debit;
                $subtotalCredit += $credit;
                $grandTotalDebit += $debit;
                $grandTotalCredit += $credit;

                $htmlReport .= "
                    <tr>
                        <td style='padding: 8px; width: 20%;'>{$account->account_code}</td>
                        <td style='padding: 8px; width: 40%;'>{$account->account_title}</td>
                        <td style='padding: 8px; text-align: right; width: 20%;'>" . number_format($debit, 2) . "</td>
                        <td style='padding: 8px; text-align: right; width: 20%;'>" . number_format($credit, 2) . "</td>
                    </tr>
                ";
            }

            // Add subtotal row with line for credit and debit columns only
            $htmlReport .= "
                <tr>
                    <td colspan='2' style='padding: 8px;'>Sub Total</td>
                    <td style='padding: 8px; text-align: right; border-top: 2px solid black;'>" . number_format($subtotalDebit, 2) . "</td>
                    <td style='padding: 8px; text-align: right; border-top: 2px solid black;'>" . number_format($subtotalCredit, 2) . "</td>
                </tr>
            ";

           // $htmlReport .= "</tbody></table><br>";
        }

        $htmlReport .= "
        <tr>
            <td colspan='2' style='padding: 8px;'>Grand Total</td>
            <td style='padding: 8px; text-align: right; border-top: 2px solid black; font-weight: bold;'>" . number_format($grandTotalDebit, 2) . "</td>
            <td style='padding: 8px; text-align: right; border-top: 2px solid black; font-weight: bold;'>" . number_format($grandTotalCredit, 2) . "</td>
        </tr>
    ";

    $htmlReport .= "</tbody></table><br>";

        return response()->make($htmlReport, 200, ['Content-Type' => 'text/html']);
    }
}

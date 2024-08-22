<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class poExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $filters;

    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function headings(): array
    {
        // Define your headers with colspan
        return [
            ['Item Name', 'Pack Size', 'Supply Group Name','Negombo', '', 'Gampaha', '', 'Colombo', '', 'Kandy', '', 'Kurunagala', '', 'Galle', '', 'Anuradhapura', '','Total'],
            
            ['','','','RDQTY','QOH','RDQTY','QOH','RDQTY', 'QOH', 'RDQTY', 'QOH', 'RDQTY', 'QOH', 'RDQTY', 'QOH', 'RDQTY', 'QOH','RDQTY','QOH'],
        ];
    }

    public function map($row): array
    {
        $total_rd = $row->rd_qty_branch_1 + $row->rd_qty_branch_2 +  $row->rd_qty_branch_3 + $row->rd_qty_branch_4+ $row->rd_qty_branch_5 + $row->rd_qty_branch_6 +$row->rd_qty_branch_7;
        $total_qoh =  $row->qoh_qty_branch_1 +  $row->qoh_qty_branch_2 +  $row->qoh_qty_branch_3 +  $row->qoh_qty_branch_4 + $row->qoh_qty_branch_5 + $row->qoh_qty_branch_6 + $row->qoh_qty_branch_7;
        return [
            $row->item_Name,
            $row->package_unit,
            $row->supply_group,
            // Append data to existing columns for Negombo
            $row->rd_qty_branch_1,
            $row->qoh_qty_branch_1,
            // Append data to existing columns for Gampaha
            $row->rd_qty_branch_2,
            $row->qoh_qty_branch_2,
            // Append data to existing columns for Colombo
            $row->rd_qty_branch_3,
            $row->qoh_qty_branch_3,
            // Append data to existing columns for Kandy
            $row->rd_qty_branch_4,
            $row->qoh_qty_branch_4,
            // Append data to existing columns for Kurunagala
            $row->rd_qty_branch_5,
            $row->qoh_qty_branch_5,
            // Append data to existing columns for Galle
            $row->rd_qty_branch_6,
            $row->qoh_qty_branch_6,
            // Append data to existing columns for Anuradhapura
            $row->rd_qty_branch_7,
            $row->qoh_qty_branch_7,

            $total_rd,
            $total_qoh
            
        ];
    }



    public function collection()
    {
        $filter_Data = $this->filters;


        $filter_options = json_decode($filter_Data);
        $fromDate = $filter_options->fromDate;
        $toDate = $filter_options->toDate;
        $supplygroup = $filter_options->supplygroup;
        $branch = $filter_options->branch;

        $in_para_sales_where = "1";
        $in_para_stock_where = "1";

        if ($fromDate && $toDate && !$branch && !$supplygroup) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "'";
            $in_para_stock_where .= " AND IH.transaction_date<='" . $toDate . "'";
        } else if ($fromDate && $toDate && $branch && $supplygroup) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "' AND B.branch_id IN ('" . implode("', '", $branch) . "') AND I.supply_group_id IN('" . implode("','", $supplygroup) . "')";
            $in_para_stock_where .= " AND IH.transaction_date<='" . $toDate . "'  AND '" . $toDate . "' AND branch_id IN ('" . implode("', '", $branch) . "') AND I.supply_group_id IN('" . implode("','", $supplygroup) . "')";
        } else if (!$fromDate && !$toDate && $branch  && !$supplygroup) {
            $in_para_stock_where = " branch_id IN ('" . implode("', '", $branch) . "') ";
            $in_para_sales_where = " B.branch_id IN ('" . implode("', '", $branch) . "') ";
        } else if (!$fromDate && !$toDate && !$branch  && $supplygroup) {
            $in_para_stock_where = " I.supply_group_id IN('" . implode("','", $supplygroup) . "') ";
            $in_para_sales_where = " I.supply_group_id IN('" . implode("','", $supplygroup) . "') ";
        } else if (!$fromDate && !$toDate && $branch  && $supplygroup) {
            $in_para_stock_where = " supply_group_id IN('" . implode("','", $supplygroup) . "') AND branch_id IN ('" . implode("', '", $branch) . "')";
            $in_para_sales_where = " I.supply_group_id IN('" . implode("','", $supplygroup) . "') AND B.branch_id IN ('" . implode("', '", $branch) . "') ";
        } else if ($fromDate && $toDate && !$branch && $supplygroup) {
            $in_para_sales_where = " SI.transaction_date BETWEEN '" . $fromDate . "'  AND '" . $toDate . "' AND I.supply_group_id IN('" . implode("','", $supplygroup) . "')";
            $in_para_stock_where .= " AND IH.transaction_date<='" . $toDate . "'  AND '" . $toDate . "' AND supply_group_id IN('" . implode("','", $supplygroup) . "')";
        }


        $data = DB::select('CALL report_po_help("' . $in_para_sales_where . '","' . $in_para_stock_where . '")');



        // Convert the result to a Laravel collection
        $collection = collect($data);
        // dd($collection);
        return $collection;
    }
}

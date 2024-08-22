<?php

namespace App\Http\Controllers;

use App\Exports\poExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportToExcel($filters)
    {


        $export = new poExport();
        $export->setFilters($filters);
    
        $filePath = storage_path('app/public/po_data.xlsx'); // Adjust the path as needed
        Excel::store($export, 'public/po_data.xlsx');
    
        return response()->json(['url' => asset('storage/po_data.xlsx')]);
    }
}

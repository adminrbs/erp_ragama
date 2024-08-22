<?php

namespace RepoEldo\ELD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function viewReport($report)
    {
        $reportViewer = new ReportViewer();
        return $reportViewer->viewReport($report);
    }
}

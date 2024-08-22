<?php

use Illuminate\Support\Facades\Route;
use RepoEldo\ELD\ReportController;
use RepoEldo\ELD\ReportViewer;

Route::get('sample',function(){
    $reportViwer = new ReportViewer();
    return $reportViwer->viewReport('sample_report.json');
});

Route::get('viewReport/{report}',[ReportController::class,'viewReport']);
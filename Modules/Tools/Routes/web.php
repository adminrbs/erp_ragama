<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Tools\Http\Controllers\DeliveryPlanController;
use Modules\Tools\Http\Controllers\UpdateBatchPriceController;

Route::prefix('tools')->middleware(['is.logged'])->group(function () {





    Route::get('/data_table', function () {
        return  view('tools::datatable');
    });

    /** Update Batch Price */
    Route::get('/update_batch_price', function () {
        return  view('tools::update_batch_price');
    })->middleware(['is.logged','can:tl_update_batch_price']);

    Route::get('/get_filter_data', [UpdateBatchPriceController::class, 'get_filter_data']);
    Route::get('/getBatchData/{filters}', [UpdateBatchPriceController::class, 'getBatchData']);
    Route::put('/updateBatchPrice/{item_setoff_id}', [UpdateBatchPriceController::class, 'updateBatchPrice']);
    /** End of Update Batch Price */


});

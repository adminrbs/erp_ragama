<?php

use Illuminate\Support\Facades\Route;
use Modules\Sc\Http\Controllers\BinCardController;
use Modules\Sc\Http\Controllers\CustomerLegerReportController;
use Modules\Sc\Http\Controllers\DebtorReportsController;
use Modules\Sc\Http\Controllers\GoodsTransferController;
use Modules\Sc\Http\Controllers\InternalOrderController;
use Modules\Sc\Http\Controllers\ItemHistoryController;
use Modules\Sc\Http\Controllers\ItemMovemetHistoryController;
use Modules\Sc\Http\Controllers\Outstandingcontroller;
use Modules\Sc\Http\Controllers\PriceApprovalController;
use Modules\Sc\Http\Controllers\RdStockreportController;
use Modules\Sc\Http\Controllers\RDStockWithoutReportController;
use Modules\Sc\Http\Controllers\RDStockWithReportController;
use Modules\Sc\Http\Controllers\SalesreturndetailsController;
use Modules\Sc\Http\Controllers\ValuationController;
use Modules\Sc\Http\Controllers\ViewStockBlanceController;
use Modules\Sc\Http\Controllers\BranchwiseStockReportController;
use Modules\Sc\Http\Controllers\CustomerOutstandingControllerInvoiceWise;
use Modules\Sc\Http\Controllers\DispatchToBranchController;
use Modules\Sc\Http\Controllers\ReverseDevisionTransferController;
use Modules\Sc\Http\Controllers\SampleDispatchController;
use Modules\Sc\Http\Controllers\StockAdjustmentController;
use Modules\Sc\Http\Controllers\TrancationAllocationReportController;
use Modules\Sc\Http\Controllers\UpdateBatchPriceController;

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

Route::prefix('sc')->middleware(['is.logged','cookie.approvalConfirm'])->group(function () {

    Route::get('/', function () {
        return view('sd::dashboard');
    })->middleware('is.logged');
    /* Route::get('/itemHistory', function () {
        return view('sc::itemHistory');
    })->middleware('is.logged'); */

    Route::get('/getItemHistory', [ItemHistoryController::class, 'getItemHistory']);
    Route::get('/stockBalanceReport/{search}', [ItemHistoryController::class, 'stockBalanceReport']);


    /*Route::get('/outstandReport', function () {
        return view('sc::outStandingrepot');
    })->middleware('is.logged');*/

    Route::get('/printoutstandinReport/{search}', [Outstandingcontroller::class, 'printoutStandinReport']);

    Route::get('/printoutsalseinvoiseAndRetirnReport', [Outstandingcontroller::class, 'printoutsalseinvoiseAndRetirnReport']);
    Route::get('/printSalesReturnDetailsReport', [SalesreturndetailsController::class, 'printSalesReturnDetailsReport']);
    Route::get('/printItemMovementHistoryReport/{search}', [ItemMovemetHistoryController::class, 'printItemMovementHistoryReport']);
    Route::get('/debtor_reports_invoiceWise/{search}', [CustomerOutstandingControllerInvoiceWise::class, 'debtor_reports_invoiceWise']);

    

    Route::get('/genarateReport', function () {
        return view('sc::genarateReport');
    })->middleware(['is.logged', 'can:sc_reports']);

    Route::get('/getproduct', [Outstandingcontroller::class, 'getproduct']);
    Route::get('/getproduct_sup_id', [Outstandingcontroller::class, 'getproduct_sup_id']);
    Route::get('/getItemCategory1', [Outstandingcontroller::class, 'getItemCategory1']);
    Route::get('/getItemCategory2', [Outstandingcontroller::class, 'getItemCategory2']);
    Route::get('/getItemCategory3', [Outstandingcontroller::class, 'getItemCategory3']);
    Route::get('/getsuplygroup', [Outstandingcontroller::class, 'getsuplygroup']);

//stock balance

    Route::get('/genarateStockBalanceReport', function () {
        return view('sc::view_stock_blance');
    });
    Route::post('/getStockBlance', [ViewStockBlanceController::class, 'getStockBlance']);
    Route::get('/getSupllyGroup', [ViewStockBlanceController::class, 'getSupllyGroup']);
    Route::get('/getbranch', [ViewStockBlanceController::class, 'getbranch']);
    Route::get('/getLocation_stock_balance/{id}',[ViewStockBlanceController::class,'getLocation_stock_balance']);
    Route::get('/getlocationForBranch',[ViewStockBlanceController::class,'getlocationForBranch']);
    Route::get('/genaratedebtorreport', function () {
        return view('sc::debtor_reports');
    });
    Route::get('/getDebtorrepor', [DebtorReportsController::class, 'getDebtorrepor']);
    Route::get('/debtor_reports/{search}', [DebtorReportsController::class, 'debtor_reports']);
    Route::get('/getCustomer', [DebtorReportsController::class, 'getCustomer']);
    Route::get('/getCustomergroup', [DebtorReportsController::class, 'getCustomergroup']);
    Route::get('/getcustomergrade', [DebtorReportsController::class, 'getcustomergrade']);
    Route::get('/getRoute', [DebtorReportsController::class, 'getRoute']);
    Route::get('/getSalesrepfor_report', [DebtorReportsController::class, 'getSalesrepfor_report']);
    Route::get('/getCollectorsfor_report',[DebtorReportsController::class,'getCollectorsfor_report']);
    // Customer_Ledger_reports
    Route::get('/Customer_Ledger_reports/{search}', [CustomerLegerReportController::class, 'Customer_Ledger_reports']);

    Route::post('/hidefilter/{id}', [DebtorReportsController::class, 'hidefilter']);
    Route::post('/stockcontrol/{id}', [ItemMovemetHistoryController::class, 'stockcontrol']);

    /**Item movment history view (bin card) */

    Route::get('/binCard', function () {
        return view('sc::binCard');
    })->middleware(['is.logged', 'can:sc_bin_card']);
    Route::get('/getproduct_binCard', [BinCardController::class, 'getItems']);
    Route::post('loadItemMovementHistoryData', [BinCardController::class, 'loadItemMovementHistoryData']);

    Route::get('/getlocation', [Outstandingcontroller::class, 'getlocation']);

    //valuation report
    Route::get('/printvaluationReport/{search}', [ValuationController::class, 'printvaluationReport']);

    /**Goods transfer note */
    Route::get('/goods_transfer_list', function () {
        return view('sc::goods_transfer_list');
    })->middleware(['is.logged', 'can:sc_goods_transfer_list']);
    Route::get('/goods_transfer', function () {
        return view('sc::goods_transfer');
    });
    Route::get('/goods_transfer_approve_list', function () {
        return view('sc::goods_transfer_approval_list');
    })->middleware(['is.logged', 'can:sc_goods_transfer_approval_list']);
    Route::get('/goods_transfer_approve', function () {
        return view('sc::goods_transfer_approve');
    });
    Route::get('/goods_transfer_view', function () {
        return view('sc::goods_transfer_view');
    });

    Route::get('/getItemInfotogrnReturn/{branch_id}/{item_id}/{location_id}', [GoodsTransferController::class, 'getItemInfotogrnReturn']);
    Route::get('/loadAllLocation/{id}', [GoodsTransferController::class, 'loadAllLocation']);
    Route::post('/add_goods_transfer', [GoodsTransferController::class, 'add_goods_transfer']);
    Route::get('/get_goods_transfer_details', [GoodsTransferController::class, 'get_goods_transfer_details']);
    Route::get('/get_each_transfer/{id}', [GoodsTransferController::class, 'get_each_transfer']);
    Route::get('/get_goods_transfer_details_approval', [GoodsTransferController::class, 'get_goods_transfer_details_approval']);
    Route::post('/approve_goods_transfer/{id}', [GoodsTransferController::class, 'approve_goods_transfer']);
    Route::post('/reject_goods_transfer/{id}', [GoodsTransferController::class, 'reject_goods_transfer']);

    /**Price approve */
    Route::get('/price_approve_list', function () {
        return view('sc::price_approval_list');
    })->middleware(['is.logged', 'can:sc_price_approval_list']);

    Route::get('/get_price_approval_list/{id}', [PriceApprovalController::class, 'load_price_approval_details']);
    Route::get('/getLocation_price_confirm', [PriceApprovalController::class, 'getLocation_price_confirm']);
    Route::post('/approve_price/{id}', [PriceApprovalController::class, 'approve_price']);

    /**RD report */
    Route::get('/rdStockreport/{search}', [RDStockWithoutReportController::class, 'rdStockreport']);
    Route::get('/rdStockreportWithFree/{search}', [RDStockWithReportController::class, 'rdStockreport']);

    
    /**Branchwise Stock report */
    Route::get('/branchwiseStockReport/{search}',[BranchwiseStockReportController::class,'branchwiseStockReport']);


    /**sample dispatche */
    Route::get('/sample_dispatch_list', function () {
        return view('sc::sample_dispatch_list');
    })->middleware(['is.logged','can:sc_sample_dispatch']);

    Route::get('/sample_dispatch', function () {
        return view('sc::sample_dispatch');
    })->middleware(['is.logged']);

    Route::get('/sample_dispatch_view',function(){
        return view('sc::sample_dispatch_view');
    })->middleware(['is.logged']);

    Route::post('/addSample_dispatch',[SampleDispatchController::class,'addSampleDispatch']);
    Route::get('/get_sample_dispatch',[SampleDispatchController::class,'get_sample_dispatch']);
    Route::get('/get_each_sample_dispatch/{id}',[SampleDispatchController::class,'get_each_sample_dispatch']);


    // Stock Adjustment
    Route::get('/stock_adjustment_list', function () {
        return view('sc::stock_adjustment_list');
    })->middleware(['is.logged','can:sc_stock_adjustment']);

    Route::get('/stock_adjustment', function () {
        return view('sc::stock_adjustment');
    })->middleware(['is.logged','can:sc_stock_adjustment']);

    Route::get('/stoch_adjustment_view', function () {
        return view('sc::stock_adjustment_view');
    });

    Route::post('/addstockadjustment', [StockAdjustmentController::class, 'addstockadjustment']);
    Route::get('/getstock_adjustmentdata', [StockAdjustmentController::class, 'getstock_adjustmentdata']);
    Route::get('/getstock_adjustment/{id}', [StockAdjustmentController::class, 'getstock_adjustment']);
    Route::get('/getstock_adjustmentitem/{id}', [StockAdjustmentController::class, 'getstock_adjustmentitem']);
    Route::delete('/delete_stock_adjustment/{id}', [StockAdjustmentController::class, 'delete_stock_adjustment']);
    Route::get('/get_each_adjustment/{id}',[StockAdjustmentController::class,'get_each_adjustment']);


    /**Dispatch to branch */
    Route::get('/dispatch_to_branch',function(){
        return view('sc::dispatch_to_branch');
    })->middleware(['is.logged','can:sc_division_transfer_entry']);

    Route::get('/dispatch_to_branch_list',function(){
        return view('sc::dispatch_to_branch_list');
    })->middleware(['is.logged','can:sc_division_transfer_entry']);

    Route::get('/dispatch_to_branch_view',function(){
        return view('sc::dispatch_to_branch_view');
    });
    
    Route::post('/add_dispatch_to_branch/{order_id}',[DispatchToBranchController::class,'add_dispatch_to_branch']);
    Route::get('/get_dispatch_list',[DispatchToBranchController::class,'get_dispatch_list']);
    Route::get('/load_dispatch_items_view/{id}',[DispatchToBranchController::class,'load_dispatch_items_view']);
    Route::get('/getItemInfotodivisiontransferentry/{branch}/{item}/{location}/{to_branch}/{to_location}',[DispatchToBranchController::class,'getItemInfotodivisiontransferentry']);
    Route::get('/loadOrderItems/{id}/{branch}/{location}',[DispatchToBranchController::class,'loadOrderItems']);
    Route::get('/getItemsFordispatchtable/{branch_id}/{location_id}/{order_id}/{to_branch}/{to_location}',[DispatchToBranchController::class,'getItemsFordispatchtable']);
    Route::post('/reject_internal_Order/{order_id}',[DispatchToBranchController::class,'reject_internal_Order']);

    /** dispatch receive */
    Route::get('/dispatch_receive',function(){
        return view('sc::dispatch_receive');
    });
    Route::get('/dispatch_receive_list_view',function(){
        return view('sc::dispatch_receive_list');
    })->middleware(['is.logged','can:sc_division_transfer_confirmation']);
    Route::get('/dispatch_receive_view',function(){
        return view('sc::dispatch_receive_view');
    });
    Route::get('/get_dispatches/{branch}/{location}',[DispatchToBranchController::class,'get_dispatches']);
    Route::get('/get_dispatch_items/{id}',[DispatchToBranchController::class,'get_dispatch_items']);
    Route::get('/load_dispatch_items/{id}',[DispatchToBranchController::class,'load_dispatch_items']);
    Route::post('/receive_dispatch/{id}/{br}/{loc}',[DispatchToBranchController::class,'receive_dispatch']);
    Route::get('/dispatch_receive_list',[DispatchToBranchController::class,'dispatch_receive_list']);
    Route::get('/load_dispatch_receive_items_view/{id}',[DispatchToBranchController::class,'load_dispatch_receive_items_view']);
    Route::get('/loadinternalOrders/{from_br}/{to_branch}',[DispatchToBranchController::class,'loadinternalOrders']);




    /**reverse devision transfer */
    Route::get('/reverse_devision_transfer',function(){
        return view('sc::reverse_devision_transfer');
    });
    Route::get('/get_dispatches_to_reverse_devision_model/{branch}/{location}',[ReverseDevisionTransferController::class,'get_dispatches_to_reverse_devision_model']);
    Route::get('/get_dispatch_items_to_reverse/{id}',[ReverseDevisionTransferController::class,'get_dispatch_items_to_reverse']);
    Route::get('/load_dispatch_items_for_reverse/{id}',[ReverseDevisionTransferController::class,'load_dispatch_items_for_reverse']);
    Route::post('/reverse_dispatch/{dispatch_id}',[ReverseDevisionTransferController::class,'reverse_dispatch']);
    Route::get('/get_revese_division_transfer/{id}',[ReverseDevisionTransferController::class,'get_revese_division_transfer']);
    Route::get('/reverse_trasnfer_approval_list',function(){
        return view('sc::reverser_transfer_approval_list');
    })->middleware(['is.logged','can:sc_reverse_division_transfer_approval']);
    Route::get('/reverse_trasnfer_list',function(){
        return view('sc::reverse_division_transfer_list');
    })->middleware(['is.logged','can:sc_reverse_division_transfer']);
    Route::get('/get_pending_reverse_trasfers',[ReverseDevisionTransferController::class,'get_pending_reverse_trasfers']);
    Route::post('/approval_request/{id}/{type}',[ReverseDevisionTransferController::class,'approval_request']);
    Route::get('/get_all_reverse_trasfers',[ReverseDevisionTransferController::class,'get_all_reverse_trasfers']);

    /**Transfer Shortage */
    Route::get('/transfer_shortage_list',function(){
        return view('sc::transfer_shortage_list');
    })->middleware(['is.logged','can:sc_divisional_transfer_shortage']);


    Route::get('/get_transfer_shortages',[DispatchToBranchController::class,'get_transfer_shortages']);
    Route::get('/getBranches',[DispatchToBranchController::class,'getBranches']);
    /**Internal orders */
    Route::get('/internal_orders',function(){
        return view('sc::internal_orders');
    });

    Route::get('/internal_orders_list',function(){
        return view('sc::internal_order_list');
    })->middleware(['is.logged','can:sc_internal_orders']);
    Route::get('/internal_order_view',function(){
        return view('sc::internal_orders_view');
    });
    Route::get('/getItemInfo_internal_order/{item_id}/{from_branch}/{to_branch}',[InternalOrderController::class,'getItemInfo_internal_order']);
    Route::post('/addInternalOrders',[InternalOrderController::class,'addInternalOrders']);
    Route::get('/get_internal_orders',[InternalOrderController::class,'get_internal_orders']);
    Route::get('/getEachInternalOrder/{id}',[InternalOrderController::class,'getEachInternalOrder']);
    Route::get('/load_supply_group',[InternalOrderController::class,'load_supply_group']);
    Route::Get('/load_supply_group_item/{id}/{from}/{to}',[InternalOrderController::class,'load_supply_group_item']);
    Route::get('/loadSelectedItems/{from}/{to}',[InternalOrderController::class,'loadSelectedItems']);

    /**stock balance batch wise */
    Route::get('/stock_balance_batch_wise',function(){
        return view('sc::stock_balance_batch_wise');
    })->middleware(['is.logged','can:sc_stock_balance_batch_wise']);

    Route::get('/get_filter_data', [UpdateBatchPriceController::class, 'get_filter_data']);
    Route::get('/getBatchData/{filters}', [UpdateBatchPriceController::class, 'getBatchData']);
    Route::put('/updateBatchPrice/{item_setoff_id}', [UpdateBatchPriceController::class, 'updateBatchPrice']);

    /**Customer transaction allocation */
    Route::get('/loadUsers',[TrancationAllocationReportController::class,'loadUsers']);
    Route::get('/customer_transaction_allocation_report/{filters}',[TrancationAllocationReportController::class,'customer_transaction_allocation_report']);
});

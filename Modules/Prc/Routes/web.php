<?php
use Illuminate\Support\Facades\Route;
use Modules\Prc\Http\Controllers\BonusClaimController;
use Modules\Prc\Http\Controllers\BonusClaimReportController;
use Modules\Prc\Http\Controllers\GoodReceivedController;
use Modules\Prc\Http\Controllers\GoodReceivedReportController;
use Modules\Prc\Http\Controllers\GoodReceivedReturnController;
use Modules\Prc\Http\Controllers\GoodsReceiveSummeryReportController;
use Modules\Prc\Http\Controllers\GoodsReturnReportController;
use Modules\Prc\Http\Controllers\GoodsReturnSummeryReportController;
use Modules\Prc\Http\Controllers\PoHelpReportController;
use Modules\Prc\Http\Controllers\PurchaseOrderController;
use Modules\Prc\Http\Controllers\PurchaseOrderReporController;
use Modules\Prc\Http\Controllers\PurchaseRequestController;
use Modules\Prc\Http\Controllers\SalesInvoiceController;

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

Route::prefix('prc')->middleware(['is.logged'])->group(function() {
    Route::get('/', function () {
        return view('prc::dashboard');
    })->middleware('is.logged');

    /**Purchasing request */
    Route::get('/purchaseRequest',function(){
        return view('prc::purchase_request');
    })->middleware('is.logged');
    route::get('/getBranches_view',[PurchaseRequestController::class,'getBranches_view']);

    Route::get('/getLocation/{id}',[PurchaseRequestController::class,'getLocation']);
    Route::post('/addPurchaseRequest/{id}',[PurchaseRequestController::class,'addPurchaseRequest']);
    Route::Get('/loadItems',[PurchaseRequestController::class,'loadItems']);
    Route::get('/getItemInfo/{item_id}',[PurchaseRequestController::class,'getItemInfo']);
    Route::post('/addPurchaseRequestDraft',[PurchaseRequestController::class,'addPurchaseRequestDraft']);// purchase request draft
    Route::get('/purchaseRequestList',function(){
        return view('prc::purchase_request_List');
    });

    Route::get('/getPurchaseReuqestData',[PurchaseRequestController::class,'getPurchaseReuqestData']);
    Route::get('/getEachPurchasingOrder/{id}/{status}',[PurchaseRequestController::class,'getEachPurchasingOrder']);
    Route::get('/getEachproduct/{id}/{status}',[PurchaseRequestController::class,'getEachproduct']);
    Route::delete('/deletePurhcaseRetqest/{id}/{status}',[PurchaseRequestController::class,'deletePurhcaseRetqest']);
    Route::get('/getEachOther/{id}/{status}',[PurchaseRequestController::class,'getEachOther']);
    Route::post('/updatePurchaserequestPermenet/{id}',[PurchaseRequestController::class,'updatePurchaserequestPermenet']);
    Route::post('/updatePurchaserequestDraft/{id}',[PurchaseRequestController::class,'updatePurchaserequestDraft']);
   
     Route::get('/purchaseReuqestApprovalList',function(){
        return view('prc::Purhcase_request_approval_list');
     });
    Route::get('/getPendingapprovals',[PurchaseRequestController::class,'getPendingapprovals']);
    Route::post('/approveRequest/{id}',[PurchaseRequestController::class,'approveRequest']);
    Route::post('/rejectRequest/{id}',[PurchaseRequestController::class,'rejectRequest']);
    Route::get('/getRequestDataForRPT/{id}',[PurchaseRequestController::class,'getRequestDataForRPT']);


    /**GRN */
    Route::get('/goodReciveNote',function(){
        return view('prc::good_recive_note');
    });
    Route::get('/loadPamentType',[GoodReceivedController::class,'loadPamentType']);
    Route::get('/loadSupplierTochooser',[GoodReceivedController::class,'loadSupplierTochooser']);
    Route::get('/loadSupplierOtherDetails/{id}',[GoodReceivedController::class,'loadSupplierOtherDetails']);
    Route::post('/addGRN/{id}',[GoodReceivedController::class,'addGRN']);
    Route::post('/addGRNDraft',[GoodReceivedController::class,'addGRNDraft']);
    Route::get('/grnList',function(){
        return view('prc::GRNlist');
    })->middleware(['is.logged','can:prc_goods_receive']);
    Route::get('/getGRNdata',[GoodReceivedController::class,'getGRNdata']);
    Route::delete('/deleteGRN/{id}/{status}',[GoodReceivedController::class,'deleteGRN']);
    Route::get('/getEachGRN/{id}/{status}',[GoodReceivedController::class,'getEachGRN']);
    Route::get('/getEachproductofGRN/{id}/{status}',[GoodReceivedController::class,'getEachproductofGRN']);
    Route::post('/updateGRN/{id}',[GoodReceivedController::class,'updateGRN']);
    Route::post('/updateGRNDraft/{id}',[GoodReceivedController::class,'updateGRNDraft']);
    Route::get('/getPendingapprovalsGRN',[GoodReceivedController::class,'getPendingapprovalsGRN']);
    Route::get('/GRNapprovalList',function(){
        return view('prc::good_recive_note_approval');
    })->middleware(['is.logged','can:prc_goods_received_approval']);
    Route::post('/approveRequestGRN/{id}',[GoodReceivedController::class,'approveRequestGRN']);
    Route::post('/rejectRequestGRN/{id}',[GoodReceivedController::class,'rejectRequestGRN']);
    Route::get('/getServerTime',[GoodReceivedController::class,'getServerTime']);
    Route::get('/getPendingPurchaseOrder/{branch_id}',[GoodReceivedController::class,'getPendingPurchaseOrder']);
    Route::get('/getorderItems/{id}',[GoodReceivedController::class,'getorderItems']);
    Route::get('/getSelectedItems/{branch_id}/{orderID}',[GoodReceivedController::class,'getSelectedItems']);
    Route::get('/getheaderDetails/{orderID}',[GoodReceivedController::class,'getheaderDetails']);
    Route::post('/completeOrderstatus/{id}',[GoodReceivedController::class,'completeOrderstatus']);
    Route::post('/completeOrderstatus_auto/{id}',[GoodReceivedController::class,'completeOrderstatus_auto']);
   


    /**Good receive return */
    Route::get('/goodReceiveReturn',function(){
        return view('prc::good_receive_note_return');
    });
    Route::get('/goodReceiveReturnList',function(){
        return view('prc::good_receive_return_list');
    })->middleware(['is.logged','can:prc_goods_return']);
    Route::get('/GRRetrunApprovalList',function(){
        return view('prc::good_receive_return_approvalList');
    });
    Route::post('/addGRReturn/{id}',[GoodReceivedReturnController::class,'addGRReturn']);
    Route::post('/addGRReturnDraft',[GoodReceivedReturnController::class,'addGRReturnDraft']);
    Route::get('/getGRRetrundata',[GoodReceivedReturnController::class,'getGRRetrundata']);
    Route::post('/updateGRReturn/{id}',[GoodReceivedReturnController::class,'updateGRReturn']);
    Route::post('/updateGRReturnDraft/{id}',[GoodReceivedReturnController::class,'updateGRReturnDraft']);
    Route::get('/getPendingapprovalsGRReturn',[GoodReceivedReturnController::class,'getPendingapprovalsGRReturn']);
    Route::post('/approveRequestGRReturn/{id}',[GoodReceivedReturnController::class,'approveRequestGRReturn']);
    Route::post('/rejectRequestGRReturn{id}',[GoodReceivedReturnController::class,'rejectRequestGRReturn']);
    Route::get('/loadGRN/{BranchIDforGRN}',[GoodReceivedReturnController::class,'loadGRN']);
    Route::GET('/getGRN_Items/{ID}',[GoodReceivedReturnController::class,'getGRN_Items']);
    Route::get('/get_selectedItem_grnReturn/{branch_id}/{grn_id}/{locationId}',[GoodReceivedReturnController::class,'get_selectedItem_grnReturn']);
    Route::get('/getheaderDetailsReturn/{id}',[GoodReceivedReturnController::class,'getheaderDetailsReturn']);
    Route::get('/getItemHistorySetoffBatch/{branchID}/{itemID}/{location_id}',[GoodReceivedReturnController::class,'getItemHistorySetoffBatch']);
    Route::get('/printGoodReturnReportPdf/{id}',[GoodReceivedReportController::class,'printGoodReturnReportPdf']);
    Route::get('/getItemInfotogrnReturn/{branch_id}/{item_id}/{location_id}',[GoodReceivedReturnController::class,'getItemInfotogrnReturn']); 
    Route::delete('/deleteGR_return/{id}/{status}',[GoodReceivedReturnController::class,'deleteGR_RTN']);
    Route::get('/getEachGR_return/{id}/{status}',[GoodReceivedReturnController::class,'getEachGR_return']);
    Route::get('getEachproductofGR_rtn/{id}',[GoodReceivedReturnController::class,'getEachproductofGR_rtn']);
    Route::get('/getItem_foc_threshold_For_grnRtn/{item_id}/{entered_qty}/{date}',[GoodReceivedReturnController::class,'getItem_foc_threshold_For_goods_returns']); //threshold FOC calculation
    Route::get('/get_Pr_price/{ID}',[GoodReceivedReturnController::class,'get_Pr_price']);
    Route::get('/loadAllLocation/{id}',[GoodReceivedReturnController::class,'loadAllLocation']);
    Route::get('/get_item_details_for_goods_rtn/{item_id}',[GoodReceivedReturnController::class,'get_item_details_for_goods_rtn']);

    //Good Return Report
    Route::get('/printGoodReturnReport/{id}',[GoodsReturnReportController::class,'printGoodReturnReport']);

    /**Purchase order note */
    Route::get('/purchaseOrderNote',function(){
        return view('prc::purchase_order');
    });
    Route::post('/addPurchaseOrder/{id}',[PurchaseOrderController::class,'addPurchaseOrder']);
    Route::get('/getDeliveryTypes',[PurchaseOrderController::class,'getDeliveryTypes']);
    Route::post('/addPurchaseOrderDraft',[PurchaseOrderController::class,'addPurchaseOrderDraft']);
    Route::get('/printpurchaseOrderReportPdf/{id}',[PurchaseOrderReporController::class,'printpurchaseOrderReportPdf']);
    Route::get('/purchaseOrderList',function(){
        return view('prc::purchase_order_note_list');
    })/* ->middleware(['is.logged','can:prc_purchase_order']) */;
    Route::get('/getPOData',[PurchaseOrderController::class,'getPOData']);
    Route::delete('/deletePo/{id}/{status}',[PurchaseOrderController::class,'deletePo']);
    Route::get('/getEachPO/{id}/{status}',[PurchaseOrderController::class,'getEachPO']);
    Route::get('/getEachproductofPO/{id}/{status}',[PurchaseOrderController::class,'getEachproductofPO']);
    Route::post('/updatePO/{id}',[PurchaseOrderController::class,'updatePO']);
    Route::post('/updatePODraft/{id}',[PurchaseOrderController::class,'updatePODraft']);
    Route::get('/purchaseOrderApprovalList',function(){
        return view('prc::purchase_order_note_approvalList');
    })/* ->middleware(['is.logged','can:prc_purchase_order_approval']); */;
    Route::get('/getPendingapprovalsPurchaseOrder',[PurchaseOrderController::class,'getPendingapprovalsPurchaseOrder']);
    Route::post('/approveRequestPO/{id}',[PurchaseOrderController::class,'approveRequestPO']);
    Route::post('/rejectRequestPO/{id}',[PurchaseOrderController::class,'rejectRequestPO']);

    Route::get('/purchaseOrderView',function(){
        return view('prc::purchase_order_view');
    });
    Route::get('/loadItemsPurchaseOrder/{id}',[PurchaseOrderController::class,'loadItems_purchaseOrder']);

    /**reference number */
    

    Route::get('/printGoodResiveReportPdf/{id}',[GoodReceivedReportController::class,'printGoodResiveReportPdf']);


    Route::get('/getviewLocation',[PurchaseRequestController::class,'getviewLocation']);
    Route::get('/goodReciveNoteView',function(){
        return view('prc::goodReciveNote_view');
    });
    Route::get('/goodReceiveReturnview',function(){
        return view('prc::good_receive_note_return_view');
    });

   //Reports
   Route::get('/reports',function(){
    return view('prc::reports');
    });
    Route::get('/poHelpReport/{filters}',[PoHelpReportController::class,'poHelpReport']);
    Route::get('/good_receive_summery_report/{filters}',[GoodsReceiveSummeryReportController::class,'goods_receive_summery_report']);
    Route::get('/goods_return_summery_report/{filters}',[GoodsReturnSummeryReportController::class,'goods_return_summery_report']);

    //bonus claim
    Route::get('/bonus_claim',function(){
        return view('prc::bonus_claim');
    });
    Route::get('/bonus_claim_List',function(){
        return view('prc::bonus_claim_List');
    })->middleware(['is.logged','can:prc_bonus_claim']);
    Route::post('/addbonusClaim/{id}',[BonusClaimController::class,'addbonusClaim']);
    Route::get('/getBonusdata/{id}',[BonusClaimController::class,'getBonusdata']);
    Route::get('/getEacchBonusclaim/{id}/{status}',[BonusClaimController::class,'getEacchBonusclaim']);
    Route::get('/getEachbonusclaimitem/{id}/{status}',[BonusClaimController::class,'getEachbonusclaimitem']);
    
    
    Route::get('/printBonusClaimReportPdf/{id}',[BonusClaimReportController::class,'printBonusClaimReportPdf']);
});


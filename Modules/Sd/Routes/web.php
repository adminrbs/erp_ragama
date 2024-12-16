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

use App\Http\Controllers\ReferenceIdController;
use Illuminate\Support\Facades\Route;
use Modules\Sd\Http\Controllers\DeliveryconfirmationController;
use Modules\Sd\Entities\deliveryconfirmation;
use Modules\Sd\Http\Controllers\AssingrouttosalesrepController;
use Modules\Sd\Http\Controllers\AssingsupplygrouptosalesrepController;
use Modules\Sd\Http\Controllers\CommisionReportController;
use Modules\Sd\Http\Controllers\customerAppuseController;
use Modules\Sd\Http\Controllers\CustomerBlockController;
use Modules\Sd\Http\Controllers\dataController;
use Modules\Sd\Http\Controllers\DeliveryconfirmationController as ControllersDeliveryconfirmationController;
use Modules\Sd\Http\Controllers\DeliveryPlanController;
use Modules\Sd\Http\Controllers\EmployeeToBranchController;
use Modules\Sd\Http\Controllers\EmployeeToCustomerController;
use Modules\Sd\Http\Controllers\freeOfferController;
use Modules\Sd\Http\Controllers\ItemController;
use Modules\Sd\Http\Controllers\PickingListController;
use Modules\Sd\Http\Controllers\RouteController;
use Modules\Sd\Http\Controllers\SalesInvoiceController;

use Modules\Sd\Http\Controllers\SalesinvoiceReportController;
use Modules\Sd\Http\Controllers\salesOrderController;
use Modules\Sd\Http\Controllers\SalesorderReportController;
use Modules\Sd\Http\Controllers\SalesReturnController;
use Modules\Sd\Http\Controllers\SalesReturnReportController;
use Modules\Sd\Http\Controllers\DeliveryreporController;
use Modules\Sd\Http\Controllers\FreeissuedReportController;
use Modules\Sd\Http\Controllers\freeOfferController_latest;
use Modules\Sd\Http\Controllers\InvoiceInfoController;
use Modules\Sd\Http\Controllers\InvoiceInqueryController;
use Modules\Sd\Http\Controllers\MissedSalesOrderController;
use Modules\Sd\Http\Controllers\ProductwisequantitysalestypeReportController;
use Modules\Sd\Http\Controllers\SalesdetailsReportController;
use Modules\Sd\Http\Controllers\SalesRepWiseMonthlySummaryReportController;
use Modules\Sd\Http\Controllers\SalesreturndetailsReportController;
use Modules\Sd\Http\Controllers\SalesreturnlibraryreportController;
use Modules\Sd\Http\Controllers\SalessummaryController;
use Modules\Sd\Http\Controllers\SpecialBonusController;
use Modules\Sd\Http\Controllers\ItemCustomerReportController;
use Modules\Sd\Http\Controllers\FreeSummaryReportController;
use Modules\Sd\Http\Controllers\InvoiceTrackingController;
use Modules\Sd\Http\Controllers\MergeOrdersController;
use Modules\Sd\Http\Controllers\MonthendSalesSummeryReportController;
use Modules\Sd\Http\Controllers\OrdersController;
use Modules\Sd\Http\Controllers\PendingOrderController;
use Modules\Sd\Http\Controllers\SalesInvoiceCoppyIssuedController;

Route::prefix('sd')->middleware(['is.logged'])->group(function () {


    Route::get('/', function () {
        return view('sd::dashboard');
    })->middleware('is.logged');




    /** Sales Order */

    /** End Of Sales Order */




    /** Free Offer */

    /* Route::get('/freeOfferView', function () {
        return view('sd::freeOffer');
    })->middleware('is.logged'); */

   /*  Route::get('/freeOfferView', function () {
        return view('sd::freeOffer');
    })->middleware('is.logged'); */

    Route::get('/freeOfferView', function () {
        return view('sd::freeOfferView');
    })->middleware('is.logged');

   /*  Route::get('/freeOfferCreateNewView', function () {
        return view('sd::freeOfferNew');
    })->middleware('is.logged');
 */

    Route::get('/freeOfferCreateNewView', function () {
        return view('sd::free_offer_modified');
    })->middleware(['is.logged','can:sd_free_offer']);
 

    

    Route::post('/addFreeOffer', [freeOfferController::class, 'addFreeOffer']);
   

    Route::get('/getAllOffers', [freeOfferController::class, 'getAllOffers']);
    Route::delete('/deleteOffer/{id}', [freeOfferController::class, 'deleteOffer']);
    route::get('/getEachOfferData/{id}', [freeOfferController::class, 'getEachOfferData']);
    route::post('/updateOffer/{id}', [freeOfferController::class, 'updateOffer']);
    route::get('/getIemstocmb', [ItemController::class, 'getItemDetails']);
    Route::post('/addOfferData/{id}', [freeOfferController::class, 'addOfferData']);
    Route::get('/getAllofferData/{id}', [freeOfferController::class, 'getAllofferData']);
    Route::delete('/deleteOfferData/{id}', [freeOfferController::class, 'deleteOfferData']);
    Route::get('/getEachOfferDataDetails/{id}', [freeOfferController::class, 'getEachOfferDataDetails']);
    Route::post('/updateOfferData/{id}/{offer_id}', [freeOfferController::class, 'updateOfferData']);
    Route::post('/addThreshold', [freeofferController::class, 'addThreshold']);
    Route::post('/addThreshold_modal/{id}', [freeofferController::class, 'addThreshold_modal']);
    Route::get('/getallthresholds/{id}', [freeofferController::class, 'getallthresholds']);
    Route::get('/geteachThreshold/{id}', [freeOfferController::class, 'geteachThreshold']);
    Route::post('/updateThresholdData/{id}', [freeOfferController::class, 'updateThresholdData']);
    Route::delete('/deleteThresholdData/{id}', [freeOfferController::class, 'deleteThresholdData']);
    Route::post('/addRange', [freeOfferController::class, 'addRange']);
    Route::post('/addRange_modal/{id}',[freeOfferController::class,'addRange_modal']);
    Route::get('/GetRangeData/{id}', [freeOfferController::class, 'GetRangeData']);
    Route::get('/getEachRangeData/{id}', [freeOfferController::class, 'getEachRangeData']);
    Route::post('/updateRangeData/{id}', [freeOfferController::class, 'updateRangeData']);
    Route::delete('/deleteRange/{id}', [freeOfferController::class, 'deleteRange']);

    //get added offer
    Route::get('/getAddedOffers/{id}',[freeOfferController::class,'getAddedOffers']);

    //get added offer data
    Route::get('/getNewAddedofferData/{id}',[freeOfferController::class,'getNewAddedofferData']);

    //get threshold data to update (trasaction table)
    Route::get('/getthreshold_data/{id}',[freeOfferController::class,'getthreshold_data']);
    Route::post('/updateNewThreshold',[freeOfferController::class,'updateNewThreshold']);

    
 

    //get added threshold
    Route::get('/getaddedthresholds/{id}',[freeOfferController::class,'getaddedthresholds']);

    //get added range
    Route::get('/GetaddedRangeData/{id}',[freeOfferController::class,'GetaddedRangeData']);

    //add offer data with supply group
    Route::post('/addOfferDatawithSupplyGroup/{id}',[freeOfferController::class,'addOfferDatawithSupplyGroup']);

    //delete offer data with check box
    Route::post('/deleteSelectedOfferData',[freeOfferController::class,'deleteSelectedOfferData']);

    //applying to
    Route::get('/getOptions/{filterBy}', [freeOfferController::class, 'getOptions']);
    Route::post('/addApplyTo/{id}', [freeOfferController::class, 'addApplyTo']);
    Route::get('/getAllOfferCustomerSData/{id}', [freeOfferController::class, 'getAllOfferCustomerSData']);
    Route::delete('/deleteofferCustomer', [freeOfferController::class, 'deleteofferCustomer']);
    Route::get('/getAllOfferLocationData/{id}', [freeOfferController::class, 'getAllOfferLocationData']);
    Route::delete('/deleteOfferLocation', [freeOfferController::class, 'deleteOfferLocation']);
    Route::get('/getAllCustomerGradeOfferData/{id}', [freeOfferController::class, 'getAllCustomerGradeOfferData']);
    Route::delete('/deleteOfferCusGrade', [freeOfferController::class, 'deleteOfferCusGrade']);
    Route::get('/getAllCustomerGroupOfferData/{id}', [freeOfferController::class, 'getAllCustomerGroupOfferData']);
    Route::delete('/DeleteOfferCusGroup', [freeOfferController::class, 'DeleteOfferCusGroup']);

    Route::get('/getSupllyGroup',[freeOfferController::class,'getSupllyGroup']);
    Route::get('/getItemsForSupGRP/{id}',[freeOfferController::class,'getItemsForSupGRP']);
    Route::get('/loadItems_freeOffer',[freeOfferController_latest::class,'loadItems_freeOffer']);
    Route::get('/freeOfferListView',function(){
        return view('sd::freeofferList');
    })->middleware(['is.logged','can:sd_free_offer']);
   Route::post('/filterOffers',[freeOfferController::class,'filterOffers']); //post method used to get data
   Route::Get('/getSupllyGroupToOfferList',[freeOfferController::class,'getSupllyGroupToOfferList']);

   Route::post('/checkThresholExist',[freeOfferController::class,'checkThresholExist']);


   /**free offer modified */
   Route::post('/addFreeOffer_latest', [freeOfferController_latest::class, 'addFreeOffer']);
   Route::post('/update_free_offer_new/{id}', [freeOfferController_latest::class, 'update_free_offer_new']);
   Route::get('/get_offer_data_apply_to/{type}',[freeOfferController_latest::class,'get_offer_data_apply_to']);
   Route::get('/load_selected_customers',[freeOfferController_latest::class,'load_selected_customers']);
   Route::get('/load_grp_customers',[freeOfferController_latest::class,'load_grp_customers']);
   Route::get('/get_each_offer/{id}',[freeOfferController_latest::class,'get_each_offer']);
   Route::get('/load_selected_groups',[freeOfferController_latest::class,'load_selected_groups']);


    /** End Of Free Offer */

    /** Assign cusotmer to employees */
    Route::get('/employeeCustomerView', function () {
        return view('sd::employee_customer');
    })->middleware(['is.logged','can:st_assign_customer_to_sales_rep']);
    Route::get('/getEmployee', [EmployeeToCustomerController::class, 'getEmployees']);
    Route::post('/getCustomerDataTOlistbox/{id}', [EmployeeToCustomerController::class, 'getFilterData']); // same for employee cystomer
    Route::get('/getEmployeeCustomerDetails', [EmployeeToCustomerController::class, 'getEmployeeCustomerDetails']);
    Route::post('/addEmployeeCustomer', [EmployeeToCustomerController::class, 'addEmployeeCustomer']);
    Route::delete('/deleteEmployeeCustomer', [EmployeeToCustomerController::class, 'deleteEmployeeCustomer']);
    Route::get('/getselectemployee/{id}', [EmployeeToCustomerController::class, 'getselectuser']);
    Route::post('/selectdeletuserBranch', [EmployeeToCustomerController::class, 'selectdeletuserBranch']);
    Route::Get('/getRoute_customers/{id}', [EmployeeToCustomerController::class, 'getRoute_customers']);
    Route::Get('/getDeliveryRoutesTofilter', [EmployeeToCustomerController::class, 'getRoutes']);
    /**End of assign customer to employees */

    /**assign sup frp to sales rep */
    Route::get('/assignsupplygrouptoSalesrep', function () {
        return view('sd::assign_supply_group_to_salesreps');
    })->middleware(['is.logged','can:st_supply_group_to_sales_rep']);
    Route::post('/getsupplygrouplistbox/{id}', [AssingsupplygrouptosalesrepController::class, 'getFilterData']); 
    Route::get('/getsalesrep', [AssingsupplygrouptosalesrepController::class, 'getsalesrep']);
    Route::post('/addsupplygrouptoSalesrep', [AssingsupplygrouptosalesrepController::class, 'addsupplygrouptoSalesrep']);
    Route::get('/getselectsupplygroup/{id}',[AssingsupplygrouptosalesrepController::class,'getselectsupplygroup']);
    Route::get('/getsupplygrouptosalesrepDteails', [AssingsupplygrouptosalesrepController::class, 'getsupplygrouptosalesrepDteail']);
    Route::delete('/deletesupplygrouptosalesrep', [AssingsupplygrouptosalesrepController::class, 'deletesupplygrouptosalesrep']);
    Route::post('/selectdeletsupplygroupsalesrep',[AssingsupplygrouptosalesrepController::class,'selectdeletsupplygroupsalesrep']);

    /**assign route to sales rep */
    Route::get('/assignrouteSalesrep', function () {
        return view('sd::assign _route _to_sales_reps');
    })->middleware(['is.logged','can:st_assign_route_to_sales_rep']);
    Route::post('/getroutelistbox/{id}', [AssingrouttosalesrepController::class, 'getFilterData']); 
    //Route::get('/getsalesrep', [AssingsupplygrouptosalesrepController::class, 'getsalesrep']);
    Route::post('/addroutetoSalesrep', [AssingrouttosalesrepController::class, 'addroutetoSalesrep']);
    Route::get('/getselectroute/{id}',[AssingrouttosalesrepController::class,'getselectroute']);
    Route::get('/getroutetosalesrepDteails', [AssingrouttosalesrepController::class, 'getroutetosalesrepDteails']);
    Route::delete('/deleteroutetosalesrep', [AssingrouttosalesrepController::class, 'deleteroutetosalesrep']);
    Route::post('/selectdeletroutesalesrep',[AssingrouttosalesrepController::class,'selectdeletroutesalesrep']);
    Route::post('/genewtsalesrep',[AssingrouttosalesrepController::class,'genewtsalesrep']);

    /**customer Appuser*/

    Route::get('/customer_Appuser', function () {
        return view('sd::customerAppuser');
    })->middleware(['is.logged','can:sd_customer_app_user']);
    Route::get('/customeruserApp', [customerAppuseController::class, 'customeruserApp']);
    Route::post('/saveCustomeerUserapp', [customerAppuseController::class, 'savecustomerUserApp']);
    Route::get('/customerEdit/{id}', [customerAppuseController::class, 'customerEdit']);
    Route::post('/customerAppUpdate/{id}', [customerAppuseController::class, 'customerAppUpdate']);
    Route::get('/customerAppSearch', [customerAppuseController::class, 'customerAppsearch']);
    Route::post('/customerAppStatus/{id}', [customerAppuseController::class, 'customerAppStatus']);
    Route::delete('/deletecustomerApp/{id}', [customerAppuseController::class, 'deletecustomerApp']);
    Route::get('/customername', [customerAppuseController::class, 'customername']);
    Route::get('/selectCustomer/{id}',[customerAppuseController::class,'selectCustomer']);
    Route::get('/townadmistrative/{id}',[customerAppuseController::class,'townadmistrative']);
    Route::get('/loadBranches_cus_app',[customerAppuseController::class,'loadBranches_cus_app']);


    /**Sales Invoice */
    Route::get('/salesInvoice', function () {
        return view('sd::salesInvoice');
    });
    Route::get('/loadCustomerTOchooser', [SalesInvoiceController::class, 'loadCustomerTOchooser']);
    Route::get('/loadCustomerOtherDetails/{id}', [SalesInvoiceController::class, 'loadCustomerOtherDetails']);
    Route::get('/loadPamentTerm', [SalesInvoiceController::class, 'loadPamentTerm']);

    Route::get('/loademployees', [SalesInvoiceController::class, 'loademployees']);
    Route::get('/loademployeesAccordingToBranch/{branch_id}',[SalesInvoiceController::class,'loademployeesAccordingToBranch']);

    Route::post('/addSalesInvoice/{id}', [SalesInvoiceController::class, 'addSalesInvoice']);
    Route::post('/addSalesInvoiceDraft', [SalesInvoiceController::class, 'addSalesInvoiceDraft']);
    Route::get('/getSalesInvoiceData', [SalesInvoiceController::class, 'getSalesInvoiceData']);
    Route::get('/salesInvoiceList', function () {
        return view('sd::salesInvoiceList');
    }) ->middleware(['is.logged','can:sd_sales_invoice']);
    Route::delete('/deleteSI/{id}/{status}', [SalesInvoiceController::class, 'deleteSI']);
    Route::get('/getEachSalesInvoice/{id}/{status}', [SalesInvoiceController::class, 'getEachSalesInvoice']);
    Route::get('/getEachproductofSalesInv/{id}/{status}', [SalesInvoiceController::class, 'getEachproductofSalesInv']);
    Route::post('/updateSalesInvoice/{id}', [SalesInvoiceController::class, 'updateSalesInvoice']);
    Route::post('/updateSalesInvoiceDraft/{id}', [SalesInvoiceController::class, 'updateSalesInvoiceDraft']);
    Route::get('/getPendingapprovalsSalesInv', [SalesInvoiceController::class, 'getPendingapprovalsSalesInv']);
    Route::get('/getSalesInvoiceApprovalList', function () {
        return view('sd::salesInvoiceApprovalList');
    });
    Route::post('/approveRequestSalesInv/{id}', [SalesInvoiceController::class, 'approveRequestSalesInv']);
    Route::post('/rejectRequestSalesInv/{id}', [SalesInvoiceController::class, 'rejectRequestSalesInv']);
    Route::get('/getItemInfoForInvoice/{item_id}/{item_branch}/{item_location_id}', [SalesInvoiceController::class, 'getItemInfoFoSI']); 
    
    Route::get('/getItemHistorySetoffBatch/{branch_id}/{item_id}/{location_id}',[SalesInvoiceController::class,'getItemHistorySetoffBatch']);

    Route::get('/getItemsForIncoiceTotable/{branch_id}/{orderID}/{date}/{locationId}',[SalesInvoiceController::class,'getItemsForIncoiceTotable']);
    Route::get('/getPaymentMethods',[SalesInvoiceController::class,'getPaymentMethods']);
    Route::get('/getItem_foc_threshold_ForInvoice/{cus_id}/{item_id}/{entered_qty}/{date}',[SalesInvoiceController::class,'getItem_foc_threshold_ForInvoice']); //threshold FOC calculation
    Route::get('/get_rep_code/{id}',[SalesInvoiceController::class,'get_rep_code']);
    Route::get('/get_branch_code/{id}',[SalesInvoiceController::class,'get_branch_code']);
    Route::get('/loadItemsforsalesinvoice/{id}',[SalesInvoiceController::class,'loadItemsforsalesinvoice']);    
    Route::get('/loadSalesReturns/{customerID}',[SalesInvoiceController::class,'loadSalesReturns']);

    /**invoice re-print */
    Route::get('/invoice_reprint',function(){
        return view('sd::sales_invoice_reprint_approval');
    });
    Route::get('/load_invoice_details_reprint/{number}',[SalesInvoiceController::class,'load_invoice_details_reprint']);
    Route::post('/allowReportin/{id}',[SalesInvoiceController::class,'allowReportin']);
    Route::get('/get_reprint_request',[SalesInvoiceController::class,'get_reprint_request']);

    Route::get('/invoice_reprint_approval_list',function(){
        return view('sd::sales_invoice_reprint_list');
    });
    Route::post('/approve_request/{id}',[SalesInvoiceController::class,'approve_request']);
    Route::Post('/reject_request/{id}',[SalesInvoiceController::class,'reject_request']);
    

    //for invoice//
    Route::get('/getSalesOrderDetailsForInvice/{branchID}',[SalesInvoiceController::class,'getSalesOrderDetailsForInvice']);
    ROute::get('/getorderItems/{id}/{date}/{cus}/{branch}/{location}',[SalesInvoiceController::class,'getorderItems']);

    //header details
    Route::get('/getheaderDetails/{id}',[SalesInvoiceController::class,'getheaderDetails']);
    /**sales order */
    Route::get('/getSalesOrderList', function () {
        return view('sd::sales_oder_list');
    })->middleware(['is.logged','can:sd_sales_order']);
    Route::get('/getSalesOrderDetails', [salesOrderController::class, 'getSalesOrderDetails']);
    Route::get('/salesOrder', function () {
        return view('sd::salesOrder');
    });
    Route::get('/getLocation{id}', [salesOrderController::class, 'getLocation']);
    Route::get('/getDeliveryTypes', [salesOrderController::class, 'getDeliveryTypes']);
    Route::get('/getServerTime', [salesOrderController::class, 'getServerTime']);
    Route::get('/loadItems', [salesOrderController::class, 'loadItems']); // need to edit
    Route::post('/addSalesOrder/{id}', [salesOrderController::class, 'addSalesOrder']);
    Route::get('/getPaymentTerm', [salesOrderController::class, 'getPaymentTerm']);
    Route::post('/addSalesOderDraft', [salesOrderController::class, 'addSalesOderDraft']);
    Route::delete('/deleteSO/{id}/{status}', [salesOrderController::class, 'deleteSO']);
    Route::get('/getEachSalesOrder/{id}/{status}', [salesOrderController::class, 'getEachSalesOrder']);
    Route::get('/getEachproductofSalesOder/{id}/{status}', [salesOrderController::class, 'getEachproductofSalesOder']);
    Route::post('/updateSalesOrder/{id}', [salesOrderController::class, 'updateSalesOrder']);
    Route::post('/updateSalesOrderDraft/{id}', [salesOrderController::class, 'updateSalesOrderDraft']);
    Route::get('/salesOrderApprovalList', function () {
        return view('sd::salesOrderApprovalList');
    });
    Route::get('/getSalesOrderPendingApprovals', [salesOrderController::class, 'getSalesOrderPendingApprovals']);
    Route::post('/approveSalesOrder/{id}', [salesOrderController::class, 'approveSalesOrder']);
    Route::post('/rejectSalesOrder/{id}', [salesOrderController::class, 'rejectSalesOrder']);
    Route::get('/getItemInfo/{item_id}', [salesOrderController::class, 'getItemInfo']);
    Route::get('/getItemInfo_sales_order/{item_id}', [salesOrderController::class, 'getItemInfo_sales_order']);
    Route::post('/rejectSalesOrderForInvocie/{id}',[salesOrderController::class,'rejectSalesOrderForInvocie']);
    Route::get('/checkSalesOrderType/{val}',[salesOrderController::class,'checkSalesOrderType']);
    Route::get('/order_check_status/{val}',[salesOrderController::class,'isAPproved']);
    
    
      //update status of order when Invoice is created
      Route::post('/updateStatusOfOrder/{id}',[salesOrderController::class,'updateStatusOfOrder']);

      Route::get('/printSalesOrderReport/{id}',[SalesorderReportController::class,'printSalesOrderReport']);


    /**sales return */
    Route::get('/salesReturn', function () {
        return view('sd::salesInvoiceReturn');
    });
    Route::post('/addSalesReturn/{id}', [SalesReturnController::class, 'addSalesReturn']);
    Route::post('/addSalesReturnDraft', [SalesReturnController::class, 'addSalesReturnDraft']);
    Route::get('/getSalesInvoiceReturnData/{id}', [SalesReturnController::class, 'getSalesInvoiceReturnData']);
    Route::get('/salesReturnList', function () {
        return view('sd::salesInvoiceReturnList');
    })->middleware(['is.logged','can:sd_sales_return']);
    Route::post('/updateReturn/{id}',[SalesReturnController::class,'updateReturn']);
    Route::post('/updateSalesReturnDraft/{id}',[SalesReturnController::class,'updateSalesReturnDraft']);
    Route::get('/getPendingapprovalsSalesInvReturn',[SalesReturnController::class,'getPendingapprovalsSalesInvReturn']);
    Route::get('/salesInvoiceRetuyrnApprovalLIst',function(){
        return view('sd::salesReturnApprovalList');
    });
    Route::post('/approveRequestSalesInvReturn/{id}',[SalesReturnController::class,'approveRequestSalesInvReturn']);
    Route::post('/rejectRequestSalesInvReturn/{id}',[SalesReturnController::class,'RejectRequestSalesInvReturn']);
    Route::get('/loadReason',[SalesReturnController::class,'loadReason']);
    Route::get('/loadCustomers',[SalesReturnController::class,'loadCustomers']);
    Route::get('/getMonthDates',[SalesReturnController::class,'getMonthDates']);
    Route::get('/loademployeesInModel',[SalesReturnController::class,'loademployeesInModel']);
    Route::post('/getInvoicesForReturn/{id}',[SalesReturnController::class,'getInvoicesForReturn']);
    Route::get('/getInvoiceItems/{id}',[SalesReturnController::class,'getInvoiceItems']);
    Route::get('/getInvoiceItemsToreturnTable/{branchID}/{InvoiceID}/{path}',[SalesReturnController::class,'getInvoiceItemsToreturnTable']);
    Route::get('/getHeaderDetailsForInvoiceReturn/{id}/{path}',[SalesReturnController::class,'getHeaderDetailsForInvoiceReturn']);
    Route::get('/printsalesReturnPdf/{id}', [SalesReturnReportController::class,'printsalesReturnPdf']);
    Route::get('/getEachSalesReturn/{id}/{status}', [SalesReturnController::class, 'getEachSalesReturn']);
    Route::get('/getEachproductofSalesReturn/{id}/{status}', [SalesReturnController::class, 'getEachproductofSalesReturn']);
    Route::get('/setPrice/{id}/{price}/{brn_id}',[SalesReturnController::class, 'setPrice']);
    Route::get('/getItem_foc_threshold_For_sales_return/{item_id}/{entered_qty}/{date}',[SalesReturnController::class,'getItem_foc_threshold_For_sales_returns']); //threshold FOC calculation
    Route::get('/checkReturnLocation/{br_id_}',[SalesReturnController::class,'checkReturnLocation']);
    Route::get('/loadBookNumber',[SalesReturnController::class,'loadBookNumber']);
    Route::get('/load_setoff_data_/{id}',[SalesReturnController::class,'load_setoff_data_']);
    Route::get('/load_setoff_data_invoice/{id}',[SalesReturnController::class,'load_setoff_data_invoice']);
    Route::get('/loadReturnSetoffData/{id}',[SalesReturnController::class,'loadReturnSetoffData']);
    Route::get('get_returned_items_details/{id}/{item_id}',[SalesReturnController::class,'get_returned_items_details']);
    Route::get('/return_getItemInfo/{item_id}', [SalesReturnController::class, 'getItemInfo']);
    Route::get('/get_wh_price_with_rt_price',[SalesReturnController::class,'get_wh_price_with_rt_price']);
    Route::get('/getEachSetOffSalesReturn/{id}',[SalesReturnController::class,'getEachSetOffSalesReturn']);
    /**sales return details */
    Route::get('/sales_return_details',function(){
        return view('sd::return_details');
    })->middleware(['is.logged','can:sd_sales_return_details']);
    Route::post('/update_sales_return_item_status',[SalesReturnController::class,'update_sales_return_item_status']);

    /**Return trnasfer */
    Route::get('/retrun_trnasfer',function(){
        return view('sd::retrun_transfer');
    })->middleware(['is.logged']);
    Route::get('/retrun_trnasfer_list',function(){
        return view('sd::return_transfer_list');
    })->middleware(['is.logged','can:sd_return_transfer_list']);
    Route::get('/get_sales_retrun_details/{branch_id}/{location_id}',[SalesReturnController::class,'get_sales_retrun_details']);
    Route::get('/get_sales_retrun_details_info',[SalesReturnController::class,'get_sales_retrun_details_info']);
    Route::Get('/getLocatiofor_return/{id}',[SalesReturnController::class,'getLocatiofor_return']);

    Route::get('/getReturnItems/{type}',[SalesReturnController::class,'getReturnItems']);
    Route::post('/addReturnTransfer',[SalesReturnController::class,'addReturnTransfer']); 
    Route::get('/getReturnTransfer',[SalesReturnController::class,'getReturnTransfer']);
    Route::get('/getEachReturnTransfer/{id}',[SalesReturnController::class,'getEachReturnTransfer']);
    //Route
    Route::get('/routeList',function(){
        return view('sd::routeList');
    })->middleware(['is.logged','can:md_route_list']);
    Route::post('/addRoute',[RouteController::class,'addRoute']);
    Route::get('/getRoutes',[RouteController::class,'getRoutes']);
    Route::get('/getEachRoute/{id}',[RouteController::class,'getEachRoute']);
    Route::post('/updateRoute/{id}',[RouteController::class,'updateRoute']);
    Route::delete('/deleteRoute/{id}',[RouteController::class,'deleteRoute']);
    Route::get('/load_non_admin_towns/{id}',[RouteController::class,'load_non_admin_towns']);
    Route::post('/add_route_town/{id}',[RouteController::class,'add_route_town']);
    Route::get('/loadSelectedtowns/{id}',[RouteController::class,'loadSelectedtowns']);
    
    Route::get('/salesinvoicereport', function () {
        return view('sd::salesinviceReport');
    })->middleware('is.logged');
    Route::get('/salesinvoiceReportiframe',function(){
        return view('sd::salesinvoiceReportiframe');
        });
    Route::get('/printsalesinvoicePdf/{id}/{status}', [SalesinvoiceReportController::class,'printsalesinvoicePdf']);

   

    



     /** Assign employee to Branch */
     Route::get('/employeBranchView', function () {
        return view('sd::employeBranchView');
    })->middleware(['is.logged','can:st_assign_employee_to_branch']);
    Route::get('/getEmployeeDataTOlistbox/{id}', [EmployeeToBranchController::class, 'getFilterData']); 
    Route::get('/getBranch', [EmployeeToBranchController::class, 'getBranch']);
   
    Route::get('/getEmployeeBranchDetails', [EmployeeToBranchController::class, 'getEmployeeBranchDetails']);
    Route::post('/addEmployeeBranch', [EmployeeToBranchController::class, 'addEmployeeBranch']);
    Route::delete('/deleteEmployeeBranch', [EmployeeToBranchController::class, 'deleteEmployeeBranch']);
    Route::get('/getselectbranch/{id}',[EmployeeToBranchController::class,'getselectbranch']);

    Route::post('/selectdeletemployeeBranch',[EmployeeToBranchController::class,'selectdeletuserBranch']);


  /** Delivery Plan */
/** Delivery Plan */
Route::get('/delivery_plan', function () {
    return  view('sd::delivery_plan');
});
Route::get('/delivery_plan/new_referance_id/{table}/{doc_number}', [ReferenceIdController::class, 'DeliveryPlan_referenceID']);
Route::get('/loadDeliveryPlanSelect2', [DeliveryPlanController::class, 'loadDeliveryPlanSelect2']);
Route::get('/loadLownsSelect2/{district}', [DeliveryPlanController::class, 'loadLownsSelect2']);
Route::post('/saveDeliveryPlan', [DeliveryPlanController::class, 'saveDeliveryPlan']);
Route::get('/getDeliveryPlansDeliverd', [DeliveryPlanController::class, 'getDeliveryPlansDeliverd']);
Route::get('/getDeliveryPlansNoneDeliverd', [DeliveryPlanController::class, 'getDeliveryPlansNoneDeliverd']);
Route::get('/getTownsFromRoute/{route_id}', [DeliveryPlanController::class, 'getTownsFromRoute']);
Route::get('/getDeliveryPlan/{delivery_plan_id}', [DeliveryPlanController::class, 'getDeliveryPlan']);
Route::post('/updateDeliveryPlan/{delivery_plan_id}', [DeliveryPlanController::class, 'updateDeliveryPlan']);
Route::get('/getNonAllocateInvoice/{delivery_plan_id}', [DeliveryPlanController::class, 'getNonAllocateInvoice']);
Route::get('/getAllocatedInvoice/{delivery_plan_id}', [DeliveryPlanController::class, 'getAllocatedInvoice']);
Route::get('/getNonPickingList/{delivery_plan_id}/{route_id}', [DeliveryPlanController::class, 'getNonPickingList']);
Route::get('/getPickingList/{delivery_plan_id}/{route_id}', [DeliveryPlanController::class, 'getPickingList']);
Route::get('/getDeliveryplanPostpone/{delivery_plan_id}', [DeliveryPlanController::class, 'getDeliveryplanPostpone']);
Route::post('/saveDeliveryPlanInvoice', [DeliveryPlanController::class, 'saveDeliveryPlanInvoice']);
Route::post('/saveDeliveryPlanNonPickingInvoice', [DeliveryPlanController::class, 'saveDeliveryPlanNonPickingInvoice']);

Route::post('/updateAllocatedRemark', [DeliveryPlanController::class, 'updateAllocatedRemark']);
Route::post('/updatePostponeDelivery', [DeliveryPlanController::class, 'updatePostponeDelivery']);
Route::delete('/removeRouteFromDeliveryPlan/{id}', [DeliveryPlanController::class, 'removeRouteFromDeliveryPlan']);
Route::get('/isInvoiceToRoute/{delivery_plan_id}/{route_id}', [DeliveryPlanController::class, 'isInvoiceToRoute']);

Route::get('/loadNonAllocatedInvoice_all', [DeliveryPlanController::class,'loadNonAllocatedInvoice_all']);
Route::get('/showPostponeDeliveryAll', [DeliveryPlanController::class,'showPostponeDeliveryAll']);
/** End of Delivery Plan */
/** End of Delivery Plan */






/**Finish Delivery plan*/
Route::get('/completed_delivery_plans',function(){
    return view('sd::completed_delivery_plan');
});
Route::get('/getVehicleOutDeliveryPlans',[DeliveryPlanController::class,'getVehicleOutDeliveryPlans']);
Route::post('/finish_plan/{id}',[DeliveryPlanController::class,'finish_plan']);

/**End of Completed Delivery plan */








    // Picking List report
   Route::get('/pickinglist/{id}',[PickingListController::class,'pickingList']);
   Route::get('/delivery_report/{id}',[DeliveryreporController::class,'delivery_report']);

    /**Delivery confirmation */
    Route::get('/delivery_confirmation', function () {
        return  view('sd::deliveryconfirmation');
    })->middleware(['is.logged','can:sd_delivery_confirmation']);
    Route::get('/getDeliveryConfirmationData/{id}',[DeliveryconfirmationController::class,'getDeliveryConfirmationData']);
    Route::post('/addDeliveryConfirmation/{id}',[DeliveryconfirmationController::class,'addDeliveryConfirmation']);
    Route::get('/loadDeliveryPlans',[DeliveryconfirmationController::class,'loadDeliveryPlans']);
    Route::post('/confirm_all',[DeliveryconfirmationController::class,'confirm_all']);
    Route::delete('/deleteDeliveryConfirmationRecord/{id}',[DeliveryconfirmationController::class,'deleteDeliveryConfirmationRecord']);
    /**End of Delivery confirmation */
   

    /**views */

    Route::get('/salesInvoiceView', function () {
        return view('sd::salesinvoiceview');
    });

    //sales invoice return view
    Route::get('/salesReturnview', function () {
        return view('sd::salesinvoice_return_view');
    });

    // sales order view

    Route::get('/salesOrderview', function () {
        return view('sd::salesOrderView');
    });


    //customer block
    Route::post('/checkBlockStatus/{empid}/{cus_id}',[CustomerBlockController::class,'checkBlockStatus']);
    Route::get('/customerBlockList',function(){
        return view('sd::customerBlockList');
    })->middleware(['is.logged','can:sd_customer_block_list']);
    Route::get('/loadCustomerBlockList/{id}',[CustomerBlockController::class,'loadCustomerBlockList']);
    Route::post('/block_release/{id}/{action}',[CustomerBlockController::class,'block_release']);
    Route::get('/get_customer_remark/{cus_id}/{block_id}',[CustomerBlockController::class,'get_customer_remark']);

    Route::get('/invoice_inquery',function(){
        return view('sd::invoice_inquery');
    });
    Route::get('/load_block_info/{id}',[CustomerBlockController::class,'CustomerBlockController']);
    Route::get('/load_block_order_info/{id}',[CustomerBlockController::class,'load_block_order_info']);

    //special bonus
    Route::get('/special_bonus',function(){
        return view('sd::special_bonus');
    })->middleware(['is.logged','can:sd_special_bonus']);
    Route::get('/load_items_for_special_bonus',[SpecialBonusController::class,'load_items_for_special_bonus']);
    Route::get('/get_customer_special_bonus',[SpecialBonusController::class,'get_customer_special_bonus']);
    Route::post('/add_special_bonus',[SpecialBonusController::class,'add_special_bonus']);
    Route::get('/getAllSpecialBonus/{val}',[SpecialBonusController::class,'getAllSpecialBonus']);
    Route::get('/get_each_special_bonus_edit/{id}',[SpecialBonusController::class,'get_each_special_bonus_edit']);
    Route::post('/update_special_bonus/{id}',[SpecialBonusController::class,'update_special_bonus']);
    Route::delete('/delete_bonus/{id}',[SpecialBonusController::class,'delete_bonus']);

    Route::get('/special_bonus_approval',function(){
        return view('sd::special_bonus_approval_list');
    })->middleware(['is.logged','can:sd_special_bonus_approval_list']);
    Route::post('/approve_reject/{id}/{type}',[SpecialBonusController::class,'approve_reject']);

    //sales Report
    Route::get('/salesReport',function(){
    return view('sd::salesReport');
    })->middleware(['is.logged','can:sd_sales_report']);

    Route::get('/getSalesrep',[SalessummaryController::class,'getSalesrep']);
    Route::post('/saleshidefilter/{id}', [SalessummaryController::class,'hidefilter']);
    Route::get('/sales_summaryReport/{search}',[SalessummaryController::class,'sales_summaryReport']);
    Route::get('/salesreturnReport/{search}',[SalesreturnlibraryreportController::class,'salesreturnReport']);
    Route::get('/salesdetailsReport/{search}',[SalesdetailsReportController::class,'salesdetailsReport']);
    Route::get('/salesreturndetailsReport/{search}',[SalesreturndetailsReportController::class,'salesreturndetailsReport']);
    Route::get('/productwisereport',function(){
        return view('sd::productWiseQtyReport');
    });


    /**missed sales orders */
    Route::get('/missed_sales_order_list',function(){
        return view('sd::missed_sales_orders');
    })->middleware(['is.logged','can:sd_missed_sales_order']);
    Route::get('/get_missed_order_sales',[MissedSalesOrderController::class,'get_missed_order_sales']);
    Route::post('/update_missed_order_sales_status',[MissedSalesOrderController::class,'update_missed_order_sales_status']);

    // product wise quantity sales type Report
    Route::get('/productwisequantitysalestype/{search}', [ProductwisequantitysalestypeReportController::class, 'productwisequantitysalestype']);
    Route::get('/getMarketingRoute', [ProductwisequantitysalestypeReportController::class, 'getMarketingRoute']);
    Route::get('/getSupplyGroup', [ProductwisequantitysalestypeReportController::class, 'getSupplyGroup']);

    //Sale - Repwise monthly summary
    Route::get('/salesRepwiseMonthlySummary/{search_data}', [SalesRepWiseMonthlySummaryReportController::class, 'salesRepwiseMonthlySummary']);

    //invoice info
    Route::get('/invoice_nfo',function(){
        return view('sd::invoice_info');
    })->middleware(['is.logged','can:sd_invoice_info']);
    Route::get('/load_inv',[InvoiceInfoController::class,'load_inv']);
    Route::get('/load_invoice_details/{number}',[InvoiceInfoController::class,'load_invoice_details']);
    Route::get('/load_return_items/{id}',[InvoiceInfoController::class,'load_return_item']);
    Route::post('/getInvoices_inv_info',[InvoiceInfoController::class,'getInvoices_inv_info']);


   /*  Route::get('/testInv',[SalesInvoiceController::class,'test']); */

    //Item Customer Report
 Route::get('/itemCustomerReport/{search_data}', [ItemCustomerReportController::class, 'itemCustomerReport']);

  //Free Summary Report
  Route::get('/freeSummaryReport/{search_data}', [FreeSummaryReportController::class, 'freeSummaryReport']);

  //Free Issued Report
  Route::get('/free_issuedReport/{search}',[FreeissuedReportController::class,'freeissuedreport']);

  /**merge order */
  Route::get('/merge_order',function(){
    return view('sd::merge_order');
  })->middleware(['is.logged','can:sd_merge_order']);

  Route::get('/load_duplicate_orders/{branch}',[MergeOrdersController::class,'load_duplicate_orders']);
  Route::get('/loadOrders/{branch}/{id}',[MergeOrdersController::class,'loadOrders']);
  Route::post('/merger_order_save',[MergeOrdersController::class,'merger_order_save']);


  /**Blocked order list */
  Route::get('/blocked_order_list',function(){
    return view('sd::pending_orders_list');
  });
  Route::get('/load_blocked_orders',[PendingOrderController::class,'load_blocked_orders']);
  Route::post('/update_order_block_status/{id}/{blockid}',[PendingOrderController::class,'update_order_block_status']);

  /**Invoice Tracking Inquery */
  Route::get('/invoice_tracking_inquery_list',function(){
    return view('sd::invoice_tracking_inquery');
  });
  Route::get('/load_invoices_for_invoice_tracking',[InvoiceTrackingController::class,'load_invoices_for_invoice_tracking']);
  Route::post('/create_inquery/{id}',[InvoiceTrackingController::class,'create_inquery']);
  Route::get('/load_statments_with_inv/{id}',[InvoiceTrackingController::class,'load_statments_with_inv']);
  /**Pending Inqueries */
  Route::get('/pending_inquery_list',function(){
    return view('sd::pending_inquery_list');
  });
  Route::get('/load_pending_inqueries',[InvoiceTrackingController::class,'load_pending_inqueries']);
  Route::post('/create_inquery_statment/{id}',[InvoiceTrackingController::class,'create_inquery_statment']);
  Route::get('/load_statments/{id}',[InvoiceTrackingController::class,'load_statment']);

//pending orders
Route::get('/pending_sales_orders',function(){
    return  view('sd::pending_sales_oder_list');
})->middleware(['is.logged']);
Route::get('/getSalesOrderPendingDetails/{id}',[OrdersController::class,'getSalesOrderPendingDetails']);

//late orders
Route::get('/late_sales_orders',function(){
    return view('sd::late_sales_oder_list');
})->middleware(['is.logged']);
Route::get('/getLateOrdersDetails/{id}',[OrdersController::class,'getLateOrdersDetails']);
  
//load oustanding details to block relase
Route::get('/loadOutstandingDataToTable/{id}/{br}',[CustomerBlockController::class,'loadOutstandingDataToTable']);

//return request
Route::get('/loadReturnRequest/{customerID}',[SalesInvoiceController::class,'loadReturnRequest']); 

  //Monthend sales summery report
  Route::get('/salessummeryreport/{search_data}', [MonthendSalesSummeryReportController::class, 'salessummeryreport']);

  //sales invoice copy issued
  Route::get('/sales_invoice_copy_issued',function(){
    return view('sd::sales_invoice_copy_issued');
  })->middleware(['is.logged']);
  Route::get('/loadEmpforsalesInvoicecopyIssued',[SalesInvoiceCoppyIssuedController::class,'loadEmpforsalesInvoicecopyIssued']);
  Route::get('/load_invoice_details_for_invoie_copy/{id}',[SalesInvoiceCoppyIssuedController::class,'load_invoice_details_for_invoie_copy']);
  Route::get('/load_inv_for_copy_issued',[SalesInvoiceCoppyIssuedController::class,'load_inv_for_copy_issued']);
  Route::post('/saveInvoiceCopyIssued',[SalesInvoiceCoppyIssuedController::class,'saveInvoiceCopyIssued']);
  Route::get('/sales_invoice_copy_issued_report/{collection}/{collector}',[SalesInvoiceCoppyIssuedController::class,'sales_invoice_copy_issued_report']);

  Route::get('/sales_invoice_copy_received',function(){
    return view('sd::sales_invoice_copy_received');
  })->middleware(['is.logged']);
  Route::get('/load_invoice_details_for_invoie_copy_received/{id}',[SalesInvoiceCoppyIssuedController::class,'load_invoice_details_for_invoie_copy_received']);
  Route::post('/saveInvoiceCopyReceived',[SalesInvoiceCoppyIssuedController::class,'saveInvoiceCopyReceived']);
  Route::get('/loadEmpforsalesInvoiceRecieved',[SalesInvoiceCoppyIssuedController::class,'loadEmpforsalesInvoiceRecieved']);
  Route::get('/sales_invoice_copy_received_report/{collection}/{collector}',[SalesInvoiceCoppyIssuedController::class,'sales_invoice_copy_received_report']);

  /**Commision Report */
  Route::get('/commisionReport',function(){
    return view('sd::commisionReport');
  })->middleware(['is.logged','can:sd_commision_report']);
  Route::get('/generatecommisionReport/{search_data}',[CommisionReportController::class,'generatecommisionReport']);
  
});

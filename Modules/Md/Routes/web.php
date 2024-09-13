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
use Modules\Md\Entities\location;
use Modules\Md\Http\Controllers\BankController;
use Modules\Md\Http\Controllers\BookController;
use Modules\Md\Http\Controllers\branchController;
use Modules\Md\Http\Controllers\CategoryLevelController;
use Modules\Md\Http\Controllers\CommonsettingController;
use Modules\Md\Http\Controllers\CustomerController;
use Modules\Md\Http\Controllers\dataController;
use Modules\Md\Http\Controllers\EmployeeController;
use Modules\Md\Http\Controllers\freeOfferController;
use Modules\Md\Http\Controllers\GlAccountController;
use Modules\Md\Http\Controllers\International_nonproprietaryController;
use Modules\Md\Http\Controllers\ItemController;
use Modules\Md\Http\Controllers\locationController;
use Modules\Md\Http\Controllers\MarketingRouteController;
use Modules\Md\Http\Controllers\RouteController;
use Modules\Md\Http\Controllers\salesOrderController;
use Modules\Md\Http\Controllers\SfaAccessController;
use Modules\Md\Http\Controllers\Suply_groupController;
use Modules\Md\Http\Controllers\SupplierController;
use Modules\Md\Http\Controllers\SupplierCustomerCodeController;
use Modules\Md\Http\Controllers\supplierItemCodeController;
use Modules\Md\Http\Controllers\TownNonadministrativeController;
use Modules\Md\Http\Controllers\VehicleController;
use Modules\Sd\Http\Controllers\dataController as ControllersDataController;

Route::prefix('md')->middleware(['is.logged'])->group(function () {

    Route::get('/', function () {
        return view('md::dashboard');
    })->middleware('is.logged');



    /** Item */
    Route::get('/item', function () {
        return view('md::item');
    })->middleware(['is.logged']);

    ROute::get('/itemList', function () {
        return view('md::itemList');
    })->middleware(['is.logged','can:md_item']);

    Route::post('/addItem', [ItemController::class, 'addItem']);
    Route::get('/getSupplyGroup', [ItemController::class, 'getSupplyGroup']);
    Route::get('/getCategoryLevelOne', [ItemController::class, 'getCategoryLevelOne']);
    Route::get('/getCategoryLevelTwo/{Cat_lvl_1_id}', [ItemController::class, 'getCategoryLevelTwo']);
    Route::get('/getCategoryLevelThree/{cat_lvl_2_id}', [ItemController::class, 'getCategoryLevelThree']);
    Route::post('/getItemDetails', [ItemController::class, 'getItemDetails']);
    Route::delete('/deleteItem/{id}', [ItemController::class, 'deleteItem']);
    Route::get('/geteachItem/{id}', [ItemController::class, 'geteachItem']);
    Route::post('/updateItem/{id}', [ItemController::class, 'updateItem']);
    Route::get('/searchItemNames', [ItemController::class, 'searchItemNames']);
    Route::get('/getInn', [ItemController::class, 'getInn']);

    Route::get('/searchINNNames',[ItemController::class,'searchINNNames']);
    Route::get('/getItemCategory2', [ItemController::class, 'getItemCategory2']);
    Route::get('/getItemCategory3', [ItemController::class, 'getItemCategory3']);

    /** End of Item */




    /** Customer */
    Route::get('/customer', function () {
        return view('md::customer');
    })->middleware('is.logged');

    Route::get('/customerList', function () {
        return view('md::customerList');
    })->middleware(['is.logged','can:md_customer']);

    Route::post('/CustomerController/saveCustomer', [CustomerController::class, 'saveCustomer']);
    Route::GET('/getDistrictId', [CustomerController::class, 'getDistrict']);
    Route::GET('/getTownId/{id}', [CustomerController::class, 'getTown']);
    Route::get('/getCustomerGroupid', [CustomerController::class, 'getCustomerGroup']);
    Route::get('/getCustomerGradeId', [CustomerController::class, 'getCustomerGrade']);
    Route::post('/saveContact/{id}', [CustomerController::class, 'addContactDetails']);


    Route::post('/addDeliveryPoint/{id}', [CustomerController::class, 'addcustomerDeliveryPoints']);
    Route::get('/getCustomerDetails', [CustomerController::class, 'getCsutomerDetails']);
    Route::get('/ViewCustomer', function () {
        return view('customerUpdate');
    })->middleware('is.logged');

    Route::get('/getCustomer/{id}', [CustomerController::class, 'getEachCustomer']);
    Route::post('/upload', [CustomerController::class, 'uploadFile']);
    Route::post('/updateCustomer/{id}', [CustomerController::class, 'updateCustomer']);
    Route::delete('/deleteCustomer/{id}', [CustomerController::class, 'deleteCustomer']);
    Route::get('/getEachContactData/{id}', [CustomerController::class, 'getEachCustomerContact']);
    Route::get('/getEachDeliveryPoint/{id}', [CustomerController::class, 'getEachDeliveryPoint']);
    Route::get('/searchNames', [CustomerController::class, 'loadNames']);
    Route::delete('/deleteCustomerContact/{id}', [CustomerController::class, 'deleteCustomerContact']);
    Route::delete('/deleteDeliveryPoint/{id}', [CustomerController::class, 'deleteDeliveryPoint']);
    Route::get('/loadPamentTerm',[CustomerController::class,'loadPamentTerm']);
    /** End Of Customer */



    /** Employee */

    //Route::get('/employee', [EmployeeController::class, 'index']);
    Route::get('/employee', function () {
        return view('md::employee');
    })->middleware('is.logged');
    Route::get('/employeeList', function () {
        return view('md::employeeList');
    })->middleware(['is.logged','can:md_employee']);

    Route::post('/saveEmployee', [EmployeeController::class, 'saveEmployee']);
    Route::get('/getEmployeeDetails', [EmployeeController::class, 'getEmployeeDetails']);
    Route::get('/getEmployeedata/{id}', [EmployeeController::class, 'getEmployeedata']);
    Route::post('/Employee/update/{id}', [EmployeeController::class, 'employeeUpdate']);
    Route::get('/getEmployeeview/{id}', [EmployeeController::class, 'getEmployview']);
    Route::delete('/deleteEmployee/{id}', [EmployeeController::class, 'employeeDelete']);
    Route::get('/getemployeestatus', [EmployeeController::class, 'employeestatus']);
    Route::get('/empdesgnation', [EmployeeController::class, 'empdesgnation']);
    Route::get('/empreport', [EmployeeController::class,'empreport']);
    Route::post('/getusarname/{action}/{id}', [EmployeeController::class, 'getusarname']);
    /** End Of Employee */


    /** Location */
    Route::get('/location', function () {
        return view('md::location');
    })->middleware('is.logged');

    Route::get('/locationList', function () {
        return view('md::locationList');
    })->middleware(['is.logged','can:md_location']);

    Route::post('/addLocation', [locationController::class, 'addLocation']);
    Route::get('/getLocationTypes', [locationController::class, 'getLocationTypes']);
    Route::get('/getLocationDetails', [locationController::class, 'getLocationDetails']);
    Route::get('/getEachLocationDetails/{id}', [locationController::class, 'getEachLocationDetails']);
    Route::post('/updateLocation/{id}', [locationController::class, 'updateLocation']);
    Route::delete('/deleteLocation/{id}', [locationController::class, 'deleteLocation']);
    Route::get('/getBranches',[locationController::class,'getBranches']);
    /** End Of Location */


    /** Vehicle */
    Route::get('/vehicle', function () {
        return view('md::vehicle');
    })->middleware(['is.logged','can:md_vehicle']);

    Route::get('/vehicalTypename', [VehicleController::class, 'vehicaltypename']);
    Route::get('/getvehicaleAlldata', [VehicleController::class, 'vehicaleAlldata']);
    Route::post('/savevehicle', [VehicleController::class, 'savevehicle']);
    Route::get('/getVehicaleEdit/{id}', [VehicleController::class, 'vehicaleEdit']);
    Route::post('/vehicaleupdate/{id}', [VehicleController::class, 'vehicaleUpdate']);
    Route::post('/vehicleStatus/{id}', [VehicleController::class, 'vehicleStatus']);
    Route::delete('/deleteVehicale/{id}', [VehicleController::class, 'deleteVehicale']);
    /** End Of Vehicle */


    /** Supply Group */
    Route::get('/suply_group', function () {
        return view('md::suply_group');
    })->middleware(['is.logged','can:md_supply_group']);
    Route::get('/suplyGroupAllData', [Suply_groupController::class, 'suplyGroupAllData']);
    Route::post('/saveSuplyGroup', [Suply_groupController::class, 'saveSuplyGroup']);
    Route::get('/suplyGroupEdite/{id}', [Suply_groupController::class, 'suplyGroupEdite']);
    Route::post('/supltGroupUpdate/{id}', [Suply_groupController::class, 'supltGroupUpdate']);
    Route::post('/suplyGroupStatus/{id}', [Suply_groupController::class, 'suplyGroupStatus']);
    Route::delete('/deleteSuplygroup/{id}', [Suply_groupController::class, 'deleteSuplygroup']);
    /** End Of Supply Group */


    /** Item Altenative Name */
    Route::get('/item_altenative_name', function () {
        return view('md::item_altenative_name');
    })->middleware(['is.logged','can:md_international_nonproprietary_name']);
    Route::get('/nonproprietaryAllData', [International_nonproprietaryController::class, 'nonproprietaryAllData']);
    Route::post('/saveNonproprietary', [International_nonproprietaryController::class, 'nonproprietaryGroup']);
    Route::get('/nonproprietaryEdite/{id}', [International_nonproprietaryController::class, 'nonproprietaryEdite']);
    Route::post('/nonproprietaryUpdate/{id}', [International_nonproprietaryController::class, 'nonproprietaryUpdate']);
    Route::post('/nonproprietaryStatus/{id}', [International_nonproprietaryController::class, 'nonproprietaryStatus']);
    Route::get('/nonproprietarysearch', [International_nonproprietaryController::class, 'nonproprietarysearch']);
    Route::delete('/deleteNonproprietary/{id}', [International_nonproprietaryController::class, 'deleteNonproprietary']);
    Route::post('/close', [International_nonproprietaryController::class, 'close']);
    /** End Of Item Altenative Name */



    /** Supplier Item Code */
    Route::get('/supplier_item_code', function () {
        return view('md::supplier_item_code');
    })->middleware(['is.logged','can:md_supplier_item_code']);
    Route::get('/suppliersname', [supplierItemCodeController::class, 'suppliersname']);
    Route::get('/getItemdata', [supplierItemCodeController::class, 'getitemdata']);
    Route::post('/savesavesuppliers', [supplierItemCodeController::class, 'savesavesuppliers']);
    
    /** End Of Supplier Item Code */



    /** Assign Customer Location */
    Route::get('/assignCustomertoLocation', function () {
        return view('md::assignCustomertoLocation');
    })->middleware(['is.logged','can:md_assign_customer_to_branch']);
    Route::get('/getCustomerDataTOlistbox/{id}', [dataController::class, 'getFilterData']); // same for employee cystomer

    Route::get('/getLocations', [dataController::class, 'getLocations']);
    Route::post('/addCustomerLocations', [dataController::class, 'addCustomerLocation']);
    Route::get('/getCustomerlocationDteails', [dataController::class, 'getCustomerlocationDteails']);
    Route::delete('/deleteCustomerLocation', [dataController::class, 'deleteCustomerLocation']);
    Route::get('/getselectcustomer/{id}',[dataController::class,'getselectcustomer']);
    Route::post('/selectdeletuserBranch',[dataController::class,'selectdeletuserBranch']);
    /** End Of Assign Customer Location */


    /** common settings */
    Route::get('/commonSetting',function(){
        return view('md::common_setting');
    })->middleware(['is.logged','can:md_common_settings']);


    Route::get('/district', function () {
        return view('md::district');
    })->middleware(['is.logged','can:md_administrative_district_list']);

//..district
Route::get('/districtData', [CommonsettingController::class,'districtData']);
Route::post('/saveDistrict', [CommonsettingController::class, 'saveDistrict']);
Route::get('/districtEdite/{id}', [CommonsettingController::class,'districtEdite']);
Route::post('/districtUpdate/{id}', [CommonsettingController::class, 'districtUpdate']);
Route::post('/updateDistrictStatus/{id}', [CommonsettingController::class, 'districtStatus']);

Route::get('/dist_search', [CommonsettingController::class,'dist_search']);

Route::get('/save_town_status', [CommonsettingController::class,'save_town_status']);
Route::delete('/deleteDistrict/{id}', [CommonsettingController::class,'deleteDistrict']);

//..Town


Route::get('/town', function () {
    return view('md::town');
})->middleware(['is.logged','can:md_administrative_town_list']);

Route::get('/twonAlldata', [CommonsettingController::class,'twonAlldata']);
Route::post('/saveTown', [CommonsettingController::class, 'saveTown']);
Route::get('/townEdite/{id}', [CommonsettingController::class,'townEdite']);
Route::post('/townUpdate/{district_id}', [CommonsettingController::class, 'townUpdate']);
Route::get('/town_search', [CommonsettingController::class,'town_search']);
Route::post('/townUpdateStatus/{id}', [CommonsettingController::class,'townUpdateStatus']);
Route::get('/updateStatusTown/{id}', [CommonsettingController::class,'updateStatusTown']);
Route::delete('/deleteTown/{id}', [CommonsettingController::class,'deleteTown']);
Route::get('loadDistrict',[CommonsettingController::class,'loadDistrict']);
Route::get('/towndistrict', [CommonsettingController::class, 'towndistrict']);
//..Group
Route::get('/groupAlldata', [CommonsettingController::class,'groupAlldata']);
Route::post('/saveGroup', [CommonsettingController::class, 'saveGroup']);
Route::get('/groupEdite/{id}', [CommonsettingController::class,'groupEdite']);
Route::post('/groupUpdate/{id}', [CommonsettingController::class, 'groupUpdate']);
Route::get('/group_search', [CommonsettingController::class,'group_search']);
Route::post('/groupUpdateStatus/{id}', [CommonsettingController::class,'groupUpdateStatus']);
Route::get('/updateStatusGroup/{id}', [CommonsettingController::class,'updateStatusGroup']);
Route::delete('/deleteGroup/{id}', [CommonsettingController::class,'deleteGroup']);

//..Grade
Route::get('/gradeAlldata', [CommonsettingController::class,'gradeAlldata']);
Route::post('/savegrade', [CommonsettingController::class, 'savegrade']);
Route::get('/gradeEdite/{id}', [CommonsettingController::class,'gradeEdite']);
Route::post('/gradeUpdate/{id}', [CommonsettingController::class, 'gradeUpdate']);
Route::get('/grade_search', [CommonsettingController::class,'grade_search']);
Route::post('/gradeUpdateStatus/{id}', [CommonsettingController::class,'gradeUpdateStatus']);
Route::get('/updateStatusGrade/{id}', [CommonsettingController::class,'updateStatusGrade']);
Route::delete('/deleteGrade/{id}', [CommonsettingController::class,'deleteGrade']);

// level 1
Route::get('/getCategorylevelOne', [CategoryLevelController::class,'categoryLevel1Data']);

Route::post('/saveCategoryLevel1', [CategoryLevelController::class,'saveCategoryLevel1']);
Route::get('/categorylevel1Edite/{id}', [CategoryLevelController::class,'categorylevel1Edite']);
Route::post('/txtCategorylevel1Update/{id}', [CategoryLevelController::class, 'txtCategorylevel1Update']);
Route::post('/updateCatLevel1tStatus/{id}', [CategoryLevelController::class, 'catLevel1tStatus']);
Route::get('/catLevel1_search', [CategoryLevelController::class,'categoryLevel1search']);
Route::delete('/deletelevel1/{id}', [CategoryLevelController::class,'deletelevel1']);

// Level 2
Route::get('/test', [CategoryLevelController::class,'categoryLevel2Data']);
Route::get('/categoryLevel2Data', [CategoryLevelController::class,'categoryLevel2Data']);
Route::post('/saveCategoryLevel2', [CategoryLevelController::class,'saveCategoryLevel2']);
Route::get('/categorylevel2Edite/{id}', [CategoryLevelController::class,'categorylevel2Edite']);
Route::post('/txtCategorylevel2Update/{id}', [CategoryLevelController::class, 'txtCategorylevel2Update']);
Route::post('/updateCatLevel2tStatus/{id}', [CategoryLevelController::class, 'catLevel2tStatus']);
Route::get('/catLevel2_search', [CategoryLevelController::class,'categoryLevel2search']);
Route::delete('/deletelevel2/{id}', [CategoryLevelController::class,'deletelevel2']);
Route::get('loadcategory2',[CategoryLevelController::class,'loadCategory2']);

// Level 3
Route::get('/categoryLevel3Data', [CategoryLevelController::class,'categoryLevel3Data']);
Route::post('/saveCategoryLevel3', [CategoryLevelController::class,'saveCategoryLevel3']);
Route::get('/categorylevel3Edite/{id}', [CategoryLevelController::class,'categorylevel3Edite']);
Route::post('/Categorylevel3Update/{id}', [CategoryLevelController::class, 'Categorylevel3Update']);
Route::post('/updateCatLevel3tStatus/{id}', [CategoryLevelController::class, 'catLevel3tStatus']);
Route::get('/catLevel3_search', [CategoryLevelController::class,'categoryLevel3search']);
Route::delete('/deletelevel3/{id}', [CategoryLevelController::class,'deletelevel3']);
Route::get('loadcategory3',[CategoryLevelController::class,'loadCaegory3']);

// Distination

Route::post('/saveDesgination', [CategoryLevelController::class,'saveDesgination']);
Route::get('/disginationData', [CategoryLevelController::class,'disginationData']);
Route::get('/desginationEdite/{id}', [CategoryLevelController::class,'desginationEdite']);
Route::post('/desginationtUpdate/{id}', [CategoryLevelController::class, 'desginationtUpdate']);
Route::post('/updateDesginationStatus/{id}', [CategoryLevelController::class, 'updateDesginationStatus']);
Route::get('/desginathionsearch', [CategoryLevelController::class,'desginathionsearch']);
Route::delete('/deletedesgination/{id}', [CategoryLevelController::class,'deletedesgination']);


//  Employee Status

Route::post('/empSaveStatus', [CategoryLevelController::class,'empSaveStatus']);
Route::get('/empStatusData', [CategoryLevelController::class,'empStatusData']);
Route::get('/empStatusEdite/{id}', [CategoryLevelController::class,'empStatusEdite']);
Route::post('/empStatusUpdate/{id}', [CategoryLevelController::class, 'empStatusUpdate']);
Route::post('/updateempStatus/{id}', [CategoryLevelController::class, 'updateempStatus']);
Route::get('/empStatussearch', [CategoryLevelController::class,'empStatussearch']);
Route::delete('/deleteempStatus/{id}', [CategoryLevelController::class,'deleteempStatus']);


// Vehicle Type
Route::get('/getVehicletype', [CategoryLevelController::class,'getVehicletype']);
Route::post('/saveVehicleType', [CategoryLevelController::class,'saveVehicleType']);
Route::get('/vehicletypeEdite/{id}', [CategoryLevelController::class,'vehicletypeEdite']);
Route::post('/vehicleTypeUpdate/{id}', [CategoryLevelController::class, 'vehicleTypeUpdate']);
Route::post('/updateVehicletypeStatus/{id}', [CategoryLevelController::class, 'vehicletypeStatus']);
Route::delete('/deleteVehicletype/{id}', [CategoryLevelController::class,'deleteVehicle']);
//delivery Type

Route::get('/getdeliveryType', [CategoryLevelController::class,'getdeliveryType']);
Route::post('/addDeliveryType', [CategoryLevelController::class,'addDeliveryType']);
Route::get('/deliveryTypeEdite/{id}', [CategoryLevelController::class,'deliveryTypeEdite']);
Route::post('/deliveryTypeUpdate/{id}', [CategoryLevelController::class, 'deliveryTypeUpdate']);
Route::post('/deliveryypeStatus/{id}', [CategoryLevelController::class, 'deliveryypeStatus']);
Route::delete('/deleteDeliveryType/{id}', [CategoryLevelController::class,'deleteDeliveryType']);


Route::get('/gesalesRetornreson', [CategoryLevelController::class,'gesalesRetornreson']);
Route::post('/addsalesRetornreson', [CategoryLevelController::class,'addsalesRetornreson']);
Route::get('/salesRetornResonEdite/{id}', [CategoryLevelController::class,'salesRetornResonEdite']);
Route::post('/salesRetornResonUpdate/{id}', [CategoryLevelController::class, 'salesRetornResonUpdate']);
Route::post('/cbxSalesRetornStatus/{id}', [CategoryLevelController::class, 'cbxSalesRetornStatus']);
Route::delete('/deletesalesretornReson/{id}', [CategoryLevelController::class,'deletesalesretornReson']);



//Supplier group
Route::post('/addSupplierGroup',[CommonsettingController::class,'addSupplierGroup']);
Route::get('/getSupplierGroupDetails',[CommonsettingController::class,'getSupplierGroupDetails']);
Route::get('/supplierGroupEdite/{id}', [CommonsettingController::class,'supplierGroupEdite']);
Route::post('/supplierGroupUpdate/{id}', [CommonsettingController::class, 'supplierGroupUpdate']);
Route::post('/supplierGroupStatus/{id}', [CommonsettingController::class, 'supplierGroupStatus']);
Route::delete('/deleteSupplierGroup/{id}',[CommonsettingController::class,'deleteSupplierGroup']);

/* //supplier payment method
Route::post('/addSupplierPaymentMethod',[CommonsettingController::class,'addSupplierPaymentMethod']);
Route::get('/getSupplierPaymentMethod',[CommonsettingController::class,'getSupplierPaymentMethod']); */

//...bank

Route::get('/bank', function () {
    return view('md::bank');
})->middleware(['is.logged','can:md_bank_list']);
Route::get('/getBankAlldata', [BankController::class,'getBankalldata']);
Route::get('/searchBank', [BankController::class,'searchBank']);
Route::post('/savebank', [BankController::class,'banksave']);
Route::get('/getbannkEdit/{id}', [BankController::class,'getbannkEdit']);
Route::post('/bankupdate/{id}', [BankController::class, 'bankupdate']);
Route::post('/bankStatus/{id}', [BankController::class, 'bankStatus']);
Route::delete('/deletebank/{id}', [BankController::class,'deletebank']);

//branchers

Route::get('/getBranchAlldata/{id}', [BankController::class,'getBranchAlldata']);
Route::get('/searchBranch', [BankController::class,'searchbranch']);
Route::post('/saveBranch', [BankController::class,'savebranch']);
Route::get('/getbranchkEdit/{id}', [BankController::class,'getbranchkEdit']);
Route::post('/branchupdate/{id}', [BankController::class, 'branchupdate']);
Route::post('/branchstatus/{id}', [BankController::class, 'branchStatus']);
Route::delete('/deletebranch/{id}', [BankController::class,'deleteBranch']);


 Route::get('/bank', function () {
    return view('md::bank');
})->middleware('is.logged');


/** Supplier */
Route::post('/addSupplier',[SupplierController::class,'addSupplier']);
ROute::post('/addSupplierContact/{id}',[SupplierController::class,'addSupplierContact']);
Route::get('/supplier',function(){
    return view('md::supplier');
});
  
Route::get('/getSupplyGroup',[SupplierController::class,'getSupplyGroup']);
Route::get('/getSupplierGroup',[SupplierController::class,'getSupplierGroup']);
Route::get('/getSupplierDetails',[SupplierController::class,'getSupplierDetails']);
route::get('/supplierList',function(){
    return view('md::supplierList');
})->middleware(['is.logged','can:md_supplier_list']);

route::get('/getEachSupplier/{id}',[SupplierController::class,'getEachSupplier']);
route::get('/getEachSupplierContact/{id}',[SupplierController::class,'getEachSupplierContact']);
Route::delete('/deleteSupplierContact/{id}',[SupplierController::class,'deleteSupplierContact']);
Route::post('/updateSupplier/{id}',[SupplierController::class,'updateSupplier']);
Route::delete('/deleteSupplier/{id}',[SupplierController::class,'deleteSupplier']);
Route::get('/loadSupplierNames',[SupplierController::class,'loadSupplierNames']);


/**End of Supplier */

//...branch..
Route::get('/branch',function(){
    return view('md::branch');
});


Route::get('/brancList',function(){
    return view('md::branchList');
})/* ->middleware(['is.logged','can:md_branch_list']) */;


Route::post('/savBranch',[branchController::class,'savBranch']);
Route::get('/getBranchDetails',[branchController::class,'getBranchDetails']);
Route::get('/branchEdite/{id}',[branchController::class,'branchEdite']);
Route::get('/getBranchview/{id}',[branchController::class,'getBranchview']);
Route::post('/updatebranch/{id}',[branchController::class,'updatebranch']);

Route::delete('/deleteBranch/{id}',[branchController::class,'deleteBranch']);


Route::get('/getPaymentTerm', [CommonsettingController::class,'getPaymentTerm']);
Route::post('/savePaymentTerm', [CommonsettingController::class,'savePaymentTerm']);
Route::get('/suplyPaymentTermEdite/{id}', [CommonsettingController::class,'suplyPaymentTermEdite']);
Route::post('/updatepaymentTerm/{id}', [CommonsettingController::class, 'updatePaymentTerm']);
Route::post('/cbxPaymentTermStatus/{id}', [CommonsettingController::class, 'cbxPaymentTermStatus']);
Route::delete('/deletepayementterm/{id}', [CommonsettingController::class,'deletepayementterm']);

//supplier pament method
Route::get('/getPaymentMethod', [CommonsettingController::class,'getPaymentMethod']);
Route::post('/saveSupplierPayment', [CommonsettingController::class,'saveSupplierPayment']);
Route::get('/suplypaymentMethordEdite/{id}', [CommonsettingController::class,'suplypaymentMethordEdite']);
Route::post('/updateSuplyPementMethord/{id}', [CommonsettingController::class, 'updateSuplyPementMethord']);
Route::post('/cbxPaymentMethordStatus/{id}', [CommonsettingController::class, 'cbxPaymentMethordStatus']);
Route::delete('/deletepayementMode/{id}', [CommonsettingController::class,'deletepayementMode']);


//customer pament method
Route::get('/getCustomerPaymentMethod', [CommonsettingController::class,'getCustomerPaymentMethod']);
Route::post('/saveCustomerPayment', [CommonsettingController::class,'saveCustomerPayment']);
Route::get('/customerpaymentMethordEdite/{id}', [CommonsettingController::class,'customerpaymentMethordEdite']);
Route::post('/updateCustomerPementMethord/{id}', [CommonsettingController::class, 'updateCustomerPementMethord']);
Route::post('/cbxCustomerPaymentMethordStatus/{id}', [CommonsettingController::class, 'cbxCustomerPaymentMethordStatus']);
Route::delete('/deletecustomerpayementMode/{id}', [CommonsettingController::class,'deletecustomerpayementMode']);

//non administrative town
Route::get('/townNon',function(){
    return view('md::townAdministrative');
})->middleware(['is.logged','can:md_town_list']);
Route::post('/addTownNonAdministrative',[TownNonadministrativeController::class,'addTownNonAdministration']);
Route::Get('/getTownNonAdmin/{id}',[TownNonadministrativeController::class,'getTownNonAdmin']); // to customer page
Route::get('/getTownList',[TownNonadministrativeController::class,'getTownList']);
Route::get('/getEachTowninfo/{id}',[TownNonadministrativeController::class,'getEachTowninfo']);
Route::delete('/deleteTownN/{id}',[TownNonadministrativeController::class,'deleteTownN']);
Route::post('/updateTownNonAdministrative/{id}',[TownNonadministrativeController::class,'updateTownNonAdministrative']);



//delivery routes
Route::get('/getDeliveryRoutes',[RouteController::class,'getRoutes']);



// gl Account
Route::get('/gl_account',function(){
    return view('md::gl_accounts');
})->middleware(['is.logged','can:md_gl_account']);
Route::post('/save_glaccount',[GlAccountController::class,'save_glaccount']); 
Route::get('/glaccountType',[GlAccountController::class,'glaccountType']);
Route::get('/allglaccountdata',[GlAccountController::class,'allglaccountdata']);
Route::get('/getglaccount/{id}',[GlAccountController::class,'getglaccount']);
Route::post('/updateglAccount/{id}',[GlAccountController::class,'updateglAccount']);
Route::delete('/glAccounDelete/{id}',[GlAccountController::class,'glAccounDelete']);

/**books */
Route::get('/book',function(){
    return view('md::book');
})->middleware(['is.logged','can:md_book']);
Route::post('/save_book',[BookController::class,'save_book']);
Route::get('/get_book_list',[BookController::class,'get_book_list']);
Route::get('/getBook_data/{id}',[BookController::class,'getBook_data']);
Route::post('/updateBook/{id}',[BookController::class,'updateBook']);
Route::DELETE('/deleteBook/{ID}',[BookController::class,'deleteBook']);

// SFA Access
Route::get('/sfa_access', function () {
    return view('md::sfa_access');
})->middleware(['is.logged','can:md_sfa_access']);

Route::get('/getEmployee',[SfaAccessController::class,'getEmployee']);
Route::post('/saveSfa',[SfaAccessController::class,'saveSfa']);
Route::get('/sfallData',[SfaAccessController::class,'sfallData']);
Route::get('/getsfaaccess/{id}',[SfaAccessController::class,'getsfaaccess']);
Route::post('/updateSFAaccess/{id}',[SfaAccessController::class,'updateSFAaccess']);
Route::delete('deleteSFAaccess/{id}',[SfaAccessController::class,'deleteSFAaccess']);

/**marketing route */
Route::get('/marketingRoute',function(){
    return view('md::marketingRouteList');
});
Route::post('/addMarketingRoute',[MarketingRouteController::class,'addMarketingRoute']);
Route::get('/getMarketingRoutes',[MarketingRouteController::class,'getMarketingRoutes']);
Route::get('/getEachMarketingRoute/{id}',[MarketingRouteController::class,'getEachMarketingRoute']);
Route::post('/updateMarketingRoute/{id}',[MarketingRouteController::class,'updateMarketingRoute']);


/**human body system */
Route::get('/human_body_system',function(){
    return view('md::human_body_systems');
});


   /** Supplier's Customer code */
   Route::get('/supplier_customer_code', function () {
    return view('md::supplier_customer_code');
})->middleware('is.logged');
Route::get('/loadBranches', [SupplierCustomerCodeController::class, 'loadBranches']);
Route::get('/loadCustomers', [SupplierCustomerCodeController::class, 'loadCustomers']);
Route::get('/isExistingRecord/{customer_id}/{branch_id}', [SupplierCustomerCodeController::class, 'isExistingRecord']);
Route::post('/saveSupplierCustomerCode', [SupplierCustomerCodeController::class, 'save']);
Route::get('/viewAllData', [SupplierCustomerCodeController::class, 'viewAllData']);
Route::get('/getSupplierCustomerData/{customer_id}/{branch_id}', [SupplierCustomerCodeController::class, 'getSupplierCustomerData']);
Route::post('/updateSupplierCustomerCode', [SupplierCustomerCodeController::class, 'update']);
Route::delete('/deleteSupplierCustomerCode/{customer_id}/{branch_id}', [SupplierCustomerCodeController::class, 'deleteSupplierCustomerCode']);
});









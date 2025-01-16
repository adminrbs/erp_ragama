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
use Modules\Sl\Http\Controllers\CreditorAgeAnalysisController;
use Modules\Sl\Http\Controllers\SupOutstandingcontroller;
use Modules\Sl\Http\Controllers\SupplierBankTransferReportController;
use Modules\Sl\Http\Controllers\SupplierCashAuditReportController;
use Modules\Sl\Http\Controllers\SupplierChequeAuditReportController;
use Modules\Sl\Http\Controllers\SupplierCreditController;
use Modules\Sl\Http\Controllers\SupplierDebitNoteController;
use Modules\Sl\Http\Controllers\SupplierLedgerReportController;
use Modules\Sl\Http\Controllers\supplierPaymentController;
use Modules\Sl\Http\Controllers\SupplierTransactionAllocationController;

Route::prefix('sl')->middleware(['is.logged','cookie.approvalConfirm'])->group(function() {
    Route::get('/', function () {
        return view('sl::dashboard');
    })->middleware('is.logged');

    /** supplier debit note */
    Route::get('/supplier_debit_note',function(){
        return view('sl::debit_note_supplier');
    })->middleware(['is.logged']);

    Route::get('/supplier_debit_note_list',function(){
        return view('sl::debit_note_supplier_list');
    })->middleware(['is.logged','can:sl_supplier_debitNote']);

    Route::get('/get_debit_note_supplier_details',[SupplierDebitNoteController::class,'get_debit_note_supplier_details']);


    Route::post('/addDebitNotesupplier',[SupplierDebitNoteController::class,'addDebitNotesupplier']);
    Route::get('/getEachsupplierDebitNote/{id}',[SupplierDebitNoteController::class,'getEachsupplierDebitNote']);

    /**supplier credit note */
    Route::get('/supplier_credit_note',function(){
        return view('sl::credit_note_supplier');
    })->middleware(['is.logged']);

    Route::post('/addCreditNotesupplier',[SupplierCreditController::class,'addCreditNotesupplier']);
    Route::get('/get_credit_note_supplier_details',[SupplierCreditController::class,'get_credit_note_supplier_details']);
    Route::get('/credit_note_supplier_list',function(){
        return view('sl::credit_note_supplier_list');
    })->middleware(['is.logged','can:sl_supplier_creditNote']);
    Route::get('/getEachCreditNote/{id}',[SupplierCreditController::class,'getEachCreditNote']);


     //supplier payment
     Route::get('/supplier_payment',function(){
        return view('sl::supplier_payment');
    });
    Route::get('/supplier_payment_list',function(){
        return view('sl::supplier_payment_list');
    })->middleware(['can:sl_supplier_payment']);
    Route::get('/customer_receipt/loadSetoffTable/{sup}',[supplierPaymentController::class,'loadSetoffTable']);
    Route::post('/supplier_receipt/save_supplier_receipt',[supplierPaymentController::class,'save_supplier_receipt']);
    Route::get('/supplier_receipt/getReceiptMethod',[supplierPaymentController::class,'getReceiptMethod']);
    Route::get('/supplier_pyment_list/getReceiptList',[supplierPaymentController::class,'getReceiptList']);
    Route::get('/supplierReceiptReport/{id}',[supplierPaymentController::class,'supplierReceiptReport']);
    Route::get('/supplier_payment/getReceipt/{id}',[supplierPaymentController::class,'getReceipt']);

    /**Transaction allocation */
    Route::get('/supplier_transaction_allocation',function(){
        return view('sl::transaction_allocation');
    })->middleware(['can:sl_supplier_transaction_allocation']);
    
    Route::get('/supplier_transaction_allocation_list', function () {
        return  view('sl::transaction_allocation_list');
    })->middleware(['can:sl_supplier_transaction_allocation']);

    Route::get('/load_supplier_data/{code}',[SupplierTransactionAllocationController::class,'load_supplier_data']);
    Route::post('/save_supplier_transaction_allocation/{branch_id}',[SupplierTransactionAllocationController::class,'save_supplier_transaction_allocation']);
    Route::get('/get_transaction_allocation_details',[SupplierTransactionAllocationController::class,'get_transaction_allocation_details']);
    Route::get('/load_info/{id}',[SupplierTransactionAllocationController::class,'load_info']);

    /** Supplier reports */
    Route::get('/supplier_reports', function () {
        return view('sl::supplier_reports');
    })->middleware(['can:sl_supplier_report']);
    Route::get('/printsupoutstandinReport/{search}', [SupOutstandingcontroller::class, 'printsupoutstandinReport']);
    Route::get('/getSupplier',[SupOutstandingcontroller::class,'getSupplier']);
    Route::get('/supplier_Ledger_reports/{filters}',[SupplierLedgerReportController::class,'supplier_Ledger_reports']);
    Route::get('/creditorAgeAnalysisReport/{filters}',[CreditorAgeAnalysisController::class,'creditorAgeAnalysisReport']);
    Route::get('/supplier_cash_audit_report/{filters}',[SupplierCashAuditReportController::class,'supplier_cash_audit_report']);
    Route::get('/supplier_cheque_audit_report/{filters}',[SupplierChequeAuditReportController::class,'supplier_cheque_audit_report']);
    Route::get('/bank_transfer_report/{search}',[SupplierBankTransferReportController::class,'bank_transfer_report']);
});

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
use Modules\Dl\Http\Controllers\CreditNoteController;
use Modules\Dl\Http\Controllers\CreditNoteReceiptController;
use Modules\Dl\Http\Controllers\DebitNoteController;
use Modules\Dl\Http\Controllers\DebitNoteReceiptController;
use Modules\Dl\Http\Controllers\TransactionAllocationController;

Route::prefix('dl')->middleware(['is.logged','cookie.approvalConfirm'])->group(function() {

    /** transaction allocation */
    Route::get('/transaction_allocation_list', function () {
        return  view('dl::transaction_allocation_list');
    })->middleware(['is.logged','can:dl_customer_transaction_allocation']);
    Route::get('/transaction_allocation', function () {
        return  view('dl::transaction_allocation');
    })->middleware(['is.logged','can:dl_customer_transaction_allocation']);
    Route::get('/load_customer_data/{code}',[TransactionAllocationController::class,'load_customer_data']);
    Route::post('/save_customer_transaction_allocation/{br_id}',[TransactionAllocationController::class,'save_customer_transaction_allocation']);
    Route::get('/get_transaction_allocation_details',[TransactionAllocationController::class,'get_transaction_allocation_details']);
    Route::get('/load_info/{id}',[TransactionAllocationController::class,'load_info']);


    /**debit note */
    Route::get('/debit_note',function(){
        return view('dl::debit_note');
    })->middleware(['is.logged','can:dl_debit_note']);

    Route::get('/debit_note_list',function(){
        return view('dl::debit_note_list');
    })->middleware(['is.logged','can:dl_debit_note']);
    Route::post('/addDebitNote',[DebitNoteController::class,'addDebitNote']);
    Route::get('/get_debit_note_details',[DebitNoteController::class,'get_debit_note_details']);
    Route::get('/getEachDebitNote/{id}',[DebitNoteController::class,'getEachDebitNote']);
    Route::get('/print_dl/{id}',[DebitNoteReceiptController::class,'print_dl']);


    /** credit note */
    Route::get('/credit_note',function(){
        return view('dl::credit_note');
    })->middleware(['is.logged','can:dl_credit_note']);

    Route::get('/credit_note_list',function(){
        return view('dl::credit_note_list');
    })->middleware(['is.logged','can:dl_credit_note']);
    Route::get('/credit_note_approval_list',function(){
        return view('dl::pending_credit_note_list');
    });
    Route::post('/addCreditNote',[CreditNoteController::class,'addCreditNote']);
    Route::get('/get_credit_note_details',[CreditNoteController::class,'get_credit_note_details']);
    Route::get('/getEachcreditNote/{id}',[CreditNoteController::class,'getEachcreditNote']);
    Route::get('/getSalesRep',[CreditNoteController::class,'getSalesRep']);
    Route::get('/print_cr/{id}',[CreditNoteReceiptController::class,'print_cr']);
    Route::post('/approveCreditNote/{id}',[CreditNoteController::class,'approveCreditNote']);
    Route::post('/rejectCreditNote/{id}',[CreditNoteController::class,'rejectCreditNote']);
    Route::post('/reviseCreditNote/{id}',[CreditNoteController::class,'reviseCreditNote']);
    Route::get('/get_credit_note_pending_details',[CreditNoteController::class,'get_credit_note_pending_details']);
});

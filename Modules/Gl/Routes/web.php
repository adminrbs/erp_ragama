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
use Modules\Gl\Http\Controllers\JournalEntryController;

Route::prefix('gl')->group(function () {
    Route::get('/', 'GlController@index');

    /**GL Reports */
    Route::get('/gl_reports', function () {
        return view('gl::gl_report');
    });



    /**GL Journal Entry */
    Route::get('/journal_entry', function () {
        $id = null;
        $action = "create";
        return view('gl::journal_entry', compact('id', 'action'));
    });

    Route::get('/journal_entry/{id}/{action}', function ($id,$action) {
        return view('gl::journal_entry', compact('id', 'action'));
    });


    Route::get('/journal_entry_view/{id}', function ($id) {
        $action = "view";
        return view('gl::journal_entry_view', compact('id', 'action'));
    });


    Route::get('/journal_entries', function () {
        return view('gl::journal_entry_list');
    });

    Route::post('/saveJournal', [JournalEntryController::class, 'saveJournal']);
    Route::post('/updateJournal/{id}', [JournalEntryController::class, 'updateJournal']);
    Route::put('/approvalJournal/{id}', [JournalEntryController::class, 'approvalJournal']);
    Route::delete('/deleteJournal/{id}', [JournalEntryController::class, 'deleteJournal']);
    Route::get('/getJournalEntry/{id}', [JournalEntryController::class, 'getJournalEntry']);
    Route::get('/getJournalEntries', [JournalEntryController::class, 'getJournalEntries']);
    Route::get('/loadAccounts',[JournalEntryController::class,'loadAccounts']);
    Route::get('/get_gl_account_name/{id}',[JournalEntryController::class,'get_gl_account_name']);
    Route::get('/loadAccountAnalysisData/{id}',[JournalEntryController::class,'loadAccountAnalysisData']);
});

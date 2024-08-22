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
use Modules\St\Http\Controllers\AssinusertoBranchController;
use Modules\St\Http\Controllers\PermissionController;
use Modules\St\Http\Controllers\roleController;
use Modules\St\Http\Controllers\UserController;

Route::prefix('st')->middleware(['is.logged'])->group(function () {
    Route::get('/', function () {
        return view('st::dashboard');
    })->middleware('is.logged');



    //......role .......//
    Route::get('/Role', function () {
        return view('st::role');
    })->middleware(['is.logged','can:st_user_role']);


    Route::get('/useroleAllData', [roleController::class, 'getuserrole']);
    Route::post('/saveUserrole', [roleController::class, 'saveuserrole']);
    Route::get('/useroleEdite/{id}', [roleController::class, 'useroleEdite']);
    Route::post('/userroleUpdate/{id}', [roleController::class, 'userroleUpdate']);
    Route::post('/updateUserRoleStatus/{id}', [roleController::class, 'userRoleStatus']);
    Route::delete('/deleteUserole/{id}', [roleController::class, 'deleteUserole']);

    //Role list
    Route::get('/getuserData/{id}', [roleController::class, 'getuserData']);

    //.........users


    Route::get('/user', function () {
        return view('st::users');
    });
    Route::get('/userrole', [UserController::class, 'userrole']);
    Route::get('/getemployee', [UserController::class, 'getEmployee']);
    Route::post('/savenewUser', [UserController::class, 'saveuser']);


    //....USER LIST

    Route::get('/userlist', function () {
        return view('st::userList');
    })->middleware(['is.logged','can:st_users']);
    Route::get('/getuserAllData', [UserController::class, 'getuserAlldata']);
    Route::get('/usersEdite/{id}', [UserController::class, 'usersEdite']);
    Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);
    Route::delete('/deleteusers/{id}', [UserController::class, 'deleteusers']);

    /**permission */
    Route::get('/permissions', function () {
        return view('st::permission');
    })/* ->middleware(['is.logged','can:st_permission']) */;
     Route::get('/getRoleData',[PermissionController::class,'getRoleData']);
     Route::get('/getModuleList/{roleId}',[PermissionController::class,'getModuleList']); 
     /* Route::get('/getSelectedModules',[PermissionController::class,'getSelectedModules']); */
     Route::get('/allPermissions/{role_id}/{module_id}',[PermissionController::class,'allPermissions']);
     Route::get('/allSubPermissions/{role_id}/{module_id}/{permission_id}',[PermissionController::class,'allSubPermissions']);
     Route::post('/allowPermission',[PermissionController::class,'allowPermission']);
     Route::post('/addRoleModule/{module_id}/{role_id}',[PermissionController::class,'addRoleModule']);
     Route::delete('/deleteRoleModule/{module_id}/{role_id}',[PermissionController::class,'deleteRoleModule']);


     //......assing user to branch .......//
    Route::get('/assignusertoBranch', function () {
        return view('st::assignusertoBranch');
    })->middleware(['is.logged','can:st_assign_user_to_branch']);

    Route::get('/getuserDataTOlistbox/{id}', [AssinusertoBranchController::class, 'getFilterData']); 

    Route::get('/getBranch', [AssinusertoBranchController::class, 'getBranch']);
    Route::post('/addUsetBranch', [AssinusertoBranchController::class, 'addUsetBranch']);
    Route::get('/getUserBranchDteails', [AssinusertoBranchController::class, 'getUserBranchDteails']);
    Route::delete('/deleteuserBranch', [AssinusertoBranchController::class, 'deleteuserBranch']);
    Route::get('/getselectuser/{id}',[AssinusertoBranchController::class,'getselectuser']);

    Route::post('/selectdeletuserBranch',[AssinusertoBranchController::class,'selectdeletuserBranch']);

});

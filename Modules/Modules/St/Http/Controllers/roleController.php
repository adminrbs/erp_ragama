<?php

namespace Modules\St\Http\Controllers;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\St\Entities\role;
use Modules\St\Entities\User;
use Modules\St\Entities\user_role;
use Modules\St\Entities\UserRole;

class roleController extends Controller
{
    public function getuserrole(){

        try {
            $customerDteails = role::all();
            if ($customerDteails) {
                return response()->json((['success' => 'Data loaded', 'data' => $customerDteails]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
        }

    }

    //........Save......
    public function saveuserrole(Request $request){


        try {

            $userrole= new role();
            $userrole->name = $request->get('txtUserRole');


            if ($userrole->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($userrole->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //edit

    public function useroleEdite(Request $request,$id){
        $userrole = role::find($id);
		return response()->json($userrole);
    }


    //........... update...
    public function userroleUpdate(Request $request,$id){
        $userrole = role::findOrFail($id)->update([
            'name' => $request->txtUserRole,
        ]);
        return response()->json($userrole);

    }


    //Delete

    public function deleteUserole($id){

        $userrole = role::find($id);
        $userrole->delete();
    return response()->json(['success'=>'Record has been Delete']);

    }

    //Status Save

    public function userRoleStatus(Request $request,$id){
        $userrole = role::findOrFail($id);
        $userrole->status = $request->status;
        $userrole->save();

        return response()->json(' status updated successfully');
    }

//............role list..........

public function getuserData($id){

        try {
            $query = "SELECT users.*, users_roles.*, roles.id AS role_id, roles.name AS role_name
            FROM users
            INNER JOIN users_roles ON users.id = users_roles.user_id
            INNER JOIN roles ON users_roles.role_id = roles.id
            WHERE roles.id = :id
            ORDER BY users.id DESC";

  $customerDetails = DB::select($query, ['id' => $id]);

  return response()->json(['status' => true, 'data' => $customerDetails]);
    } catch (Exception $ex) {
        if ($ex instanceof ValidationException) {
            return response()->json([
                'ValidationException' => [
                    'id' => collect($ex->errors())->keys()[0],
                    'message' => $ex->errors()[collect($ex->errors())->keys()[0]]
                ]
            ]);
        }
    }


}


public function usersallEdite($id){
    try {


         $query = "SELECT users.*, user_roles.*, employees.*
       FROM users
       INNER JOIN user_roles ON users.id = user_roles.user_id
       LEFT JOIN employees ON users.user_id = employees.employee_id
       WHERE users.id = :id";

        $user = DB::select($query, ['id' => $id]);
        return response()->json(["user" => $user]);

    /*$user = User::find($id);
        return response()->json(["user" => $user]);*/
    } catch (Exception $ex) {
        return response()->json(["error" => $ex->getMessage()]);
    }


    }
    public function updateAllUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $inputPassword = $request->get('txtPassword');
            if ($inputPassword) {
                if ($user) {
                    $user->name = $request->input('txtname');
                    $user->email = $request->input('txtEmail');
                    $user->user_id = $request->input('cmbuEmployee');
                    $user_type = $request->input('cmbuserTypeRole');
                    $user->user_type = ($user_type == 0) ? 'Guest' : 'Employee';
                    $user->save();



                    $userRoleId = $request->input('cmbuserRole');
                    $userRole = UserRole::where('user_id', $user->id)->firstOrFail();

                    if ($userRoleId !== 'null') {
                        $userRole->role_id = $userRoleId;
                        $userRole->save();


                    }

                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['error' => 'User not found']);
                }

            } else {
                return response()->json(['status' => false]);
            }


        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

}

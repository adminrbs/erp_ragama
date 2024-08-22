<?php

namespace App\Http\Controllers;

use App\Models\global_setting;
use Dotenv\Exception\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{



    public function loginForm(Request $request){



        $email = $request->txtEmail;
        $password = $request->txtPassword;

        if (Auth::attempt(['email' => $email, 'password' => $password], true)) {
            // Authentication passed...
            $empId = Auth::user()->user_id;
            $userID = Auth::user()->id;
            //dd($empId);
            $branch_id_array ='';
            if($empId != null){
                $qry = "SELECT branch_id FROM user_baranchs WHERE user_id = $userID";
                $branch_id_array = DB::select($qry);
            }

            
            Session::put('empID', $empId);
            Session::put('branch_id_array', $branch_id_array);
            //dd(Session::get('branch_id_array'));
            $login_status = ["status" => "200", "redirect" => ""];


            return $login_status;
        } else {
            return "201";
        }



        /*if (!$request->has('txtEmail')) {
            return response()->json(['message' => 'Email not provided.'], 400);
        }

        $txtEmail = $request->input('txtEmail');
        $txtpassword = $request->input('txtPassword');



        // Check if the user with the given email exists in the database
        $user = User::where('email', $txtEmail)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }


        if (!Hash::check($txtpassword, $user->password)) {
            return response()->json(['message' =>'Invalide Password'], 401);
        }

        // If the email and password match, return a success message
        return response()->json(['message' => 'Login successful.']);*/
    }


//dashboard login

public function dashboardlogin(Request $request){



    $email = $request->txtEmail;
    $password = $request->txtPassword;

    if (Auth::attempt(['email' => $email, 'password' => $password], true)) {
        // Authentication passed...
      

        $login_status = ["status" => "200", "redirect" => ""];


        return $login_status;
    } else {
        return "201";
    }



    /*if (!$request->has('txtEmail')) {
        return response()->json(['message' => 'Email not provided.'], 400);
    }

    $txtEmail = $request->input('txtEmail');
    $txtpassword = $request->input('txtPassword');



    // Check if the user with the given email exists in the database
    $user = User::where('email', $txtEmail)->first();
    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }


    if (!Hash::check($txtpassword, $user->password)) {
        return response()->json(['message' =>'Invalide Password'], 401);
    }

    // If the email and password match, return a success message
    return response()->json(['message' => 'Login successful.']);*/
}


    // ...

   //public function logout()
   //{
   //    Auth::logout();

   //    return redirect('/submitForm');
   //}


   public function getLogoPpath(){
    try{
        $global_setting = global_setting::first();
        $path = $global_setting->logo_path;
        return response()->json(['logo_path' => $path]);
    }catch(Exception $ex){
        return $ex;
    }
   }
}




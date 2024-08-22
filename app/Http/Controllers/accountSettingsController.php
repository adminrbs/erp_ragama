<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class accountSettingsController extends Controller
{
    //update passwrod
    public function updatePassword(Request $reqest)
    {
        try {
            $id = Auth::user()->id;
            $current_password = $reqest->input('currentPassword');
            $new_password = $reqest->input('newPassword');

            $user = User::find($id);
            if ($user) {
                if (Hash::check($current_password, $user->password)) {
                    $user->password = Hash::make($new_password);
                    $user->update();
                    return response()->json(['status'=>true,'message' => 'updated']);
                }else{
                    return response()->json(['status'=>false,'message' => 'mismatched']);
                }
            }


        } catch (Exception $ex) {
            return $ex;
        }
    }
}

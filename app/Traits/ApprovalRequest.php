<?php

namespace App\Traits;

use App\Models\AuthenticationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

trait ApprovalRequest
{
    public static function isApproval($userId, $browser)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return false;
        }


        // Ensure the browser parameter is provided
        if (empty($browser)) {
            return false;
        }

        // Fetch the approval record for the user and browser
        $authRequest = AuthenticationRequest::where([
            ['user_id', '=', $userId],
            ['browser', '=', $browser]
        ])->first();

        if (!$authRequest || $authRequest->approval != 1) {
            return false;
        }

        // Check if the cookie matches the approval secret
        $cookieValue = request()->cookie($authRequest->approvalSecret);

        //dd($cookieValue);
        // Use hash_equals for secure string comparison
        if ($cookieValue !== null && hash_equals($cookieValue, $authRequest->approvalSecret)) {
            return true;
        }

        return false;
    }

    public static function sendRequest($browser, $id)
    {


        $cookieName = "RBS-user_" . $id;
        AuthenticationRequest::where('user_id', $id)
            ->where('browser', $browser)
            ->where('approvalSecret', $cookieName)
            ->delete();


        $auth_request =  AuthenticationRequest::where('user_id', $id)->where('browser', $browser)->first();
        //dd($auth_request);
        if ($auth_request == null) {
            if ($browser != null) {
                $authRequest = new AuthenticationRequest();
                $authRequest->user_id = $id;
                $authRequest->browser = $browser;
                if ($authRequest->save()) {
                    $cookieTime = 1;
                    $cookie = cookie($cookieName, $cookieName, $cookieTime, '/', null, false, false, false)->withSameSite('Lax');

                    return response()->json()->cookie($cookie);
                }
            }
        }
    }
}

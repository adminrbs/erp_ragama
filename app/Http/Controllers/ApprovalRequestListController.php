<?php

namespace App\Http\Controllers;

use App\Models\AuthenticationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class ApprovalRequestListController extends Controller
{
    //
    public function approvalRequestList()
    {

        $query = 'SELECT authentication_requests.id,
        users.`name`,
        users.email,
        authentication_requests.browser,
        authentication_requests.remark,
        authentication_requests.approval
        FROM authentication_requests
        INNER JOIN users ON authentication_requests.user_id = users.id';

        return DB::select($query);
    }


    public function confirmRequest(Request $request)
    {
        $id = $request->get('request_id');
        $status = $request->get('status');
        $time = $request->get('time');
        $remark = $request->get('remark');
        $authRequest = AuthenticationRequest::find($id);
        if ($authRequest) {
            //$user_id = Auth::user()->id;
            $minutes = $time;
            $cookieName = "RBS-user_{$authRequest->user_id}";
            $approvalSecret = $cookieName . "_" . rand(1000, 9999);
            $authRequest->approval = $status;
            $authRequest->approvalSecret = $approvalSecret;
            //$authRequest->time = $minutes;
            $authRequest->time = 262800;
            $authRequest->remark = $remark;
            $authRequest->update();
            //Cookie::queue($cookieName, $approvalSecret, $minutes); // Set the cookie with a value of 1
        }
    }


    public function inactiveRequest($id)
    {

        $authRequest = AuthenticationRequest::find($id);
        if ($authRequest) {
            $authRequest->approval = 0;
            $authRequest->update();
        }
    }
}

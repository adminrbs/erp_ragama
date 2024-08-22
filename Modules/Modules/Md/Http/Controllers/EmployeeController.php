<?php

namespace Modules\Md\Http\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Modules\Md\Entities\employee;
use Modules\Md\Entities\employee_designation;
use Dotenv\Exception\ValidationException;
use Modules\Md\Entities\employee_Status;
use Exception;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee');
    }
    //...employee status loard

    public function employeestatus()
    {
        $data = employee_Status::all();
        return response()->json($data);
    }
    //...........save employee

    public function reportEmployee()
    {
        $data = employee::orderBy('employee_id', 'ASC')->get();
        return response()->json($data);
    }



    public function saveEmployee(Request $request)
    {
        $file =  $request->file('file');
        $image_icon =  $request->get('imageIcon');

        // $userpassword =  $request->get('txtuserPassword');

        try {

            if ($_code = $request->input('code')) {
                $query = "SELECT COUNT(*) AS count FROM employees WHERE code = $_code";
                $reuslt = DB::select($query);
                if ($reuslt) {
                    if ($reuslt[0]->count > 0) {
                        return response()->json(["status" => false, "message" => "duplicated"]);
                    }
                } else if (strlen($_code) < 2) {
                    return response()->json(["status" => false, "message" => "length"]);
                }
            }

            if ($c_code = $request->get('txtEmployeeCode')) {
                $query = "SELECT COUNT(*) AS count FROM employees WHERE employee_code = '" . $c_code . "'";
                $reuslt = DB::select($query);
                if ($reuslt) {
                    if ($reuslt[0]->count > 0) {
                        return response()->json(["status" => false, "message" => "code_duplicated"]);
                    }
                }
            }

            $employee = new employee();
            $employee->employee_code = $request->get('txtEmployeeCode');
            $employee->employee_name = $request->get('txtNameinitial');
            $employee->full_name = $request->get('txtNamefull');
            $employee->nick_name = $request->get('txtNamenick');
            $employee->nic_no = $request->get('txtnic');
            $employee->emergency_contact_number = $request->get('txtemagcontact');
            $employee->from_town = $request->get('txttown');
            $employee->gps = $request->get('txtgps');
            $employee->date_of_birth = $request->get('txtdateofbirth');
            $employee->certificate_file_no = $request->get('txtcertificatefile');
            $employee->file_no = $request->get('txtfileno');

            $employee->office_mobile = $request->get('txtOfficemobileno');
            $employee->office_email = $request->get('txtofficeemail');
            $employee->persional_mobile = $request->get('txtPersionalmobile');
            $employee->persional_fixed = $request->get('txtPersionalfixedno');
            $employee->persional_email = $request->get('txtPersionalemail');
            $employee->address = $request->get('txtAddress');
            $employee->desgination_id = $request->get('cmbDesgination');
            $employee->report_to = $request->get('cmbReport');
            $employee->date_of_joined = $request->get('txtDateofjoined');
            $employee->date_of_resign = $request->get('txtDateofresign');
            $employee->status_id = $request->get('cmbempStatus');
            $employee->mobile_user_name  = $request->get('txtuserName');
            $employee->credit_amount_alert_limit = $request->input('txtAlertcreaditamountlimit');
            $employee->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
            $employee->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');
            $employee->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
            $employee->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
            $employee->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');
            $employee->sales_target = $request->input('txtSalesTarget');
            if ($request->get('cmbDesgination') == 7) {
                $employee->code = $request->input('code');
            }

            /* if ($userpassword  == null) {
                $employee->mobile_app_password = null;
            } else {
                $employee->mobile_app_password = Hash::make($request->get('txtuserPassword'));
            }*/

            $employee->note = $request->get('txtNote');




            if ($employee->save()) {
                $this->uploadImageIcon($image_icon, $employee->employee_id);
                $this->uploadAttachment($file, $employee->employee_id);

                return response()->json(['status' => true, 'file' => $file]);
            }
            return response()->json(['status' => false]);
        } catch (Exception $ex) {
            return response()->json($ex);
        }
    }
    // image upload
    public function uploadAttachment($file, $id)
    {

        if ($file) {
            $file_name = $file->getClientOriginalName();

            $filename = url('/') . '/attachment/' . uniqid() . '' . time() . '.' . str_replace(' ', '', $file_name);
            $filename = str_replace(' ', '', str_replace('\'', '', $filename));
            $file->move(public_path('attachment'), $filename);

            $attachment = employee::find($id);
            $attachment->employee_attachments = $filename;
            $attachment->save();
        } else {
            $attachment = employee::find($id);
            $attachment->employee_attachments = url('/') . '/images/' . 'profile.png';
            $attachment->save();
        }
    }


    private function uploadImageIcon($img, $id)
    {

        if ($img != "undefined") {
            $folderPath = 'employee_icon/' . $id . ".png";
            $image_parts = explode(";base64,", $img);
            $image_base64 = base64_decode($image_parts[1]);

            // Create the directory if it doesn't exist
            if (!file_exists('employee_icon')) {
                mkdir('employee_icon', 0777, true);
            }

            $file = $folderPath;
            file_put_contents($file, $image_base64);

            $employee = employee::find($id);
            $employee->employee_icon = url('/') . '/' . $file;
            $employee->save();
        } else {

            $employee = employee::find($id);
            $employee->employee_icon = url('/') . '/employee_icon/' . "profile.png";
            $employee->save();
        }

        return null;
    }

    //................List employee..........

    public function getEmployeeDetails(Request $request)
    {

        try {
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');


            /* $query = "SELECT employees.*, employee_designations.*, employee_statuses.*
            FROM employees
            LEFT JOIN employee_designations ON employees.desgination_id = employee_designations.employee_designation_id
            LEFT JOIN employee_statuses ON employees.status_id = employee_statuses.employee_status_id
            WHERE employees.employee_id != 1
            ORDER BY employees.employee_id DESC"; */

            $query = DB::table('employees')
                ->select('employees.*', 'employee_designations.*', 'employee_statuses.employee_status', DB::raw('SUBSTRING(employees.employee_name, 1, 20) as employee_name_substr'))
                ->leftJoin('employee_designations', 'employees.desgination_id', '=', 'employee_designations.employee_designation_id')
                ->leftJoin('employee_statuses', 'employees.status_id', '=', 'employee_statuses.employee_status_id')
                ->where('employees.employee_id', '!=', 1)
                ->orderByDesc('employees.employee_id');


            if (!empty($searchValue)) {
                $query->where(function ($query) use ($searchValue) {
                    $query->where('employees.employee_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('employees.employee_code', 'like', '%' . $searchValue . '%')
                        ->orWhere('employee_designations.employee_designation', 'like', '%' . $searchValue . '%')
                        ->orWhere('employee_statuses.employee_status', 'like', '%' . $searchValue . '%')
                        ->orWhere('employees.office_mobile', 'like', '%' . $searchValue . '%');
                });
            }

            $results = $query->take($pageLength)->skip($skip)->get();
            $results->transform(function ($item) {
                $disabled = "disabled";
                $buttons = '<button class="btn btn-primary btn-sm" onclick="edit(' . $item->employee_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-success btn-sm" onclick="view(' . $item->employee_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
                $buttons .= '<button class="btn btn-danger btn-sm" onclick="_delete(' . $item->employee_id . ')"' . $disabled . '><i class="fa fa-trash" aria-hidden="true"></i></button>';

                $item->buttons = $buttons;

                return $item;
            });



            return response()->json([
                'success' => 'Data loaded',
                'data' => $results,
                'draw' => request('draw'),
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function getEmployeedata($id)
    {

        try {
            //code...
            $employee = employee::find($id);
            return response()->json(["employee" => $employee]);
        } catch (Exception $ex) {
        }
        return response()->json(["error" => $ex]);
    }

    //..........Employee update.......

    public function employeeUpdate(Request $request, $id)
    {
        $file =  $request->file('file');
        $image_icon =  $request->get('imageIcon');

        // dd($request);
        /* $userpassword =  $request->get('txtuserPassword');
        if($c_code = $request->get('txtEmployeeCode')){
            $query = "SELECT COUNT(*) AS count FROM employees WHERE employee_code = '".$c_code."'";
            $reuslt = DB::select($query);
            if ($reuslt){
                if ($reuslt[0]->count > 1) {
                    return response()->json(["status" => false,"message" => "code_duplicated"]);
                }
            }
        }

        if ($userpassword == null) {
             //check user name
             if($_username = $request->input('txtuserName')){
                $query = "SELECT COUNT(*) AS count FROM employees WHERE mobile_user_name = '" . $_username . "'";

                $reuslt = DB::select($query);
                if ($reuslt){
                    if ($reuslt[0]->count > 1) {
                        return response()->json(["status" => false,"message" => "user_duplicated"]);
                    }
                }
            }*/


        if ($c_code = $request->get('txtEmployeeCode')) {
            $query = "SELECT COUNT(*) AS count FROM employees WHERE employee_code = '" . $c_code . "'";
            $reuslt = DB::select($query);
            if ($reuslt) {
                if ($reuslt[0]->count >= 1) {
                    $qry = "SELECT employee_code FROM employees WHERE employees.employee_id ='" . $id . "'";
                    $emp_code_result = DB::select($qry);
                    $emp_code = $emp_code_result[0]->employee_code;
                    if ($emp_code != $c_code) {
                        return response()->json(["status" => false, "message" => "code_duplicated"]);
                    }
                }
            }
        }
        $employee = employee::find($id);


        $employee->employee_code =  $request->get('txtEmployeeCode');
        $employee->employee_name = $request->get('txtNameinitial');
        $employee->full_name = $request->get('txtNamefull');
        $employee->nick_name = $request->get('txtNamenick');
        $employee->nic_no = $request->get('txtnic');
        $employee->emergency_contact_number = $request->get('txtemagcontact');
        $employee->from_town = $request->get('txttown');
        $employee->gps = $request->get('txtgps');
        $employee->date_of_birth = $request->get('txtdateofbirth');
        $employee->certificate_file_no = $request->get('txtcertificatefile');
        $employee->file_no = $request->get('txtfileno');
        $employee->office_mobile = $request->get('txtOfficemobileno');
        $employee->office_email = $request->get('txtofficeemail');
        $employee->persional_mobile = $request->get('txtPersionalmobile');
        $employee->persional_fixed = $request->get('txtPersionalfixedno');
        $employee->persional_email = $request->get('txtPersionalemail');
        $employee->address = $request->get('txtAddress');
        $employee->desgination_id = $request->get('cmbDesgination');
        $employee->report_to = $request->get('cmbReport');
        $employee->date_of_joined = $request->get('txtDateofjoined');
        $employee->date_of_resign = $request->get('txtDateofresign');
        $employee->status_id = $request->get('cmbempStatus');
        /*    $employee->mobile_user_name = $request->get('txtuserName'); */
        /*   $employee->mobile_app_password = $employee->mobile_app_password; */
        $employee->note = $request->get('txtNote');

        $employee->credit_amount_alert_limit = $request->input('txtAlertcreaditamountlimit');
        $employee->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
        $employee->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');
        $employee->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
        $employee->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
        $employee->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');
        $employee->sales_target = $request->input('txtSalesTarget');
        if ($request->get('cmbDesgination') == 7) {
            $employee->code = $request->input('code');
        }
        if ($employee->update()) {
            if (isset($file)) {
                $this->uploadImageIcon($image_icon, $employee->employee_id);
                $this->uploadAttachment($file, $employee->employee_id);
                return response()->json(['status' => true, 'file' => $file]);
            }
        }

        return response()->json(['status' => true]);


        //return response()->json(["employee" => $employee]);
        /* } else {

            if($_code = $request->input('code')){
                $query = "SELECT COUNT(*) AS count FROM employees WHERE code = $_code";
                $reuslt = DB::select($query);
                if ($reuslt){
                    if ($reuslt[0]->count > 0) {
                        return response()->json(["status" => false,"message" => "duplicated"]);
                    }
                }else if(strlen($_code) < 2){
                    return response()->json(["status" => false,"message" => "length"]);
                }
            }

            //check user name
            if($_username = $request->input('txtuserName')){
                $query = "SELECT COUNT(*) AS count FROM employees WHERE mobile_user_name = '" . $_username . "'";
                $reuslt = DB::select($query);
                if ($reuslt){
                    if ($reuslt[0]->count > 1) {
                        return response()->json(["status" => false,"message" => "user_duplicated"]);
                    }
                }
            }
            $employee = employee::find($id);
            $employee->employee_code = $request->get('txtEmployeeCode');
            $employee->txtNameinitial = $request->get('txtName');
            $employee->office_mobile = $request->get('txtOfficemobileno');
            $employee->office_email = $request->get('txtofficeemail');
            $employee->persional_mobile = $request->get('txtPersionalmobile');
            $employee->persional_fixed = $request->get('txtPersionalfixedno');
            $employee->persional_email = $request->get('txtPersionalemail');
            $employee->address = $request->get('txtAddress');
            $employee->desgination_id = $request->get('cmbDesgination');
            $employee->report_to = $request->get('cmbReport');
            $employee->date_of_joined = $request->get('txtDateofjoined');
            $employee->date_of_resign = $request->get('txtDateofresign');
            $employee->status_id = $request->get('cmbempStatus');
            $employee->mobile_user_name  = $request->get('txtuserName');
            $employee->credit_amount_alert_limit = $request->input('txtAlertcreaditamountlimit');
            $employee->credit_amount_hold_limit = $request->input('txtHoldcreditamountlimit');
            $employee->credit_period_alert_limit = $request->input('txtAlertcreditperiodlimit');
            $employee->credit_period_hold_limit = $request->input('txtHoldcreditperiodlimit');
            $employee->pd_cheque_limit = $request->input('txtPDchequeamountlimit');
            $employee->pd_cheque_max_period = $request->input('txtMaximumPdchequeperiod');
            $employee->mobile_app_password = Hash::make($request->get('txtuserPassword'));
            $employee->note = $request->get('txtNote');
            if($request->get('cmbDesgination') == 7){
                $employee->code = $request->input('code');
            }
        }

        if ($employee->update()) {

            return response()->json(["status" => true]);
        }
        return response()->json(["status" => false]);*/
    }
    //image update

    public function uploadUpdateAttachment($file, $id)
    {
        if ($file) {
            $file_name = $file->getClientOriginalName();
            $filename = url('/') . '/attachment/' . uniqid() . '' . time() . '.' . str_replace(' ', '', $file_name);
            $filename = str_replace(' ', '', str_replace('\'', '', $filename));
            $file->move(public_path('attachment'), $filename);

            $attachment = employee::find($id);

            // Delete previous photo
            if ($attachment->employee_attachments) {
                if (count(explode("/$attachment/", $attachment->employee_attachments)) == 2) {
                    $previous_photo = explode("/$attachment/", $attachment->employee_attachments)[1];
                    $previous_photo_path = public_path('attachment/' . $previous_photo);

                    if (\File::exists($previous_photo_path)) {
                        \File::delete($previous_photo_path);
                    }
                }
            }

            $attachment->employee_attachments = $filename;
            $attachment->save();
        }
    }
    //.........employee View......


    public function getEmployview($id)
    {
        try {
            //code...
            $employee = employee::find($id);
            return response()->json(["employee" => $employee]);
        } catch (Exception $ex) {
        }
        return response()->json(["error" => $ex]);
    }

    //.......Employee Delete......


    public function employeeDelete($id)
    {

        $employee = employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'message' => 'Student deleted successfully'
        ], 200);
    }

    public function empdesgnation()
    {
        $data = employee_designation::all();
        return response()->json($data);
    }

    public function empreport()
    {
        $query = "SELECT E.employee_id,E.employee_name FROM employees E";
        $data = DB::select($query);

        return response()->json($data);
    }

    public function getusarname(Request $request, $action, $id)
    {
        $bool = false;

        $userName =  $request->get('userName');
        if ($action == 'save') {


            $employee = employee::where('mobile_user_name', $userName)
                ->where('desgination_id', '=', 7)->first();

            if ($employee) {
                //return response()->json(['result' => true]);
                $bool = true;
            } else {
                //return response()->json(['result' => false]);
                $bool = false;
            }
        } else {
            $employee = employee::find($id);
            if ($employee) {
                if ($employee->mobile_user_name == $userName) {
                    $bool = false;
                } else {
                    $employee = employee::where('mobile_user_name', $userName)->first();

                    if ($employee) {
                        //return response()->json(['result' => true]);
                        $bool = true;
                    } else {
                        //return response()->json(['result' => false]);
                        $bool = false;
                    }
                }
            }
        }
        return response()->json(['result' => $bool]);
    }
}

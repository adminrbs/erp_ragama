<?php

namespace Modules\Md\Http\Controllers;


use Modules\Md\Entities\location;
use Modules\Md\Entities\locationType;
use Illuminate\Routing\Controller;
use Modules\Md\Entities\branch;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Locale;

class locationController extends Controller
{
    public function addLocation(Request $request){
        try{
            $request->validate([
                'txtName' => 'required'
               
            ]);

            $location = new location();
            $location ->branch_id = $request->input('cmbBranch');
            $location ->location_name = $request->input('txtName');
            $location ->address = $request->input('txtAddress');
            $location ->fixed_number = $request->input('txtFixed');
            $location ->email = $request->input('txtEmail');
            $location ->location_type_id = $request->input('cmbLocationType');
            $location ->Status = $request->input('chkStatus');

            if($location->save()){
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
            return response()->json(["error" => $ex]);

        }


    }

    public function getLocationTypes(){
        try{
            $locationTypeDetails = locationType::orderBy('location_type_id','asc')->get();
        
            if ($locationTypeDetails) {
                return response()->json($locationTypeDetails);
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        }catch(Exception $ex){
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
            return response()->json(["error" => $ex]);

        }

    }

    public function getLocationDetails(Request $request){
        try{
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = request('search.value');

            $query = DB::table('locations')
    ->select('location_id', 'location_name', 'locations.address', DB::raw('IF(Status=1,"Yes","No") AS is_active'), 'location_types.location_type_name','branches.branch_name')
    ->join('location_types', 'locations.location_type_id', '=', 'location_types.location_type_id')
    ->join('branches','locations.branch_id','=','branches.branch_id');
    
    if (!empty($searchValue)) {
        $query->where(function ($query) use ($searchValue) {
            $query->where('locations.location_name', 'like', '%' . $searchValue . '%')
                ->orWhere('locations.address', 'like', '%' . $searchValue . '%')
                
                ->orWhere('location_types.location_type_name', 'like', '%' . $searchValue . '%'); 
        });
    }

    $results = $query->take($pageLength)->skip($skip)->get();
    $results->transform(function ($item) {
        $disabled = "disabled";
        $buttons = '<button class="btn btn-primary btn-sm" onclick="edit(' . $item->location_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160';
        $buttons .= '<button class="btn btn-success btn-sm" onclick="view(' . $item->location_id . ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160';
        $buttons .= '<button class="btn btn-danger btn-sm" onclick="_delete(' . $item->location_id . ')"' . $disabled . '><i class="fa fa-trash" aria-hidden="true"></i></button>';

        $item->buttons = $buttons;


        $statusLabel = '<label class="badge badge-pill bg-danger"></label>';

            if ($item->is_active == "Yes") {
                $statusLabel = '<label class="badge badge-pill bg-success">Yes</label>';
            } else{
                $statusLabel = '<label class="badge badge-pill bg-danger">No</label>';
            }
    
            $item->statusLabel = $statusLabel;
        return $item;
    });

    return response()->json([
        'success' => 'Data loaded',
        'data' => $results,
        'draw' => request('draw'),
    ]);


        }catch(Exception $ex){
            return $ex;
        }
    }

    public function getEachLocationDetails($id){
        try{
            $seachLocation = location::find($id);
            if ($seachLocation) {
                return response()->json((['success' => 'Data loaded', 'data' => $seachLocation]));

            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }

        }catch(Exception $ex){
            return $ex;
        }

    }
    
    public function updateLocation(Request $request,$id){
        try{
            $request->validate([
                'txtName' => 'required',
               
            ]);

            $location = location::find($id);
            $location ->branch_id = $request->input('cmbBranch');
            $location ->location_name = $request->input('txtName');
            $location ->address = $request->input('txtAddress');
            $location ->fixed_number = $request->input('txtFixed');
            $location ->email = $request->input('txtEmail');
            $location ->location_type_id = $request->input('cmbLocationType');
            $location ->Status = $request->input('chkStatus');

            if($location->update()){
                return response()->json(["status" => true]);
            } else {
                return response()->json(["status" => false]);
            }

        }catch(Exception $ex){
            if ($ex instanceof ValidationException) {
                return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]);
            }
            return response()->json(["error" => $ex]);

        }

    }

    public function deleteLocation($id){
        $location = location::find($id);
        if($location->delete()){
            return response()->json(["status" => true]);
        } else {
            return response()->json(["status" => false]);
        }

    }

    public function getBranches(){
        try{
            $branch = branch::all();
            return response()->json($branch);

        }catch(Exception $ex){
            return $ex;
        }
    }
}

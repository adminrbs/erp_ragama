<?php

namespace Modules\Md\Http\Controllers;
use Illuminate\Routing\Controller;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Md\Entities\item_altenative_name;

class International_nonproprietaryController extends Controller
{
     //.....all data...........

     public function nonproprietaryAllData(){

        try {
            $customerDteails = item_altenative_name::all();
            if ($customerDteails) {
                return response()->json((['success' => 'Data loaded', 'data' => $customerDteails]));
            } else {
                return response()->json((['error' => 'Data is not loaded']));
            }
        } catch (Exception $ex) {
            if ($ex instanceof ValidationException) {
                /* return response()->json(["ValidationException" => ["id" => collect($ex->errors())->keys()[0], "message" => $ex->errors()[collect($ex->errors())->keys()[0]]]]); */
                return $ex;
            }
        }
    }

    //........Save......
    public function nonproprietaryGroup(Request $request){

        $validatedData = $request->validate([
            'txtNonproprietary' => 'required',
        ]);

        try {

            $item_altenative= new item_altenative_name();
            $item_altenative->item_altenative_name = $request->get('txtNonproprietary');


            if ($item_altenative->save()) {

                return response()->json(['status' => true]);
            } else {
                Log::error('Error saving common setting: ' . print_r($item_altenative->getErrors(), true));
                return response()->json(['status' => false]);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    //....edit....

    public function nonproprietaryEdite(Request $request,$id){
        $item_altenative = item_altenative_name::find($id);
		return response()->json($item_altenative);
    }


    //........... update...
    public function nonproprietaryUpdate(Request $request,$id){
        $item_altenative = item_altenative_name::findOrFail($id)->update([
            'item_altenative_name' => $request->txtNonproprietary,
        ]);
        return response()->json($item_altenative);

    }


    //......Delete........

    public function deleteNonproprietary($id){

        $item_altenative = item_altenative_name::find($id);
        $item_altenative->delete();
    return response()->json(['success'=>'Record has been Delete']);

    }

    //.......Status Save.........

    public function nonproprietaryStatus(Request $request,$id){
        $item_altenative = item_altenative_name::findOrFail($id);
        $item_altenative->status_id = $request->status;
        $item_altenative->save();

        return response()->json(' status updated successfully');
    }

  public function close(){
    return response()->json(' close');
  }

}

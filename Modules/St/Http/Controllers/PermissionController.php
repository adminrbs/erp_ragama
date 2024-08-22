<?php

namespace Modules\St\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\St\Entities\module;
use Modules\St\Entities\role;
use Modules\St\Entities\RoleModule;

class PermissionController extends Controller
{
    //load role data
    public function getRoleData()
    {
        try {
            $roleData = role::where("status", "=", 1)->get();
            if ($roleData) {
                return response()->json(["data" => $roleData]);
            } else {
                return response()->json(["data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load module data
    public function getModuleList($role_id)
    {
        try {
           /*  $moduleData = Module::all();
            if ($moduleData) {
                return response()->json(["data" => $moduleData]);
            } else {
                return response()->json(["data" => []]);
            } */
            $query = "SELECT modules.*,role_modules.role_id,role_modules.module_id as RM_moduleId FROM modules LEFT JOIN role_modules 
            ON modules.module_id = role_modules.module_id AND role_modules.role_id = $role_id";
            $reuslt = DB::select($query);
            if($reuslt){
                return response()->json(["data" => $reuslt]);
            }else{
                return response()->json(["data" => []]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    //load menu
    public function allPermissions($role_id, $module_id)
    {
        try {
            $query = "SELECT
           permissions.`id` AS id,
           permissions.`name` AS name,
           permissions.`slug` AS slug,
           permissions.`sub` AS sub,
           module_permissions.`module_id` AS module_id,
           module_permissions.`permission_id` AS permission_id
      FROM
           `permissions` permissions INNER JOIN `module_permissions` module_permissions ON permissions.`id` = module_permissions.`permission_id` WHERE module_permissions.module_id = '" . $module_id . "' AND permissions.sub = 'null'";

            $permissions = DB::select($query);
            foreach ($permissions as $permission) {

                $query = "SELECT * FROM `roles_permissions` WHERE role_id = '" . $role_id . "' AND permission_id = '" . $permission->permission_id . "'";
                $allows = DB::select($query);
                if ($allows) {
                    $permission->allow = true;
                } else {
                    $permission->allow = false;
                }

                $query_count = "SELECT COUNT(*) AS count FROM `permissions` WHERE sub = '" . $permission->permission_id . "'";
                $count = DB::select($query_count)[0];
                if ($count) {
                    $permission->sub_count = $count->count;
                }
            }


            return response()->json(["success" => true, "result" => $permissions]);
        } catch (\Exception $exception) {
            /*  $responseBody = $this->responseBody(false, "Permission", "error", $exception); */
            return $exception;
        }
        /*  return response()->json(["data" => $responseBody]); */
    }

    public function allSubPermissions($role_id, $module_id, $permission_id)
    {
        try {
            $permissions = $this->subPermissions($module_id, $permission_id);
            foreach ($permissions as $permission) {

                $query = "SELECT * FROM `roles_permissions` WHERE role_id = '" . $role_id . "' AND permission_id = '" . $permission->permission_id . "'";
                $allows = DB::select($query);
                if ($allows) {
                    $permission->allow = true;
                } else {
                    $permission->allow = false;
                }

                $query_count = "SELECT COUNT(*) AS count FROM `permissions` WHERE sub = '" . $permission->permission_id . "'";
                $count = DB::select($query_count)[0];
                if ($count) {
                    $permission->sub_count = $count->count;
                }
            }


            return response()->json(["success" => true, "result" => $permissions]);
        } catch (\Exception $exception) {
            /* $responseBody = $this->responseBody(false, "Permission", "error", $exception); */
            return $exception;
        }
        /*  return response()->json(["data" => $responseBody]); */
    }


    //allow permision
    public function allowPermission(Request $request)
    {
        try {
            $user_id = $request->get('user_id');
            $role_id = $request->get('role_id');
            $permission_id = $request->get('permission_id');
            $module_id = $request->get('module_id');
           
            $allow = $this->booleanToInteger($request->get('allow'));
            
            if ($allow == 1) {

                $this->allow($role_id, $permission_id);
                $this->allow_all($module_id, $role_id, $permission_id);
                $responseBody = response()->json(["success" => true, "result" => $allow]);
            } else {
                
                $this->remove($role_id, $permission_id);
                $this->remove_all($module_id, $role_id, $permission_id);
                $responseBody = response()->json(["success" => true, "result" => $allow]);
            }
        } catch (\Exception $exception) {
            $responseBody = $exception;
        }

        return $responseBody;
    }

    private function allow($role_id, $permission_id)
    {
        $query = "INSERT INTO roles_permissions (`role_id`,`permission_id`) VALUES('" . $role_id . "','" . $permission_id . "')";
        DB::insert($query);
    }

    private function allow_all($module_id, $role_id, $permission_id)
    {


        $sub_permissions = $this->subPermissions($module_id, $permission_id);
        foreach ($sub_permissions as $sub_permission) {
            $id = $sub_permission->id;
            $this->allow($role_id, $id);
            $this->allow_all($module_id, $role_id, $id);
        }
    }

    private function remove($role_id, $permission_id)
    {
        $query = "DELETE FROM roles_permissions WHERE role_id = '" . $role_id . "' AND permission_id = '" . $permission_id . "'";
        DB::delete($query);
    }


    private function remove_all($module_id, $role_id, $permission_id)
    {
       
        $sub_permissions = $this->subPermissions($module_id, $permission_id);
        foreach ($sub_permissions as $sub_permission) {
            $id = $sub_permission->id;
            $this->remove($role_id, $id);
            $this->remove_all($module_id, $role_id, $id);
        }
    }


    function booleanToInteger($bool)
    {
        if ($bool == 'true') {
            return 1;
        }
        return 0;
    }

    private function subPermissions($module_id, $permission_id)
    {

        $query = "SELECT
            permissions.`id` AS id,
            permissions.`name` AS name,
            permissions.`slug` AS slug,
            permissions.`sub` AS sub,
            module_permissions.`module_id` AS module_id,
            module_permissions.`permission_id` AS permission_id
       FROM
            `permissions` permissions INNER JOIN `module_permissions` module_permissions ON permissions.`id` = module_permissions.`permission_id` WHERE module_permissions.module_id = '" . $module_id . "' AND permissions.sub = '" . $permission_id . "'";
//dd($query);
        return  DB::select($query);
    }

    //add role module
    public function addRoleModule($moduleId,$role_id){
        try{
             $query = "SELECT COUNT(*) AS count FROM role_modules WHERE role_modules.role_id = $role_id AND role_modules.module_id = $moduleId";
             $result = DB::select($query);
             if($result[0]->count < 1){
                $roleModule = new RoleModule();
                $roleModule->role_id = $role_id;
                $roleModule->module_id = $moduleId;
                $roleModule->save();

             }

        }catch(Exception $ex){
            return $ex;
        } 
    }

    //delete role module
    public function deleteRoleModule($moduleId,$role_id){
        try{
             $query = "SELECT COUNT(*) AS count FROM role_modules WHERE role_modules.role_id = $role_id AND role_modules.module_id = $moduleId";
             $result = DB::select($query);
             if($result[0]->count >= 1){
                RoleModule::where('module_id', $moduleId)
                ->where('role_id', $role_id)
                ->delete();
               
             }

        }catch(Exception $ex){
            return $ex;
        } 
    }
}

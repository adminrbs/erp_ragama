<?php

namespace App\Permissions;

use App\Models\Module;
use App\Models\RoleModule;
use App\Models\UserRole;

trait HasModulesTrait
{

    public function hasModule($module)
    {
        return Module::all()->contains('module_name', $module);
    }


    public function hasModulePermission($module_name)
    {
        $user_role = UserRole::where('user_id', '=', $this->id)->first();
        if ($user_role) {
            $role_modules = RoleModule::where('role_id', '=', $user_role->role_id)->get();
            foreach ($role_modules as $role_module) {
                $module = Module::find($role_module->module_id);
                if ($module->module_name == $module_name) {
                    return true;
                }
            }
        }
        return false;
    }




}

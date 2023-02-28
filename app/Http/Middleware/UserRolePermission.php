<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class UserRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $assignId = null)
    {

     
       if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        $permissions =   app('permission');

        if(!$this->hasPermission($assignId)){
            abort(403);
        }

        if( (! is_null($permissions)) &&  (Auth::user()->role_id != 1) ){
            if( in_array($assignId , $permissions )){
                return $next($request);
            }
            else{
                abort('403');
            }
        }

        else{
            return $next($request);
        }
    }

    public function hasPermission($module_id){
        $student_or_parent = in_array(auth()->user()->role_id, [2,3]);


        if($student_or_parent){
            $permissions = InfixModuleStudentParentInfo::where('id', $module_id)->with('parent')->first();
            if($permissions){
                if($permissions->parent){
                    $key = $permissions->parent->admin_section;
                } else{
                    $key = $permissions->admin_section;
                }
                if($key) {
                    return isMenuAllowToShow($key);
                }
            }

            return true;
        } else{
            $module_ids = getPlanPermissionMenuModuleId();


            $permissions = InfixModuleInfo::where('parent_id', 0)->with(['children' ])->whereIn('id', $module_ids)->get();

            $parent_module = $permissions->where('id', $module_id)->first();

            if(!$parent_module){
                foreach($permissions as $permission){
                    $children_module = $permission->children->where('id', $module_id)->first();
                    if($children_module){
                        $parent_module = $permission;
                        break;
                    }
                }
            }

            if($parent_module){
                $parent_module_id = $parent_module->id;

                // get permission name

               $key = getModuleAdminSection($parent_module_id);

                if($key) {
                    return isMenuAllowToShow($key);
                }
            }
        }

    return true;
    }
}
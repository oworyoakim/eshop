<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

class RolesController extends TenantBaseController {
    /**
     * RolesController constructor.
     */
    public function __construct()
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            if (!Sentinel::hasAccess('employees.roles')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $roles = Role::all();
            return view('manager.employees.roles.index', compact('roles'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        try {
            if (!Sentinel::hasAccess('employees.roles')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $data = array();
            $permissions = Permission::where('parent_id', 0)->get();
            foreach ($permissions as $permission) {
                array_push($data, $permission);
                $subs = Permission::where('parent_id', $permission->id)->get();
                foreach ($subs as $sub) {
                    array_push($data, $sub);
                }
            }
            return view('manager.employees.roles.create', compact('data'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createProcess(Request $request) {
        try {
            if (!Sentinel::hasAccess('employees.roles')) {
                Flash::warning("Permission Denied");
                return redirect()->back();
            }
//        dd($request->permission);
            $role = new Role();
            $role->name = $request->name;
            $role->slug = str_slug($request->name, '');
            $role->save();
            if (!empty($request->permission)) {
                foreach ($request->permission as $key) {
                    $role->updatePermission($key, true, true)->save();
                }
            }
            Flash::success("Role Saved!");
            return redirect('manager/employees/roles');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($subdomain,$id) {
        try {
            
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($subdomain,$id) {
        try {
            if (!Sentinel::hasAccess('employees.roles')) {
                Flash::warning("Permission Denied");
                return redirect()->back();
            }

            $data = array();
            $permissions = Permission::where('parent_id', 0)->get();
            foreach ($permissions as $permission) {
                array_push($data, $permission);
                $subs = Permission::where('parent_id', $permission->id)->get();
                foreach ($subs as $sub) {
                    array_push($data, $sub);
                }
            }
            $role = Role::find($id);
            return view('manager.employees.roles.update', compact('data', 'role'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProcess(Request $request, $subdomain,$id) {
        try {
            if (!Sentinel::hasAccess('employees.roles')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }
//        dd($request->permission);
            $role = Sentinel::findRoleById($id);
            $role->name = $request->name;
            $role->slug = str_slug($request->name, '');
            $role->permissions = array();
            $role->save();
            //remove permissions which have not been ticked
            //create and/or update permissions
            if (!empty($request->permission)) {
                foreach ($request->permission as $key) {
                    $role->updatePermission($key, true, true)->save();
                }
            }

            Flash::success("Role Saved!");
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($subdomain,$id) {
        try {
            if (!Sentinel::hasAccess('employees.roles')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }
            Role::destroy($id);
            Flash::success("Role Deleted!");
            return redirect('manager/employees/roles');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}

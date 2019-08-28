<?php

namespace App\Http\Controllers\System;

use App\Models\Permission;
use App\Models\Role;
use App\Http\Controllers\SystemBaseController;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

class RolesController extends SystemBaseController {
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
            if (!Sentinel::hasAccess('users.roles')) {
                Flash::warning("Permission Denied");
                return redirect('/');
            }

            $data = Role::all();
            return view('admin.users.roles.index', compact('data'));
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
            if (!Sentinel::hasAccess('users.roles')) {
                Flash::warning("Permission Denied");
                return redirect('/');
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
            return view('admin.users.roles.create', compact('data'));
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
            if (!Sentinel::hasAccess('users.roles')) {
                Flash::warning("Permission Denied");
                return redirect('/');
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
            Flash::success("Successfully Saved");
            return redirect('admin/users/roles');
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
    public function show($id) {
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
    public function update($id) {
        try {
            if (!Sentinel::hasAccess('users.roles')) {
                Flash::warning("Permission Denied");
                return redirect('/');
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
            return view('admin.users.roles.update', compact('data', 'role'));
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
    public function updateProcess(Request $request, $id) {
        try {
            if (!Sentinel::hasAccess('users.roles')) {
                Flash::warning("Permission Denied");
                return redirect('/');
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


            Flash::success("Role Successfully Saved");
            return redirect('admin/users/roles');
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
    public function destroy($id) {
        try {
            Role::destroy($id);
            Flash::success("Successfully Saved");
            return redirect('admin/users/roles');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}

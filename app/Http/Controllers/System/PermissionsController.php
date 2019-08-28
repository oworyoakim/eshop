<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Cartalyst\Sentinel\Roles\RoleInterface;
use Laracasts\Flash\Flash;
use Exception;

class PermissionsController extends SystemBaseController {
    /**
     * PermissionsController constructor.
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
            $data = array();
            $permissions = Permission::where('parent_id', 0)->get();
            foreach ($permissions as $permission) {
                array_push($data, $permission);
                $subs = Permission::where('parent_id', $permission->id)->get();
                foreach ($subs as $sub) {
                    array_push($data, $sub);
                }
            }
            return view('admin.users.permissions.index', compact('data'));
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
            $parents = Permission::where('parent_id', 0)->get();
            $parent = array();
            $parent['0'] = "None";
            foreach ($parents as $key) {
                $parent[$key->id] = $key->name;
            }

            return view('admin.users.permissions.create', compact('parent'));
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
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->parent_id = $request->parent_id;
            $permission->description = $request->description;
            if (!empty($request->slug)) {
                $permission->slug = $request->slug;
            } else {
                $permission->slug = str_slug($request->name, '_');
            }

            $permission->save();
            Flash::success("Permission Saved!");
            return redirect('admin/users/permissions');
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
            $permission = Permission::find($id);
            $parents = Permission::where('parent_id', 0)->get();
            $parent = array();
            $parent['0'] = "None";
            foreach ($parents as $key) {
                $parent[$key->id] = $key->name;
            }
            if ($permission->parent_id == 0) {
                $selected = 0;
            } else {
                $selected = 1;
            }

            return view('admin.users.permissions.update', compact('parent', 'permission', 'selected'));
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
            $permission = Permission::find($id);
            $permission->name = $request->name;
            $permission->parent_id = $request->parent_id;
            $permission->description = $request->description;
            if (!empty($request->slug)) {
                $permission->slug = $request->slug;
            } else {
                $permission->slug = str_slug($request->name, '_');
            }
            $permission->save();
            Flash::success("Permission Saved!");
            return redirect('admin/users/permissions');
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
            Permission::destroy($id);
            Flash::success("Permission Deleted!");
            return redirect('admin/users/permissions');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}

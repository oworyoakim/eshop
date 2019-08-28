<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\IBranchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

class EmployeesController extends TenantBaseController {

    /**
     * @var IEmployeeRepository
     */
    protected $employeeRepository;

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * EmployeesController constructor.
     * @param IEmployeeRepository $employeeRepository
     */
    public function __construct(IEmployeeRepository $employeeRepository, IBranchRepository $branchRepository) {
        $this->employeeRepository = $employeeRepository;
        $this->branchRepository = $branchRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            if (!Sentinel::hasAccess('employees.view')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            $roles = [];
            $branches = [];
            $branch_id = $user->roles()->first()->pivot->branch_id;
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                $employees = $branch->employees;
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
                $employees = $this->employeeRepository->all();
            }

            foreach (Sentinel::getRoleRepository()->get() as $row){
                $roles[$row->id] = $row->name;
            }

            return view('employees.index', compact('employees', 'roles','branch_id','branches'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile() {
        try {
            $user = Sentinel::getUser();
            $role = $user->roles()->first();
            if($role->slug === 'cashier'){
                return view('cashier.profile',compact('user'));
            }
            return view('profile',compact('user'));
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
            if (!Sentinel::hasAccess('users.create')) {
                Flash::success('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            $branch_id = $user->roles()->first()->pivot->branch_id;
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
            }else{
                $branch = null;
            }

            $roles = [];
            foreach (Sentinel::getRoleRepository()->get() as $row) {
                $roles[$row->id] = $row->name;
            }

            $brows = $this->branchRepository->all();

            $branches = [];
            foreach ($brows as $row) {
                $branches[$row->id] = $row->name;
            }
            return view('employees.create', compact('roles', 'branches','branch'));
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
            if (!Sentinel::hasAccess('users.create')) {
                Flash::success('Permission Denied!');
                return redirect()->back();
            }

            $loggedInUser = $this->employeeRepository->find(Sentinel::getUser()->id);

            //dd($user);
            $branch_id = $loggedInUser->branches()->first()->pivot->branch_id;
            
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'role_id' => 'required',
                'gender' => 'required',
                'dob' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'confirmed|required',
                'password_confirmation' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $credentials = [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'gender' => $request->get('gender'),
                'dob' => $request->get('dob'),
                'address' => $request->get('address'),
                'password' => $request->get('password'),
                'avatar' => 'png'
            ];
            // register the user
            $user = Sentinel::registerAndActivate($credentials);
            // attach this user to the role
            $role = Sentinel::findRoleById($request->get('role_id'));

            $user->roles()->attach($role->id, [
                'branch_id' => $request->get('branch_id'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'active'=>1
            ]);

            Flash::success('User Created!');
            return redirect('manager/employees');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput()->with(["error" => $ex]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProcess(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

    public function processProfile(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }

            $loggedUser = Sentinel::getUser();
            $userid = intval($request->user_id);

            if (!$userid) {
                Flash::warning('Invalid Request');
                return redirect()->back()->withInput();
            }

            $user = $this->employeeRepository->find($userid);

            if (!$user) {
                Flash::warning('Invalid Request');
                return redirect()->back()->withInput();
            }

            if ($loggedUser->id != $user->id) {
                Flash::warning('Invalid Request');
                return redirect()->back()->withInput();
            }
//        dd($request->all());

            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'country' => 'required',
                'city' => 'required',
                'phone' => 'required'
            ];
            // if new email address
            if ($user->email !== $request->email) {
                $rules['email'] = 'required|email|unique:users';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $credentials = [
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone'),
                    'country' => $request->get('country'),
                    'city' => $request->get('city'),
                    'address' => $request->get('address')
                ];

                $this->employeeRepository->update($user->id,$credentials);

                if ($request->hasFile('avatar')) {
                    $file = array('avatar' => $request->file('avatar'));
                    $rules = array('avatar' => 'required|mimes:jpeg,jpg,bmp,png');
                    $validator = Validator::make($file, $rules);
                    if ($validator->fails()) {
                        Flash::warning(trans('general.validation_error'));
                        return redirect()->back()->withInput()->withErrors($validator);
                    } else {
                        $ext = $request->file('avatar')->getClientOriginalExtension();
                        $fileName = md5($request->file('avatar')->getClientOriginalName()).'.'.$ext;
                        $this->employeeRepository->update($user->id, ['avatar' => $fileName]);
                        $request->file('avatar')->move(public_path() . '/uploads/user-images', $fileName);
                    }
                }

                Flash::success("Profile Updated!");
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function processChangePassword(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }
//        dd($request->all());
            $rules = [
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $user = Sentinel::getUser();

                $credentials = [
                    'password' => $request->new_password
                ];
                $user = Sentinel::update($user, $credentials);
                Flash::success("Password Changed!");
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

}

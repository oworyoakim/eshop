<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICustomerRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use Illuminate\Http\Request;
use Flash;
use Exception;
use Sentinel;
use Validator;


class CustomersController extends TenantBaseController
{
    /**
     * @var ICustomerRepository
     */
    protected $customerRepository;

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;
    /**
     * @var IEmployeeRepository
     */
    private $employeeRepository;

    /**
     * CustomersController constructor.
     * @param ICustomerRepository $customerRepository
     * @param IBranchRepository $branchRepository
     * @param IEmployeeRepository $employeeRepository
     */
    public function __construct(ICustomerRepository $customerRepository,
                                IBranchRepository $branchRepository,
                                IEmployeeRepository $employeeRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->branchRepository = $branchRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($subdomain)
    {
        try {
            if (!Sentinel::hasAnyAccess(['customers', 'customers.view'])) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }
            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $branch_id = null;
            $branch = $user->getActiveBranch();
            if (Sentinel::inRole('businessmanager')) {
                $customers = $this->customerRepository->all();
                foreach ($this->branchRepository->all() as $row):
                    $branches[$row->id] = $row->name;
                endforeach;
            } elseif ($branch) {
                $branch_id = $branch->id;
                $branches[$branch->id] = $branch->name;
                $customers = $branch->customers;
            } else {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }
            return view('customers.index', compact('customers', 'branches', 'branch_id'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $subdomain)
    {
        try {
            if (!Sentinel::hasAccess('customers.create')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }
            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            //dd($request->all());

            $rules = [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'branch_id' => 'required'
            ];

            $email = $request->get('email');

            if ($email) {
                $rules['email'] = 'email|unique:customers';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Flash::warning('validation_error');
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $customer = $this->customerRepository->create([
                'name' => $request->get('name'),
                'email' => $email,
                'phone' => $request->get('phone'),
                'address' => $request->get('address'),
                'branch_id' => $request->get('branch_id'),
                'user_id' => $user->id
            ]);

            Flash::success('Customer Saved!');
            return redirect()->back()->with('customer_id',$customer->id);
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAnyAccess(['customers', 'customers.view'])) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $customer = $this->customerRepository->find($id);
            if (!$customer) {
                Flash::warning("Invalid Customer!");
                return redirect()->back();
            }
            return view('customers.show', compact('customer'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('customers.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $customer = $this->customerRepository->find($id);
            if (!$customer) {
                Flash::warning("Invalid Customer!");
                return redirect()->back();
            }

            $branch = $user->getActiveBranch();
            if (Sentinel::inRole('businessmanager')) {
                foreach ($this->branchRepository->all() as $row):
                    $branches[$row->id] = $row->name;
                endforeach;
            } elseif ($branch) {
                $branches[$branch->id] = $branch->name;
            } else {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }
            return view('customers.update', compact('customer', 'branches'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('customers.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $customer = $this->customerRepository->find($id);
            if (!$customer) {
                Flash::warning("Invalid Customer!");
                return redirect()->back();
            }

            //dd($request->all());

            $rules = [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'branch_id' => 'required'
            ];

            $email = $request->get('email');

            if ($email && $email !== $customer->email) {
                $rules['email'] = 'email|unique:customers';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Flash::warning('validation_error');
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $customer->update([
                'name' => $request->get('name'),
                'email' => $email,
                'phone' => $request->get('phone'),
                'address' => $request->get('address'),
                'branch_id' => $request->get('branch_id'),
                'user_id' => $user->id
            ]);

            Flash::success('Customer Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('customers.delete')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $customer = $this->customerRepository->find($id);
            if (!$customer) {
                Flash::warning("Invalid Customer!");
                return redirect()->back();
            }

            $customer->delete();

            Flash::success('Customer Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

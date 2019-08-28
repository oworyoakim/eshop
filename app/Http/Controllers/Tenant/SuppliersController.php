<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ISupplierRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class SuppliersController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var ISupplierRepository
     */
    protected $supplierRepository;

    /**
     * SuppliersController constructor.
     * @param IBranchRepository $branchRepository
     * @param ISupplierRepository $supplierRepository
     */
    public function __construct(IBranchRepository $branchRepository, ISupplierRepository $supplierRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->supplierRepository = $supplierRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAnyAccess(['suppliers','suppliers.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $suppliers = $this->supplierRepository->findBy([
                    'branch_id' => $branch_id
                ]);
                $branches[$branch->id] = $branch->name;
            } else {
                $suppliers = $this->supplierRepository->all();
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
            }

            return view('manager.suppliers.index', compact('suppliers','branches','branch_id'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            if (!Sentinel::hasAccess('suppliers.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
            }
            return view('manager.suppliers.create',compact('branches','branch_id'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createProcess(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('suppliers.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $this->supplierRepository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
                'branch_id' => $request->get('branch_id'),
                'user_id' => $user->id,
            ]);
            Flash::success('Supplier Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
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
            if (!Sentinel::hasAccess('suppliers.show')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $supplier = $this->supplierRepository->find($id);
            if (!$supplier) {
                Flash::warning('Invalid Supplier!');
                return redirect()->back();
            }

            return view('manager.suppliers.show', compact('supplier'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('suppliers.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $supplier = $this->supplierRepository->find($id);
            if (!$supplier) {
                Flash::warning('Invalid Supplier!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
            }
            return view('manager.suppliers.update', compact('supplier','branches','branch_id'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateProcess(Request $request,$subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('suppliers.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $supplier = $this->supplierRepository->find($id);
            if (!$supplier) {
                Flash::warning('Invalid Supplier!');
                return redirect()->back();
            }

            $this->supplierRepository->update($id, [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
                'branch_id' => $request->get('branch_id')
            ]);
            Flash::success('Supplier Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($subdomain,$id)
    {
        try {
            if (!Sentinel::hasAccess('suppliers.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $supplier = $this->supplierRepository->find($id);
            if (!$supplier) {
                Flash::warning('Invalid Supplier!');
                return redirect()->back();
            }
            $this->supplierRepository->delete($id);
            Flash::success('Supplier Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }
}

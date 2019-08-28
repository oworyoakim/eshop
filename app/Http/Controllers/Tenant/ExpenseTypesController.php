<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\IExpenseRepository;
use App\Repositories\Tenant\IExpenseTypeRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class ExpenseTypesController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;
    /**
     * @var IExpenseTypeRepository
     */
    protected $expenseTypeRepository;
    /**
     * @var IExpenseRepository
     */
    protected $expenseRepository;

    /**
     * ExpensesController constructor.
     * @param IBranchRepository $branchRepository
     * @param IExpenseTypeRepository $expenseTypeRepository
     * @param IExpenseRepository $expenseRepository
     */
    public function __construct(IBranchRepository $branchRepository,IExpenseTypeRepository $expenseTypeRepository, IExpenseRepository $expenseRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->expenseTypeRepository = $expenseTypeRepository;
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        if (!Sentinel::hasAccess('expenses.types')) {
            Flash::warning('Permission Denied!');
            return redirect()->back();
        }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $branches = [];
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                $expense_types = $this->expenseTypeRepository->findBy([
                    'branch_id' => $branch_id
                ]);
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
                $expense_types = $this->expenseTypeRepository->all();
            }
        return view('manager.expenses.types.index', compact('expense_types','branches','branch_id'));
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
            if (!Sentinel::hasAccess('expenses.types')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $branches = [];
            foreach ($this->branchRepository->all() as $row){
                $branches[$row->id] = $row->name;
            }
            return view('manager.expenses.types.create', compact('branches','branch_id'));
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
            if (!Sentinel::hasAccess('expenses.types')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            // $branch_id = $user->roles()->first()->pivot->branch_id;

            $this->expenseTypeRepository->create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'branch_id' => $request->get('branch_id'),
                'user_id' => $user->id,
            ]);
            Flash::success('Expense Type Saved!');
            return redirect()->back();
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
    public function update($subdomain,$id)
    {
        try {
            if (!Sentinel::hasAccess('expenses.types')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $rows = $this->branchRepository->all();
            
            $branches = [];
            foreach ($rows as $row){
                $branches[$row->id] = $row->name;
            }

            $expense_type = $this->expenseTypeRepository->find($id);
            if (!$expense_type) {
                Flash::warning('Invalid Expense Type!');
                return redirect()->back();
            }
            return view('manager.expenses.types.update', compact('expense_type','branches','branch_id'));
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
    public function updateProcess(Request $request, $subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('expenses.types')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $expense_type = $this->expenseTypeRepository->find($id);
            if (!$expense_type) {
                Flash::warning('Invalid Expense Type!');
                return redirect()->back();
            }
            $this->expenseTypeRepository->update($id, [
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'branch_id' => $request->get('branch_id')
            ]);
            Flash::success('Expense Type Saved!');
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
            if (!Sentinel::hasAccess('expenses.types.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $expense_type = $this->expenseTypeRepository->find($id);
            if (!$expense_type) {
                Flash::warning('Invalid Expense Type!');
                return redirect()->back();
            }
            $this->expenseTypeRepository->delete($id);
            Flash::success('Expense Type Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }
}

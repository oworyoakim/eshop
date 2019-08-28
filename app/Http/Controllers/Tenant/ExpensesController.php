<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\IExpenseRepository;
use App\Repositories\Tenant\IExpenseTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class ExpensesController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IEmployeeRepository
     */
    protected $employeeRepository;
    /**
     * @var IExpenseRepository
     */
    protected $expenseRepository;

    /**
     * @var IExpenseTypeRepository
     */
    protected $expenseTypeRepository;

    /**
     * ExpensesController constructor.
     * @param IBranchRepository $branchRepository
     * @param IExpenseRepository $expenseRepository
     * @param IExpenseTypeRepository $expenseTypeRepository
     * @param IEmployeeRepository $employeeRepository
     */
    public function __construct(IBranchRepository $branchRepository, IExpenseRepository $expenseRepository, IExpenseTypeRepository $expenseTypeRepository, IEmployeeRepository $employeeRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->expenseRepository = $expenseRepository;
        $this->expenseTypeRepository = $expenseTypeRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAccess('expenses')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);

            $branch = $user->getActiveBranch();

            $branch_id = null;

            $branches = $this->branchRepository->all();

            if ($branch) {
                $branch_id = $branch->id;
                $expenses = $this->expenseRepository->findBy([
                    'branch_id' => $branch->id
                ]);
                $expense_types = $this->expenseTypeRepository->findBy([
                    'branch_id' => $branch->id
                ]);
            } else {
                $expenses = $this->expenseRepository->all();
                $expense_types = $this->expenseTypeRepository->all();
            }

            return view('expenses.index', compact('expenses', 'expense_types', 'branch', 'branches', 'branch_id'));
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
            if (!Sentinel::hasAccess('expenses.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);

            $branch = $user->getActiveBranch();

            $branch_id = null;

            $expense_types = [];
            $branches = [];
            if ($branch) {
                if (Sentinel::inRole('businessmanager')) {
                    foreach ($this->branchRepository->all() as $row) {
                        $branches[$row->id] = $row->name;
                    }
                } else {
                    $branches[$branch->id] = $branch->name;
                }
                $branch_id = $branch->id;
                $types = $this->expenseTypeRepository->findBy([
                    'branch_id' => $branch->id
                ]);
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
                $types = $this->expenseTypeRepository->all();
            }
            foreach ($types as $row) {
                $expense_types[$row->id] = $row->title;
            }

            return view('expenses.create', compact('expense_types', 'branches', 'branch', 'branch_id'));
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
            if (!Sentinel::hasAccess('expenses.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;

            if ($branch_id && $branch_id !== $request->get('branch_id')) {
                Flash::warning("Invalid Branch!");
                return redirect()->back()->withInput();
            }

            $this->expenseRepository->create([
                'barcode' => time(),
                'expense_type_id' => $request->get('expense_type_id'),
                'amount' => $request->get('amount'),
                'comment' => $request->get('comment'),
                'status' => $request->get('status'),
                'branch_id' => $request->get('branch_id'),
                'user_id' => $user->id,
            ]);

            Flash::success('Expense Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            if (!Sentinel::hasAccess('expenses.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;

            $expense = $this->expenseRepository->find($id);

            if (!$expense) {
                Flash::warning('Invalid Expense!');
                return redirect()->back();
            }

            $branches = [];
            $expense_types = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $types = $this->expenseTypeRepository->findBy([
                    'branch_id' => $branch_id
                ]);
            } else {
                $branch = null;
                $types = $this->expenseTypeRepository->all();
            }
            $brows = $this->branchRepository->all();
            foreach ($brows as $row) {
                $branches[$row->id] = $row->name;
            }
            foreach ($types as $row) {
                $expense_types[$row->id] = $row->title;
            }

            return view('expenses.update', compact('expense', 'expense_types', 'branches', 'branch'));
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
    public function updateProcess(Request $request, $id)
    {
        try {
            if (!Sentinel::hasAccess('expenses.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $expense = $this->expenseRepository->find($id);
            if (!$expense) {
                Flash::warning('Invalid Expense!');
                return redirect()->back();
            }

            $this->expenseRepository->update($id, [
                'branch_id' => $request->get('branch_id'),
                'expense_type_id' => $request->get('expense_type_id'),
                'amount' => $request->get('amount'),
                'comment' => $request->get('comment'),
                'status' => $request->get('status')
            ]);
            Flash::success('Expense Saved!');
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
    public function destroy($id)
    {
        try {
            if (!Sentinel::hasAccess('expenses.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $expense = $this->expenseRepository->find($id);
            if (!$expense) {
                Flash::warning('Invalid Expense!');
                return redirect()->back();
            }
            $this->expenseRepository->delete($id);
            Flash::success('Expense Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }
}

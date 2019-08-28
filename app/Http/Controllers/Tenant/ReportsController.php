<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\IExpenseRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IPurchasesRepository;
use App\Repositories\Tenant\ISalesRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

class ReportsController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;
    /**
     * @var IPurchasesRepository
     */
    private $purchasesRepository;
    /**
     * @var IExpenseRepository
     */
    private $expenseRepository;
    /**
     * @var ISalesRepository
     */
    private $salesRepository;

    /**
     * @var IProductRepository
     */
    private $productRepository;

    /**
     * @var IEmployeeRepository
     */
    private $employeeRepository;

    /**
     * ReportsController constructor.
     * @param IBranchRepository $branchRepository
     * @param ISalesRepository $salesRepository
     * @param IPurchasesRepository $purchasesRepository
     * @param IExpenseRepository $expenseRepository
     * @param IProductRepository $productRepository
     * @param IEmployeeRepository $employeeRepository
     */
    public function __construct(IBranchRepository $branchRepository,
                                ISalesRepository $salesRepository,
                                IPurchasesRepository $purchasesRepository,
                                IExpenseRepository $expenseRepository,
                                IProductRepository $productRepository,
                                IEmployeeRepository $employeeRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->purchasesRepository = $purchasesRepository;
        $this->expenseRepository = $expenseRepository;
        $this->salesRepository = $salesRepository;
        $this->productRepository = $productRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            //dd($request->all());

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $branch_id = $request->get('branch_id');
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
            } else {
                $branch = $user->getActiveBranch();
            }

            if (Sentinel::inRole('businessmanager')) {
                $branches = [
                    0 => "All branches"
                ];
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
            } elseif ($branch) {
                $branches[$branch->id] = $branch->name;
            } else {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            $totalSales = 0;
            $totalPurchases = 0;
            $totalExpenses = 0;

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            $sdate = $start_date;

            $months = $sales = $expenses = $purchases = [];
            while ($sdate <= $end_date) {
                // get the year
                $year = date('Y', strtotime($sdate));
                // get the month
                $month = date('M', strtotime($sdate));

                if ($branch_id) {
                    $sale = $this->salesRepository->getBranchTotalMonthlySales($branch_id, $sdate);
                    $purchase = $this->purchasesRepository->getBranchTotalMonthlyPurchases($branch_id, $sdate);
                    $expense = $this->expenseRepository->getBranchTotalMonthlyExpenses($branch_id, $sdate);
                } elseif ($branch) {
                    $sale = $this->salesRepository->getBranchTotalMonthlySales($branch->id, $sdate);
                    $purchase = $this->purchasesRepository->getBranchTotalMonthlyPurchases($branch->id, $sdate);
                    $expense = $this->expenseRepository->getBranchTotalMonthlyExpenses($branch->id, $sdate);
                } elseif (Sentinel::inRole('businessmanager')) {
                    $sale = $this->salesRepository->getTotalMonthlySales($sdate);
                    $purchase = $this->purchasesRepository->getTotalMonthlyPurchases($sdate);
                    $expense = $this->expenseRepository->getTotalMonthlyExpenses($sdate);
                } else {
                    Flash::warning('Invalid Branch!');
                    return redirect()->back();
                }

                $totalSales += $sale;
                $totalPurchases += $purchase;
                $totalExpenses += $expense;

                $ext_month = $month . ' ' . $year;

                // dd($ext_month,$sale,$expense,$purchase);

                array_push($months, $ext_month);
                array_push($sales, $sale);
                array_push($expenses, $expense);
                array_push($purchases, $purchase);


                //add 1 month to start date
                $sdate = date_format(date_add(date_create($sdate), date_interval_create_from_date_string('1 months')), 'Y-m-d');
            }

            $totals = array($totalSales, $totalPurchases, $totalExpenses);

            $graphData = array(
                "months" => $months,
                "sales" => $sales,
                "expenses" => $expenses,
                "purchases" => $purchases
            );
            // dd($graphData);
            return view('reports.index', compact('start_date', 'end_date', 'branches', 'branch', 'branch_id', 'graphData', 'totals'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function salesReport(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $branch_id = $request->get('branch_id');

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                if (Sentinel::inRole('businessmanager')) {
                    $branches = [
                        0 => "All branches"
                    ];
                    foreach ($this->branchRepository->all() as $row) {
                        $branches[$row->id] = $row->name;
                    }
                } else {
                    $branches[$branch->id] = $branch->name;
                }
                $salesSummary = $this->salesRepository->getSalesSummary($start_date, $end_date, $branch_id);

            } else {
                $branch = $user->getActiveBranch();
                if (Sentinel::inRole('businessmanager')) {
                    $branches = [
                        0 => "All branches"
                    ];
                    foreach ($this->branchRepository->all() as $row) {
                        $branches[$row->id] = $row->name;
                    }
                    $salesSummary = $this->salesRepository->getSalesSummary($start_date, $end_date);
                } elseif ($branch) {
                    $branches[$branch->id] = $branch->name;
                    $salesSummary = $this->salesRepository->getSalesSummary($start_date, $end_date, $branch->id);
                } else {
                    Flash::warning('Invalid Branch!');
                    return redirect()->back();
                }
            }

            //dd($salesSummary);

            $data = $salesSummary['data'];
            $graphData = $salesSummary['graphData'];

            return view('reports.sales', compact('branches', 'branch_id', 'start_date', 'end_date', 'data', 'graphData'));

        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function dailySales(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $branch_id = $request->get('branch_id');

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from 7 days ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('7 days')), 'Y-m-d');
            }

            $allBranches = $this->branchRepository->all();
            $data = [];

            if ($branch_id) {
                $brows = $this->branchRepository->findBy(['id' => $branch_id]);
            } else {
                $branch = $user->getActiveBranch();
                if (Sentinel::inRole('businessmanager')) {
                    $brows = $allBranches;
                } elseif ($branch) {
                    $brows = $this->branchRepository->findBy(['id' => $branch->id]);
                } else {
                    Flash::warning('Invalid Branch!');
                    return redirect()->back();
                }
            }

            foreach ($brows as $branch) {
                if (Sentinel::inRole('businessmanager')) {
                    // we need to return all branches to form
                    $branches = [
                        0 => "All branches"
                    ];
                    foreach ($allBranches as $row) {
                        $branches[$row->id] = $row->name;
                    }
                } else {
                    $branches[$branch->id] = $branch->name;
                }
                $data[] = $this->salesRepository->getBranchDailySalesValues($branch, $start_date, $end_date);
            }

            //dd($data);

            return view('reports.sales_daily', compact('branches', 'branch_id', 'start_date', 'end_date', 'data'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function monthlySales(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $branch_id = $request->get('branch_id');

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from 5 months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('5 months')), 'Y-m-d');
            }

            $allBranches = $this->branchRepository->all();
            $data = [];

            if ($branch_id) {
                $brows = $this->branchRepository->findBy(['id' => $branch_id]);
            } else {
                $branch = $user->getActiveBranch();
                if (Sentinel::inRole('businessmanager')) {
                    $brows = $allBranches;
                } elseif ($branch) {
                    $brows = $this->branchRepository->findBy(['id' => $branch->id]);
                } else {
                    Flash::warning('Invalid Branch!');
                    return redirect()->back();
                }
            }

            foreach ($brows as $branch) {
                if (Sentinel::inRole('businessmanager')) {
                    // we need to return all branches to form
                    $branches = [
                        0 => "All branches"
                    ];
                    foreach ($allBranches as $row) {
                        $branches[$row->id] = $row->name;
                    }
                } else {
                    $branches[$branch->id] = $branch->name;
                }
                $data[] = $this->salesRepository->getBranchMonthlySalesValues($branch, $start_date, $end_date);
            }

            //dd($data);

            return view('reports.sales_monthly', compact('branches', 'branch_id', 'start_date', 'end_date', 'data'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function salesReceivable(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }

            $branch_id = $request->get('branch_id');
            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from 1 month ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('1 months')), 'Y-m-d');
            }

            $allBranches = $this->branchRepository->all();

            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $brows = [$branch];
                if (Sentinel::inRole('businessmanager')) {
                    $branches = [
                        0 => "All branches"
                    ];
                    foreach ($allBranches as $row) {
                        $branches[$row->id] = $row->name;
                    }
                } else {
                    $branches[$branch->id] = $branch->name;
                }
            } else {
                $branch = $user->getActiveBranch();
                if (Sentinel::inRole('businessmanager')) {
                    $branches = [
                        0 => "All branches"
                    ];
                    $brows = $allBranches;
                    foreach ($allBranches as $row) {
                        $branches[$row->id] = $row->name;
                    }
                } elseif ($branch) {
                    $brows = [$branch];
                    $branches[$branch->id] = $branch->name;
                } else {
                    Flash::warning('Invalid Branch!');
                    return redirect()->back();
                }
            }

            $data = [];

            foreach ($brows as $branch) {
                $data[] = [
                    'branch' => $branch,
                    'receivables' => $this->salesRepository->getAccountsReceivables($start_date, $end_date, $branch->id),
                ];
            }
            return view('reports.sales_receivable', compact('branches', 'branch_id', 'start_date', 'end_date', 'data'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function purchasesReport(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $request->get('branch_id');
            if (!$branch_id) {
                $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            }

            $branch = null;
            if (Sentinel::inRole('businessmanager') || !$branch_id) {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
                $products = $this->productRepository->getProducts();
            } else {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                $products = $this->productRepository->getBranchProducts($branch_id);
            }
            $data = [];
            //dd($products);

            $totalPurchases = 0;
            $totalVolume = 0;
            $totalReturns = 0;
            $totalDiscount = 0;

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            foreach ($products as $product) {
                if ($branch_id) {
                    $purchase = $this->purchasesRepository->getProductTotalPurchases($product->id, $start_date, $end_date, $branch_id);
                    $volume = $this->purchasesRepository->getProductTotalVolume($product->id, $start_date, $end_date, $branch_id);
                    $returns = $this->purchasesRepository->getProductTotalReturns($product->id, $start_date, $end_date, $branch_id);
                    $discount = $this->purchasesRepository->getProductTotalDiscount($product->id, $start_date, $end_date, $branch_id);
                } else {
                    //dd($product->id);
                    $purchase = $this->purchasesRepository->getProductTotalPurchases($product->id, $start_date, $end_date);
                    $volume = $this->purchasesRepository->getProductTotalVolume($product->id, $start_date, $end_date);
                    $returns = $this->purchasesRepository->getProductTotalReturns($product->id, $start_date, $end_date);
                    $discount = $this->purchasesRepository->getProductTotalDiscount($product->id, $start_date, $end_date);
                }
                $data[$product->id] = [
                    'purchases' => $purchase,
                    'volume' => $volume,
                    'returns' => $returns,
                    'discount' => $discount,
                    'product' => $product->title . ' => ' . $product->category
                ];

                $totalPurchases += $purchase;
                $totalVolume += $volume;
                $totalReturns += $returns;
                $totalDiscount += $discount;
            }

            //dd($data);

            $graphData = [
                'purchases' => $totalPurchases,
                'volume' => $totalVolume,
                'returns' => $totalReturns,
                'discount' => $totalDiscount,
            ];

            return view('reports.purchases', compact('branches', 'branch_id', 'start_date', 'end_date', 'data', 'graphData'));

        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function branchesOverall(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $request->get('branch_id');
            if (!$branch_id) {
                $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            }
            $branch = null;
            $data = [];

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }


            if (Sentinel::inRole('businessmanager') || !$branch_id) {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
                $receivables = $this->salesRepository->getAccountsReceivables($start_date, $end_date);
                // more data
            } else {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                $receivables = $this->salesRepository->getAccountsReceivables($start_date, $end_date, $branch_id);
                // more data
            }


            return view('reports.branches_overall', compact('branches', 'branch_id', 'start_date', 'end_date', 'data'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function accountsPayableReport(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $request->get('branch_id');
            if (!$branch_id) {
                $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            }
            $branch = null;

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            if (Sentinel::inRole('businessmanager') || !$branch_id) {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
                $payables = $this->purchasesRepository->getAccountsPayable($start_date, $end_date);
            } else {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                $payables = $this->purchasesRepository->getAccountsPayable($start_date, $end_date, $branch_id);
            }

            return view('reports.accounts_payable', compact('branches', 'branch_id', 'start_date', 'end_date', 'payables'));

        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function expensesReport(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $request->get('branch_id');
            if (!$branch_id) {
                $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            }
            $branch = null;
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
            }

            $totalSales = 0;
            $totalPurchases = 0;
            $totalExpenses = 0;

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            $sdate = $start_date;

            return view('reports.expenses', compact('branches', 'branch_id', 'start_date', 'end_date'));

        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function profitAndLossReport(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $request->get('branch_id');
            if (!$branch_id) {
                $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            }
            $branch = null;
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
            }

            $totalSales = 0;
            $totalPurchases = 0;
            $totalExpenses = 0;

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            $sdate = $start_date;

            return view('reports.income', compact('branches', 'branch_id', 'start_date', 'end_date'));

        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function balanceSheetReport(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('reports')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $request->get('branch_id');
            if (!$branch_id) {
                $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            }
            $branch = null;
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
            }

            $totalSales = 0;
            $totalPurchases = 0;
            $totalExpenses = 0;

            $end_date = $request->get('end_date');
            if (!$end_date) {
                $end_date = date('Y-m-d');
            }
            $start_date = $request->get('start_date');
            if (!$start_date) {
                // from three months ago
                $start_date = date_format(date_sub(date_create($end_date), date_interval_create_from_date_string('2 months')), 'Y-m-d');
            }

            $sdate = $start_date;

            return view('reports.balance_sheet', compact('branches', 'branch_id', 'start_date', 'end_date'));

        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}

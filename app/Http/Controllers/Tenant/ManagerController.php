<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\System\IUserRepository;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\ICustomerRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\IExpenseRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IPurchasesRepository;
use App\Repositories\Tenant\ISalesRepository;
use App\Repositories\Tenant\ISupplierRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Flash;
use Sentinel;

class ManagerController extends TenantBaseController
{
    /**
     * @var ISupplierRepository
     */
    protected $supplierRepository;
    /**
     * @var ICustomerRepository
     */
    protected $customerRepository;

    /**
     * @var ISalesRepository
     */
    protected $salesRepository;

    /**
     * @var IProductRepository
     */
    protected $productRepository;

    /**
     * @var ICategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var IUserRepository;
     */
    protected $userRepository;

    /**
     * @var IBusinessRepository
     */
    protected $businessRepository;

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IPurchasesRepository
     */
    protected $purchasesRepository;

    /**
     * @var IExpenseRepository
     */
    protected $expenseRepository;

    /**
     * @var IEmployeeRepository
     */
    protected $employeeRepository;

    /**
     * ManagerController constructor.
     * @param ISupplierRepository $supplierRepository
     * @param ICustomerRepository $customerRepository
     * @param ISalesRepository $salesRepository
     * @param IPurchasesRepository $purchasesRepository
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     * @param IUserRepository $userRepository
     * @param IBranchRepository $branchRepository
     * @param IExpenseRepository $expenseRepository
     * @param IEmployeeRepository $employeeRepository
     */
    public function __construct(ISupplierRepository $supplierRepository,
                                ICustomerRepository $customerRepository,
                                ISalesRepository $salesRepository,
                                IPurchasesRepository $purchasesRepository,
                                IProductRepository $productRepository,
                                ICategoryRepository $categoryRepository,
                                IUserRepository $userRepository,
                                IBranchRepository $branchRepository,
                                IExpenseRepository $expenseRepository,
                                IEmployeeRepository $employeeRepository)
    {
        $this->supplierRepository = $supplierRepository;
        $this->customerRepository = $customerRepository;
        $this->salesRepository = $salesRepository;
        $this->purchasesRepository = $purchasesRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->branchRepository = $branchRepository;
        $this->expenseRepository = $expenseRepository;
        $this->employeeRepository = $employeeRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAccess('manager.dashboard')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            //dd('ok');
            $user = $this->employeeRepository->find(Sentinel::getUser()->id);

            //dd($user);
            $branch = $user->getActiveBranch();

            $branch_id = null;

            $branches = $this->branchRepository->all();
            $date = date('Y-m-d');
            //dd($date);

            if (Sentinel::inRole('businessmanager')) {
                $suppliers = $this->supplierRepository->all();
                $customers = $this->customerRepository->all();
                $products = $this->productRepository->all();
                $categories = $this->categoryRepository->all();
                $latest_sales = $this->salesRepository->getLatestSales();
                $total_returned_sales = $this->salesRepository->getDaysReturnedSales($date);
                $total_sales_by_price = $this->salesRepository->getDaysTotalSalesByPrice($date);
                $top_selling_for_month = $this->salesRepository->getCurrentMonthTopSellingProducts();
                $top_selling_by_price = $this->salesRepository->getTopSellingProductsByPrice();
                $top_selling_by_qty = $this->salesRepository->getTopSellingProductsByQuantity();
                $total_purchases = $this->purchasesRepository->getTotalPurchases($date);
                $total_returned_purchases = $this->purchasesRepository->getTotalReturnedPurchases($date);
                $total_expenses = $this->expenseRepository->getTotalExpenses($date);
                $total_returned_expenses = $this->expenseRepository->getTotalReturnedExpenses($date);
                $purchases = $this->purchasesRepository->all();
            } elseif($branch){
                $branch_id = $branch->id;
                $suppliers = $branch->suppliers;
                $customers = $branch->customers;
                $products = $branch->products;
                $categories = $branch->categories;
                $latest_sales = $this->salesRepository->getLatestSales($branch_id);
                $total_returned_sales = $this->salesRepository->getBranchDaysReturnedSales($branch_id);
                $total_sales_by_price = $this->salesRepository->getBranchDaysTotalSalesByPrice($branch_id);
                $top_selling_for_month = $this->salesRepository->getBranchCurrentMonthTopSellingProducts($branch_id);
                $top_selling_by_price = $this->salesRepository->getBranchTopSellingProductsByPrice($branch_id);
                $top_selling_by_qty = $this->salesRepository->getBranchTopSellingProductsByQuantity($branch_id);
                $total_purchases = $this->purchasesRepository->getBranchTotalPurchases($branch_id,$date);
                $total_returned_purchases = $this->purchasesRepository->getBranchTotalReturnedPurchases($branch_id,$date);
                $total_expenses = $this->expenseRepository->getBranchTotalExpenses($branch_id,$date);
                $total_returned_expenses = $this->expenseRepository->getBranchTotalReturnedExpenses($branch_id,$date);
                $purchases = $branch->purchases;
            }else{
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            return view('dashboard', compact('user', 'business', 'branch', 'branches', 'customers', 'suppliers', 'products', 'categories', 'purchases', 'top_selling_for_month','top_selling_by_price','top_selling_by_qty','total_sales_by_price','latest_sales','total_returned_sales','total_purchases','total_returned_purchases','total_expenses','total_returned_expenses','branch_id'));
        } catch (Exception $ex) {
            //return back();
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createUser()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function createUserProcess(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function updateProcess(Request $request, $id)
    {
        //
    }
}

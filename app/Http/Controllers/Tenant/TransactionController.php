<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\ISalesRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class TransactionController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IProductRepository
     */
    protected $productRepository;

    /**
     * @var ICategoryRepository
     */
    protected $categoryRepository;


    /**
     * @var ISalesRepository
     */
    protected $salesRepository;

    /**
     * SalesController constructor.
     * @param IBranchRepository $branchRepository
     * @param ISalesRepository $salesRepository
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     */
    public function __construct(IBranchRepository $branchRepository,ISalesRepository $salesRepository,IProductRepository $productRepository, ICategoryRepository $categoryRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->salesRepository = $salesRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAccess('sales.view')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;

            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $categories = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                $products = $this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]);
                $sales = $this->salesRepository->findBy([
                    'branch_id' => $branch_id
                ]);

            }else{
                $branch = null;
                $categories = $this->categoryRepository->all();
                $products = $this->productRepository->all();
                $sales = $this->salesRepository->all();
            }

            return view('manager.sales.index', compact('branch', 'sales', 'products' ,'categories'));
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
    public function create()
    {
        try {
            if (!Sentinel::hasAccess('sales.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;

            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $categories = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                $products = $this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]);
                $sales = $this->salesRepository->findBy([
                    'branch_id' => $branch_id
                ]);

            }else{
                $branch = null;
                $categories = $this->categoryRepository->all();
                $products = $this->productRepository->all();
                $sales = $this->salesRepository->all();
            }

            return view('manager.sales.create', compact('branch', 'sales', 'products' ,'categories'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified Transaction Invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if (!Sentinel::hasAccess('sales.view')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            return view('manager.invoices.index');

        }catch (Exception $ex){
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

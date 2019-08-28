<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\ISalesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class CashierController extends TenantBaseController {

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
     * CashierController constructor.
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     */
    public function __construct(IProductRepository $productRepository, ICategoryRepository $categoryRepository, ISalesRepository $salesRepository) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->salesRepository = $salesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            if (!Sentinel::hasAccess('sales.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            
            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;

            if ($branch_id) {
                $categories = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                $products = $this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                $sales = $this->salesRepository->findBy([
                    'branch_id' => $branch_id,
                    'user_id' => $user->id
                ]);
            } else {
                $categories = $this->categoryRepository->all();
                $products = $this->productRepository->all();
                $sales = $this->salesRepository->all();
            }

            return view('cashier.sales.index', compact('$this->user', 'products', 'categories', 'sales'));
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}

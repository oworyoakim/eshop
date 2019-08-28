<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IPurchasesRepository;
use App\Repositories\Tenant\ISettingRepository;
use App\Repositories\Tenant\IStockRepository;
use App\Repositories\Tenant\ISupplierRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

class PurchasesController extends TenantBaseController
{
    /**
     * @var IPurchasesRepository
     */
    protected $purchasesRepository;

    /**
     * @var IProductRepository
     */
    protected $productRepository;

    /**
     * @var ICategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ISettingRepository
     */
    protected $settingRepository;

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var ISupplierRepository
     */
    protected $supplierRepository;

    /**
     * @var IStockRepository
     */
    protected $stockRepository;

    /**
     * PurchasesController constructor.
     * @param IBranchRepository $branchRepository
     * @param IPurchasesRepository $purchasesRepository
     * @param IProductRepository $productsRepository
     * @param ICategoryRepository $categoryRepository
     * @param ISettingRepository $settingRepository
     * @param ISupplierRepository $supplierRepository
     * @param IStockRepository $stockRepository
     */
    public function __construct(IBranchRepository $branchRepository,
                                IPurchasesRepository $purchasesRepository,
                                IProductRepository $productsRepository,
                                ICategoryRepository $categoryRepository,
                                ISettingRepository $settingRepository,
                                ISupplierRepository $supplierRepository,
                                IStockRepository $stockRepository)
    {
        $this->purchasesRepository = $purchasesRepository;
        $this->productRepository = $productsRepository;
        $this->categoryRepository = $categoryRepository;
        $this->settingRepository = $settingRepository;
        $this->branchRepository = $branchRepository;
        $this->supplierRepository = $supplierRepository;
        $this->stockRepository = $stockRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if(!Sentinel::hasAccess('purchases')){
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;

            $branches = [];
            $categories = [];
            $products = [];

            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;

                $catRows = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                foreach ($catRows as $row){
                    $categories[$row->id]  = $row->title;
                }

                $prodRows = $this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                foreach ($prodRows as $row){
                    $products[$row->id]  = $row->title .' ('.$row->category->title .') in '.$row->unit->slug;
                }

                $purchases = $this->purchasesRepository->findBy([
                    'branch_id'=>$branch_id
                ]);
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }

                $catRows = $this->categoryRepository->all();
                foreach ($catRows as $row){
                    $categories[$row->id]  = $row->title;
                }

                $prodRows = $this->productRepository->all();
                foreach ($prodRows as $row){
                    $products[$row->id]  = $row->title .' ('.$row->category->title .') in '.$row->unit->slug;
                }

                $purchases = $this->purchasesRepository->all();
            }

            return view('manager.purchases.index',compact('purchases','branches','branch_id','products','categories'));
        }catch (Exception $ex){
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
        try{
            if(!Sentinel::hasAccess('purchases.create')){
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;

            $branches = [];
            $categories = [];
            $suppliers = [];

            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;

                $products = $this->productRepository->getBranchProducts($branch_id);

            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }

                $products = $this->productRepository->getProducts();
            }

            foreach ($this->categoryRepository->all() as $row){
                $categories[$row->id]  = $row->title;
            }

            foreach ($this->supplierRepository->all() as $row){
                $suppliers[$row->id]  = $row->name;
            }

            $invoiceNumber = time();

            return view('manager.purchases.create',compact('user','branches','branch_id','products','categories','suppliers','invoiceNumber'));
        }catch (Exception $ex){
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createProcess(Request $request,$subdomain)
    {
        try{
            if(!Sentinel::hasAccess('purchases.create')){
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $data = [];
            $user = Sentinel::getUser();

            // dd($request->all());

            $data['transcode'] = $request->get('invoiceNumber');
            $data['supplier_id'] = $request->get('supplier_id');
            $data['branch_id'] = $request->get('branch_id');
            $data['gross_amount'] = floatval(str_replace(',','',$request->get('grandTotal')));
            $data['net_amount'] = floatval(str_replace(',','',$request->get('grandTotal')));
            $data['paid_amount'] = floatval(str_replace(',','',$request->get('amountPaid')));
            $data['due_amount'] = floatval(str_replace(',','',$request->get('amountDue')));
            $data['user_id'] = $user->id;
            $data['transact_date'] = $request->get('paymentDate');
            $data['status'] = $request->get('status');
            if (intval(str_replace(',','',$request->get('amountDue'))) > 0){
                $data['payment_status'] = 'partial';
            } elseif (intval(str_replace(',','',$request->get('amountPaid'))) === 0){
                $data['payment_status'] = 'pending';
            }else {
                $data['payment_status'] = 'settled';
            }
            //dd($request->all());
            // dd($data);

            $trans = $this->purchasesRepository->create($data);
            if(!$trans){
                Flash::warning('Failed create purchase!');
                return redirect()->back()->withInput();
            }


            foreach ($request->get('basketItems') as $key => $value){
                //dd($value);
                $product = $this->productRepository->findOneBy(['barcode'=>$key]);
                if(!$product){
                    Flash::warning('Invalid Product code:  '.$key);
                    return redirect()->back()->withInput();
                }

                $costPrice = floatval(str_replace(',','',$value['price']));
                $quantity = floatval(str_replace(',','',$value['quantity']));

                if(! $this->purchasesRepository->createPurchaseOrderline([
                    'transcode' => $trans->transcode,
                    'product_id' => $product->id,
                    'cost_price' => $costPrice,
                    'quantity' => $quantity,
                    'gross_amount' => $costPrice * $quantity,
                    'net_amount' => $costPrice * $quantity,
                ])){
                    Flash::warning('Failed to create purchase orderline!');
                    return redirect()->back()->withInput();
                }

                $sellPrice = (float)(($product->margin  + 100) * $costPrice) / 100;

                $stock = $this->stockRepository->findOneBy([
                    'product_id' => $product->id,
                    'branch_id' => $request->get('branch_id')
                ]);

                if(!$stock){
                    $this->stockRepository->create([
                        'quantity' => $quantity,
                        'sell_price' => $sellPrice,
                        'cost_price' => $costPrice,
                        'product_id' => $product->id,
                        'branch_id' => $request->get('branch_id'),
                        'user_id'=>$user->id,
                        'supplier_id'=>$request->get('supplier_id')
                    ]);
                }else {
                    $qty = $stock->quantity + $quantity;
                    $stock->update([
                        'quantity' => $qty,
                        'sell_price' => $sellPrice,
                        'cost_price' => $costPrice
                    ]);
                }
            }

            if ($request->hasFile('receipt')) {
                $file = array('receipt' => $request->file('receipt'));
                $rules = array('receipt' => 'required|mimes:pdf,jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $ext = $request->file('receipt')->getClientOriginalExtension();
                    $fileName = md5($trans->transcode).'.'.$ext;
                    $this->purchasesRepository->update($trans->id, ['receipt' => $fileName]);
                    $request->file('receipt')->move(public_path() . '/uploads/'.$subdomain, $fileName);
                }
            }

            Flash::success('Purchase Saved!');
            return redirect()->back();
        }catch (Exception $ex){
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
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
    public function updateProcess(Request $request, $id)
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

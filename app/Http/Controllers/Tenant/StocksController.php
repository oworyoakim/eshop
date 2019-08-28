<?php

namespace App\Http\Controllers\Tenant;

use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IStockRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

use Sentinel;
use Exception;

class StocksController extends Controller
{
    /**
     * @var IStockRepository
     */
    protected $stockRepository;


    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IProductRepository
     */
    protected $productRepository;


    /**
     * StocksController constructor.
     * @param IStockRepository $stockRepository
     * @param IBranchRepository $branchRepository
     * @param IProductRepository $productRepository
     */
    public function __construct(IStockRepository $stockRepository, IBranchRepository $branchRepository, IProductRepository $productRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->branchRepository = $branchRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $branches = [];
            $products = [];

            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                foreach ($this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]) as $row){
                    $products[$row->id] = $row->title;
                }

                $stocks = $this->stockRepository->findBy([
                    'branch_id' => $branch_id
                ]);

            }else{
                $branch = null;
                foreach ($this->productRepository->all() as $row){
                    $products[$row->id] = $row->title;
                }
                $stocks = $this->stockRepository->all();
            }

            return view('manager.stocks.index', compact('branches','branch','branch_id', 'stocks', 'products'));

        }catch(Exception $ex){
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
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

        }catch(Exception $ex){
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
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

        }catch(Exception $ex){
            Flash::warning($ex->getMessage());
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
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

        }catch(Exception $ex){
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
    public function adjust($subdomain)
    {
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $branches = [];

            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
            }else{
                $branch = null;
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
            }

            return view('manager.stocks.adjust', compact('branches','branch_id'));

        }catch(Exception $ex){
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processAdjust(Request $request, $id)
    {
        try{
            if (!Sentinel::hasAccess('stocks.adjust')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            foreach ($request->basketItems as $key => $item){
                $product = $this->productRepository->findOneBy(['barcode'=>$key]);
                if (!$product){
                    Flash::warning('Invalid Product code:  '.$key);
                    return redirect()->back()->withInput();
                }
                $stock = $this->stockRepository->findOneBy([
                    'product_id'=>$product->id,
                    'branch_id' => $request->get('branch_id')
                ]);
                if(!$stock){
                    $stock = $this->stockRepository->create([
                        'product_id'=>$product->id,
                        'branch_id' => $request->get('branch_id'),
                        'user_id'=>$user->id
                    ]);
                }

                //$costPrice = floatval(str_replace(',','',$item['price']));
                $sellPrice = floatval(str_replace(',','',$item['price']));
                $qty = floatval(str_replace(',','',$item['quantity']));
                $discount = floatval(str_replace(',','',$item['discount']));

                //$sellPrice = (float) ((100 + $product->margin) * $costPrice) / 100;

                $stock->update([
                    'quantity' => $qty,
                    //'cost_price' => $costPrice,
                    'sell_price' => $sellPrice,
                    'discount' => $discount,
                    'user_id' => $user->id,
                ]);
                //dd(floatval(str_replace(',','',$item['price'])));
            }

            // dd($request->all());
            Flash::success('Stocks Updated!');
            return redirect()->back();

        }catch(Exception $ex){
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Modify the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transfer($id)
    {
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            return view('manager.stocks.transfer');

        }catch(Exception $ex){
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Modify the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processTransfer($id)
    {
        try{
            if (!Sentinel::hasAnyAccess(['stocks','stocks.view'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

        }catch(Exception $ex){
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Models\Tenant\BusinessSetting;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\ICustomerRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\ISalesRepository;
use App\Repositories\Tenant\ISettingRepository;
use App\Repositories\Tenant\IStockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;
use PDF;
use DNS1D;

class SalesController extends TenantBaseController
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
     * @var ICustomerRepository
     */
    protected $customerRepository;


    /**
     * @var ISalesRepository
     */
    protected $salesRepository;

    /**
     * @var IStockRepository
     */
    protected $stockRepository;

    /**
     * @var ISettingRepository
     */
    protected $settingRepository;

    /**
     * SalesController constructor.
     * @param IBranchRepository $branchRepository
     * @param ISalesRepository $salesRepository
     * @param IProductRepository $productRepository
     * @param ICustomerRepository $customerRepository
     * @param IStockRepository $stockRepository
     * @param ISettingRepository $settingRepository
     */
    public function __construct(IBranchRepository $branchRepository,
                                ISalesRepository $salesRepository,
                                IProductRepository $productRepository,
                                ICustomerRepository $customerRepository,
                                IStockRepository $stockRepository,
                                ISettingRepository $settingRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->salesRepository = $salesRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
        $this->settingRepository = $settingRepository;
        $this->stockRepository = $stockRepository;
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

                $sales = $this->salesRepository->findBy([
                    'branch_id' => $branch_id
                ]);

            }else{
                $branch = null;
                foreach ($this->productRepository->all() as $row){
                    $products[$row->id] = $row->title;
                }
                $sales = $this->salesRepository->all();
            }

            return view('sales.index', compact('branches','branch','branch_id', 'sales', 'products'));
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
            $branches = [];
            $customers = [];
            $products = [];
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                $prodRows = $this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]);
                foreach($prodRows as $row){
                    $products[$row->id] = $row->title;
                }
                $custRows = $this->salesRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                foreach($custRows as $row){
                    $customers[$row->id] = $row->phone . '( '. $row->name .')';
                }

            }else{
                foreach($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }

                foreach ($this->productRepository->all() as $row){
                    $products[$row->id] = $row->title;
                }

                foreach ($this->customerRepository->all() as $row){
                    $customers[$row->id] = $row->phone. '( '. $row->name .')';
                }
            }

            $invoiceNumber = time();

            return view('sales.create', compact('branches', 'branch_id', 'products' ,'customers','invoiceNumber'));
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
    public function createProcess(Request $request, $subdomain)
    {
        try {
            if (!Sentinel::hasAccess('sales.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            $data = [];

            $data['transcode'] = $request->get('invoiceNumber');
            $data['customer_id'] = $request->get('customer_id');
            $data['branch_id'] = $request->get('branch_id');
            $data['gross_amount'] = floatval(str_replace(',','',$request->get('subTotal')));
            $data['vat_amount'] = floatval(str_replace(',','',$request->get('vat')));
            $data['vat_rate'] = floatval(str_replace(',','',$this->settingRepository->get('vat')));
            $data['net_amount'] = floatval(str_replace(',','',$request->get('grandTotal')));
            $data['paid_amount'] = floatval(str_replace(',','',$request->get('amountPaid')));
            $data['due_amount'] = floatval(str_replace(',','',$request->get('amountDue')));
            $data['due_date'] = $request->get('dueDate');
            $data['user_id'] = $user->id;
            $data['transact_date'] = $request->get('paymentDate');
            $data['created_at'] = $request->get('paymentDate');
            $data['status'] = $request->get('status');
            if ($data['due_amount'] > 0){
                $data['payment_status'] = 'partial';
            } elseif ($request->get('amountPaid') === 0){
                $data['payment_status'] = 'pending';
            }else {
                $data['payment_status'] = 'settled';
            }

            $discount = floatval(str_replace(',','',$request->get('discount')));

            if($discount){
                $data['discount_rate'] = $this->settingRepository->get('discount');
            }

            $data['discount_amount'] = $discount;

            //dd($request->all());

            $trans = $this->salesRepository->create($data);
            if(!$trans){
                Flash::warning('Failed Create Sale!');
                return redirect()->back()->withInput();
            }

            foreach ($request->get('basketItems') as $key => $value){
                //dd($value);
                $product = $this->productRepository->findOneBy(['barcode'=>$key]);
                if(!$product){
                    Flash::warning('Invalid Product code:  '.$key);
                    return redirect()->back()->withInput();
                }

                $sellPrice = floatval(str_replace(',','',$value['price']));
                $quantity = floatval(str_replace(',','',$value['quantity']));
                $discount_rate = floatval(str_replace(',','',$value['discount_rate']));
                $gross_amount = $sellPrice * $quantity;
                $discount_amount = round($sellPrice * $quantity * $discount_rate / 100,2);
                $net_amount = $gross_amount - $discount_amount;
                if(! $this->salesRepository->createSalesOrderline([
                    'transcode' => $trans->transcode,
                    'product_id' => $product->id,
                    'sell_price' => $sellPrice,
                    'quantity' => $quantity,
                    'gross_amount' => $gross_amount,
                    'discount_rate' => $discount_rate,
                    'net_amount' => $net_amount
                ])){
                    Flash::warning('Failed to create sale orderline!');
                    return redirect()->back()->withInput();
                }

                $stock = $this->stockRepository->findOneBy([
                    'product_id' => $product->id,
                    'branch_id' => $request->get('branch_id')
                ]);

                if($stock){
                    $qty = $stock->quantity - $quantity;
                    $stock->update([
                        'quantity' => $qty
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
                    $this->salesRepository->update($trans->id, ['receipt' => $fileName]);
                    $request->file('receipt')->move(public_path() . '/uploads/'.$subdomain, $fileName);
                }
            }

            if($request->get('printReceipt')){
                if(Sentinel::inRole('cashier')){
                    return redirect('cashier/sales/print/'.$trans->transcode);
                }
               return redirect('manager/sales/print/'.$trans->transcode);
            }

            Flash::success('Sales Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $transcode
     * @return \Illuminate\Http\Response
     */
    public function show($subdomain, $transcode)
    {
        try {
            if (!Sentinel::hasAccess('sales.show')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $sale = $this->salesRepository->findOneBy(['transcode'=>$transcode]);
            if(!$sale){
                Flash::warning('Invalid Transaction!');
                return redirect()->back();
            }
            $currencySymbol = $this->settingRepository->get('currency_symbol');
            return view('sales.invoice',compact('sale','subdomain','currencySymbol'));

        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Prints the specified sales transaction receipt.
     *
     * @param  int|string  $transcode
     * @return \Illuminate\Http\Response
     */
    public function print($subdomain, $transcode)
    {
        try {
            $trans = $this->salesRepository->findOneBy(['transcode'=>$transcode]);

//            $barcode = DNS1D::getBarcodeHTML($transcode, 'C93');
            $barcode = DNS1D::getBarcodePNG($transcode, "C93");
            return view('sales.receipt',compact('trans','subdomain','barcode'));

        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect('manager/sales/create');
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

    public function pdfInvoice($subdomain, $transcode)
    {
        try {
            if (!Sentinel::hasAccess('sales.show')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $sale = $this->salesRepository->findOneBy(['transcode'=>$transcode]);
            if (!$sale){
                Flash::warning('Unknown Transaction Code!');
                return redirect()->back();
            }

            $currencySymbol = $this->settingRepository->get('currency_symbol');

            $data = [
                'sale' => $sale,
                'subdomain'=>$subdomain,
                'currencySymbol' => $currencySymbol
            ];

            //return view('sales.pdf', $data);
            $pdf = PDF::loadView('sales.pdf', $data);
            $pdf->setPaper('a4');
            $pdf->setOptions(['dpi' => 150,'margin'=>10]);
            $fileName = 'invoice-'. $transcode. '.pdf';
            // If you want to store the generated pdf to the server then you can use the store function
            $pdf->save(public_path().'/uploads/'.$subdomain.'/'. $fileName);
            return $pdf->stream($fileName);

        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

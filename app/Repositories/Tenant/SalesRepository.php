<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:29 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Sales;
use App\Models\Tenant\SalesTransaction;
use App\Repositories\GenericRepository;
use Illuminate\Support\Facades\DB;

class SalesRepository extends GenericRepository implements ISalesRepository
{
    /**
     * SalesRepository constructor.
     * @param SalesTransaction $model
     */
    public function __construct(SalesTransaction $model, IProductRepository $productRepository)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingProductsByQuantity()
    {
        return DB::table('sales_transactions')
            ->select('sales.product_id', 'products.barcode', 'products.title', DB::raw('sum(sales.quantity) as total'))
            ->leftJoin('sales', 'sales_transactions.transcode', '=', 'sales.transcode')
            ->leftJoin('products', 'sales.product_id', '=', 'products.id')
            ->groupBy('sales.product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @param $branch_id
     * @return \Illuminate\Support\Collection
     */
    public function getBranchTopSellingProductsByQuantity($branch_id)
    {
        return DB::table('sales_transactions')
            ->select('sales.product_id', 'products.barcode', 'products.title', DB::raw('sum(sales.quantity) as total'))
            ->leftJoin('sales', 'sales_transactions.transcode', '=', 'sales.transcode')
            ->leftJoin('products', 'sales.product_id', '=', 'products.id')
            ->where('sales_transactions.branch_id', $branch_id)
            ->groupBy('sales.product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingProductsByPrice()
    {
        return DB::table('sales_transactions')
            ->select('sales.product_id', 'products.barcode', 'products.title', DB::raw('sum(sales.net_amount) as total'))
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->leftJoin('products', 'sales.product_id', '=', 'products.id')
            ->groupBy('sales.product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @param $branch_id
     * @return \Illuminate\Support\Collection
     */
    public function getBranchTopSellingProductsByPrice($branch_id)
    {
        return DB::table('sales_transactions')
            ->select('sales.product_id', 'products.barcode', 'products.title', DB::raw('sum(sales.net_amount) as total'))
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->leftJoin('products', 'sales.product_id', '=', 'products.id')
            ->where('sales_transactions.branch_id', $branch_id)
            ->groupBy('sales.product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }


    /**
     * @param null $branch_id
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentMonthTopSellingProducts($branch_id = null)
    {
        $year = intval(date('Y'));
        $month = intval(date('n'));

        return DB::table('sales_transactions')
            ->select('sales.product_id', 'products.barcode', 'products.title', DB::raw('sum(sales.net_amount) as total'))
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->leftJoin('products', 'sales.product_id', '=', 'products.id')
            ->whereYear('sales_transactions.transact_date', '=', $year)
            ->whereMonth('sales_transactions.transact_date', '=', $month)
            ->groupBy('sales.product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @param $branch_id
     * @return \Illuminate\Support\Collection
     */
    public function getBranchCurrentMonthTopSellingProducts($branch_id)
    {
        $year = intval(date('Y'));
        $month = intval(date('n'));

        return DB::table('sales_transactions')
            ->select('sales.product_id', 'products.barcode', 'products.title', DB::raw('sum(sales.net_amount) as total'))
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->leftJoin('products', 'sales.product_id', '=', 'products.id')
            ->where('sales_transactions.branch_id', $branch_id)
            ->whereYear('sales_transactions.transact_date', '=', $year)
            ->whereMonth('sales_transactions.transact_date', '=', $month)
            ->groupBy('sales.product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @param null $date
     * @return mixed
     */
    public function getDaysTotalSalesByPrice($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        return DB::table('sales_transactions')
            ->where('status','<>','canceled')
            ->whereDate('transact_date', '=', $date)
            ->sum('net_amount');
    }

    /**
     * @param $branch_id
     * @param null $date
     * @return mixed
     */
    public function getBranchDaysTotalSalesByPrice($branch_id, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        return DB::table('sales_transactions')
            ->where('branch_id', $branch_id)
            ->whereDate('transact_date', '=', $date)
            ->where('status','<>','canceled')
            ->sum('net_amount');
    }


    /**
     * @param null $date
     * @return mixed
     */
    public function getDaysTotalSalesByQuantity($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        return DB::table('sales_transactions')
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->whereDate('sales_transactions.transact_date', '=', $date)
            ->sum('sales.quantity');
    }

    /**
     * @param $branch_id
     * @param null $date
     * @return mixed
     */
    public function getBranchDaysTotalSalesByQuantity($branch_id, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        return DB::table('sales_transactions')
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->whereDate('sales_transactions.transact_date', '=', $date)
            ->where('sales_transactions.branch_id', $branch_id)
            ->sum('sales.quantity');
    }

    public function getLatestSales($branch_id = null)
    {
        if($branch_id){
            return $this->model->where('branch_id',$branch_id)->latest()->take(5)->get();
        }
        return $this->model->latest()->take(5)->get();
    }

    public function getDaysReturnedSales($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        return DB::table('sales_transactions')
            ->where('status','=','canceled')
            ->whereDate('transact_date', '=', $date)
            ->sum('net_amount');
    }

    public function getBranchDaysReturnedSales($branch_id, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        return DB::table('sales_transactions')
            ->where('branch_id',$branch_id)
            ->where('status','=','canceled')
            ->whereDate('transact_date', '=', $date)
            ->sum('net_amount');
    }

    public function getTotalMonthlySales($date)
    {
        $year = intval(date('Y',strtotime($date)));
        $month = intval(date('n',strtotime($date)));
        return DB::table('sales_transactions')
            ->where('status','<>','canceled')
            ->whereYear('transact_date','=',$year)
            ->whereMonth('transact_date','=',$month)
            ->sum('net_amount');
    }

    public function getBranchTotalMonthlySales($branch_id, $date)
    {
        $year = intval(date('Y',strtotime($date)));
        $month = intval(date('n',strtotime($date)));
        return DB::table('sales_transactions')
            ->where('branch_id',$branch_id)
            ->where('status','<>','canceled')
            ->whereYear('transact_date','=',$year)
            ->whereMonth('transact_date','=',$month)
            ->sum('net_amount');
    }

    public function createSalesOrderline(array $data){
        return Sales::create($data);
    }

    public function getProductTotalRevenues($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('sales_transactions')
                ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
                ->where('sales.product_id',$product_id)
                ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
                ->where('sales.status','complete')
                ->where('sales_transactions.branch_id',$branch_id)
                ->sum('sales.net_amount');
        }
        return DB::table('sales_transactions')
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->where('sales.product_id',$product_id)
            ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
            ->where('sales.status','complete')
            ->sum('sales.net_amount');
    }

    public function getProductTotalVolume($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('sales_transactions')
                ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
                ->where('sales.product_id',$product_id)
                ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
                ->where('sales.status','complete')
                ->where('sales_transactions.branch_id',$branch_id)
                ->sum('sales.quantity');
        }
        return DB::table('sales_transactions')
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->where('sales.product_id',$product_id)
            ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
            ->where('sales.status','complete')
            ->sum('sales.quantity');
    }

    public function getProductTotalReturns($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('sales_transactions')
                ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
                ->where('sales.product_id',$product_id)
                ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
                ->where('sales_transactions.branch_id',$branch_id)
                ->sum('sales.returns');
        }
        return DB::table('sales_transactions')
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->where('sales.product_id',$product_id)
            ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
            ->sum('sales.returns');
    }

    public function getProductTotalDiscount($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('sales_transactions')
                ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
                ->where('sales.product_id',$product_id)
                ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
                ->where('sales_transactions.branch_id',$branch_id)
                ->where('sales.status','complete')
                ->sum('sales.discount_amount');
        }
        return DB::table('sales_transactions')
            ->leftJoin('sales','sales_transactions.transcode','=','sales.transcode')
            ->where('sales.product_id',$product_id)
            ->whereBetween('sales_transactions.transact_date',[$start_date,$end_date])
            ->where('sales.status','complete')
            ->sum('sales.discount_amount');
    }

    public function getAccountsReceivables($start_date, $end_date, $branch_id = null){
        if($branch_id){
            return $this->model->receivables()
                ->where('branch_id',$branch_id)
                ->whereBetween('transact_date',[$start_date,$end_date])
                ->get();
        }
        return $this->model->receivables()
            ->whereBetween('transact_date',[$start_date,$end_date])
            ->get();
    }

    /**
     * @param string $start_date
     * @param string $end_date
     * @param int|null $branch_id
     * @return array
     */
    public function getSalesSummary(string $start_date, string $end_date, int $branch_id = null){
        $totalRevenues = 0;
        $totalVolume = 0;
        $totalReturns = 0;
        $totalDiscount = 0;
        $data = [];

        if($branch_id){
            $products = $this->getBranchProducts($branch_id);
            foreach ($products as $product) {
                $revenue = $this->getProductTotalRevenues($product->id, $start_date, $end_date, $branch_id);
                $volume = $this->getProductTotalVolume($product->id, $start_date, $end_date, $branch_id);
                $returns = $this->getProductTotalReturns($product->id, $start_date, $end_date, $branch_id);
                $discount = $this->getProductTotalDiscount($product->id, $start_date, $end_date, $branch_id);
                $data[$product->id] = [
                    'revenues' => $revenue,
                    'volume' => $volume,
                    'returns' => $returns,
                    'discount' => $discount,
                    'product' => $product->title . ' => ' . $product->category
                ];
                $totalRevenues += $revenue;
                $totalVolume += $volume;
                $totalReturns += $returns;
                $totalDiscount += $discount;
            }
        }else{
            $products = $this->getProducts();
            foreach ($products as $product) {
                $revenue = $this->getProductTotalRevenues($product->id, $start_date, $end_date);
                $volume = $this->getProductTotalVolume($product->id, $start_date, $end_date);
                $returns = $this->getProductTotalReturns($product->id, $start_date, $end_date);
                $discount = $this->getProductTotalDiscount($product->id, $start_date, $end_date);

                $data[$product->id] = [
                    'revenues' => $revenue,
                    'volume' => $volume,
                    'returns' => $returns,
                    'discount' => $discount,
                    'product' => $product->title . ' => ' . $product->category
                ];

                $totalRevenues += $revenue;
                $totalVolume += $volume;
                $totalReturns += $returns;
                $totalDiscount += $discount;
            }
        }
        $graphData = [
            'revenues' => $totalRevenues,
            'volume' => $totalVolume,
            'returns' => $totalReturns,
            'discount' => $totalDiscount,
        ];

        return ['data'=>$data,'graphData'=>$graphData];
    }


    /**
     * @param Branch $branch
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function getBranchDailySalesValues(Branch $branch, string $start_date, string $end_date){
        $totalRevenues = 0;
        $totalCanceled = 0;
        $totalReturns = 0;
        $totalDiscount = 0;
        $totalReceivable = 0;
        $totalTaxPayable = 0;
        $totalTaxReturned = 0;
        $bdata = [];
        $bdata['branch_id'] = $branch->id;
        $bdata['branch_name'] = $branch->name;
        $bdata['data'] = [];
        while ($start_date <= $end_date) {
            // get the data
            $revenues = $branch->daysTotalRevenues($start_date);
            $canceled = $branch->daysTotalCanceledSales($start_date);
            $returns = $branch->daysTotalsReturnsInwards($start_date);
            $receivables = $branch->daysTotalReceivables($start_date);
            $taxReturned = $branch->daysTotalReturnedTax($start_date);
            $taxPayable = $branch->daysTotalTaxPayable($start_date);
            $discount = $branch->daysTotalDiscountPayable($start_date);
            // compute totals
            $totalRevenues += $revenues;
            $totalReturns += $returns;
            $totalCanceled += $canceled;
            $totalDiscount += $discount;
            $totalReceivable += $receivables;
            $totalTaxPayable += $taxPayable;
            $totalTaxReturned += $taxReturned;

            $bd = [
                'date' => $start_date,
                'revenues' => $revenues,
                'canceled' => $canceled,
                'returns' => $returns,
                'receivable' => $receivables,
                'tax' => $taxPayable + $taxReturned,
                'tax_payable' => $taxPayable,
                'tax_returned' => $taxReturned,
                'discount' => $discount,
                'cash' => $revenues - ($canceled + $returns + $receivables + $discount + $taxReturned)
            ];
            $bdata['data'][] = $bd;
            //add 1 day to start date
            $start_date = date_format(date_add(date_create($start_date), date_interval_create_from_date_string('1 days')), 'Y-m-d');
        }

        $totalCash = $totalRevenues - ($totalCanceled + $totalReturns + $totalReceivable + $totalDiscount + $totalTaxReturned);
        $rdata = array_reverse($bdata['data']);
        //dd($bdata['data'],$rdata);
        $bdata['data'] = $rdata;
        $bdata['total_revenues']['value'] = $totalRevenues;
        $bdata['total_revenues']['percent'] = 100;
        if($totalRevenues > 0){
            $bdata['total_returns']['value'] = $totalReturns;
            $bdata['total_returns']['percent'] = round($totalReturns * 100 / $totalRevenues,2);
            $bdata['total_canceled']['value'] = $totalCanceled;
            $bdata['total_canceled']['percent'] = round($totalCanceled * 100 / $totalRevenues,2);
            $bdata['total_receivable']['value'] = $totalReceivable;
            $bdata['total_receivable']['percent'] = round($totalReceivable * 100 / $totalRevenues,2);
            $bdata['total_discount']['value'] = $totalDiscount;
            $bdata['total_discount']['percent'] = round($totalDiscount * 100 / $totalRevenues,2);
            $bdata['total_tax']['value'] = $totalTaxPayable + $totalTaxReturned;
            $bdata['total_tax']['percent'] = round(($totalTaxPayable + $totalTaxReturned) * 100 / $totalRevenues,2);
            $bdata['total_tax_payable']['value'] = $totalTaxPayable;
            $bdata['total_tax_payable']['percent'] = round($totalTaxPayable * 100 /$totalRevenues,2);
            $bdata['total_tax_returned']['value'] = $totalTaxReturned;
            $bdata['total_tax_returned']['percent'] = round($totalTaxReturned * 100 /$totalRevenues,2);
            $bdata['total_cash']['value'] = $totalCash;
            $bdata['total_cash']['percent'] = round($totalCash * 100 / $totalRevenues,2);
        }else{
            $bdata['total_returns']['value'] = $totalReturns;
            $bdata['total_returns']['percent'] = 0;
            $bdata['total_canceled']['value'] = $totalCanceled;
            $bdata['total_canceled']['percent'] = 0;
            $bdata['total_receivable']['value'] = $totalReceivable;
            $bdata['total_receivable']['percent'] = 0;
            $bdata['total_discount']['value'] = $totalDiscount;
            $bdata['total_discount']['percent'] = 0;
            $bdata['total_tax']['value'] = $totalTaxPayable + $totalTaxReturned;
            $bdata['total_tax']['percent'] = 0;
            $bdata['total_tax_payable']['value'] = $totalTaxPayable;
            $bdata['total_tax_payable']['percent'] = 0;
            $bdata['total_tax_returned']['value'] = $totalTaxReturned;
            $bdata['total_tax_returned']['percent'] = 0;
            $bdata['total_cash']['value'] = $totalCash;
            $bdata['total_cash']['percent'] = 0;
        }

        return $bdata;
    }

    /**
     * @param Branch $branch
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function getBranchMonthlySalesValues(Branch $branch, string $start_date, string $end_date){
        $totalRevenues = 0;
        $totalCanceled = 0;
        $totalReturns = 0;
        $totalDiscount = 0;
        $totalReceivable = 0;
        $totalTaxPayable = 0;
        $totalTaxReturned = 0;

        $bdata = [];
        $bdata['branch_id'] = $branch->id;
        $bdata['branch_name'] = $branch->name;
        $bdata['data'] = [];
        while ($start_date <= $end_date) {
            // get the year
            $year = date('Y', strtotime($start_date));
            // get the month
            $month = date('M', strtotime($start_date));
            $ext_month = $month . ' ' . $year;
            // get the data
            $revenues = $branch->monthsTotalRevenues($start_date);
            $canceled = $branch->monthsTotalCanceledSales($start_date);
            $returns = $branch->monthsTotalReturnsInwards($start_date);
            $receivables = $branch->monthsTotalReceivables($start_date);
            $taxReturned = $branch->monthsTotalReturnedTax($start_date);
            $taxPayable = $branch->monthsTotalTaxPayable($start_date);
            $discount = $branch->monthsTotalDiscountPayable($start_date);
            // compute totals
            $totalRevenues += $revenues;
            $totalReturns += $returns;
            $totalCanceled += $canceled;
            $totalDiscount += $discount;
            $totalReceivable += $receivables;
            $totalTaxPayable += $taxPayable;
            $totalTaxReturned += $taxReturned;

            $bd = [
                'month' => $ext_month,
                'revenues' => $revenues,
                'canceled' => $canceled,
                'returns' => $returns,
                'receivable' => $receivables,
                'tax' => $taxPayable + $taxReturned,
                'tax_payable' => $taxPayable,
                'tax_returned' => $taxReturned,
                'discount' => $discount,
                'cash' => $revenues - ($canceled + $returns + $receivables + $discount + $taxReturned)
            ];
            $bdata['data'][] = $bd;
            //add 1 month to start date
            $start_date = date_format(date_add(date_create($start_date), date_interval_create_from_date_string('1 months')), 'Y-m-d');
        }

        $totalCash = $totalRevenues - ($totalCanceled + $totalReturns + $totalReceivable + $totalDiscount + $totalTaxReturned);
        $rdata = array_reverse($bdata['data']);
        //dd($bdata['data'],$rdata);
        $bdata['data'] = $rdata;
        $bdata['total_revenues']['value'] = $totalRevenues;
        $bdata['total_revenues']['percent'] = 100;
        if($totalRevenues > 0){
            $bdata['total_returns']['value'] = $totalReturns;
            $bdata['total_returns']['percent'] = round($totalReturns * 100 / $totalRevenues,2);
            $bdata['total_canceled']['value'] = $totalCanceled;
            $bdata['total_canceled']['percent'] = round($totalCanceled * 100 / $totalRevenues,2);
            $bdata['total_receivable']['value'] = $totalReceivable;
            $bdata['total_receivable']['percent'] = round($totalReceivable * 100 / $totalRevenues,2);
            $bdata['total_discount']['value'] = $totalDiscount;
            $bdata['total_discount']['percent'] = round($totalDiscount * 100 / $totalRevenues,2);
            $bdata['total_tax']['value'] = $totalTaxPayable + $totalTaxReturned;
            $bdata['total_tax']['percent'] = round(($totalTaxPayable + $totalTaxReturned) * 100 / $totalRevenues,2);
            $bdata['total_tax_payable']['value'] = $totalTaxPayable;
            $bdata['total_tax_payable']['percent'] = round($totalTaxPayable * 100 /$totalRevenues,2);
            $bdata['total_tax_returned']['value'] = $totalTaxReturned;
            $bdata['total_tax_returned']['percent'] = round($totalTaxReturned * 100 /$totalRevenues,2);
            $bdata['total_cash']['value'] = $totalCash;
            $bdata['total_cash']['percent'] = round($totalCash * 100 / $totalRevenues,2);
        }else{
            $bdata['total_returns']['value'] = $totalReturns;
            $bdata['total_returns']['percent'] = 0;
            $bdata['total_canceled']['value'] = $totalCanceled;
            $bdata['total_canceled']['percent'] = 0;
            $bdata['total_receivable']['value'] = $totalReceivable;
            $bdata['total_receivable']['percent'] = 0;
            $bdata['total_discount']['value'] = $totalDiscount;
            $bdata['total_discount']['percent'] = 0;
            $bdata['total_tax']['value'] = $totalTaxPayable + $totalTaxReturned;
            $bdata['total_tax']['percent'] = 0;
            $bdata['total_tax_payable']['value'] = $totalTaxPayable;
            $bdata['total_tax_payable']['percent'] = 0;
            $bdata['total_tax_returned']['value'] = $totalTaxReturned;
            $bdata['total_tax_returned']['percent'] = 0;
            $bdata['total_cash']['value'] = $totalCash;
            $bdata['total_cash']['percent'] = 0;
        }

        return $bdata;
    }
}
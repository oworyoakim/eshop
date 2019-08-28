<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{

    use SoftDeletes;

    protected $table = "branches";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'city',
        'country',
        'user_id',
    ];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function expenseTypes()
    {
        return $this->hasMany(ExpenseType::class);
    }

    public function purchases()
    {
        return $this->hasMany(PurchasesTransaction::class, 'branch_id');
    }

    public function sales()
    {
        return $this->hasMany(SalesTransaction::class, 'branch_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'role_users', 'branch_id', 'user_id')->withPivot('start_date', 'end_date', 'active')->withTimestamps();
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function user()
    {
        return $this->belongsTo(Employee::class,'user_id');
    }

    /**
     * @return Employee|null
     */
    public function manager()
    {
        foreach ($this->employees as $employee){
            $role = $employee->getActiveRole();
            //dd($role);
            if ($role && $role->slug === 'branchmanager'){
                return $employee;
            }
        }
        return null;
    }

    public function daysRevenues($date)
    {
        return $this->sales()
			->where('status','<>','canceled')
            ->whereDate('transact_date', $date)
            ->get();
    }

    public function daysTotalRevenues($date)
    {
        return $this->sales()
			->where('status','<>','canceled')
            ->whereDate('transact_date', $date)
            ->sum('gross_amount');
    }

    public function monthsRevenues($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
			->where('status','<>','canceled')
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->get();
    }

    public function monthsTotalRevenues($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
			->where('status','<>','canceled')
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->sum('gross_amount');
    }

    public function daysReceivables($date)
    {
        return $this->sales()
			->where('status','<>','canceled')
			->where('payment_status','=','partial')
			->where('status','<>','canceled')
            ->whereDate('transact_date', $date)
            ->get();
    }

    public function daysTotalReceivables($date)
    {
        return $this->sales()
			->where('payment_status','partial')
			->where('status','<>','canceled')
			->whereDate('transact_date', $date)
            ->sum('due_amount');
    }

    public function monthsReceivables($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
			->where('status','<>','canceled')
            ->where('payment_status','partial')
            ->get();
    }

    public function monthsTotalReceivables($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
			->where('status','<>','canceled')
            ->where('payment_status','partial')
            ->sum('due_amount');
    }

  
    public function daysReturnedSales($date){
        //dd($date);
        return $this->sales()
            ->whereDate('transact_date',$date)
            ->where('status','<>','canceled')
            ->where(function ($query){
                return $query->where('status','partially_returned')->orWhere('status','fully_returned');
            })
            ->get();
    }

    public function daysTotalReturnedTax($date){
        $sum = 0;
        $sales = $this->daysReturnedSales($date);
        //dd($sales);
        foreach ($sales as $sale){
            $sum += $sale->totalReturnedTax();
        }
        return $sum;
    }

    public function daysTotalTaxPayable($date)
    {
        $amount = $this->sales()
            ->whereDate('transact_date', $date)
            ->where('status','<>','canceled')
            ->sum('vat_amount');

        $returns = $this->daysTotalReturnedTax($date);

        return $amount - $returns;
    }

    public function monthsReturnedSales($date){
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where('status','<>','canceled')
            ->where(function ($query){
                return $query->where('status','partially_returned')->orWhere('status','fully_returned');
            })->get();
    }

    public function monthsTotalReturnedTax($date){
        $sum = 0;
        foreach ($this->monthsReturnedSales($date) as $sale){
            $sum += $sale->totalReturnedTax();
        }
        return $sum;
    }

    public function monthsTotalTaxPayable($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        $amount = $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where('status','<>','canceled')
            ->sum('vat_amount');
        $returns = $this->monthsTotalReturnedTax($date);

        return $amount - $returns;
    }

    public function daysDiscountPayable($date)
    {
        return $this->sales()
            ->whereDate('transact_date', $date)
            ->where('status','<>','canceled')
            ->get();
    }

    public function daysTotalDiscountPayable($date)
    {
        return $this->sales()
            ->whereDate('transact_date', $date)
            ->where('status','<>','canceled')
            ->sum('discount_amount');
    }

    public function monthsDiscountPayable($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where('status','<>','canceled')
            ->get();
    }

    public function monthsTotalDiscountPayable($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where('status','<>','canceled')
            ->sum('discount_amount');
    }

    public function daysCanceledSales($date)
    {
        return $this->sales()
            ->whereDate('transact_date', $date)
            ->where('status', 'canceled')
            ->get();
    }

    public function daysTotalCanceledSales($date)
    {
        return $this->sales()
            ->whereDate('transact_date', $date)
            ->where('status', 'canceled')
            ->sum('gross_amount');
    }

    public function monthsCanceledSales($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where('status', 'canceled')
            ->get();
    }

    public function monthsTotalCanceledSales($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where('status', 'canceled')
            ->sum('gross_amount');
    }

    public function daysReturnsInwards($date)
    {
        return $this->sales()
            ->whereDate('transact_date', $date)
            ->where(function ($query){
                return $query->where('status','partially_returned')->orWhere('status','fully_returned');
            })->get();
    }

    public function daysTotalsReturnsInwards($date)
    {
        $sum = 0;
        foreach ($this->daysReturnsInwards($date) as $row) {
            $sum += $row->returnsInwards();
        }
        return $sum;
    }

    public function monthsReturnsInwards($date)
    {
        $year = intval(date('Y', strtotime($date)));
        $month = intval(date('n', strtotime($date)));
        return $this->sales()
            ->whereYear('transact_date', '=', $year)
            ->whereMonth('transact_date', '=', $month)
            ->where(function ($query){
                return $query->where('status','partially_returned')->orWhere('status','fully_returned');
            })
            ->get();
    }

    public function monthsTotalReturnsInwards($date)
    {
        $sum = 0;
        foreach ($this->monthsReturnsInwards($date) as $row) {
            $sum += $row->returnsInwards();
        }
        return $sum;
    }


}

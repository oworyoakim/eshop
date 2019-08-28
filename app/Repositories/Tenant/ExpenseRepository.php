<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 4:40 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\Expense;
use App\Repositories\GenericRepository;
use Illuminate\Support\Facades\DB;

class ExpenseRepository extends GenericRepository implements IExpenseRepository
{
    /**
     * ExpenseRepository constructor.
     * @param Expense $model
     */
    public function __construct(Expense $model)
    {
        $this->model = $model;
    }

    public function getTotalMonthlyExpenses($date)
    {
        $year = intval(date('Y',strtotime($date)));
        $month = intval(date('n',strtotime($date)));
        return DB::table('expenses')
            ->where('status','<>','canceled')
            ->whereYear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
    }

    public function getBranchTotalMonthlyExpenses($branch_id, $date)
    {
        $year = intval(date('Y',strtotime($date)));
        $month = intval(date('n',strtotime($date)));
        return DB::table('expenses')
            ->where('branch_id',$branch_id)
            ->where('status','<>','canceled')
            ->whereYear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('amount');
    }

    public function getTotalExpenses($date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('status','<>','canceled')
                ->whereYear('created_at','=',$year)
                ->whereMonth('created_at','=',$month)
                ->whereDay('created_at','=',$day)
                ->sum('amount');
        }
        return $this->model->where('status','<>','canceled')->sum('amount');
    }

    public function getBranchTotalExpenses($branch_id, $date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('branch_id',$branch_id)
                ->where('status','<>','canceled')
                ->whereYear('created_at','=',$year)
                ->whereMonth('created_at','=',$month)
                ->whereDay('created_at','=',$day)
                ->sum('amount');
        }

        return $this->model->where('branch_id',$branch_id)
            ->where('status','<>','canceled')
            ->sum('amount');
    }

    public function getTotalReturnedExpenses($date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('status','=','canceled')
                ->whereYear('created_at','=',$year)
                ->whereMonth('created_at','=',$month)
                ->whereDay('created_at','=',$day)
                ->sum('amount');
        }
        return $this->model->where('status','=','canceled')->sum('amount');
    }

    public function getBranchTotalReturnedExpenses($branch_id, $date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('branch_id',$branch_id)
                ->where('status','=','canceled')
                ->whereYear('created_at','=',$year)
                ->whereMonth('created_at','=',$month)
                ->whereDay('created_at','=',$day)
                ->sum('amount');
        }

        return $this->model->where('branch_id',$branch_id)
            ->where('status','=','canceled')
            ->sum('amount');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:36 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\Employee;
use App\Repositories\GenericRepository;
use Illuminate\Support\Facades\DB;

class EmployeeRepository extends GenericRepository implements IEmployeeRepository
{
    /**
     * EmployeeRepository constructor.
     * @param User $model
     */
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function getBranchEmployees(int $branch_id)
    {
        return $this->model->wherePivot('branch_id',$branch_id)->get();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:10 PM
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class GenericRepository implements IGenericRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update($id, array $data)
    {
        return $this->model->where('id',$id)->update($data);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id,$columns);
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(array $data, $columns = array('*'))
    {
        return $this->model->where($data)->get($columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * @param  $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * @param array $data
     * @return boolean
     */
    public function exists(array $data)
    {
        return $this->model->where($data)->exists();
    }

    public function getProductByBarcode(string $barcode)
    {
        return DB::table('products')
            ->select('products.id','products.barcode','products.title','stocks.sell_price as sellPrice','stocks.cost_price as costPrice','stocks.discount','stocks.quantity','product_units.slug as unit','categories.title as category')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('product_units', 'products.unit_id', '=', 'product_units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.barcode', $barcode)
            ->first();
    }

    public function getBranchProductByBarcode(string $barcode, int $branch_id)
    {
        return DB::table('products')
            ->select('products.id','products.barcode','products.title','stocks.sell_price as sellPrice','stocks.cost_price as costPrice','stocks.discount','stocks.quantity','product_units.slug as unit','categories.title as category')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('product_units', 'products.unit_id', '=', 'product_units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.barcode', '=', $barcode)
            ->where('products.branch_id', $branch_id)
            ->first();
    }

    public function getProducts()
    {
        return DB::table('products')
            ->select('products.id','products.barcode','products.title','stocks.sell_price as sellPrice','stocks.cost_price as costPrice','stocks.quantity','stocks.discount','product_units.slug as unit','categories.title as category')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('product_units', 'products.unit_id', '=', 'product_units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->get();
    }

    public function getBranchProducts(int $branch_id)
    {
        return DB::table('products')
            ->select('products.id','products.barcode','products.title','stocks.sell_price as sellPrice','stocks.cost_price as costPrice','stocks.quantity','stocks.discount','product_units.slug as unit','categories.title as category')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('product_units', 'products.unit_id', '=', 'product_units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.branch_id', $branch_id)
            ->get();
    }

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollBack();
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model->getModel();
    }

}
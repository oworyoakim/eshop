<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:02 PM
 */

namespace App\Repositories;

interface IGenericRepository
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'));

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns= array('*'));

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneByOrFail(array $data);

    /**
     * @param array $data
     * @param array $columns
     * @return mixed
     */
    public function findBy(array $data, $columns = array('*'));

    /**
     * @param array $data
     * @return boolean
     */
    public function exists(array $data);
    /**
     * @param $id
     * @return mixed
     */
    public function findOneOrFail($id);

    public function getProductByBarcode(string $barcode);

    public function getBranchProductByBarcode(string $barcode,int $branch_id);

    public function getProducts();

    public function getBranchProducts(int $branch_id);

    public function beginTransaction();

    public function commitTransaction();

    public function rollbackTransaction();
    /**
     * @return mixed
     */
    public function getModel();
}
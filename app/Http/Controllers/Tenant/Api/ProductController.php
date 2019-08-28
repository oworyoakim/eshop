<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 1:17 PM
 */

namespace App\Http\Controllers\Tenant\Api;


use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IProductRepository;
use Exception;

class ProductController extends TenantBaseController
{
    /**
     * @var IProductRepository
     */
    private $productRepository;

    /**
     * ProductController constructor.
     * @param IProductRepository $productRepository
     */
    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(){
        try{
            $products = $this->productRepository->getProducts();
            //return response()->json($products);
            return response($products,200);
        }catch (Exception $ex) {
            return response()->json('Server Error: '.$ex->getMessage(),500);
        }
    }

    public function getProductByBarcode($subdomain,$barcode){
        try{
            $product = $this->productRepository->getProductByBarcode($barcode);
            return response()->json($product);
        }catch (Exception $ex) {
            return response()->json('Server Error: '.$ex->getMessage(),500);
        }
    }

    public function getBranchProducts($subdomain,$branch_id){
        try{
            $product = $this->productRepository->getBranchProducts($branch_id);
            return response()->json($product);
        }catch (Exception $ex) {
            return response()->json('Server Error: '.$ex->getMessage(),500);
        }
    }
}
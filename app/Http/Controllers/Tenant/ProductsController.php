<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IProductUnitRepository;
use App\Repositories\Tenant\ISettingRepository;
use App\Repositories\Tenant\IStockRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class ProductsController extends TenantBaseController
{
    /**
     * @var IProductRepository
     */
    protected $productRepository;

    /**
     * @var ICategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IProductUnitRepository
     */
    protected $unitRepository;

    /**
     * @var IStockRepository
     */
    protected $stockRepository;

    /**
     * @var ISettingRepository
     */
    protected $settingRepository;
    /**
     * @var IEmployeeRepository
     */
    private $employeeRepository;

    /**
     * ProductsController constructor.
     * @param IBranchRepository $branchRepository
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     * @param IProductUnitRepository $unitRepository
     * @param IStockRepository $stockRepository
     * @param IEmployeeRepository $employeeRepository
     */
    public function __construct(IBranchRepository $branchRepository,
                                IProductRepository $productRepository,
                                ICategoryRepository $categoryRepository,
                                IProductUnitRepository $unitRepository,
                                IStockRepository $stockRepository,
                                IEmployeeRepository $employeeRepository,
                                ISettingRepository $settingRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->unitRepository = $unitRepository;
        $this->stockRepository = $stockRepository;
        $this->settingRepository = $settingRepository;
        $this->employeeRepository = $employeeRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($subdomain)
    {
        try {
            if (!Sentinel::hasAccess('products')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            
            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            //dd($branch_id);
            $branches = [];
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;

                $categories = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                $products = $this->productRepository->findBy([
                    'branch_id' => $branch_id
                ]);
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }

                $categories = $this->categoryRepository->all();

                $products = $this->productRepository->all();
            }
            return view('manager.products.index', compact('products', 'categories','branches','branch_id','subdomain'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($subdomain)
    {
        try {
            if (!Sentinel::hasAccess('products.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }
            $branches = [];
            $categories = [];
            $units = [];
            $branch_id = null;
            $branch = $user->getActiveBranch();
            //dd($branch);
            if (Sentinel::inRole('businessmanager')) {
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
                $catRows = $this->categoryRepository->all();
                foreach ($catRows as $row){
                    $categories[$row->id]  = $row->title;
                }
                foreach ($this->unitRepository->all() as $row){
                    $units[$row->id]  = $row->title;
                }
            }elseif ($branch){
                $branch_id = $branch->id;
                $branches[$branch->id] = $branch->name;
                $catRows = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                foreach ($catRows as $row){
                    $categories[$row->id]  = $row->title;
                }
                $unitRows = $this->unitRepository->findBy([
                    'branch_id' => $branch_id
                ]);
                foreach ($unitRows as $row){
                    $units[$row->id]  = $row->title;
                }
            }else{
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }
            return view('manager.products.create', compact('products', 'categories','units','branches','branch_id'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function createProcess(Request $request,$subdomain)
    {
        try {
            if (!Sentinel::hasAccess('products.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            // check for margin override
            $margin = $request->get('profit_margin');
            if(!$margin){
                if($this->settingRepository->get('enable_global_margin')) {
                    $margin = floatval($this->settingRepository->get('profit_margin'));
                }else {
                    $margin = 10.00; // default is 10%
                }
            }

            $data = [
                'barcode' => time(),
                'title' => $request->get('title'),
                'margin' => $margin,
                'description' => $request->get('description'),
                'category_id' => $request->get('category_id'),
                'unit_id' => $request->get('unit_id'),
                'branch_id' => $request->get('branch_id'),
                'user_id' => $user->id
            ];

            $product = $this->productRepository->create($data);

            $stock = $this->stockRepository->create([
                'product_id' => $product->id,
                'branch_id' => $product->branch_id,
                'user_id' => $user->id
            ]);

            if ($request->hasFile('avatar')) {
                $file = array('avatar' => $request->file('avatar'));
                $rules = array('avatar' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $ext = $request->file('avatar')->getClientOriginalExtension();
                    $fileName = md5($request->file('avatar')->getClientOriginalName()).'.'.$ext;
                    $product->update(['avatar' => $fileName]);
                    $request->file('avatar')->move(public_path('uploads/'.$subdomain), $fileName);
                }
            }
            Flash::success('Product Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($subdomain,$id)
    {
        try {
            if (!Sentinel::hasAccess('products.show')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $product = $this->productRepository->find($id);

            if (!$product) {
                Flash::warning('Invalid Product!');
                return redirect()->back();
            }

            return view('manager.products.show', compact('product'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function update($subdomain,$id)
    {
        try {
            if (!Sentinel::hasAccess('products.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = $this->employeeRepository->find(Sentinel::getUser()->id);
            if (!$user) {
                Flash::warning('Invalid User!');
                return redirect()->back();
            }
            $branches = [];
            $categories = [];
            $units = [];
            $branch_id = null;
            $branch = $user->getActiveBranch();


            $product = $this->productRepository->find($id);

            if (!$product) {
                Flash::warning('Invalid Product!');
                return redirect()->back();
            }

            $branch_id = null;

            if(Sentinel::inRole('businessmanager')){
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }

                $catRows = $this->categoryRepository->all();
                foreach ($catRows as $row){
                    $categories[$row->id]  = $row->title;
                }

                foreach ($this->unitRepository->all() as $row){
                    $units[$row->id]  = $row->title;
                }
            }elseif($branch){
                $branch_id = $branch->id;
                $branches[$branch->id] = $branch->name;

                $catRows = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                foreach ($catRows as $row){
                    $categories[$row->id]  = $row->title;
                }

                $unitRows = $this->unitRepository->findBy([
                    'branch_id' => $branch_id
                ]);

                foreach ($unitRows as $row){
                    $units[$row->id]  = $row->title;
                }

            }else{
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            return view('manager.products.update', compact('product', 'categories','units','branches','branch_id'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function updateProcess(Request $request, $subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('products.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user  = Sentinel::getUser();

            $data = [
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'category_id' => $request->get('category_id'),
                'unit_id' => $request->get('unit_id'),
                'branch_id' => $request->get('branch_id'),
                'margin' => $request->get('profit_margin'),
                'user_id' => $user->id
            ];

            $product = $this->productRepository->find($id);

            if(!$product){
                Flash::warning('Invalid Product!');
                return redirect()->back()->withInput();
            }

            $product->update($data);

            if ($request->hasFile('avatar')) {
                $file = array('avatar' => $request->file('avatar'));
                $rules = array('avatar' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $ext = $request->file('avatar')->getClientOriginalExtension();
                    $fileName = md5($request->file('avatar')->getClientOriginalName()).'.'.$ext;
                    $product->update(['avatar' => $fileName]);
                    $request->file('avatar')->move(public_path() . '/uploads/'.$subdomain, $fileName);
                }
            }
            Flash::success('Product Saved!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($subdomain,$id)
    {
        try {
            if (!Sentinel::hasAccess('products.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $product = $this->productRepository->find($id);

            if (!$product) {
                Flash::warning('Invalid Product!');
                return redirect()->back();
            }

            $this->productRepository->delete($id);
            Flash::success('Product Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

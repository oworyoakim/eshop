<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IProductUnitRepository;
use App\Repositories\Tenant\IBranchRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;

class ProductUnitsController extends TenantBaseController {

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;
    /**
     * @var IProductUnitRepository
     */
    protected $productUnitRepository;

    /**
     * ProductUnitsController constructor.
     * @param IBranchRepository $branchRepository
     * @param IProductUnitRepository $productUnitRepository
     */
    public function __construct(IBranchRepository $branchRepository,IProductUnitRepository $productUnitRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->productUnitRepository = $productUnitRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        try {
            if (!Sentinel::hasAccess('products.categories.view')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;
                $units = $this->productUnitRepository->findBy([
                    'branch_id' => $branch_id
                ]);
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
                $units = $this->productUnitRepository->all();
            }

            return view('manager.products.units.index', compact('units','branches','branch_id'));
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
    public function create() {
        try {
            if (!Sentinel::hasAccess('products.units.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
            }
            return view('manager.products.units.create',compact('branches','branch_id'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function createProcess(Request $request) {
        try {
            if (!Sentinel::hasAccess('products.units.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $rules = [
                'slug' => 'required|unique:product_units',
                'title' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $data = [
                    'title' => $request->get('title'),
                    'slug' =>$request->get('slug'),
                    'description' =>$request->get('description'),
                    'user_id' => $user->id
                ];

                $this->productUnitRepository->create($data);

                Flash::success('Product Unit Saved!');
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($subdomain,$id) {
        try {
            if (!Sentinel::hasAccess('products.units.show')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $unit = $this->productUnitRepository->find($id);
            if (!$unit) {
                Flash::warning('Invalid Unit!');
                return redirect()->back()->withInput();
            }

            return view('manager.products.units.show', compact('user', 'unit'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($subdomain,$id) {
        try {
            if (!Sentinel::hasAccess('products.units.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $unit = $this->productUnitRepository->find($id);
            if (!$unit) {
                Flash::warning('Invalid Unit!');
                return redirect()->back()->withInput();
            }

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if($branch_id){
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;
            }else{
                foreach ($this->branchRepository->all() as $row){
                    $branches[$row->id] = $row->name;
                }
            }
            return view('manager.products.units.update', compact('unit','branches','branch_id'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updateProcess(Request $request,$subdomain, $id) {
        try {
            if (!Sentinel::hasAccess('products.units.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $unit = $this->productUnitRepository->find($id);
            if (!$unit) {
                Flash::warning('Invalid Unit!');
                return redirect()->back()->withInput();
            }
            $rules = [];

            if ($unit->title !== $request->get('title')) {
                $rules['title'] = 'required';
            }
            if ($unit->slug !== $request->get('slug')) {
                $rules['slug'] = 'required|unique:product_units';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $data = [
                    'title' => $request->get('title'),
                    'slug' =>$request->get('slug'),
                    'description' =>$request->get('description'),
                    'branch_id' => $request->get('branch_id')
                ];
                $this->productUnitRepository->update($id,$data);

                Flash::success('Product Unit Saved!');
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($subdomain,$id) {
        try {
            if (!Sentinel::hasAccess('products.units.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $unit = $this->productUnitRepository->find($id);
            if (!$unit) {
                Flash::warning('Invalid Unit!');
                return redirect()->back()->withInput();
            }
            $this->productUnitRepository->delete($id);
            Flash::success('Product Unit Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}

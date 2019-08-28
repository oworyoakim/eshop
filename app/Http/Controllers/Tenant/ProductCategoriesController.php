<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;

class ProductCategoriesController extends TenantBaseController
{

    /**
     * @var ICategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * ProductCategoriesController constructor.
     * @param IBranchRepository $branchRepository
     * @param ICategoryRepository $categoryRepository
     */
    public function __construct(IBranchRepository $branchRepository, ICategoryRepository $categoryRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAccess('products.categories.view')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;
                $categories = $this->categoryRepository->findBy([
                    'branch_id' => $branch_id
                ]);
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
                $categories = $this->categoryRepository->all();
            }

            return view('manager.products.categories.index', compact('categories', 'branches', 'branch_id'));
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
    public function create()
    {
        try {
            if (!Sentinel::hasAccess('products.categories.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch_id = $user->roles()->first()->pivot->branch_id;
            $branches = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
            }
            return view('manager.products.categories.create', compact('branches', 'branch_id'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function createProcess(Request $request, $subdomain)
    {
        try {
            if (!Sentinel::hasAccess('products.categories.create')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $rules = [
                'title' => 'required',
                'slug' => 'required|unique:categories'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $user = Sentinel::getUser();
                $data = [
                    'title' => $request->get('title'),
                    'slug' => $request->get('slug'),
                    'description' => $request->get('description'),
                    'branch_id' => $request->get('branch_id'),
                    'user_id' => $user->id
                ];

                $this->categoryRepository->create($data);

                Flash::success('Category Saved!');
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {

        } catch (Exception $ex) {

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function update($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('products.categories.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            $branch_id = $user->roles()->first()->pivot->branch_id;

            $category = $this->categoryRepository->find($id);

            if (!$category) {
                Flash::warning('Invalid Product Category!');
                return redirect()->back();
            }

            $branches = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch_id] = $branch->name;
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }
            }

            return view('manager.products.categories.update', compact('category', 'branches', 'branch_id'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
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
            if (!Sentinel::hasAccess('products.categories.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $category_id = $request->get('category_id');
            if ($id != $category_id) {
                Flash::warning('Invalid Product Category!');
                return redirect()->back();
            }

            $category = $this->categoryRepository->find($id);
            if (!$category) {
                Flash::warning('Invalid Product Category!');
                return redirect()->back()->withInput();
            }

            $rules = [];

            if ($category->title !== $request->get('title')) {
                $rules['title'] = 'required';
            }
            if ($category->slug !== $request->get('slug')) {
                $rules['slug'] = 'required|unique:categories';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $data = [
                    'title' => $request->get('title'),
                    'slug' => $request->get('slug'),
                    'description' => $request->get('description'),
                    'branch_id' => $request->get('branch_id')
                ];

                $this->categoryRepository->update($id, $data);

                Flash::success('Category Saved!');
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
     * @param  int $id
     * @return Response
     */
    public function destroy($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('products.categories.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $category = $this->categoryRepository->find($id);
            if (!$category) {
                Flash::warning('Invalid Product Category!');
                return redirect()->back();
            }

            $this->categoryRepository->delete($id);
            Flash::success('Category Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}

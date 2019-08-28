<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

class BranchesController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;
   
    /**
     * BranchesController constructor.
     * @param IBranchRepository $branchRepository
     */
    public function __construct(IBranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAccess('branches')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $user = Sentinel::getUser();
            $branches = $this->branchRepository->all();

            return view('branches.index', compact('branches'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
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
            if (!Sentinel::hasAccess('branches.create')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            return view('branches.create');
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
    public function createProcess(Request $request,$subdomain)
    {
        try {
            if (!Sentinel::hasAccess('branches.create')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $user = Sentinel::getUser();

            $branch = $this->branchRepository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
                'user_id' => $user->id
            ]);

            Flash::success("Branch Saved");
            return redirect('manager/branches');
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
    public function show($subdomain, $id)
    {
        try {
            if (!Sentinel::hasAccess('branches.show')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $branch = $this->branchRepository->find($id);

            if (!$branch) {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            return view('branches.show', compact('branch'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
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
            if (!Sentinel::hasAccess('branches.update')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $branch = $this->branchRepository->find($id);

            if (!$branch) {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            return view('branches.update', compact('branch'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function updateProcess(Request $request, $subdomain,$id)
    {
        try {
            if (!Sentinel::hasAccess('branches.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $branch = $this->branchRepository->find($id);

            if (!$branch) {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            $this->branchRepository->update($id, [
                'name' => $request->get('name'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'email' => $request->get('email')
            ]);

            Flash::success("Branch Saved");
            return redirect()->back();
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
            if (!Sentinel::hasAccess('branches.delete')) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }

            $branch = $this->branchRepository->find($id);

            if (!$branch) {
                Flash::warning('Invalid Branch!');
                return redirect()->back();
            }

            $this->branchRepository->delete($id);

            Flash::success('Branch Deleted!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
            return redirect()->back();
        }
    }
}

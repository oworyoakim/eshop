<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IStockTransferRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class StockTransfersController extends TenantBaseController
{
    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IStockTransferRepository
     */
    protected $stockTransferRepository;

    /**
     * @var IProductRepository
     */
    protected $productRepository;

    /**
     * @var ICategoryRepository
     */
    protected $categoryRepository;

    /**
     * StockTransfersController constructor.
     * @param IBranchRepository $branchRepository
     * @param IStockTransferRepository $stockTransferRepository
     * @param IProductRepository $productRepository
     * @param ICategoryRepository $categoryRepository
     */
    public function __construct(IBranchRepository $branchRepository,
                                IStockTransferRepository $stockTransferRepository,
                                IProductRepository $productRepository,
                                ICategoryRepository $categoryRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->stockTransferRepository = $stockTransferRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index($subdomain)
    {
        try {
            if (!Sentinel::hasAnyAccess(['stocks', 'stocks.transfer'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();
            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $branches = [];
            $categories = [];
            $products = [];
            if ($branch_id) {
                $branch = $this->branchRepository->find($branch_id);
                $branches[$branch->id] = $branch->name;
                foreach ($this->productRepository->findBy(['branch_id' => $branch_id]) as $row) {
                    $products[$row->id] = $row->title;
                }
                foreach ($this->categoryRepository->findBy(['branch_id' => $branch_id]) as $row) {
                    $categories[$row->id] = $row->title;
                }

                $transfers = $this->stockTransferRepository->getBranchTransfers($branch_id);
            } else {
                foreach ($this->branchRepository->all() as $row) {
                    $branches[$row->id] = $row->name;
                }

                foreach ($this->productRepository->all() as $row) {
                    $products[$row->id] = $row->title;
                }
                foreach ($this->categoryRepository->all() as $row) {
                    $categories[$row->id] = $row->title;
                }

                $transfers = $this->stockTransferRepository->getTransfers();
            }
            return view('manager.stocks.transfer', compact('transfers', 'branches', 'branch_id', 'products', 'categories'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

    public function requests($subdomain)
    {
        try {
            if (!Sentinel::hasAnyAccess(['stocks', 'stocks.transfer'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();
            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;
            $branches = [
                0 => 'All Branches'
            ];
            $categories = [];
            $products = [];
            if ($branch_id) {
                //$branch = $this->branchRepository->find($branch_id);
                //$branches[$branch->id] = $branch->name;
                foreach ($this->productRepository->findBy(['branch_id' => $branch_id]) as $row) {
                    $products[$row->id] = $row->title;
                }
                foreach ($this->categoryRepository->findBy(['branch_id' => $branch_id]) as $row) {
                    $categories[$row->id] = $row->title;
                }

                $requests = $this->stockTransferRepository->getBranchRequests($branch_id);
            } else {
                foreach ($this->productRepository->all() as $row) {
                    $products[$row->id] = $row->title;
                }
                foreach ($this->categoryRepository->all() as $row) {
                    $categories[$row->id] = $row->title;
                }

                $requests = $this->stockTransferRepository->getRequests();
            }
            foreach ($this->branchRepository->all() as $row) {
                if ($row->id != $branch_id) {
                    $branches[$row->id] = $row->name;
                }
            }
            return view('manager.stocks.requests', compact('requests', 'branches', 'branch_id', 'products', 'categories'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

    public function processRequest(Request $request)
    {
        try {
            if (!Sentinel::hasAnyAccess(['stocks', 'stocks.transfer'])) {
                Flash::warning('Permission Denied!');
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $branch_id = $user->roles()->withPivot(['branch_id'])->first()->pivot->branch_id;

            $data = [
                'request_code' => time(),
                'requesting_branch_id' => $request->get('requesting_branch_id'),
                'notes' => $request->get('notes'),
                'user_id' => $user->id,
                'request_date' => date('Y-m-d')
            ];

            $requested_branch_id = intval($request->get('requested_branch_id'));
            if ($requested_branch_id) {
                $data['is_global'] = false;
                $data['requested_branch_id'] = $requested_branch_id;
            } else {
                $data['is_global'] = true;
            }

            //dd($data);

            $stockRequest = $this->stockTransferRepository->createRequest($data);
            $products = [];
            foreach ($request->get('basketItems') as $barcode => $item) {
                $product = $this->productRepository->findOneBy(['barcode' => $barcode]);
                if (!$product) {
                    Flash::warning('Invalid Product code:  ' . $barcode);
                    return redirect()->back()->withInput();
                }

                $products[$product->id] = ['quantity' => intval($item['quantity'])];
            }

            $stockRequest->products()->sync($products);

            //dd($request->all());
            Flash::success('Request Created!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }


}

@extends('layoutnew')
@section('title')
    {{trans_choice('general.stock',2)}} {{trans_choice('general.transfer',2)}}
@endsection
@section('header-scripts')
    <script>
        var products = [];
        var basket = new Inventory();
        $(document).ready(function () {
            getItems(0);
        });
    </script>
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class=""
                                    href="{{url('manager/stocks/transfer')}}">{{ trans_choice('general.transfer',2) }}</a>
            </li>
            <li class="nav-item"><a class="active"
                                    href="{{url('manager/stocks/transfer/requests')}}">{{ trans_choice('general.request',2) }}</a>
            </li>
        </ul>
        <div class="box-body row">
            <div class="col-6"></div>
            <div class="col-6 text-right">
                @if(Sentinel::hasAccess('stocks.transfer'))
                    <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#request-modal">
                        <i
                                class="fa fa-reply"></i> {{trans_choice('general.request',1)}} {{trans_choice('general.stock',2)}}
                    </button>
                @endif
            </div>
        </div>
        <div class="box-body row">
            <div class="col-12 table-responsive">
                <table id="stock-transfer-requests-datatable" class="table table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Code</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $row)
                        <tr>
                            <td>{{date('d/m/Y',strtotime($row->request_date))}}</td>
                            <td>{{$row->request_code}}</td>
                            <td>{{$row->requestingBranch->name}}</td>
                            <td>
                                @if($row->is_global)
                                    All Branches
                                @else
                                    {{$row->requestedBranch->name}}
                                @endif
                            </td>
                            <td>
                                @if($row->status === 'pending')
                                    <span class="label label-warning">{{$row->status}}</span>
                                @endif
                                @if($row->status === 'canceled')
                                    <span class="label label-danger">{{$row->status}}</span>
                                @endif
                                @if($row->status === 'partial')
                                    <span class="label label-info">{{$row->status}}</span>
                                @endif
                                @if($row->status === 'approved')
                                    <span class="label label-success">{{$row->status}}</span>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="request-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form action="{{url('manager/stocks/transfer/requests')}}" method="post"
                      onsubmit="return submitRequest();">
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans_choice('general.new',1)}} {{trans_choice('general.request',1)}}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="requesting_branch_id" value="{{$branch_id}}">
                            <div class="form-group">
                                <label>Branch</label>
                                {!! Form::select('requested_branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required','onchange'=>'getItems(event.target.value);')) !!}
                            </div>
                            <div class="form-group">
                                <label>Items</label>
                                <select id="barcode" name="barcode" class="form-control input-lg"
                                        autofocus="autofocus">

                                </select>
                            </div>
                            <div class="form-group table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>#</th>
                                        <th>Barcode</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th><i class="fa fa-trash"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="prodList">
                                    <tr>
                                        <th colspan="5">No items selected!</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                {!! Form::textarea('notes',null, array('class' => 'form-control', 'required'=>'required','rows'=>3)) !!}
                            </div>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-bold btn-pure btn-primary float-right">Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal -->
    </div>
    <!-- /.nav-tabs-custom -->
@endsection
@section('scripts')
    <script>
        $('#stock-transfer-requests-datatable').DataTable();
        $('#barcode').select2({
            width: '100%'
        });
        $('#barcode').change((event) => {
            let barcode = event.target.value;
            let prod = products.find((x) => {
                return x.barcode === barcode;
            });

            if (prod) {
                let product = new Product(prod.barcode, prod.title, prod.sellPrice, prod.quantity, prod.unit, prod.category, 'adjust');
                // console.log(product);
                basket.addProduct(product);
                //console.log('Inventory: ', inventory);
                renderBasket();
            }
            $('#barcode').val(null);
            //$('#barcode').val(null).trigger('change');
        });

        async function getItems(branchId) {
            let html = '<option value=""></option>';
            try {
                if (branchId > 0) {
                    products = await basket.fetchProducts("{{url('api/products/get-branch-products/')}}" + branchId);
                } else {
                    products = await basket.fetchProducts("{{url('api/products')}}");
                }
                console.log(products);
                for (var i = 0; i < products.length; i++) {
                    let id = products[i].barcode;
                    let name = products[i].barcode + ' (' + products[i].title + ' => ' + products[i].category + ')';
                    html += '<option value="' + id + '">' + name + '</option>';
                }
                $('#barcode').html(html);
            } catch (error) {
                console.error(error);
                swal({
                    title: 'Response Status!',
                    text: 'Products ' + error,
                    icon: 'error'
                });
                $('#barcode').html(html);
            }
        }

        function renderBasket() {
            let basketHtml = '';
            let keys = Object.keys(basket.products);
            if (keys.length === 0) {
                basketHtml += '<tr><th colspan="7">No items in the basket!</th></tr>';
            } else {
                // console.log('Keys: ', keys);
                Object.keys(basket.products).forEach((code, index) => {
                    let item = basket.products[code];
                    basketHtml += '<tr>';

                    basketHtml += '<td width="5">' + (index + 1) + '</td>';
                    basketHtml += '<td width="100">' + item.barcode + '</td>';
                    basketHtml += '<td>' + item.title + ' (' + item.category + ')</td>';
                    basketHtml += '<td><div class="input-group"><input type="number" id="quantiy_' + code + '" name="basketItems[' + code + '][quantity]" value="' + item.quantity + '" max="' + item.stockQty + '" placeholder="' + item.stockQty + ' ' + item.units + ' Available!" title="' + item.stockQty + ' ' + item.units + ' Available!"  class="form-control input-sm text-left" min="1" onchange="updateQuantity(event.target.value,' + code + ')"  required><span class="input-group-addon"><small class="text-muted">' + item.units + '</small></span></div></td>';
                    basketHtml += '<td width="40"><button type="button" class="btn btn-warning btn-sm" onclick="removeItem(' + code + ')"><i class="fa fa-times"></i></button></td>';
                    basketHtml += '</tr>';
                });
            }
            $('#prodList').html(basketHtml);
        }

        function updateQuantity(qty, prodCode) {
            console.log(qty, prodCode);
            basket.updateItemQuantity(prodCode, numeral(qty).value());
            renderBasket();
        }

        function removeItem(prodCode) {
            // console.log(prodCode);
            basket.removeProduct(prodCode);
            renderBasket();
        }

        function submitRequest() {
            if (Object.keys(basket.products).length === 0) {
                swal({
                    title: 'Error!',
                    text: 'No item selected!',
                    icon: 'error',
                    timer: 5000
                });
                return false;
            }
            return true;
        }
    </script>
@endsection
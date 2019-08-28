@extends('layoutnew')
@section('title')
    {{trans_choice('general.new',1)}} {{trans_choice('general.purchase',1)}}
@endsection
@section('header-scripts')
    <script type="text/javascript">
        var products = {!! json_encode($products) !!};

        var basket = new Basket();

        $(document).ready(async function () {
            try {
                // products = await basket.fetchProducts("{{url('api/products')}}");
                console.log(products);
                let html = '<option value=""></option>';
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
            }
        });
    </script>
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('purchases.view'))
                    <a href="{{url('manager/purchases')}}" class="pull-right btn btn-default btn-sm"><i
                                class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>Product</h3>
                    <select id="barcode" name="barcode" class="form-control input-lg" autofocus="autofocus" onchange="addItem(event.target.value);">

                    </select>
                </div>
            </div>
            <br/>
            <form action="{{url('manager/purchases/create')}}" method="post" onsubmit="return makePurchase();">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                            <tr>
                                <th>#</th>
                                <th>Barcode</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="prodList">
                            <tr>
                                <th colspan="7">No items in the basket!</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h3>Supplier Information</h3>
                        <table class="table table-condensed">
                            <tr>
                                <th>Invoice Number</th>
                                <td>
                                    <input type="text" name="invoiceNumber" class="form-control" id="invoiceNumber"
                                           value="{{$invoiceNumber}}" required>
                                </td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>
                                    {!! Form::select('supplier_id',$suppliers,null, array('class' => 'form-control select2', 'required'=>'required','id'=>'supplier_id')) !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    {!! Form::select('status',array('pending'=>'Pending','received'=>'Received','canceled'=>'Canceled'),'received', array('class' => 'form-control', 'required'=>'required')) !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Files</th>
                                <td>
                                    {!! Form::file('receipt',array('class'=>'form-control')) !!}
                                    <p class="text-muted">Formats: pdf,jpeg,jpg,bmp,png</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h3>Payment <span class="pull-right"><b>Invoice: <span id="invoiceId">{{$invoiceNumber}}</span></b></span>
                        </h3>
                        <table class="table table-condensed">
                            <tr>
                                <th>Branch</th>
                                <td>
                                    {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>
                                    <input type="date" name="paymentDate" class="form-control" value="{{date('Y-m-d')}}"
                                           max="{{date('Y-m-d')}}"
                                           required>
                                </td>
                            </tr>
                            <tr>
                                <th>Grand Total</th>
                                <td><input type="text" class="form-control input-sm text-left" name="grandTotal"
                                           id="grandTotal" value="0" min="0" readonly></td>
                            </tr>
                            <tr>
                                <th>Amount Received</th>
                                <td><input type="text" class="form-control input-sm text-left" name="amountPaid"
                                           id="amountPaid" value="0" min="0" onkeyup="setAmountDue()" required></td>
                            </tr>
                            <tr>
                                <th>Amount Due</th>
                                <td><input type="text" class="form-control input-sm text-left" name="amountDue"
                                           id="amountDue" value="0" min="0" readonly></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @if(Sentinel::hasAccess('purchases.view'))
                            <a href="{{url('manager/purchases')}}"
                               class="btn btn-default btn-sm pull-left">{{trans_choice('general.cancel',1)}}</a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <button type="submit"
                                class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}} {{trans_choice('general.purchase',1)}}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">


        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#barcode').select2({
                width: '100%'
            });
            $('#supplier_id').select2({
                width: '100%'
            });

            $('#invoiceNumber').keyup((event) => {
                $('#invoiceId').html($('#invoiceNumber').val());
            });
        });

        function renderBasket() {
            let basketHtml = '';
            let keys = Object.keys(basket.items);
            if (keys.length === 0) {
                basketHtml += '<tr><th colspan="7">No items in the basket!</th></tr>';
                $('#amountDue').val(numeral(0).format('0,0'));
                $('#amountPaid').val(numeral(0).format('0,0'));
            } else {
                // console.log('Keys: ', keys);
                Object.keys(basket.items).forEach((code, index) => {
                    let item = basket.items[code];
                    basketHtml += '<tr>';

                    basketHtml += '<td width="5">' + (index + 1) + '</td>';
                    basketHtml += '<td>' + item.product.barcode + '</td>';
                    basketHtml += '<td>' + item.product.title + ' (' + numeral(item.product.category).format("0,0") + ')</td>';
                    basketHtml += '<td width="120"><input type="text" id="quantiy_' + code + '" name="basketItems[' + code + '][quantity]" value="' + numeral(item.quantity).format("0,0") + '"  class="form-control input-sm text-left" min="0" onchange="updateQuantity(event.target.value,' + code + ')"></td>';
                    basketHtml += '<td width="120"><input type="text" id="price_' + code + '" name="basketItems[' + code + '][price]" value="' + numeral(item.product.price).format("0,0") + '" class="form-control input-sm text-left" min="0" onchange="updatePrice(event.target.value,' + code + ')" required></td>';
                    basketHtml += '<td width="120"><input type="text" id="amount_' + code + '" name="basketItems[' + code + '][amount]" value="' + numeral(item.netAmount).format("0,0") + '" class="form-control input-sm text-left" min="0" disabled></td>';
                    basketHtml += '<td width="40"><button type="button" class="btn btn-warning btn-sm" onclick="removeItem(' + code + ')"><i class="fa fa-times"></i></button></td>';
                    basketHtml += '</tr>';
                });
            }
            $('#prodList').html(basketHtml);
            $('#grandTotal').val(numeral(basket.netAmount).format('0,0'));
            setAmountDue();
        }

        function setAmountDue() {
            let amountPaid = numeral($('#amountPaid').val()).value();
            let amountDue = basket.netAmount - amountPaid;
            $('#amountDue').val(numeral(amountDue).format('0,0'));
            $('#amountPaid').val(numeral(amountPaid).format('0,0'));
        }

        function updateQuantity(qty, prodCode) {
            console.log(qty, prodCode);
            basket.updateItemQuantity(prodCode, numeral(qty).value());
            renderBasket();
            setAmountDue();
        }

        function updatePrice(price, prodCode) {
            console.log(price, prodCode);
            basket.updateItemPrice(prodCode, numeral(price).value());
            renderBasket();
            setAmountDue();
        }

        function addItem(barcode) {
            let prod = products.find((x) => {
                return x.barcode === barcode;
            });

            if (prod) {
                let product = new Product(prod.barcode, prod.title, prod.costPrice, prod.quantity, prod.unit, prod.category, 'buy');
                console.log(product);
                let item = new Item(product);
                item.setQuantity(1);
                basket.addItem(item);
                //console.log('Basket: ', basket);
                renderBasket();
            }
            $('#barcode').val(null);
            //$('#barcode').val(null).trigger('change');
        }

        function removeItem(prodCode) {
            // console.log(prodCode);
            basket.removeItem(prodCode);
            renderBasket();
            setAmountDue();
        }

        function makePurchase() {
            if (Object.keys(basket.items).length === 0) {
                swal({
                    title: 'Error!',
                    text: 'No item in the basket!',
                    icon: 'warning'
                });
                return false;
            }
            return true;
        }

    </script>
@endsection
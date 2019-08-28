@extends('layoutnew')
@section('title')
    {{trans_choice('general.new',1)}} {{trans_choice('general.sale',1)}}
@endsection
@section('header-scripts')
    <script>
        var products = [];
        var basket = new Basket();

        @if(\App\Models\Tenant\BusinessSetting::where('setting_key','enable_vat')->first()->setting_value)
        basket.setTaxRate({{\App\Models\Tenant\BusinessSetting::where('setting_key','vat')->first()->setting_value}});
        @endif

        $(document).ready(async function () {
            try {
                products = await basket.fetchProducts("{{url('api/products')}}");
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
    <div class="box box-body">
        <div class="row">
            <div class="col-12">
                <h3>Product</h3>
                <select id="barcode" name="barcode" class="form-control-lg" autofocus="autofocus">

                </select>
            </div>
        </div>
    </div>
    <div class="box">
        <form action="{{url('manager/sales/create')}}" method="post" onsubmit="return makePurchase();">
            <div class="box-body">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead class="bg-secondary">
                            <tr>
                                <th class="w-sm-50">#</th>
                                <th class="w-sm-50">Barcode</th>
                                <th class="w-sm-50">Name</th>
                                <th class="w-sm-50">Quantity</th>
                                <th class="w-sm-50">Unit Price</th>
                                <th class="w-sm-50">Total</th>
                                <th class="w-sm-50">Discount</th>
                                <th class="w-sm-50"><i class="fa fa-trash"></i></th>
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
                        <h3>Customer Information</h3>
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" name="invoiceNumber" class="form-control" id="invoiceNumber"
                                   value="{{$invoiceNumber}}" required>
                        </div>
                        <div class="form-group">
                            <label>Branch</label>
                            {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="paymentDate" id="paymentDate" class="form-control" value="{{date('Y-m-d')}}"
                                   max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            {!! Form::select('customer_id',$customers,isset($customer_id)?:null, array('class' => 'form-control select2', 'required'=>'required','id'=>'customer_id')) !!}
                            @if(Sentinel::hasAccess('customers.create'))
                                <button type="button" data-toggle="modal" data-target="#customer-modal"
                                        class="btn btn-success btn-sm mt-5"><i
                                            class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.customer',1)}}
                                </button>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            {!! Form::select('status',array('pending'=>'Pending','completed'=>'Completed','canceled'=>'Canceled'),'completed', array('class' => 'form-control', 'required'=>'required')) !!}
                        </div>
                        <div class="form-group">
                            <label>Files</label>
                            {!! Form::file('receipt',array('class'=>'form-control')) !!}
                            <p class="text-muted">Formats: pdf,jpeg,jpg,bmp,png</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Payment Information <span class="pull-right"><b>(Invoice: <span id="invoiceId">{{$invoiceNumber}}</span>)</b></span>
                        </h3>
                        <div class="form-group">
                            <label>Sub Total</label>
                            <input type="text" class="form-control input-sm text-left" name="subTotal"
                                   id="subTotal" value="0" min="0" readonly>
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" class="form-control input-sm text-left" name="discount" id="discount"
                                   value="0" min="0" readonly>
                        </div>
                        <div class="form-group">
                            <label>{{trans_choice('general.vat',1)}} (<span
                                        id="vatRate">{{\App\Models\Tenant\BusinessSetting::where('setting_key','vat')->first()->setting_value}}</span>%)</label>
                            <input type="text" class="form-control input-sm text-left" name="vat"
                                   id="vat" value="0" min="0" readonly>
                        </div>
                        <div class="form-group">
                            <label>Grand Total</label>
                            <input type="text" class="form-control input-sm text-left" name="grandTotal"
                                   id="grandTotal" value="0" min="0" readonly>
                        </div>
                        <div class="form-group">
                            <label>Amount Received</label>
                            <input type="text" class="form-control input-sm text-left" name="amountPaid"
                                   id="amountPaid" value="0" min="0" onkeyup="setAmountDue()" required>
                        </div>
                        <div class="form-group">
                            <label>Amount Due</label>
                            <input type="text" class="form-control input-sm text-left" name="amountDue"
                                   id="amountDue" value="0" min="0" readonly>
                        </div>
                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" name="dueDate" id="dueDate" class="form-control" placeholder="Due Date">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-4">
                        @if(Sentinel::hasAccess('sales.view'))
                            <a href="{{url('manager/sales')}}"
                               class="btn btn-default btn-lg pull-left">{{trans_choice('general.cancel',1)}}</a>
                        @endif
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-warning btn-lg pull-right" name="printReceipt"
                                value="printReceipt"><i class="fa fa-print"></i> Print Receipt
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="submit"
                                class="btn btn-primary btn-lg pull-right">{{trans_choice('general.save',1)}} {{trans_choice('general.transaction',1)}}</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Customer Creation Modal -->
        <div class="modal fade" id="customer-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form action="{{url('manager/customers/create')}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans_choice('general.new',1)}} {{trans_choice('general.customer',1)}}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                {!! Form::label('branch_id',trans_choice('general.branch',1), array('class' => '')) !!}
                                {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('name',trans_choice('general.name',1), array('class' => '')) !!}
                                {!! Form::text('name',null,array('class' => 'form-control', 'required'=>'required')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('email',trans_choice('general.email',1), array('class' => '')) !!}
                                {!! Form::email('email',null,array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('phone',trans_choice('general.phone',1), array('class' => '')) !!}
                                {!! Form::text('phone',null,array('class' => 'form-control', 'required'=>'required')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('address',trans_choice('general.address',1), array('class' => '')) !!}
                                {!! Form::textarea('address',null,array('class' => 'form-control', 'required'=>'required','rows'=>5)) !!}
                            </div>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-bold btn-pure btn-primary float-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal -->
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#barcode').select2({
                width: '100%'
            });
            $('#customer_id').select2({
                width: '100%',
                fontSize: '14px'
            });
            $('.pcheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                increaseArea: '30%' // optional
            });
            $('#barcode').change((event) => {
                let barcode = event.target.value;
                let prod = products.find((x) => {
                    return x.barcode === barcode;
                });

                if (prod) {
                    let product = new Product(prod.barcode, prod.title, prod.sellPrice, prod.quantity, prod.unit, prod.category, 'sell');
                    let enableDiscount = Boolean({{\App\Models\Tenant\BusinessSetting::where('setting_key','enable_discount')->first()->setting_value}});
                    console.log(enableDiscount);
                    let discount = null;
                    if (enableDiscount) {
                        discount = numeral(prod.discount);
                        console.log(discount);
                        if (discount > 0) {
                            product.setDiscount(discount);
                            console.log("Product Scope", discount);
                        } else {
                            discount = numeral({{\App\Models\Tenant\BusinessSetting::where('setting_key','discount')->first()->setting_value}});
                            console.log("Global Scope", discount);
                            if (discount > 0) {
                                product.setDiscount(discount);
                            }
                        }
                    }
                    if (discount > 0) {
                        product.setDiscount(discount);
                    } else if (enableDiscount) {
                        product.setDiscount({{\App\Models\Tenant\BusinessSetting::where('setting_key','discount')->first()->setting_value}});
                    }

                    console.log(product);
                    let item = new Item(product);
                    item.setQuantity(1);
                    basket.addItem(item);
                    //console.log('Basket: ', basket);
                    renderBasket();
                }
                $('#barcode').val(null);
                //$('#barcode').val(null).trigger('change');
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
                $('#discount').val(numeral(0).format('0,0'));
            } else {
                // console.log('Keys: ', keys);
                Object.keys(basket.items).forEach((code, index) => {
                    let item = basket.items[code];
                    basketHtml += '<tr>';

                    basketHtml += '<td width="5">' + (index + 1) + '</td>';
                    basketHtml += '<td width="100">' + item.product.barcode + '</td>';
                    basketHtml += '<td>' + item.product.title + ' (' + item.product.category + ')</td>';
                    basketHtml += '<td><div class="input-group"><input type="text" id="quantiy_' + code + '" name="basketItems[' + code + '][quantity]" value="' + numeral(item.quantity).format('0,0') + '"  class="form-control input-sm text-left" min="0" onchange="updateQuantity(event.target.value,' + code + ')"><span class="input-group-addon"><small class="text-muted">' + item.product.units + '</small></span></div></td>';
                    basketHtml += '<td><input type="text" id="price_' + code + '" name="basketItems[' + code + '][price]" value="' + numeral(item.product.price).format('0,0') + '" class="form-control input-sm text-left" min="0" onchange="updatePrice(event.target.value,' + code + ')" readonly></td>';
                    basketHtml += '<td><input type="text" id="amount_' + code + '" name="basketItems[' + code + '][amount]" value="' + numeral(item.netAmount).format('0,0') + '" class="form-control input-sm text-left" min="0" disabled></td>';
                    basketHtml += '<td><input type="text" id="discount_' + code + '" name="basketItems[' + code + '][discount_rate]" value="' + numeral(item.product.discount).format('0,0') + '" class="form-control input-sm text-left" min="0" onchange="updateItemDiscount(event.target.value,' + code + ')"></td>';
                    basketHtml += '<td width="40"><button type="button" class="btn btn-warning btn-sm" onclick="removeItem(' + code + ')"><i class="fa fa-times"></i></button></td>';
                    basketHtml += '</tr>';
                });
            }
            $('#prodList').html(basketHtml);
            $('#subTotal').val(numeral(basket.grossAmount).format('0,0'));
            $('#discount').val(numeral(basket.discount).format('0,0'));
            $('#vat').val(numeral(basket.taxAmount).format('0,0'));
            $('#vatRate').val(numeral(basket.taxRate).format('0,0'));
            $('#grandTotal').val(numeral(basket.netAmount).format('0,0'));
            $('#amountPaid').val(numeral(basket.netAmount).format('0,0'));

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

        function updateItemDiscount(rate, prodCode) {
            console.log(rate, prodCode);
            basket.updateItemDiscount(prodCode, numeral(rate).value());
            renderBasket();
            setAmountDue();
        }

        function updatePrice(price, prodCode) {
            console.log(price, prodCode);
            basket.updateItemPrice(prodCode, numeral(price).value());
            renderBasket();
            setAmountDue();
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

            let dueAmount = numeral($('#amountDue').val()).value();
            let dueDate = $('#dueDate').val().trim();

            if(dueAmount > 0 && 0 === dueDate.length){
                swal({
                    title: 'Error!',
                    text: 'Due date must be supplied!',
                    icon: 'error'
                });
                return false;
            }

            let paymentDate = $('#paymentDate').val().trim();

            if(new Date(dueDate) < new Date(paymentDate)){
                swal({
                    title: 'Error!',
                    text: 'Due date must be greater than or equal to payment date!',
                    icon: 'error'
                });
                return false;
            }
            return true;
        }

    </script>
@endsection
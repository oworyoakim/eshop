@extends('layoutnew')
@section('title')
    {{trans_choice('general.stock',2)}} {{trans_choice('general.adjustment',2)}}
@endsection
@section('header-scripts')
    <script>
        var products = [];
        var basket = new Inventory();

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
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool pull-right" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <h3>Item</h3>
                <select id="barcode" name="barcode" class="form-control input-lg" autofocus="autofocus">

                </select>
            </div>
        </div>
        <br/>
        <form action="{{url('manager/stocks/adjust')}}" method="post" onsubmit="return updateInventory();">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-secondary">
                        <tr>
                            <th>#</th>
                            <th>Barcode</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Sell Price</th>
                            <th>Discount <br/>(in %age)</th>
                            <th><i class="fa fa-trash"></i></th>
                        </tr>
                        </thead>
                        <tbody id="prodList">
                        <tr>
                            <th colspan="7">No items selected!</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table-condensed" width="100%" cellpadding="10">
                        <tr>
                            <th>Branch</th>
                            <td>
                                {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>
                                {!! Form::textarea('notes',null, array('class' => 'form-control', 'required'=>'required')) !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @if(Sentinel::hasAnyAccess(['stocks','stocks.view']))
                        <a href="{{url('manager/stocks')}}"
                           class="btn btn-default btn-sm pull-left">{{trans_choice('general.cancel',1)}}</a>
                    @endif
                </div>
                <div class="col-md-6">
                    <button type="submit"
                            class="btn btn-primary btn-sm pull-right">{{trans_choice('general.update',1)}} {{trans_choice('general.inventory',1)}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#barcode').select2({
            width: '100%'
        });

        $('#barcode').change((event) => {
            let barcode = event.target.value;
            let prod = products.find((x) => {
                return x.barcode === barcode;
            });

            if (prod) {
                let product = new Product(prod.barcode, prod.title, prod.sellPrice, prod.quantity, prod.unit, prod.category,'adjust');
                // console.log(product);
                basket.addProduct(product);
                //console.log('Inventory: ', inventory);
                renderBasket();
            }
            $('#barcode').val(null);
            //$('#barcode').val(null).trigger('change');
        });

    });

    function renderBasket() {
        let basketHtml = '';
        let keys = Object.keys(basket.products);
        if (keys.length === 0) {
            basketHtml += '<tr><th colspan="7">No items in the basket!</th></tr>';
        }else {
            // console.log('Keys: ', keys);
            Object.keys(basket.products).forEach((code, index) => {
                let item = basket.products[code];
                basketHtml += '<tr>';

                basketHtml += '<td width="5">' + (index + 1) + '</td>';
                basketHtml += '<td width="100">' + item.barcode + '</td>';
                basketHtml += '<td>' + item.title + ' ('+ item.category +')</td>';
                basketHtml += '<td><div class="input-group"><input type="text" id="quantiy_' + code + '" name="basketItems[' + code + '][quantity]" value="' + numeral(item.stockQty).format('0,0') + '"  class="form-control input-sm text-left" min="0" onchange="updateQuantity(event.target.value,' + code + ')"><span class="input-group-addon"><small class="text-muted">'+ item.units +'</small></span></div></td>';
                basketHtml += '<td><input type="text" id="price_' + code + '" name="basketItems[' + code + '][price]" value="' + numeral(item.price).format('0,0') + '" class="form-control input-sm text-left" min="0" onchange="updatePrice(event.target.value,' + code + ')"></td>';
                basketHtml += '<td><input type="text" id="discount_' + code + '" name="basketItems[' + code + '][discount]" value="' + numeral(item.discount).format('0,0') + '" class="form-control input-sm text-left" min="0"></td>';
                basketHtml += '<td width="40"><button type="button" class="btn btn-warning btn-sm" onclick="removeItem(' + code + ')"><i class="fa fa-times"></i></button></td>';
                basketHtml += '</tr>';
            });
        }
        $('#prodList').html(basketHtml);
    }

    function updateQuantity(qty, prodCode) {
        console.log(qty, prodCode);
        basket.updateStock(prodCode, numeral(qty).value());
        renderBasket();
    }

    function updateDiscount(rate, prodCode) {
        console.log(numeral(rate), prodCode);
        basket.updateDiscount(prodCode, numeral(rate).value());
        renderBasket();
    }

    function updatePrice(price, prodCode) {
        console.log(numeral(price), prodCode);
        basket.updatePrice(prodCode, numeral(price).value());
        renderBasket();
    }

    function removeItem(prodCode) {
        // console.log(prodCode);
        basket.removeProduct(prodCode);
        renderBasket();
    }

    function updateInventory() {
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
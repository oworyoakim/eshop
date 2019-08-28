<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="{{asset('admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/master_style.css')}}">
    <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/js/jquery.min.js')}}"></script>
    <script src="{{asset('admin/js/admin.js')}}"></script>
    <script src="{{asset('admin/js/app.js')}}"></script>
    <title>Bill</title>
    <style>
        body {
            width: 80mm;
            margin: 0;
            padding: 0;
            background-color: #FFF;
            color: #000;
            font: 18px Arial, sans-serif;
        }
    </style>
</head>
<body onload="printReceipt();" class="text-center">
@if(Sentinel::inRole('cashier'))
    @php($returnUrl = url('cashier/sales/create'))
@else
    @php($returnUrl = url('manager/sales/create'))
@endif
<table class="table table-condensed table-sm" width="100%">
    <thead>
    <tr>
        <td colspan="5" class="text-bold text-justify">
            <h4>
                <img src="{{asset('uploads/'.$subdomain.'/'.\App\Models\Tenant\BusinessSetting::where('setting_key','company_logo')->first()->setting_value)}}"
                     class="img-responsive">
            </h4>
            <h4>
                    <span class="">
                        Invoice #:
                    </span>
                <span class="float-right">
                        {{$trans->transcode}}
                    </span>
            </h4>
            <h4>
                    <span>
                        Date:
                    </span>
                <span class="float-right">
                        {{date('l d M, Y',strtotime($trans->transact_date))}}
                    </span>
            </h4>
            <h4>
                    <span>
                        Branch:
                    </span>
                <span class="float-right">
                        {{$trans->branch->name}}
                    </span>
            </h4>
            <h4>
                    <span>
                        Customer:
                    </span>
                <span class="float-right">
                        {{$trans->customer->phone}}
                    </span>
            </h4>
            <h4>
                    <span>
                        Cashier:
                    </span>
                <span class="float-right">
                        {{$trans->user->fullName()}}
                    </span>
            </h4>
            <h4>
                    <span>
                        Time:
                    </span>
                <span class="float-right">
                        {{date('g:i:s A',strtotime($trans->transact_date))}}
                    </span>
            </h4>
        </td>
    </tr>

    <tr class="bg-gray-light text-bold bb-1 bt-1">
        <th>Code</th>
        <th>Item</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody class="small bg-gray-light bb-1">
    @foreach($trans->orderlines as $key => $item)
        <tr>
            <td>{{$item->product->barcode}}</td>
            <td>{{$item->product->title}}</td>
            <td>{{$item->sell_price}}</td>
            <td>{{$item->quantity}} <span class="text-muted">{{$item->product->unit->slug}}</span></td>
            <td>{{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}} {{number_format($item->gross_amount)}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot class="">
    <tr class="text-bold bb-1">
        <td colspan="2" class="text-left">
            Subtotal
        </td>
        <td colspan="3" class="text-right">
            {{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}} {{number_format($trans->gross_amount)}}
        </td>
    </tr>
    <tr class="bb-1">
        <td colspan="2" class="text-left">
            Tax ({{$trans->vat_rate}}%)
        </td>
        <td colspan="3"
            class="text-right">{{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}} {{number_format($trans->vat_amount)}}
        </td>
    </tr>
    <tr class="text-bold bb-1">
        <td colspan="2" class="text-left">
            Total
        </td>
        <td colspan="3" class="text-right">
            {{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}} {{number_format($trans->net_amount)}}
        </td>
    </tr>
    <tr class="text-bold bb-1">
        <td colspan="2" class="text-left">
            Paid
        </td>
        <td colspan="3" class="text-right">
            {{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}} {{number_format($trans->paid_amount)}}
        </td>
    </tr>
    <tr class="text-bold bb-1">
        <td colspan="2" class="text-left">
            Due
        </td>
        <td colspan="3" class="text-right">
            {{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}} {{number_format($trans->due_amount)}}
        </td>
    </tr>
    <tr class="no-border">
        <td></td>
        <td colspan="3" class="text-lg-center">
            {{--{!! $barcode !!}--}}
            <img src="data:image/png;base64,{{$barcode}}" alt="barcode" class="img-responsive h-50"/><br/>
            <span class="text-center">{{$trans->transcode}}</span>
        </td>
        <td></td>
    </tr>
    </tfoot>
</table>
<script>
    // $(document).ready(function () {
    //     printReceipt();
    // });
    //window.onload = printReceipt;
    function printReceipt() {
        window.document.close();
        window.focus();
        window.print();
        window.location = '{{$returnUrl}}';
        window.close();
    }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bill</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <style type="text/css">
        html {
            font-family: sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .container {
            /*margin: 20px;*/
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            overflow-x: auto;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-red {
            color: #fc4b6c;
        }

        .text-blue {
            color: #398bf7;
        }

        .text-yellow {
            color: #fcc525 !important;;
        }

        .text-green {
            color: #06D73E;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            border-spacing: 0;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse !important;
        }

        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
        }

        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }

        .table-condensed > thead > tr > th,
        .table-condensed > tbody > tr > th,
        .table-condensed > tfoot > tr > th,
        .table-condensed > thead > tr > td,
        .table-condensed > tbody > tr > td,
        .table-condensed > tfoot > tr > td {
            padding: 5px;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .table-hover > tbody > tr:hover {
            background-color: #f5f5f5;
        }
        .lead {
            margin-bottom: 20px;
            font-size: 12pt;
            /*font-weight: bold;*/
            line-height: 1.4;
        }
        .small {
            font-size: 85%;
        }
    </style>
</head>
<body>
<div class="container">
    <table class="table table-condensed table-striped table-hover" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="text-bold" style="font-size: 20pt; border-bottom: 1px solid;">
            <td colspan="3" class="text-left">
                <span>INVOICE</span>
            </td>
            <td colspan="3" class="text-right">
                <span>Date: {{date('d/m/Y',strtotime($sale->transact_date))}}</span>
            </td>
        </tr>
        <tr class="" style="font-size: 12pt;margin-top: 20px;">
            <td colspan="2" class="text-left" style="border-top: 1px solid #ddd;">
                <span class="text-bold">From</span>
                <address>
                    <strong class="text-red">
                        {{\App\Models\Tenant\BusinessSetting::where('setting_key','company_name')->first()->setting_value}}
                    </strong><br>
                    {{\App\Models\Tenant\BusinessSetting::where('setting_key','portal_address')->first()->setting_value}}
                    <br>
                    {{\App\Models\Tenant\BusinessSetting::where('setting_key','company_address')->first()->setting_value}}
                    , {{\App\Models\Tenant\BusinessSetting::where('setting_key','company_country')->first()->setting_value}}
                    <br>
                    Phone: {{\App\Models\Tenant\BusinessSetting::where('setting_key','company_phone')->first()->setting_value}}
                    <br>
                    Email: {{\App\Models\Tenant\BusinessSetting::where('setting_key','company_email')->first()->setting_value}}
                </address>
            </td>
            <td colspan="2" class="text-center" style="border-top: 1px solid #ddd;">
                <span style="margin-bottom: 40px;">
                    <span class="text-bold">Invoice #: </span>
                    <span class="text-right">{{$sale->transcode}}</span>
                </span><br/>
                <span style="margin-bottom: 40px;">
                    <span class="text-bold">Payment: </span>
                    <span class="text-right">@if($sale->due_date) {{date('d/m/Y',strtotime($sale->due_date))}} @else {{date('d/m/Y',strtotime($sale->transact_date))}}  @endif</span>
                </span><br/>
                <span>
                    <span class="text-bold">Account: </span>
                    <span class="text-right">{{$sale->transcode}}</span>
                </span>
            </td>
            <td colspan="2" class="text-right" style="border-top: 1px solid #ddd;">
                <span class="text-bold">To</span>
                <address>
                    <strong class="text-blue">{{$sale->customer->name}}</strong><br>
                    {{$sale->customer->address}}<br>
                    Phone: {{$sale->customer->phone}}<br>
                    Email: {{$sale->customer->email}}
                </address>
            </td>
        </tr>

        <tr class="" style="font-size: 13pt; background-color: #F2F6F8;">
            <th style="border-top: 1px solid #ddd;">
                #
            </th>
            <th style="border-top: 1px solid #ddd;">
                Code
            </th>
            <th style="border-top: 1px solid #ddd;">
                Title
            </th>
            <th class="text-center" style="border-top: 1px solid #ddd;">
                Quantity
            </th>
            <th class="text-center" style="border-top: 1px solid #ddd;">
                Unit Cost
            </th>
            <th class="text-center" style="border-top: 1px solid #ddd;">
                Total
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($sale->orderlines as $key => $row)
            <tr>
                <td width="15">{{++$key}}</td>
                <td>{{$row->product->barcode}}</td>
                <td width="105">{{$row->product->title}} ({{$row->product->category->title}})</td>
                <td class="text-center">{{$row->quantity}} {{$row->product->unit->slug}}</td>
                <td class="text-center">{{$row->sell_price}}</td>
                <td class="text-center">{{$row->gross_amount}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot style="border-top: 1px solid #ddd;">
        <tr class="">
            <!-- accepted payments column -->
            <td colspan="4" class="">
                <p class="lead"><b>Note:</b></p>
                <p class="small" style="margin-top: 10px;">
                    Goods once sold, are not refundable!
                </p>
            </td>
            <!-- /.col -->
            <td colspan="2">
                <table cellpadding="5" cellspacing="5">
                    <tr class="lead">
                        <th width="150" class="text-bold">Sub-Total Amount:</th>
                        <td width="15" class="text-right">({{$currencySymbol}})</td>
                        <td class="text-bold text-left">{{number_format($sale->gross_amount)}}</td>
                    </tr>
                    <tr class="lead">
                        <th class="text-bold">Tax ({{number_format($sale->vat_rate)}}%):</th>
                        <td width="15" class="text-right">({{$currencySymbol}})</td>
                        <td class="text-yellow text-bold text-left">{{number_format($sale->vat_amount)}}</td>
                    </tr>
                    <tr class="lead">
                        <th class="text-bold">Discount ({{number_format($sale->discount_rate)}}%):</th>
                        <td width="15" class="text-right">({{$currencySymbol}})</td>
                        <td class="text-yellow text-bold text-left">{{number_format($sale->discount_amount)}}</td>
                    </tr>
                    <tr class="lead">
                        <th class="text-bold" style="border-top: 1px solid #ddd;">
                            Total Amount:
                        </th>
                        <td width="15" class="text-right" style="border-top: 1px solid #ddd;">
                            ({{$currencySymbol}})
                        </td>
                        <td class="text-bold text-left" style="border-top: 1px solid #ddd;">
                            {{number_format($sale->net_amount)}}
                        </td>
                    </tr>
                    <tr class="lead">
                        <th class="text-bold">Amount Paid:</th>
                        <td width="15" class="text-right">({{$currencySymbol}})</td>
                        <td class="text-green text-bold text-left">{{number_format($sale->paid_amount)}} </td>
                    </tr>
                    <tr class="lead">
                        <th class="text-bold">Amount Due:</th>
                        <td width="15" class="text-right">({{$currencySymbol}})</td>
                        <td class="text-red text-bold text-left">
                            {{number_format($sale->due_amount)}}
                        </td>
                    </tr>
                </table>
            </td>
            <!-- /.col -->
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
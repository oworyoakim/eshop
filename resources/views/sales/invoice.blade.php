@extends('layoutnew')
@section('title')
    Invoice Template
@endsection
@section('content')
    <div class="box">
        <div class="box-body" id="printableArea">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h2 class="page-header">
                        INVOICE
                        <small class="pull-right">Date: {{date('d/m/Y',strtotime($sale->transact_date))}}</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-4 invoice-col">
                    From
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
                </div>
                <!-- /.col -->
                <div class="col-4 invoice-col">
                    <img src="{{asset('uploads/'.$subdomain.'/'.\App\Models\Tenant\BusinessSetting::where('setting_key','company_logo')->first()->setting_value)}}"
                         class="img-responsive">
                </div>
                <div class="col-4 invoice-col text-right">
                    To
                    <address>
                        <strong class="text-blue">{{$sale->customer->name}}</strong><br>
                        {{$sale->customer->address}}<br>
                        Phone: {{$sale->customer->phone}}<br>
                        Email: {{$sale->customer->email}}
                    </address>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-12 invoice-col">
                    <div class="invoice-details row no-margin">
                        <div class="col-3 text-left"><b>Invoice </b>#{{$sale->transcode}}</div>
                        <div class="col-3 text-center"><b>Payment
                                Due:</b> @if($sale->due_date) {{date('d/m/Y',strtotime($sale->due_date))}} @else {{date('d/m/Y',strtotime($sale->transact_date))}}  @endif
                        </div>
                        <div class="col-3 text-right"><b>Account:</b> {{$sale->transcode}}</div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sale->orderlines as $key => $row)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$row->product->barcode}}</td>
                                <td>{{$row->product->title}} ({{$row->product->category->title}})</td>
                                <td class="text-right">{{$row->quantity}} {{$row->product->unit->slug}}</td>
                                <td class="text-right">{{$row->sell_price}}</td>
                                <td class="text-right">{{$row->gross_amount}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row bt-1">
                <!-- accepted payments column -->
                <div class="col-8">
                    <p class="lead"><b>Note:</b></p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        Goods once sold, are not refundable!
                    </p>
                </div>
                <!-- /.col -->
                <div class="col-4 text-right">
                    <table width="100%">
                        <tr class="lead">
                            <td class="text-bold">Sub-Total Amount: </td>
                            <td width="30" class="">({{$currencySymbol}})</td>
                            <td class="text-bold">{{number_format($sale->gross_amount)}}</td>
                        </tr>
                        <tr class="lead bb-1">
                            <td class="text-bold">Discount ({{number_format($sale->discount_rate)}}%): </td>
                            <td width="30">({{$currencySymbol}})</td>
                            <td class="text-yellow text-bold">{{number_format($sale->discount_amount)}}</td>
                        </tr>
                        <tr class="lead bb-1">
                            <td class="text-bold">Tax ({{number_format($sale->vat_rate)}}%): </td>
                            <td width="30">({{$currencySymbol}})</td>
                            <td class="text-yellow text-bold">{{number_format($sale->vat_amount)}}</td>
                        </tr>
                        <tr class="lead">
                            <td  class="text-bold text-right">Total Amount:</td>
                            <td width="30">({{$currencySymbol}})</td>
                            <td class="text-bold">
                                {{number_format($sale->net_amount)}}
                            </td>
                        </tr>
                        <tr class="lead">
                            <td class="text-bold">Amount Paid:</td>
                            <td width="30">({{$currencySymbol}})</td>
                            <td class="text-green text-bold">{{number_format($sale->paid_amount)}} </td>
                        </tr>
                        <tr class="lead bb-1">
                            <td class="text-bold">Amount Due:</td>
                            <td width="30">({{$currencySymbol}})</td>
                            <td class="text-red text-bold">
                                {{number_format($sale->due_amount)}}
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-3">
                    <button id="printInvoice" class="btn btn-danger" type="button"><span><i
                                    class="fa fa-print"></i> Print Invoice</span>
                    </button>
                </div>

                <div class="col-3">
                    <button type="button" id="printReceipt" class="btn btn-warning pull-right"
                            style="margin-right: 5px;">
                        <i class="fa fa-print"></i> Print Receipt
                    </button>
                </div>
                <div class="col-3">
                    <a href="{{url('manager/sales/pdf/'.$sale->transcode)}}" target="_blank" id="downloadPdf"
                       class="btn btn-info pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> Generate PDF
                    </a>
                </div>
                <div class="col-3">
                    <button id="cancelOrder" class="btn btn-danger-outline pull-right" type="button"><span><i
                                    class="fa fa-cut"></i> Cancel Order</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('admin/js/jquery.PrintArea.js')}}"></script>
    <script>
        $(document).ready(function () {
            /*
            $("#printInvoice").printPreview({
                obj2print:'#printableArea'
            });
            */
            $("#printInvoice").click(function () {

                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("#printableArea").printArea(options);
            });


            $("#printReceipt").click(function () {
                let printWindow = window.open("{{url('manager/sales/print/'.$sale->transcode)}}", "", "width=11cm");
                printWindow.document.close();
                printWindow.focus();
                //printWindow.print();
                //printWindow.location = "about:blank";
                //printWindow.close();
            });
        });
    </script>
@endsection
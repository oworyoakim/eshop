@extends('layoutnew')
@section('title')
Invoice Template
@endsection
@section('content')
    <section class="invoice printableArea">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h2 class="page-header">
                    INVOICE
                    <small class="pull-right">Date: 12/04/2017</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                From
                <address>
                    <strong class="text-red">MultiPurpose Admin</strong><br>
                    124 Lorem Ipsum, Suite 478<br>
                    Dummuy, USA 123456<br>
                    Phone: (00) 123-456-7890<br>
                    Email: info@example.com
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 invoice-col text-right">
                To
                <address>
                    <strong class="text-blue">Doe Shina</strong><br>
                    124 Lorem Ipsum, Suite 478<br>
                    Dummuy, USA 123456<br>
                    Phone: (00) 789-456-1230<br>
                    Email: conatct@example.com
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-12 invoice-col">
                <div class="invoice-details row no-margin">
                    <div class="col-md-6 col-lg-3"><b>Invoice </b>#0154879</div>
                    <div class="col-md-6 col-lg-3"><b>Order ID:</b> FC12548</div>
                    <div class="col-md-6 col-lg-3"><b>Payment Due:</b> 14/08/2017</div>
                    <div class="col-md-6 col-lg-3"><b>Account:</b> 0001245879315</div>
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
                        <th>Description</th>
                        <th>Serial #</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Unit Cost</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Milk Powder</td>
                        <td>12345678912514</td>
                        <td class="text-right">2</td>
                        <td class="text-right">$26.00</td>
                        <td class="text-right">$52.00</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Air Conditioner</td>
                        <td>12345678912514</td>
                        <td class="text-right">1</td>
                        <td class="text-right">$1500.00</td>
                        <td class="text-right">$1500.00</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>TV</td>
                        <td>12345678912514</td>
                        <td class="text-right">2</td>
                        <td class="text-right">$540.00</td>
                        <td class="text-right">$1080.00</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Mobile</td>
                        <td>12345678912514</td>
                        <td class="text-right">3</td>
                        <td class="text-right">$320.00</td>
                        <td class="text-right">$960.00</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-12 col-sm-6">
                <p class="lead"><b>Payment Methods:</b></p>
                <img src="../../../images/visa.png" alt="Visa">
                <img src="../../../images/mastercard.png" alt="Mastercard">
                <img src="../../../images/american-express.png" alt="American Express">
                <img src="../../../images/paypal2.png" alt="Paypal">

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                    plugg
                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                </p>
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 text-right">
                <p class="lead"><b>Payment Due</b><span class="text-danger"> 14/08/2017 </span></p>

                <div>
                    <p>Sub - Total amount : $3,592.00</p>
                    <p>Tax (18%) : $646.56</p>
                    <p>Shipping : $110.44</p>
                </div>
                <div class="total-payment">
                    <h3><b>Total :</b> $4,349.00</h3>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <button id="print" class="btn btn-warning" type="button"><span><i class="fa fa-print"></i> Print</span>
                </button>
                <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit
                    Payment
                </button>
                <button type="button" class="btn btn-danger pull-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i> Generate PDF
                </button>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
@endsection
@section('scripts')

@endsection
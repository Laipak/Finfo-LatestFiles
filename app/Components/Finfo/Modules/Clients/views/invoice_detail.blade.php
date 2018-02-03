@extends($app_template['backend'])

@section('content')
<section class="content">
	<div class="container outer-section">      
            <div class="row text-center">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    This is an electronic generated receipt , for any issues please contact &nbsp;<strong> info@your domain.com</strong>

                </div>
            </div>
            <hr>

            <div class="row ">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h2>Client Details :</h2>
                    <h4><strong>{{ $invoice->amount }}</strong></h4>
                    <h4>678, Lamen Trees Lane,Boston Bay</h4>
                    <h4>United States - 2018976</h4>
                    <h4><strong>Email: </strong>justindemo@domain.com</h4>
                    <h4><strong>Call: </strong>+01-90-89-56-00</h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h2>Payment Details :</h2>
                    <h4><strong>Invoice No: </strong>#10009</h4>
                    <h4>Invoice Date:  	{{ date('d F Y', strtotime($invoice->invoice_date)) }}</h4>
                    <h4>Purchased On:  21st Jan 2015</h4>
                    <h4><strong>Amount Paid : </strong>672 USD</h4>
                    <h4><strong>Delivery Status : </strong>Completed</h4>
                </div>
            </div>
            <hr>
            <br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Perticulars</th>
                                    <th>Quantity.</th>
                                    <th>Unit Price</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Plugin Development</td>
                                    <td>2</td>
                                    <td>100 USD</td>
                                    <td>200 USD</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Wordpress Installation</td>
                                    <td>1</td>
                                    <td>300 USD</td>
                                    <td>300 USD</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Hosting Space</td>
                                    <td>1</td>
                                    <td>25 USD</td>
                                    <td>25 USD</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Website Design</td>
                                    <td>1</td>
                                    <td>75 USD</td>
                                    <td>75 USD</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9" style="text-align: right; padding-right: 30px;">
                    Total Amount Without Applying Any Taxes : 
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <strong>600 USD </strong>
                </div>
                <hr>
                <div class="col-lg-9 col-md-9 col-sm-9" style="text-align: right; padding-right: 30px;">
                    Total Amount After Applying Any Taxes ( 12.50 % ) : 
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <strong>672 USD </strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <strong>IMPORTANT INSTRUCTIONS :
                    </strong>
                    <h5># This is an electronic receipt so doesn't require any signature.</h5>
                    <h5># All perticulars are listed with 10.50 % taxes , so if any issue please contact us immediately.</h5>
                    <h5># You can contact us between 10:am to 6:00 pm on all working days.</h5>
                </div>
            </div>

        </div>
    </div>
 </section>
@stop

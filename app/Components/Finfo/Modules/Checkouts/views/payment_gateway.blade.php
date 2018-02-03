@extends($app_template['frontend'])

@section('content')

<section>
	<div class="container">
		<div class="row">
	<div class="col-xs-12">
            <div>                
                <h2>Payment Summary ID = {{ $id }}</h2>
                <p>Please review the following details for this transaction.</p>
            </div>
            <hr>
    </div>
    <div class="row">
        <div class="col-xs-12">            
                    <div class="table-responsive">
                        <table border="1" class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <td><strong>Basic Plan upFront Fees</strong></td>
                                    <td><strong>Item Price</strong></td>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>14 days Free Trial Period</td>
                                    <td class="text-right">$0.00</td>                                   
                                </tr>
                                <tr>
                                    <td>Initial One-Time Charge</td>
                                    <td class="text-right">$14.95</td>                                   
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Today's Total</strong></td>
                                    <td class="text-right"><strong>$14.95</strong></td>                                    
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
        </div>
	</div>
	<div class="row">
        <div class="col-md-12">            
                    <div class="table-responsive">
                        <table border="1" class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <td><strong>Basic Plan upFront Fees</strong></td>
                                    <td><strong>Item Price</strong></td>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Recurring Monthly Fee After Trial Period</td>
                                    <td class="text-right">$14.95</td>                                   
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2"><i>This recurring charge will expire after 12 months</i></td>                                                                    
                                </tr>                         
                            </tbody>
                        </table>
                    </div>
        </div>
	</div>
	<form action="" class="form-horizontal">
	<div class="row">
		<div class="col-md-12">
			<h3>Your Billing Information</h3>			
				<div class="form-group">
		          <label class="col-lg-3 control-label">Address 1:</label>
		          <div class="col-lg-8">
		            <input name="first_name" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Address 2:</label>
		          <div class="col-lg-8">
		            <input name="last_name" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">City:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Zip/Postal Code:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>
		        <?php 
		        	$all = array(

		        		);

		         ?>
		        <div class="form-group">
		          <label class="col-lg-3 control-label">Country:</label>
		          <div class="col-lg-8">
		            <select class="form-control" name="country" id="country">
	                </select>
		          </div>
		        </div>
				
				<div class="form-group">
		          <label class="col-lg-3 control-label">State:</label>
		          <div class="col-lg-8">
		            <select class="form-control" name="state" id="state">
	                </select>
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Phone:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Your Payment Information</h3>
				<div class="form-group">
		          	<label class="col-lg-4 control-label">Use the shipping address as billing address: </label>
					<div class="col-lg-8">
			            <div class="checkbox"><input name="checkbox" type="checkbox"></div>
			        </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">First Name:</label>
		          <div class="col-lg-8">
		            <input name="last_name" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Last Name:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Card Number:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Card Verification Number:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>
				
				<div class="form-group">
		          <label class="col-lg-3 control-label">Expiration Date:</label>
		          <div class="row">
		          	<div class="col-md-3">
		            <select class="form-control" name="expiry_month" id="expiry_month">
	                    <option >1</option>
	                    <option>2</option>
	                </select>
		          </div>
		          <div class="col-md-3">
		            <select class="form-control" name="expiry_year" id="expiry_year">
	                    <option >2015</option>
	                    <option>2016</option>
	                </select>
		          </div>
		          </div>
		        </div>

		        <div class="form-group">
		          <label class="col-lg-3 control-label">Email:</label>
		          <div class="col-lg-8">
		            <input name="email_address" class="form-control" value="" type="text">
		          </div>
		        </div>

		        <div class="form-group">
		         	<label class="col-lg-3 control-label"></label>		          
		          <div class="col-lg-8">
		            <input class="btn btn-success" type="Submit" value="Pay Now">
		          </div>
		        </div>

			  </div>				
			</div>
			<div class="row">
				<div class="col-md-3">
					<h3>Credit Card</h3>
					<i class="fa fa-lock" style="color:orange"></i> <span>Secure</span>			
				</div>	
				<div class="col-md-6">
					<h3>
						<a href="#"><img src="{{ asset('img/credit/visa.png') }}" alt=""></a>
						<a href="#"><img src="{{ asset('img/credit/master-card.png') }}" alt=""></a>
						<a href="#"><img src="{{ asset('img/credit/american_express.png') }}" alt=""></a>
						<a href="#"><img src="{{ asset('img/credit/discover.png') }}" alt=""></a>
					</h3>
				</div>	
			</div>
	
	    </form>
	</div>
</section>
@stop

@section('style')
	<style type="text/css">
		.text-right {
			text-align: right;
		}
		.text-left {
			text-align: left;
		}
	</style>
@stop

@section('script')
{!! Html::script('js/country-and-month.js') !!}

<script language="javascript">
	populateCountries("country", "state");
	expMonth('expiry_month');
	expYear('expiry_year');

</script>

@stop
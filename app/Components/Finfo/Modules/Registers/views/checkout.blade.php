@extends($app_template['frontend'])

@section('content')
<div class="panel-grid" id="pg-453-5" >
	<div class="panel-row-style-default panel-row-style nav-one-page" style="background-color: #ecf0f5; padding-top: 100px; padding-bottom: 100px; " overlay="background-color: rgba(255,255,255,0); " id="checkout">
		<div class="container">
			<div class="panel-grid-cell" id="pgc-453-5-0" >

				<div class="panel-builder widget-builder widget_origin_title panel-first-child" id="panel-453-4-0-0">
					<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
						<h3  class="align-center">Payment Summary</h3><div class="divider colored"></div>
					</div>
				</div>

				<div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-5-0-1">
					<div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank">
						<div style="margin-bottom:60px;"></div>
					</div>
				</div>

				@if (count($errors) > 0)
	            <div class="col-xs-12">   
	                <div class="alert alert-danger text-center">
	                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	                        @foreach ($errors->all() as $error)
	                            {{ $error }}<br>
	                        @endforeach
	                </div>
	            </div>
	            @endif
            
				<div class="well well-sm panel-builder widget-builder widget_spot" id="panel-453-5-0-2">
					<div class="panel-grid" id="pg-905-0" >
						<div class="panel-row-style-default panel-row-style" >
							<div class="container">
								 <div class="table-responsive">
			                        <table border="1" class="table table-bordered">
			                            <thead>
			                                <tr class="success">
			                                    <td><strong>Summary</strong></td>
			                                    <td></td>
			                                </tr>
			                            </thead>
			                            <tbody>
			                                <tr>
			                                    <td>Package Selection</td>
			                                    <td class="text-right">
			                                    {{ strtoupper($package_subscribed->name)}}
			                                    </td>
			                                </tr>
			                                <tr>
			                                    <td>Package Price</td>
			                                    <td class="text-right">
			                                    {{$package_subscribed->pakage_price}}
			                                    </td>
			                                </tr>
			                                <tr>
			                                    <td>Package Period</td>
			                                    <td class="text-right">1 month</td>
			                               </tr>
			                                <tr>
			                                    <td>Subscription valid until</td>
			                                    <td class="text-right">{{Session::has('data') ? \Carbon\Carbon::parse(Session::get('data')['valid_until'])->format('d/M/Y') : ''}}</td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    {!! Form::open(array('url'=>route('finfo.registers.docheckout'), 'class'=>'form-horizontal', 'novalidate'=>'')) !!}
								<div class="row">
									<div class="col-md-12">
										<h3>Your Billing Information</h3>			
											<div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
									          <label class="col-lg-3 control-label">Street:</label>
									          <div class="col-lg-8">
									            <input name="street" class="form-control" value="{{ old('street') }}" type="text">
									          </div>
									        </div>
									        <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
									          <label class="col-lg-3 control-label">City:</label>
									          <div class="col-lg-8">
									            <input name="city" class="form-control" value="{{ old('city') }}" type="text">
									          </div>
									        </div>

									        <div class="form-group {{ $errors->has('zip_code') ? ' has-error' : '' }}">
									          <label class="col-lg-3 control-label">Zip/Postal Code:</label>
									          <div class="col-lg-8">
									            <input name="zip_code" class="form-control" value="{{ old('zip_code') }}" type="text">
									          </div>
									        </div>

									        <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
									          <label class="col-lg-3 control-label">Country:</label>
									          <div class="col-lg-8">
									            <select class="form-control" name="country" id="country" value="{{old('country')}}"></select>
									          </div>
									        </div>
											
											<div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
									          <label class="col-lg-3 control-label">State:</label>
									          <div class="col-lg-8">
									            <select class="form-control" name="state" id="state" value="{{old('state')}}"></select>
									          </div>
									        </div>

									        <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
									          <label class="col-lg-3 control-label">Phone:</label>
									          <div class="col-lg-8">
                                                                                      <input name="phone" class="form-control" value="{{ old('phone') }}" type="text" minlength="6" maxlength="20">
									          </div>
									        </div>
							                <input name="hide_company" class="form-control" value="{{Session::has('data') ? Session::get('data')['company_id'] : ''}}" type="hidden">
							                <input name="hide_csp" class="form-control" value="{{Session::has('data') ? Session::get('data')['csp_id'] : ''}}" type="hidden">

							                <input type="hidden" name="email" value="{{$email }}">
									</div>
								</div>
							<div class="row">
								<div class="col-md-12">
									<h3>Your Payment Information</h3>
						                        <div class="form-group {{ $errors->has('card_holder_name') ? ' has-error' : '' }}">
								          <label class="col-lg-3 control-label">Support card:</label>
								          <div class="col-lg-8">
								            <h3>
						                                <img id="card1" src="{{ asset('img/credit/visapng.png') }}" alt="">
						                                <img id="card2" src="{{ asset('img/credit/master-card.png') }}" alt="">
						                                <img id="card3" src="{{ asset('img/credit/american_express.png') }}" alt="">
						                                <img id="card4"  src="{{ asset('img/credit/discover.png') }}" alt="">
						                            </h3>
								          </div>
								        </div>
                       <!--  <div class="form-group">
                                  <label class="col-lg-3 control-label">Payment Method:</label>
                                        <div class="col-lg-8">
                     <select name="payment_method" class="form-control">
                      @foreach($payment_method as $payment)
                      <option value="{{$payment->id}}">{{$payment->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                
                                                                            </div>
								        </div> -->
								        <div class="form-group {{ $errors->has('card_holder_name') ? ' has-error' : '' }}">
								          <label class="col-lg-3 control-label">Card holder name:</label>
								          <div class="col-lg-8">
								            <input name="card_holder_name" class="form-control" value="" type="text">
								          </div>
								        </div>

								        <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
								          <label class="col-lg-3 control-label">Card Number:</label>
								          <div class="col-lg-8">
								            <input name="card_number" class="form-control" value="" type="text">
								          </div>
								        </div>

								        <div class="form-group {{ $errors->has('cvv_number') ? ' has-error' : '' }}">
								          <label class="col-lg-3 control-label">Card Verification Number:</label>
								          <div class="col-lg-8">
								            <input name="cvv_number" class="form-control" value="" type="text">
								          </div>
								        </div>
										
										<div class="form-group {{ $errors->has('expiry_month') || $errors->has('expiry_year')  ? ' has-error' : '' }}">
								          <label class="col-lg-3 control-label">Expiration Date:</label>
								          <div class="row">
								          	<div class="col-md-3">
								            <select class="form-control" name="expiry_month" id="expiry_month">
								            	<option value="">Select Month</option>
							                    <option value="1">01-January</option>
							                    <option value="2">02-February</option>
							                    <option value="3">03-March</option>
							                    <option value="4">04-April</option>
							                    <option value="5">05-May</option>
							                    <option value="6">06-June</option>
							                    <option value="7">07-July</option>
							                    <option value="8">08-August</option>
							                    <option value="9">09-September</option>
							                    <option value="10">10-October</option>
							                    <option value="11">11-November</option>
							                    <option value="12">12-December</option>
							                </select>
								          </div>
								          <div class="col-md-3">
								            <select class="form-control" name="expiry_year" id="expiry_year">
							                </select>
								          </div>
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
							    </form>
							</div><!-- END CONTAINER -->
						</div>
					</div>
				</div>

				<div class="panel-builder widget-builder widget_origin_title panel-last-child" id="panel-453-4-0-3">
					<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
						<h3 class="align-center"></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('seo')
    <title>Finfo | Registration - Checkout</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('script')
{!! Html::script('js/country-and-month.js') !!}
<script type="text/javascript">
	$('document').ready(function(){
		populateCountries("country", "state");		
		expYear('expiry_year');
		$('#country option[value="{{old("country")}}"]').prop('selected', true);		
		populateStates("country", "state");
		$('#state option[value="{{old("state")}}"]').prop('selected', true);
	});

</script>

@stop
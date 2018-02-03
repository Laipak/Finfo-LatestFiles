@extends($app_template['client.backend'])

@section('content')
<section>
	<div class="content">
		<div class="row">
	        <div class="col-xs-12">
	            <div>                
	                <h2>Payment Summary</h2>
	                <p>Please review the following details for this transaction.</p>
	            </div>
            <hr>
		    </div>
		</div>
           
    	<div class="row">
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
    		<div class="col-xs-12">            
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
                                <td class="text-right">{{ isset($data['package'][0]->title ) ? $data['package'][0]->title  :  "" }}</td>
                            </tr>
                            <tr>
                                <td>Package Price</td>
                                <td class="text-right">{{$price_cur['symbol']}} {{ isset($data['package'][0]->price )  ? number_format( round($data['package'][0]->price * $price_cur['exchange_rate'], 0, PHP_ROUND_HALF_UP), 2) : ""}}</td>
                            </tr>
                            <tr>
                                <td>Package Period</td>
                                <td class="text-right"> 1 month</td>
                           </tr>
                            <tr>
                                <td>Subscription valid until</td>
                                <td class="text-right"> {{ date('d-M-Y', strtotime($data['date'])) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        	</div>
		</div> 
	
	{!! Form::open(array('url'=>route('client.upgrade.docheckout'), 'class'=>'form-horizontal', 'novalidate'=>'')) !!}
	
	<div class="row">
		<div class="col-md-12">
			<h3>Your Payment Information</h3>
            <div class="form-group {{ $errors->has('card_holder_name') ? ' has-error' : '' }}">
	          <label class="col-sm-4 col-lg-3 control-label">Support card:</label>
	          	<div class="col-sm-7 col-lg-8">
	            <h3>
                            <img id="card1" src="{{ asset('img/credit/visapng.png') }}" alt="">
                            <img id="card2" src="{{ asset('img/credit/master-card.png') }}" alt="">
                            <img id="card3" src="{{ asset('img/credit/american_express.png') }}" alt="">
                            <img id="card4"  src="{{ asset('img/credit/discover.png') }}" alt="">
                        </h3>
	          	</div>
	        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Payment Method:</label>
                            <div class="col-lg-8">
                                <select name="payment_method" class="form-control">
                                    @foreach($payment_method as $payment)
                                        <option value="{{$payment->id}}">{{$payment->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
	        <div class="form-group {{ $errors->has('card_holder_name') ? ' has-error' : '' }}">
	          <label class="col-sm-4 col-lg-3 control-label">Card holder name:</label>
	          <div class="col-sm-7 col-lg-8">
	            <input name="card_holder_name" class="form-control" value="" type="text">
	            {!! $errors->first('card_holder_name', '<span class="help-block">:message</span>') !!}
	          </div>
	        </div>

	        <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
	          <label class="col-sm-4 col-lg-3 control-label">Card Number:</label>
	          <div class="col-sm-7 col-lg-8">
	            <input name="card_number" class="form-control" value="" type="text">
	            {!! $errors->first('card_number', '<span class="help-block">:message</span>') !!}
	          </div>
	        </div>

	        <div class="form-group {{ $errors->has('cvv_number') ? ' has-error' : '' }}">
	          <label class="col-sm-4 col-lg-3 control-label">Card Verification Number:</label>
	          <div class="col-sm-7 col-lg-8">
	            <input name="cvv_number" class="form-control" value="" type="text">
	            {!! $errors->first('cvv_number', '<span class="help-block">:message</span>') !!}
	          </div>
	        </div>
			
			<div class="form-group {{ $errors->has('expiry_month') || $errors->has('expiry_year')  ? ' has-error' : '' }}">
	          <label class="col-sm-4 col-lg-3 control-label">Expiration Date:</label>
	          
	          <div class="col-sm-4 col-md-3">
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
                {!! $errors->first('expiry_month', '<span class="help-block">:message</span>') !!}                
	          </div>
	          <div class="col-sm-4 col-md-3">
	            <select class="form-control" name="expiry_year" id="expiry_year">
                </select>
                {!! $errors->first('expiry_year', '<span class="help-block">:message</span>') !!}
	          </div>
	        
	        </div>
	        <div class="form-group">
	         	<label class="col-sm-4 col-lg-3 control-label"></label>		          
	          <div class="col-sm-7 col-lg-8">
	            <input class="btn btn-success" type="Submit" value="Pay Now">
	          </div>
	        </div>
		</div>				
	</div>
			<input type="hidden" name='package_name' value="{{$data['package'][0]->title}}">
			<input type="hidden" name='package_id' value="{{$data['package'][0]->id}}">
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
		.selected {
			border: 2px solid #e53840;
			width: 55px;
		}
		@media (max-width: 767px){
			#expiry_month{
				margin-bottom: 10px;
			}
		}
		
	</style>
@stop

@section('script')
{!! Html::script('js/country-and-month.js') !!}
	<script type="text/javascript">
	$('document').ready(function(){
		expYear('expiry_year');

	});

	</script>

@stop



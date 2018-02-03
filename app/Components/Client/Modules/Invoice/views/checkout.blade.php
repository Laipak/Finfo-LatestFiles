@extends($app_template['client.backend'])

@section('content')
	<section class="content" id="list-user">
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
        {!! Form::open(array('route' => 'client.invoices.backend.do-checkout', 'method' => 'post')) !!}
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
                            <td class="text-right">
                            	{{$checkout->name}}
                            </td>
                        </tr>
                        <tr>
                            <td>Package Price</td>
                            <td class="text-right">
                            	$ {{number_format(round($checkout->amount), 2)}}
                            </td>
                        </tr>
                        <tr>
                            <td>Package Period</td>
                            <td class="text-right">1 Month</td>
                       </tr>
                        <tr>
                            <td>Subscription valid until</td>
                            <td class="text-right">{{$valid_date}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>

		<div class="row">
		<div class="col-md-12">
			<h3>Your Payment Information</h3>
                <div class="form-group {{ $errors->has('card_holder_name') ? ' has-error' : '' }}">
		          <label class="col-lg-3 control-label label-card">Support card:</label>
		          <div class="col-lg-8">
		            <h3>
                        <img id="card1" src="{{ asset('img/credit/visapng.png') }}" alt="">
                        <img id="card2" src="{{ asset('img/credit/master-card.png') }}" alt="">
                        <img id="card3" src="{{ asset('img/credit/american_express.png') }}" alt="">
                        <img id="card4"  src="{{ asset('img/credit/discover.png') }}" alt="">
                    </h3>
		          </div>
		          <div class="clearfix"></div>
		        </div>
		        <div class="form-group {{ $errors->has('card_holder_name') ? ' has-error' : '' }}">
		          <label class="col-lg-3 control-label">Card holder name:</label>
		          <div class="col-lg-8">
		            <input name="card_holder_name" class="form-control" value="" type="text">
		          </div>
		          <div class="clearfix"></div>
		        </div>

		        <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
		          <label class="col-lg-3 control-label">Card Number:</label>
		          <div class="col-lg-8">
		            <input name="card_number" class="form-control" value="" type="text">
		          </div>
		          <div class="clearfix"></div>
		        </div>

		        <div class="form-group {{ $errors->has('cvv_number') ? ' has-error' : '' }}">
		          <label class="col-lg-3 control-label">Card Verification Number:</label>
		          <div class="col-lg-8">
		            <input name="cvv_number" class="form-control" value="" type="text">
		          </div>
		          <div class="clearfix"></div>
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
			<input type="hidden" name="invoice_id" value="{{$checkout->id}}">
			<input type="hidden" name='package_name' value="{{$checkout->name}}">
			<input type="hidden" name='package_id' value="{{$checkout->package_id}}">
		{!! Form::close() !!}
    </section>
@stop
@section('style')
<style type="text/css">
	.label-card{
		padding-top: 25px;
	}    
</style>
@stop

@section('script')
{!! Html::script('js/country-and-month.js') !!}
<script type="text/javascript">
	expYear('expiry_year');

</script>
@stop

@extends($app_template['frontend'])

@section('content')
<div class="panel-grid" id="pg-453-5" >
	<div class="panel-row-style-default panel-row-style nav-one-page" style="background-color: #ecf0f5; padding-top: 100px; padding-bottom: 100px; " overlay="background-color: rgba(255,255,255,0); " id="pricing">
		<div class="container">
			<div class="panel-grid-cell" id="pgc-453-5-0" >

				<div class="panel-builder widget-builder widget_origin_title panel-first-child" id="panel-453-4-0-0">
					<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
						<h3  class="align-center">Sign up now!</h3><div class="divider colored"></div>
					</div>
				</div>

				<div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-5-0-1">
					<div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank">
						<div style="margin-bottom:60px;"></div>
					</div>
				</div>
				@if (count($errors) > 0)
              <div class="alert alert-danger text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                  @endforeach
              </div>
            @endif


            @if(session()->has('success'))              
              <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session('success') }}
              </div>
            @endif
            @if(session()->has('error'))              
              <div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  {{ session('error') }}
              </div>
            @endif
				<div class="well well-sm panel-builder widget-builder widget_spot" id="panel-453-5-0-2">
					<div class="col-sm-12">
						{!! Form::open(array('url'=>'do-register', 'class'=>'register', 'novalidate'=>'')) !!}
							<legend>Package Information</legend>
							<div class="control-group {{ $errors->has('package_information') ? ' has-error' : '' }}">
								<lable><strong>Package:</strong></lable>
                                {!! Form::select('package_id', $package, $package_subscribed->id, ['class' => 'form-control']) !!}
                            </div>
                            <!--11-10-2017 new div class="control-group">
                                <lable><strong>Market:</strong></lable>
                                {!! Form::select('market', $market , (isset($_GET['market']))?$_GET['market']:""  , array('class'=>'form-control spacing', 'id'=> 'lunch_market')) !!}
                            </div end of 11-10-2017-->
							
							<div class="control-group">
                                <lable><strong>Country:</strong></lable>
                                <select name='market' class='form-control' id='lunch_market'>
                                    @foreach($market as $key => $value)
                                        @if($currency_val == $key)
                                            <option value='{{$key}}' selected>{{ ucfirst(strtolower($value))}}</option>
                                        @else
                                            <option value='{{$key}}'>{{ ucfirst(strtolower($value))}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
							
                            <br>
							
                            <legend>Company Information</legend>
                            <div class="control-group {{ $errors->has('company_name') ? ' has-error' : '' }}">
                                <div class="form-group floating-label-form-group controls" >
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" placeholder="Company Name" name="company_name"  value="{{ old('company_name') }}" autofocus>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
							
                            <div class="control-group {{ $errors->has('account_name1') ? ' has-error' : '' }}">

                                <div class="form-group floating-label-form-group controls">
                                    <label>Preferred URL</label>
                                    <input type="text" class="form-control accountName" placeholder="Name of Company" name="account_name1"  value="{{ old('account_name1') }}" id="account_name1" autofocus>
                                    <span class='surrfix-domain'></span>
                                    <p class="help-block text-danger"></p>
                                    <label id="account_name1-error" class="has-error" for="account_name1"></label>
                                    
                                    <input type="hidden" name="host_name" id="host_name" />
                                    <!-- <label id="account_name1-error" class="has-error" for="account_name1"></label> -->
                                </div>
                                
                            </div>
                            <!--<div class="control-group {{ $errors->has('account_name2') ? ' has-error' : '' }}">
                                <div class="form-group floating-label-form-group controls">
                                    <label>Preferred Account Name 2</label>
                                    <input type="text" class="form-control accountName" placeholder="Preferred Account Name 2" name="account_name2"  value="{{ old('account_name2') }}" id="account_name2" readonly="" autofocus>
                                    <span class='surrfix-domain'></span>
                                    <label id="account_name2-error" class="has-error" for="account_name2"></label>
                                    <p class="help-block text-danger"></p>
                                </div>
                                
                            </div>!-->
                            <div class="control-group {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                <div class="form-group floating-label-form-group controls">
                                    <label>Company Phone Number</label>
                                    <input type="text" class="form-control" placeholder="Company Phone Number" name="phone_number"  value="{{ old('phone_number') }}" minlength="6" maxlength="20">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group {{ $errors->has('company_email') ? ' has-error' : '' }}">
                                <div class="form-group floating-label-form-group controls">
                                    <label style="display: inline-block;">Company Email Address</label><p id="pop-co-email-help">(?)</p>
                                    <input type="text" class="form-control" placeholder="Company Email Address" name="company_email"  value="{{ old('company_email') }}">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group {{ $errors->has('company_website') ? ' has-error' : '' }}">
                                <div class="form-group floating-label-form-group controls">
                                    <label>Company Website Address</label>
                                    <input type="text" class="form-control" placeholder="Company Website Address" name="company_website"  value="{{ old('company_website') }}">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group {{ $errors->has('company_address') ? ' has-error' : '' }}">
                                <div class="form-group floating-label-form-group controls">
                                    <label>Company Address</label>
                                    <input type="text" class="form-control" placeholder="Company Address" name="company_address"  value="{{ old('company_address') }}">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
							
                            <br>
							
                            <legend>User Information</legend>
							
                            <div class="control-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
	                              <div class="form-group floating-label-form-group controls">
	                                <label>First Name</label> 
	                                  <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" autofocus>
	                                </div>
	                        </div>
							
	                        <div class="control-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
	                              <div class="form-group floating-label-form-group controls">
	                                <label>Last Name</label> 
	                                  <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" autofocus>
	                                </div>
	                        </div>
	                                <label class="error"></label>
	                            <div class="control-group {{ $errors->has('phone') ? ' has-error' : '' }}">
	                                <div class="form-group floating-label-form-group controls">
	                                    <label>Phone Number</label>
	                                    <input type="text" class="form-control" id="phone" placeholder="Phone Number" name="phone" value="{{ old('phone') }}" autofocus minlength="6" maxlength="20">
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                            </div> 
	                            <div class="control-group {{ $errors->has('email') ? ' has-error' : '' }}">
	                                <div class="form-group floating-label-form-group controls">
	                                    <label style="display: inline-block;">Email Address</label><p id="pop-email-help">(?)</p>
	                                    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" value="{{ old('email') }}" autofocus>
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                            </div>
	                            <div class="control-group {{ $errors->has('password') ? ' has-error' : '' }}">
	                                <div class="form-group floating-label-form-group controls">
	                                    <label>Password</label>
	                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" minlength=8 >
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                            </div>
	                            <div class="control-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
	                                <div class="form-group floating-label-form-group controls">
	                                    <label>Confirm Password</label>
	                                    <input class="form-control" type="password"  placeholder="Confirm Password" name="password_confirmation" minlength=8 >
	                                    <p class="help-block text-danger"></p>
	                                </div>
	                            </div>
	                            <div class="control-group">
	                                <div class="form-group floating-label-form-group controls">
	                                     <input name="checkbox" type="checkbox" >&nbsp&nbsp&nbsp&nbsp<lable><a href="{{route('term-of-service')}}" target="_brank" style="color:inherit;">Terms and conditions</a></lable>
	                                    <label id="checkbox-error" class="has-error" for="checkbox"></label>
	                                </div>
	                            </div>
	                            <div class="clearfix"></div>
	                            <button type="submit" class="btn btn-success btn-lg">Register</button>
	                            <input type="hidden" name="h_package_currency_type" value="{{$package_subscribed->currencyType}}">
						{!! Form::close() !!}
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
    <title>Finfo | Registration</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
<style type="text/css">
 	.has-error{
        color: red;
        opacity: 1 !important;
        position: static !important;
    }
    .accountName{
        width: 85% !important;
        display: inline !important;
    }
    .floating-label-form-group label{
        display: block;
    }
    #pop-email-help, #pop-co-email-help{
        display: inline-block; 
        padding-left: 10px;
        color:#DF810C;
        cursor: pointer;
    }
    .popover{
        min-width: 500px;
    }
    @media (max-width: 767px){
        .popover{
            min-width: 200px;
        }
    }
</style>
@stop

@section('script')
<script type="text/javascript">

$(document).ready(function(){
    
   var url = document.location.hostname;
   
   $('#host_name').val(url);
    
    
});
    
</script>
@stop


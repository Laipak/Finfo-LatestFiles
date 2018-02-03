@extends($app_template['frontend'])

@section('content')
	<section id="login-container">
		<div class="col-md-4 col-md-offset-4 panel-top">
			<div class="box" id="login-box">
				<div class="row">
	                <div class="col-lg-12 text-center">
	                    <h2>Reset Password</h2>
	                    <hr class="star-primary">
	                </div>
	            </div>

				{!! Form::open(array('url' => route('finfo.admin.do.reset.password'), 'id' => 'frm-reset-password')) !!}
				    <div class="box-body">
				    	@if(Session::has('message'))
				    		<?php
				    			$alert 		= 'danger';
				    			$message 	= Session::get('message');

				    			if($message['status'] == 1){
				    				$alert = 'success';
				    			}
				    		?>
			
							<div class="alert alert-{{$alert}} text-center">               
		                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>              
		                            {{ $message['message'] }}                                            
		                    </div>
						@endif
				        <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
				            {!! Form::label('password', 'Password') !!}
				            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password', 'value' => Input::old('password'), 'minlength' => 8]) !!}
				            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
				        </div>
				        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
				            {!! Form::label('password_confirmation', 'Password confirmation' )!!}
				            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password confirmation', 'id' => 'password_confirmation', 'value' => Input::old('password_confirmation'), 'minlength' => 8]) !!}
				            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
				        </div>
				    </div>
				    <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-success btn-lg">Reset Password</button>
                            </div>
                        </div>
				    {!! Form::hidden('password_reset_token', $data['password_reset_token']) !!}

				{!! Form::close() !!}
			</div>
		</div>
		<div class="clearfix"></div>
	</section>
@stop

@section('style')
{!! Html::style('css/finfo/forget-password.css') !!}
<style type="text/css">
	.erorr{
        color: red;
    }
    .btn-success{
        padding: 10px 15px;
    }

</style>
@stop


@section('script')
{!! HTML::script('js/jquery.validate.min.js') !!}

<script type="text/javascript">
	jQuery.validator.addMethod("pwcheck", function(value, element) {
       return   /([A-Z])/.test(value) // upper case
            &&  /([!,%,&,@,#,$,^,*,?,_,~])/.test(value) // has spacial character
            &&  /[a-z]/.test(value) // has a lowercase letter
            &&  /\d/.test(value) // has a digit
    }, "Password must contain at least one letter, one capital letter, number and special character.");


	$("#frm-reset-password").validate({
		rules: {
			'password'		: {
				required: true,
				pwcheck: true,
			},
			'password_confirmation'	:{
				required: true,
				pwcheck: true,
				equalTo: "#password"
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	 });
</script>
@stop


@extends($app_template['client.frontend'])

@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="media-access">
  <div class="col-md-12">Media Access</div>
</div>

{!! Form::open(array('url' => route('package.media-access.do-forgot-password'), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=> 'frm_forgotpassword')) !!}
<div class="row">
  <div class="col-sm-3 col-md-3 left-col">
   <p class="description">Forgot Password</p>
  </div>
  <div class="col-sm-9 col-md-9 right-col">
        @if( session()->has('mediaAccessSuccessSendForgotpassword') )  
            <div class='alert alert-success'>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{session('mediaAccessSuccessSendForgotpassword')}}
            </div>
        @endif     
       <div class="row">
         <div class="col-md-10">
            <p class="btn-title">We will resend you the password.</p>
              <div class="form-group">                  
                  <label class="control-label col-sm-4" for="name">Enter you registered email</label>
                  <div class="col-sm-7 required">
                     <input type="email" class="form-control input-email" name="email">
                    {!! $errors->first('email', '<label class="error">:message</span>') !!}
                  </div>          
              </div>
              <div class="form-group">  
                <label class="col-sm-4 col-md-4">&nbsp;</label>          
                <div class="col-sm-5 col-md-5">
                  <input type="submit" class="btn btn-customize" value="Send">
                </div>
              </div>
           </div>
       </div>
  </div>
</div>
{!! Form::close() !!}
</section>
@else 
  <h2>Media Access</h2>
  <div class="row">
    {!! Form::open(array('url' => route('package.media-access.do-forgot-password'), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=> 'frm_forgotpassword')) !!}
        <div class="col-md-6">
          <h3>Forgot Password</h3>
          <hr>
          <div class="form-group">
            <label for="email">Enter you registered email</label>
            <input type="email" class="form-control input-email" name="email">
            {!! $errors->first('email', '<span class="error">:message</span>') !!}
          </div>
          <div class="form-group">       
              <div>
                <input type="submit" class="btn btn-success" value="Send">
              </div>
          </div>
        </div>
    {!! Form::close()!!}
  </div>
@endif 
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Media Access</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
@if ($active_theme == "default")
  {!! Html::style('css/package/general-style.css') !!}
  <style>
    #login-frm .form-group{
        max-width:400px;
    }
    #register-frm-action label{
        color:#000;
        padding-top: 13px;
    }
    #register-frm-action label.error,
    #register-frm-action span.error,
    #frm_forgotpassword label.error
    {
        color: red;
    }
    .main-footer {
        border-top: 1px solid #DFDFDF;
    }
    .top-content {
        padding-bottom: 0px;
    }
  </style>
@endif
@stop

@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}

  <script type="text/javascript">
    jQuery.validator.addMethod("pwcheck", function(value, element) {
            return   /([A-Z])/.test(value) &&  /([!,%,&,@,#,$,^,*,?,_,~])/.test(value) &&  /[a-z]/.test(value) &&  /\d/.test(value)
        },
        "Password must contain at least one letter, one capital letter, number and special character."
    );
    $('.menu-active').removeClass('menu-active');
    $('#media-access').addClass('menu-active');
    $("#login-frm-action").validate({
        rules: {
            'email': { 
                required : true,
                email : true
            },
            'password': {
                required : true
            },
            'confirm_password': { 
                required : true
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    $("#register-frm-action").validate({
        rules: {
            'name': 'required',
            'password': {
                required : true,
                pwcheck: true
            },
            'confirm_password': { 
                required : true,
                pwcheck: true
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
  </script>
@stop
@extends($app_template['client.frontend'])

@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="media-access">
    <div class="col-md-12">
     Media Access
    </div>
</div>

<div class="row">
  <div class="col-sm-3 col-md-3 left-col">
   <p class="description">Login</p>
  </div>
  <div class="col-sm-9 col-md-9 right-col">
    {!! Form::open(array('url' => route('package.media-access.do-login'), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=> 'register-frm-action')) !!}
       <div class="row">
            <div class="col-md-10">
                <p><span>*</span>Required.</p>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="email">Email</label>
                    <div class="col-sm-9 required">
                     <input type="email" class="form-control" placeholder="Email address" name="login_email" required email >
                     {!! $errors->first('login_email', '<span class="error">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="address">Password</label>
                    <div class="col-sm-9 required">
                        <input type="password" name="login_password" placeholder="Password" class="form-control required" required>
                        {!! $errors->first('login_password', '<span class="error">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="address"></label>
                    <div class="col-sm-9">
                        <input type="submit" class="btn btn-customize" value="Login">
                    </div>
                </div>
           </div>
    {!! Form::close() !!}
  </div>
</div>
</div>
</section>
@else 
    <h2>Media Access</h2>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
        <h3>Login</h3>
        <hr>
        {!! Form::open(array('url' => route('package.media-access.do-login'), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=> 'register-frm-action')) !!}
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" placeholder="Email address" name="login_email" required email >
                {!! $errors->first('login_email', '<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="pwd">Password</label>
                <input type="password" name="login_password" placeholder="Password" class="form-control required" required>
                {!! $errors->first('login_password', '<span class="error">:message</span>') !!}
              </div>

              <button type="submit" class="btn btn-success">Submit</button>
        </form>
        </div>
    </div>
@endif
@stop

@section('style')
    @if($active_theme == "default")
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
        #register-frm-action span.error
        {
            color: red;
        }
        .main-footer {
            border-top: 1px solid #DFDFDF;
            position: absolute;
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
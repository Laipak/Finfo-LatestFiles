@extends($app_template['client.frontend'])

@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="media-access">
    <div class="col-md-12">
     Media Access
    </div>
</div>

<div class="row" style="border-bottom: 2px solid #ddd">
  <div class="col-sm-3 col-md-3 left-col">
   <p class="description">Login</p>
  </div>
  <div class="col-sm-9 col-md-9 right-col" id="login-frm">
        @if (session()->has('resetPasswordSuucess'))
            <div class='alert alert-success'>
                {{session('resetPasswordSuucess')}}
            </div>
        @endif
            <p class="btn-title">Already have an account?</p>
            <span class="btn-group">
              <a href="{{route('package.media-access.login')}}" class="btn btn-customize">Login</a>
            </span>
            <div class="col-xs-12 col-md-9 pull-right forget-password">
                <a href="{{URL::to('media-access/forgot-password')}}" class="text-left" style="font-size:16px">Forgot password?</a>
            </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-3 col-md-3 left-col">
   <p class="description">Do not have an account?</p><p style="color:#000;">Fill in the following form to register.</p>
  </div>
  <div class="col-sm-9 col-md-9 right-col">
    {!! Form::open(array('url' => route('package.media-access.form'), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=> 'register-frm-action')) !!}
       <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-sm-3" for="name">Name</label>
                <div class="col-sm-9 required">
                    <input type="text" class="form-control" name='name' value='{{Input::old('name')}}' minlength="5" required>
                    {!! $errors->first('name', '<span class="error">:message</span>') !!}
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-sm-3" for="organization">Organization</label>
                <div class="col-sm-9 required">
                  <input type="text" class="form-control" name='organization' value='{{Input::old('organization')}}' required>
                  {!! $errors->first('organization','<span class="error">:message</span>') !!}
                </div>
            </div>
          </div>
        </div>
        <div class="row">
         <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-3" for="designation">Designation</label>
              <div class="col-sm-9 required">
                <input type="text" class="form-control" name='designation' minlength="5" value='{{Input::old('designation')}}'>
              </div>
            </div>
         </div>
         <div class="col-md-6">
           <div class="form-group">
              <label class="control-label col-sm-3" for="tel-did">Tel (DID)</label>
              <div class="col-sm-9 required">
                  <input type="tel" class="form-control" name='organization_phone' value='{{Input::old('organization_phone')}}' tel minlength="6" maxlength="20">
              </div>
          </div>
         </div>
        </div>
        <div class="row">
          <div class="col-md-6">
             <div class="form-group">
                <label class="control-label col-sm-3" for="tel">Tel (Main)</label>
                <div class="col-sm-9 required">
                    <input type="tel" class="form-control" name='phone_number' minlength="6" maxlength="20" value='{{Input::old('phone_number')}}' >
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-sm-3" for="mobile">Mobile</label>
                <div class="col-sm-9 required">
                  <input type="tel" class="form-control" name='organization_mobile' value='{{Input::old('organization_mobile')}}' tel minlength="6" maxlength="20">
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-sm-3" for="fax">Fax</label>
                <div class="col-sm-9 required">
                  <input type="text" class="form-control" name='fax' value='{{Input::old('fax')}}'>
                </div>
            </div>
          </div>
          <div class="col-md-6">
           <div class="form-group">
              <label class="control-label col-sm-3" for="address">Address</label>
              <div class="col-sm-9 required">
                <textarea name="address" class="form-control" rows="4">{{Input::old('address')}}</textarea>
              </div>
          </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-sm-3" for="email">Email</label>
                <div class="col-sm-9 required">
                  <input type="email" class="form-control" name='user_email' value='{{Input::old('user_email')}}' minlength="5" required>
                  {!! $errors->first('user_email', '<span class="error">:message</span>') !!}
                </div>
            </div>
          </div>
          <div class="col-md-6"></div>
        </div>
        <div class="row">
          <div class="col-md-6">
             <div class="form-group">
                <label class="control-label col-sm-3" for="address">Password</label>
                <div class="col-sm-9 required">
                    <input type="password" class="form-control" name='password' required minlength="8" id="password">
                 {!! $errors->first('password', '<span class="error">:message</span>') !!}
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-sm-3" for="address">Cofirm pasword</label>
                <div class="col-sm-9 required">
                    <input type="password" class="form-control" name='confirm_password' required minlength="8">
                    {!! $errors->first('confirm_password', '<span class="error">:message</span>') !!}
                </div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
              <div class="form-group">
                 <label class="col-sm-3 col-md-3">&nbsp;</label>
                 <div class="col-sm-3 col-md-9">
                   <div class="g-recaptcha" data-sitekey="6Lf82hITAAAAAN6hMMyd-v6sm1tR1dLW7a4RaZQp"></div>
                   {!! $errors->first('g-recaptcha-response', '<span class="error">:message</span>')!!}
                 </div>
               </div>
            </div>
       </div>
       <div class="row">
         <div class="col-xs-12 col-md-6">
           <div class="form-group">
              <label class="col-sm-3 col-md-3">&nbsp;</label>
              <div class="col-sm-3 col-md-3">
                <input type="submit" class="btn btn-customize" value="Register">
              </div>
            </div>
         </div>
       </div>
    {!! Form::close() !!}
  </div>
</div>
</section>
@else

        <h2>Media Access</h2>
        <div class="row">
          <div class="col-md-3">
            <div class="col-md-12">
              
              <h3>Login</h3>
              <p>Already have an account?</p>
              <a href="{{route('package.media-access.login')}}" class="btn btn-danger" style="width:100%">Login</a>

            </div>
            <div class="col-md-12">
              <a href="{{URL::to('media-access/forgot-password')}}" style="font-size:14px;">Forgot password?</a>
            </div>
          </div>
          <div class="col-md-8">
          {!! Form::open(array('url' => route('package.media-access.form'), 'method' => 'POST', 'class'=>'form-horizontal', 'id'=> 'register-frm-action')) !!}
          <h3>Do not have an account?</h3>
          <p>Fill in the following form to register.</p>            
              
              <div class="form-group">
                <label for="name">Name (required)</label>
                <input type="text" class="form-control" name='name' value='{{Input::old('name')}}' minlength="5" required>
                {!! $errors->first('name', '<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="organization">Organization (required)</label>
                <input type="text" class="form-control" name='organization' value='{{Input::old('organization')}}' required>
                {!! $errors->first('organization','<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="name">Designation (required)</label>
                <input type="text" class="form-control" name='designation' minlength="5" value='{{Input::old('designation')}}'>
                {!! $errors->first('designation','<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label>Tel (DID) (required)</label>
                <input type="tel" class="form-control" name='organization_phone' value='{{Input::old('organization_phone')}}' tel minlength="6" maxlength="20">
                {!! $errors->first('organization_phone','<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label>Tel (Main) (required)</label>
                <input type="tel" class="form-control" name='phone_number' minlength="6" maxlength="20" value='{{Input::old('phone_number')}}' >
                {!! $errors->first('phone_number','<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="mobile">Mobile (required)</label>
                <input type="tel" class="form-control" name='organization_mobile' value='{{Input::old('organization_mobile')}}' tel minlength="6" maxlength="20">
                {!! $errors->first('organization_mobile','<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="email">Email (required)</label>
                <input type="email" class="form-control" name='user_email' value='{{Input::old('user_email')}}' minlength="5" required>
                {!! $errors->first('user_email', '<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="pwd">Password (required)</label>
                <input type="password" class="form-control" name='password' required minlength="8" id="password">
                {!! $errors->first('password', '<span class="error">:message</span>') !!}
              </div>

              <div class="form-group">
                <label for="confirm-pwd">Confirm Password (required)</label>
                <input type="password" class="form-control" name='confirm_password' required minlength="8">
                {!! $errors->first('confirm_password', '<span class="error">:message</span>') !!}
              </div>

              <div class="checkbox">
                <div class="g-recaptcha" data-sitekey="6Lf82hITAAAAAN6hMMyd-v6sm1tR1dLW7a4RaZQp"></div>
                {!! $errors->first('g-recaptcha-response', '<span class="error">:message</span>')!!}
              </div>

              <div class="form-group">
                <br>
                <button type="submit" class="btn btn-success">Submit</button>
              </div>

            </form>
          </div>
        </div>
@endif
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Media Access</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
  @if($active_theme == "default")
    {!! Html::style('css/package/general-style.css') !!}
    <style>
      #login-frm .form-group{
          max-width:400px;
      }
      #login-frm a{
          text-decoration: none;
      }
      #login-frm a:hover{
          text-decoration: underline;
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
      }
      .top-content {
          padding-bottom: 0px;
      }
      @media (min-width: 414px) {
        .content #login-frm a.pull-right {
          padding: 10px 0;
        }
      }
      @media (max-width: 768px) {
        .content #login-frm a.pull-right {
          padding:18px 0px;
        }
      }
      .forget-password{
          margin-top: 25px;
          margin-bottom: 20px;
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
            'designation': 'required',
            'organization_phone': 'required',
            'phone_number': 'required',
            'organization_mobile': 'required',
            'fax': 'required',
            'address': 'required',
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

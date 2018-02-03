@extends($app_template['backend'])

@section('content')
<section class="content" id="company">
      @if (count($errors) > 0)
        <div class="alert alert-danger text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          @foreach ($errors->all() as $error)
              {{ $error }}<br>
          @endforeach
      </div>
      @endif


      @if (Session::has('message'))
        <div class="alert alert-success text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get('message') }}
        </div>
      @endif
<br>

<br>
<div class="row">
      <div class="col-sm-6">
        <div class="head-search">
            <h2 style="margin:0;">Profile</h2>
        </div>
        <div class="box col-sm-12">
          <div class="box-body">
              
              {!! Form::open(['url'=>'admin/users/profile/update', 'class'=>'form-horizontal']) !!}

                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                  <label>First name:</label>
               
                    <input name="first_name" class="form-control" value="{{ $user->first_name }}" type="text">
          
                </div>
                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                  <label>Last name:</label>
            
                    <input name="last_name" class="form-control" value="{{ $user->last_name }}" type="text">
           
                </div>
                <div class="form-group {{ $errors->has('email_address') ? ' has-error' : '' }}">
                  <label>Email:</label>
              
                    <input name="email_address" class="form-control" value="{{ $user->email_address }}" type="text">
               
                </div>

                <div class="form-group">

      
                    <input class="btn btn-primary" type="submit" value="Save Change">
                    <a href="{{route('finfo.user.backend.list')}}" class="btn btn-danger">Back</a>
       
                </div>

            {!! Form::close() !!}
            </div>
        </div>
    </div>
  <div class="col-sm-6">
      <div class="head-search">
        <h2 style="margin:0;">Update Password</h2>
      </div>
        <div class="box">
            <div class="box-body">
               {!! Form::open(array('route' => 'finfo.user.profile.update.password', 'id' => 'frm_profile_password')) !!}
                  <div class="form-group{{ $errors->has('current_password') || Session::has('message-error') ? ' has-error' : '' }}">
                        {!! Form::label('current_password', 'Password') !!}
                        {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => 'Current Password']) !!}

                        @if(Session::has('message-error'))
                            <span class="help-block" style="color:red;">{{Session::get('message-error')}}</span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                        {!! Form::label('new_password', 'Password') !!}
                        {!! Form::password('new_password',['class' => 'form-control', 'placeholder' => 'New Password', 'minlength' => 8]) !!}
                    </div>
                
                    <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                        {!! Form::label('new_password_confirmation', 'Password confirmation') !!}
                        {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm New Password', 'minlength' => 8]) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                        <a href="{{route('finfo.user.backend.list')}}" class="btn btn-danger">Back</a>
                    </div>
                    
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
</section>
@stop

@section('style')
  <style type="text/css">
    .btn{
      border-radius: 0;
    }
    .error{
      color: red;
      font-weight: 500;
    }
  </style>
@stop

@section('script')
    {!! Html::script('js/jquery.validate.min.js') !!}
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });

            jQuery.validator.addMethod("pwcheck", function(value, element) {

               return   /([A-Z])/.test(value) // upper case
                    &&  /([!,%,&,@,#,$,^,*,?,_,~])/.test(value) // has spacial character
                    &&  /[a-z]/.test(value) // has a lowercase letter
                    &&  /\d/.test(value) // has a digit
            }, "Password must contain at least one letter, one capital letter, number and special character.");

            $("#frm_profile_password").validate({
                rules: {
                    'current_password': 'required',
                    'new_password' : {
                        required: true,
                        pwcheck: true
                    },
                    'new_password_confirmation' : {
                        required: true,
                        pwcheck: true,
                        equalTo: "#new_password"
                    }
                },

                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@stop

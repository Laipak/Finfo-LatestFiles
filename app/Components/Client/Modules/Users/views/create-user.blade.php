@extends($app_template['client.backend'])
@section('title')
Users Manager
@stop
@section('content')
    <section class="content" id="list-user">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Add New User</lable>
            </div>
            
        </div>
        <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-12 col-md-6">
                        {!! Form::open(array('route' => 'client.user.backend.save', 'id' => 'frm_user')) !!}
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                {!! Form::label('first_name', 'First Name') !!}
                                {!! Form::text('first_name', Input::old('first_name'), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                {!! Form::label('last_name', 'Last Name') !!}
                                {!! Form::text('last_name', Input::old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                                {!! Form::label('email', 'Email') !!}
                                {!! Form::text('email_address', Input::old('email_address'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('user_type') ? ' has-error' : '' }}">
                                {!! Form::label('user_type', 'User type') !!}
                                {!! Form::select('user_type', $data['user_type'], Input::old('user_type'), ['class' => 'form-control']) !!}
                                {!! $errors->first('user_type', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                {!! Form::label('password', 'Password') !!}
                                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'minlength' => 8]) !!}
                                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                  <span>Eg: passWord168!</span>
                            </div>
                        
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                {!! Form::label('password_confirmation', 'Password confirmation') !!}
                                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password confirmation', 'minlength' => 8]) !!}
                                {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group" style="margin-top:30px;">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                                <a href="{{route('client.user.backend.list')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>
                            </div>
                            
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/client/list-user.css') !!}
    <style type="text/css">
        .error{
            color: red;
            font-weight: 500;
        }
    </style>
@stop

@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}

<script type="text/javascript">
    $('.active').removeClass('active');
    $('.user').addClass('active');
    $('.user_form').addClass('active');

    $(document).ready(function(){
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

        $("#frm_user").validate({
            rules: {

                'email_address'    :{
                        required:   true,
                        email:      true,
                        remote: {
                                  url: '/admin/users/check-exit-email',
                                  type: "post",
                                }
                },
                'password'      :{
                    pwcheck: true,
                    required: true,
                },
                'password_confirmation' :{
                    pwcheck: true,
                    required: true,
                    equalTo: "#password"
                }

            },
            messages: {
                    email_address: {
                        remote: "Email already in use!"
                    }
                },

            submitHandler: function(form) {
                form.submit();
            }
        });        
    });
     
 </script>

@stop
@extends($app_template['client.backend'])
@section('title')
Users Manager
@stop
@section('content')
	<section class="content" id="list-user">
		<div class="row head-search">
			<div class="col-sm-6">
				<lable class="label-title">Edit User</lable>
			</div>
			
      	</div>
        <div class="row">
        <div class="col-md-12">
            <div class="box">
        		<div class="box-body">
                    <div class="col-sm-12 col-md-6">
                    <?php 
                        $user = $data['user']; 
                    ?>
                        {!! Form::open(array('route' => ['client.user.backend.update', $user['id']], 'id' => 'frm_user')) !!}
                            <input type="hidden" id="user_id" name="user_id" value="{{$user['id']}}">
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                {!! Form::label('first_name', 'First Name') !!}
                                {!! Form::text('first_name', $user['first_name'], ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                {!! Form::label('last_name', 'Last Name') !!}
                                {!! Form::text('last_name', $user['last_name'], ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                                {!! Form::label('email', 'Email') !!}
                                {{--*/ $disabeled = '' /*--}}
                                @if (\Auth::user()->email_address == $user['email_address'] ) 
                                    {{--*/ $disabeled = 'disabled' /*--}}
                                @endif
                                {!! Form::text('email_address', $user['email_address'], ['class' => 'form-control', 'placeholder' => 'Email', $disabeled]) !!}
                                {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('user_type_id') ? ' has-error' : '' }}">
                                {!! Form::label('user_type_id', 'User type') !!}
                                {!! Form::select('user_type_id', $data['user_type'], $user['user_type_id'], ['class' => 'form-control']) !!}
                                {!! $errors->first('user_type_id', '<span class="help-block">:message</span>') !!}
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
                            @if (\Auth::user()->email_address != $user['email_address'] ) 
                                <div class="form-group" style="margin-bottom:0px;">
                                    <label>Status</label>
                                </div>
                                <div class="checkbox" style="margin-top:0px;">
                                    <label>
                                        <input type="checkbox" value="1" <?php if($user['is_active'] == 1) echo 'checked'  ?> name="is_active">
                                        Is publish
                                    </label>
                                </div>
                            @endif
                            <div class="form-group" style="margin-top:30px;">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
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
    //$('.user_form').addClass('active');

    $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });
    jQuery.validator.addMethod("pwcheck", function(value, element) {
        if (value) {
            return   /([A-Z])/.test(value) // upper case
            &&  /([!,%,&,@,#,$,^,*,?,_,~])/.test(value) // has spacial character
            &&  /[a-z]/.test(value) // has a lowercase letter
            &&  /\d/.test(value) // has a digit
        }else{
            return true;
        }
        
    }, "Password must contain at least one letter, one capital letter, number and special character.");
    
     $("#frm_user").validate({
        rules: {
        'password' :{
            required:false,
            pwcheck: true
        },
        'password_confirmation' :{
            required:false,
            pwcheck: true,
            equalTo: "#password"
        },
        'email_address'    :{
                required:   true,
                email:      true,
                remote: {
                          url: '/admin/users/check-exit-email',
                          type: "post",
                          data: {
                                'email_address': function(){return $("#frm_user :input[name='email_address']").val();},
                                'user_id' : $('#user_id').val()
                            }
                      }
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
 
 </script>
@stop
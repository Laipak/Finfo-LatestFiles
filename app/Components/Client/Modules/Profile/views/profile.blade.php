@extends($app_template['client.backend'])

@section('content')
	<section class="content" id="list-user">
		@if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-6">
            	<div class="head-search">
		            <h2 style="margin:0;">Update Profile</h2>
		        </div>
                <div class="box">
                    <div class="box-body">
                        {!! Form::open(array('route' => 'client.admin.profile.update', 'id' => 'frm_profile')) !!}
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                {!! Form::label('first_name', 'First Name') !!}
                                {!! Form::text('first_name', $data['first_name'], ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                {!! Form::label('last_name', 'Last Name') !!}
                                {!! Form::text('last_name', $data['last_name'], ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                {!! Form::label('phone', 'Phone') !!}
                                {!! Form::tel('phone', $data['phone'], ['class' => 'form-control', 'placeholder' => 'Phone',' minlength' => 6,  'maxlength' => 20]) !!}
                                {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                                {!! Form::label('email_address', 'Email') !!}
                                {!! Form::text('email_address', $data['email_address'], ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
                                {!! Form::label('profile_picture', 'Profile picture') !!}
                                <div class="profile-pic"></div>
                                <input type="file"  class="hidden" id="profile-pic" name="profile_pic">
                                <input type="hidden" id="profile_picture" name="profile_picture" value="{{$data['profile_picture']}}">
                                {!! $errors->first('profile_picture', '<span class="help-block">:message</span>') !!}
                            </div>
                       

                            <div class="form-group pull-right">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
                                <a href="{{route('client.admin.dashboard')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>
                                
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
                        {!! Form::open(array('route' => 'client.admin.profile.update.password', 'id' => 'frm_password')) !!}
                        	<div class="form-group{{ $errors->has('current_password') || Session::has('message') ? ' has-error' : '' }}">
                                {!! Form::label('current_password', 'Current Password') !!}
                                {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => 'Current Password']) !!}
                                {!! $errors->first('current_password', '<span class="help-block">:message</span>') !!}

                                @if(Session::has('message'))
                                    <span class="help-block">{{Session::get('message')}}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                {!! Form::label('new_password', 'New Password') !!}
                                {!! Form::password('new_password',['class' => 'form-control', 'placeholder' => 'New Password', 'minlength' => 8]) !!}
                                {!! $errors->first('new_password', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                {!! Form::label('new_password_confirmation', 'Confirm New Password') !!}
                                {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm New Password', 'minlength' => 8]) !!}
                                {!! $errors->first('new_password_confirmation', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group pull-right">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
                                <a href="{{route('client.admin.dashboard')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                            </div>
                            
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
	</section>
@stop

@section('style')
	{!! Html::style('css/finfo/list-user.css') !!}
    <style type="text/css">
        .error{
            color: red;
            font-weight: 500;
        }
        .profile-pic{
            margin-top: 15px;
            width: 100px;
            min-height: 100px;
            background-color: #F0F0F0;
            cursor: pointer;
            background-image: url("/img/uploader_text.png");
            background-size: cover;
            background-repeat: no-repeat;
            border-radius: 15px;
            position: relative;
        }
        .img-logo{
            background-color: #DDDDDD;
            border-radius: 15px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
@stop


@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}

<script type="text/javascript">
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

     $("#frm_profile").validate({
        rules: {

        'email_address'    :{
                required:   true,
                email:      true,
                remote: {
                          url: '/admin/profile/check-exit-email',
                          type: "post"
                      }
            },
        'phone' : 'number'

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

     $("#frm_password").validate({
        rules: {
            'current_password'  : {
                required: true,
                pwcheck: true
            },
            'new_password'      :   {
                required:   true,
                pwcheck: true,
            },
            'new_password_confirmation':   {
                required:   true,
                pwcheck: true,
                equalTo: "#new_password"
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
     });
    $('.profile-pic').click(function(){
        $("#profile-pic").click();
    });




    var logo = $('#profile_picture').val();

    if(logo != ""){
        $('.profile-pic').html('<img src="/'+logo+'" class="img-logo" style="width:100%;position: absolute; margin: auto;top: 0;left: 0;right: 0;bottom: 0;" >');
    }

    $("#profile-pic").on('change', function() {
        var file = this.files[0];
        var imagefile = file.type;

        var match = ["image/jpeg", "image/png", "image/jpg"];
        // validate file extension
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            alert('Please select image file.');
            $('#profile-pic').val('');
            return false;
        } else {
            var formData = new FormData($('#frm_profile')[0]);
            $.ajax({
                url: baseUrl + '/admin/profile/upload/picture',
                processData: false,
                contentType: false,
                type: "POST",
                data: formData,
                success: function(data) {
                    console.log(data);
                    $('#profile_picture').val(data);
                    $('.profile-pic').html('<img src="/'+data+'" class="img-logo" style="width:100%;position: absolute; margin: auto;top: 0;left: 0;right: 0;bottom: 0;" >');
                },
            });
        }
    });

</script>
@stop

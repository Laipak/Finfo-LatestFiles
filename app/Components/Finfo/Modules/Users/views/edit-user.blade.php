@extends($app_template['backend'])

@section('content')
	<section class="content" id="list-user">
		<div class="row head-search">
			<div class="col-sm-6">
				<h2 style="margin:0;">Edit User</h2>
			</div>
			
      	</div>
        <div class="row">
        <div class="col-md-12">
    		    <div class="box">
        			<div class="box-body">
                    @if(Session::has('global'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ Session::get('global') }}
                        </div>
                    @endif
                    <div class="col-sm-6">
                    <?php 
                        $user = $data['user']; 
                    ?>
                        {!! Form::open(array('route' => ['finfo.user.backend.update', $user['id'] ], 'id' => 'frm_user')) !!}
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
                                {!! Form::text('email_address', $user['email_address'], ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email_address']) !!}
                                {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('user_type_id') ? ' has-error' : '' }}">
                                {!! Form::label('user_type_id', 'User Type') !!}
                                {!! Form::select('user_type_id', $data['user_type'], $user['user_type_id'], ['class' => 'form-control']) !!}
                                {!! $errors->first('user_type_id', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="checkbox">
                                <label>
                                <input type="checkbox" value="1" <?php if($user['is_active'] == 1) echo 'checked'  ?> name="is_active">
                                    Is publish
                                </label>
                            </div>


                            <div class="form-group" style="margin-top:30px;">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
                                <a href="{{route('finfo.user.backend.list')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>
                                
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
	{!! Html::style('css/finfo/list-user.css') !!}
        {!! Html::style('css/finfo/customize.css') !!}
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

    $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });
     $("#frm_user").validate({
        rules: {

        'email_address'    :{
                required:   true,
                email:      true,
                remote: {
                          url: '/admin/users/check-exit-email',
                          type: "post",
                          data: {
                                'email_address': function(){return $("#frm_user :input[name='email_address']").val();},
                                'user_id' : $('#user_id').val()
                            },
                      }
            },

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

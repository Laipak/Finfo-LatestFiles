@extends($app_template['client.backend'])
@section('title')
Company Profile
@stop
@section('content')
	<section class="content" id="company">
		@if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Company Profile</lable>
            </div>
        </div>

            <div class="box">
                <div class="box-body">
                <div class="row">
                <div class="col-sm-12 col-md-6">
                    {!! Form::open(array('route' => 'client.admin.company.update','files'=> true, 'id' => 'frm-company')) !!}
                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            {!! Form::label('company_name', 'Company Name') !!}
                            {!! Form::text('company_name', $data['company_name'], ['class' => 'form-control', 'placeholder' => 'Company Name', 'readonly']) !!}
                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    
                        <div class="form-group{{ $errors->has('finfo_account_name') ? ' has-error' : '' }}">
                            {!! Form::label('finfo_account_name', 'Finfo Account Name') !!}
                            {!! Form::text('finfo_account_name', $data['finfo_account_name'], ['class' => 'form-control', 'placeholder' => 'Finfo Account Name', 'readonly']) !!}
                            {!! $errors->first('finfo_account_name', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            {!! Form::label('phone', 'Phone') !!}
                            {!! Form::tel('phone', $data['phone'], ['class' => 'form-control', 'placeholder' => 'Phone', 'minlength' => '6', 'maxlength' => '20']) !!}
                            {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            {!! Form::label('address', 'Address') !!}
                            {!! Form::text('address', $data['address'], ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                            {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                            {!! Form::label('email_address', 'Email') !!}
                            {!! Form::text('email_address', $data['email_address'], ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                            {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                            {!! Form::label('website', 'Website') !!}
                            {!! Form::text('website', $data['website'], ['class' => 'form-control', 'placeholder' => 'Website']) !!}
                            {!! $errors->first('website', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('established') ? ' has-error' : '' }}">
                            {!! Form::label('established', 'Established') !!}
                            {!! Form::text('established', strtotime($data['established_at']) > 0 ? date('d F, Y' , strtotime($data['established_at'])) : "", ['class' => 'form-control', 'placeholder' => 'established', 'id' => 'established']) !!}
                            {!! $errors->first('established', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('number_of_employee') ? ' has-error' : '' }}">
                            {!! Form::label('number_of_employee', 'Number of Employee') !!}
                            {!! Form::number('number_of_employee', $data['number_of_employee'], ['class' => 'form-control', 'placeholder' => 'Number of Employee']) !!}
                            {!! $errors->first('number_of_employee', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('common_stock') ? ' has-error' : '' }}">
                            {!! Form::label('common_stock', 'Common Stock') !!}
                            {!! Form::number('common_stock', $data['common_stock'], ['class' => 'form-control', 'placeholder' => 'Common Stock']) !!}
                            {!! $errors->first('common_stock', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('main_business_activities') ? ' has-error' : '' }}">
                            {!! Form::label('main_business_activities', 'Main Business Activities') !!}
                            {!! Form::text('main_business_activities', $data['main_business_activities'], ['class' => 'form-control', 'placeholder' => 'Main Business Activities']) !!}
                            {!! $errors->first('main_business_activities', '<span class="help-block">:message</span>') !!}
                        </div>
                        
                        <div class="form-group footer">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
                            <a href="{{route('client.admin.dashboard')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>
                        </div>

    
                        
                    {!! Form::close() !!}
                </div>
                </div>
            </div>
        </div>
	</section>
@stop

@section('style')
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
	{!! Html::style('css/client/company.css') !!}
    <style type="text/css">
    .error{
        color: red;
        font-weight: 500;
    }
    </style>

@stop


@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}

<script type="text/javascript">
    $(document).ready(function(){
        $('.active').removeClass('active');
        $('#menu-account-info').addClass('active');
        $('#m-company').addClass('active');
        
        $("#frm-reset-password").validate({
            rules: {
                    'password'		: "required",
                    'password_confirmation'	:{
                            required: true,
                            equalTo: "#password"
                    }
            },
            submitHandler: function(form) {
                    form.submit();
            }
        });

        $('#established').datetimepicker({
             format: 'D MMMM, YYYY'
        });

        $("#frm-company").validate({
            rules: {
                    'company_name'  : "required",
                    'phone'         : "number",
                    'address'       : "required",

            },
            submitHandler: function(form) {
                    form.submit();
            }
        });

    });
</script>
@stop

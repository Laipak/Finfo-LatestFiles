@extends($app_template['client.frontend'])
@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="media-access">
    <div class="col-md-12">Media Access</div>
</div>
<div class="row">
    <div class="col-sm-3 col-md-3 left-col">
        <p class="description">Reset Password</p>
    </div>
    <div class="col-sm-9 col-md-9 right-col">
         @if( session()->has('mediaAccessSuccessSendForgotpassword') )  
            <div class='alert alert-success'>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{session('mediaAccessSuccessSendForgotpassword')}}
            </div>
        @endif 
        <p class="btn-title">Enter your password</p>
        {!! Form::open(array('url'=>route('package.media-access.do-reset-password'), 'method' => 'POST', 'id'=> 'frm_resetpassword')) !!}
            <div class="col-sm-7 form-group">
                <input type="password" class="form-control input-email" placeholder="Password" name="password" id="password">
                {!! $errors->first('password', '<label class="error">:message</span>') !!}
            </div>
            <div class="col-sm-7 form-group">
                <input type="password" class="form-control input-email" placeholder="Confirm password" name="confirm_password">
                {!! $errors->first('confirm_password', '<label class="error">:message</span>') !!}
            </div>
            <div class="col-sm-7 form-group">
                <input type="submit" class="btn btn-customize" value="Send">                    
            </div>
            <input type="hidden" name="key" value='{{$auth_key}}'>
         {!! Form::close() !!}
    </div>
</div>
</section>
@else 
Update me
@endif
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Media Access</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}
<script type="text/javascript">
    jQuery.validator.addMethod("pwcheck", function(value, element) {
            return   /([A-Z])/.test(value) &&  /([!,%,&,@,#,$,^,*,?,_,~])/.test(value) &&  /[a-z]/.test(value) &&  /\d/.test(value)
        },
        "Password must contain at least one letter, one capital letter, number and special character."
    );
    $(function(){
        $('.menu-active').removeClass('menu-active');
        $('#media-access').addClass('menu-active');
        $("#frm_resetpassword").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                    pwcheck: true
                },
                confirm_password: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password",
                    pwcheck: true
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@stop
@section('style')
@if ($active_theme == "default")
<style>
    #frm_resetpassword .input-email{
        border: 1px solid #e1e1e1;
    }
    #frm_resetpassword label.error{
        color: red;
    }
    
</style>
    {!! Html::style('css/package/general-style.css') !!}
    {!! Html::style('css/package/media-access.css') !!}
@endif
@stop
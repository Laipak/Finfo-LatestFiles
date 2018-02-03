@extends($app_template['client.frontend'])
@section('content')
@if ($active_theme == "default")
<div class="row title" id="media-access">
    <div class="col-md-12">Media Access</div>
</div>
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
        <p class="btn-title">Enter your registered email</p>
        {!! Form::open(array('url'=>route('package.media-access.do-forgot-password'), 'method' => 'POST', 'id'=> 'frm_forgotpassword')) !!}
            <div class="col-sm-7 form-group">
                <input type="email" class="form-control input-email" name="email">
                {!! $errors->first('email', '<label class="error">:message</span>') !!}
            </div>
            <div class="col-sm-5 form-group">
                <input type="submit" class="btn btn-customize" value="Send">                    
            </div>
         {!! Form::close() !!}
    </div>
</div>
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
    $('.menu-active').removeClass('menu-active');
    $('#media-access').addClass('menu-active');
    $("#frm_forgotpassword").validate({
        rules: {
            'email': {
                required: true,
                email: true
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
</script>
@stop
@section('style')
<style>
    #frm_forgotpassword .input-email{
        border: 1px solid #e1e1e1;
    }
</style>
    {!! Html::style('css/package/general-style.css') !!}
    {!! Html::style('css/package/media-access.css') !!}
@stop
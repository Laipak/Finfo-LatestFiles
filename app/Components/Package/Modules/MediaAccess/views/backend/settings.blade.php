@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
   <section class="content" id="setting">
        @if (session()->has('successSettings'))
            <div class="row">
               <div class="col-sm-12">
                   <div class='alert alert-success'>
                       {{session('successSettings')}}
                   </div>
               </div>
            </div>
        @endif
	<div class="row head-search">
	    <div class="col-sm-6">
	        <lable class="label-title">Settings Media Access </lable>
	    </div>	    
	</div>
       {!! Form::open(array('route' => 'package.admin.media-access.do.settings',  'method' => 'post', 'id' => 'frm_media_settings' )) !!}
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Auto Approved</h5>
                            <div>		
                                <select name="auto_approved" class="form-control">
                                    <option value='1' {{ (isset($mediaAccessSettings->auto_approved) && $mediaAccessSettings->auto_approved == 1) ? "selected": "" }}>Yes</option>
                                    <option value="0" {{ (isset($mediaAccessSettings->auto_approved) && $mediaAccessSettings->auto_approved == 0) ? "selected": "" }}>No</option>
                                </select>
                            </div>			
                        </div>
                        <div class="col-md-4">
                            <h5>Default Expiry Date</h5>
                            <div id="default_expiry_date" class="input-group date">
                                <input type="text" name="defaul_expiry_date" class="form-control" value="{{ isset($mediaAccessSettings->default_expiry_date) ? date('d F, Y', strtotime($mediaAccessSettings->default_expiry_date)) : "" }}"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            {!! $errors->first('defaul_expiry_date', '<span class="help-block">:message</span>')!!}
                        </div>
                        <div class="col-md-4">
                            <h5>Recipe Notification Email</h5>
                            <div class="notification-email">									
                                <input name="recipe_email" type="text" class="form-control" value="{{ isset($mediaAccessSettings->recipe_notify_email) ? $mediaAccessSettings->recipe_notify_email : Input::old('recipe_email') }}" email />
                            </div>
                            {!! $errors->first('recipe_email', '<span class="help-block">:message</span>')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-save">Save</button>
                            <a href="{{route('package.admin.media-access')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                        </div>			
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
</section>
@stop
@section('style')
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    <style>
        .btn-overwrite-cancel{
            margin-left: 0px;
            width: 130px;
            border-radius: 0px;
            text-transform: uppercase;
        }
        #setting .btn-save{
             margin-left: 0px;
        }
        .btn-setting-save {
            background: #75b600;
            margin-top: 20px;
            margin-left: 0px;
            width: 130px;
            text-transform: uppercase;
            color: #fff;
        }
        #frm_media_settings span.help-block{
            color: red;
        }
    </style>
@stop
@section('script')
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}
<script type="text/javascript">
    $(function () {
        $('#default_expiry_date').datetimepicker({
            format: 'D MMMM, YYYY'
        });
    });
     $('.active').removeClass('active');
    $('.media_acc').addClass('active');
    $('.media-setting').addClass('active');
</script>
@stop
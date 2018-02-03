@extends($app_template['client.backend'])
@section('title')
Email Alert
@stop
@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Add New Email Alert</lable>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! Form::open(array('route' => 'package.admin.email-alerts.post', 'id' => 'frm-ir-calendar', 'files'=> true)) !!}
                    
                    <div class="form-group">
                            {!! Form::label('name', 'Name') !!}
                            {!! Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Name']) !!}
                            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('email_address', 'Email Address') !!}
                            {!! Form::text('email_address', '', ['class'=>'form-control', 'placeholder'=>'Email Address']) !!}
                            {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('category', 'Categories') !!}
                            @foreach($modules as $cat)
                            @if($cat->name != 'Email Alerts' && $cat->name != 'Media Access')
                                <div class="checkbox">
                                   <label>
                                      <input type="checkbox" name="category[]" value="{{$cat->id}}">{{$cat->name}}
                                   </label>
                                </div>
                            @endif
                            @endforeach
        
                            {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                    </div>

                   
                    <div class="form-group" style="margin-top:30px;">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    <a href="{{route('package.admin.email-alerts')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('script')
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
<script type="text/javascript">
    $('.active').removeClass('active');
    $('.email_alert').addClass('active');
    $('.email_alert_form').addClass('active');
</script>
@stop

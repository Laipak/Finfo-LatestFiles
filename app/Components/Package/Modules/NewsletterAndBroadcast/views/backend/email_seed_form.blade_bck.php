@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
@stop
@section('content')
    <section class="content" id="financial-annual-report">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Settings</lable>
            </div>
        </div>
        <div class="row">
            {!! Form::open(array('route' => 'package.admin.newsletter-broadcast.email-seed-list.save', 'id' => 'frm-reports', 'files'=> true)) !!}
            <div class="col-md-6">

               <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group" style="margin-top:30px;">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    <a href="{{route('package.admin.newsletter-broadcast.email-seed-list')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                </div>
            </div>

            
            {!! Form::close() !!}
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/financial-annual-report.css') !!}
@stop

@section('script')
<script type="text/javascript">
    $('.active').removeClass('active');
    $('.news_brod').addClass('active');
    $('.news_brod_email_seed_list').addClass('active');
</script>
@stop

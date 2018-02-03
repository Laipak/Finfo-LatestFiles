@extends($app_template['client.backend'])
@section('title')
Webcast
@stop
@section('content')
    <section class="content" id="financial-annual-report">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Webcast</lable>
            </div>
        </div>
        <div class="row">
            {!! Form::open(array('route' => ['package.admin.webcast.save', $data->id], 'id' => 'frm-webcast')) !!}
            <div class="col-md-6">

                <div class="form-group">
                        {!! Form::label('quarter', 'Quarter') !!}
                        {!! Form::select('quarter', $quarter, $data->quarter, array('class' => 'form-control', 'disabled' => 'disabled')); !!}
                        {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                </div>

               
                <div class="form-group">
                        {!! Form::label('year', 'Year') !!}
                        {!! Form::select('year', $year, $data->year, array('class' => 'form-control', 'disabled' => 'disabled')); !!}
                        {!! $errors->first('year', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group">
                        {!! Form::label('url', 'Url') !!}
                        {!! Form::text('url', $data->url, ['class' => 'form-control', 'placeholder' => 'Url    Ex: http://www.abc.com']) !!}
                        {!! $errors->first('url', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group">
                        {!! Form::label('caption', 'Caption') !!}
                        {!! Form::text('caption', $data->caption, ['class' => 'form-control', 'placeholder' => 'Caption']) !!}
                        {!! $errors->first('caption', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                        {!! Form::label('publish_date', 'Publish date') !!}
                        {!! Form::text('publish_date', date('d M, Y', strtotime( $data->publish_date)), ['class' => 'form-control', 'id'=> 'publish_date', 'placeholder' => 'Publish date']) !!}
                        {!! $errors->first('publish_date', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group" style="margin-top:30px;">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    <a href="{{route('package.admin.webcast')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                </div>
            </div>

            
            {!! Form::close() !!}
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/financial-annual-report.css') !!}
    {!! Html::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') !!}
@stop

@section('script')
<script type="text/javascript">
    $('.active').removeClass('active');
    $('.web_cast').addClass('active');
    //$('.web_cast_form').addClass('active');
    $('#publish_date').datepicker({
        dateFormat: "dd M yy"
    })
</script>
@stop

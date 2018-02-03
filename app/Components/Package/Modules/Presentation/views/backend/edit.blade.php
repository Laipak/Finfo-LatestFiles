@extends($app_template['client.backend'])
@section('title')
Presentations
@stop
@section('content')
    <section class="content" id="financial-annual-report">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Presentation</lable>
            </div>
        </div>
        <div class="row">
            {!! Form::open(array('route' => ['package.admin.presentation.save', $data->id], 'id' => 'frm-reports', 'files'=> true)) !!}
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
                    {!! Form::label('upload', 'Upload PDF') !!}
                    <div class="form-group">
                        <input type="file" class="hidden" accept="application/pdf, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation" id="file" name="myfile">
                        <div class="row">
                            <div class="col-xs-8 upload-file">
                                {!! Form::text('upload', $data->upload, ['class' => 'file-upload-input form-control', 'placeholder' => 'Support only pdf, ptt and pptx', 'id' => 'file_upload', 'readonly' => '']) !!}
                            </div>
                            <div class="col-xs-4 div-sel-file">
                                <button type="button" class="btn btn-upload upload-file">Select File</button>
                            </div>
                        </div>
                        {!! $errors->first('upload', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                 <div class="form-group">
                    {!! Form::label('publish_date', 'Publish date') !!}
                    {!! Form::text('publish_date', $data->publish_date, ['class' => 'form-control', 'id'=> 'publish_date', 'placeholder' => 'Publish date']) !!}
                    {!! $errors->first('publish_date', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group" style="margin-top:30px;">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    <a href="{{route('package.admin.presentation')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                </div>
            </div>

            
            {!! Form::close() !!}
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/financial-annual-report.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('script')
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}

<script type="text/javascript">
    $('.active').removeClass('active');
    $('.presentation').addClass('active');
    //$('.presentation_form').addClass('active');
    $('#publish_date').datetimepicker({
        format: 'D MMMM, YYYY'
    });
    $('.upload-file').click(function(){
        $('#file').click();
    });

    $('#file').change(function(e) {
        $('#file_upload').val('');

        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;

        var match = ["application/pdf", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation"];
        if (!(filetype == match[0] || filetype == match[1] || filetype == match[2])) {
            alert('Please select pdf, ppt, pptx file.');
            $('#file').val('');
            if ('function' == typeof pCallback) {
                pCallback(false);
            }
            return;
        }

        if (3145728 < filesize) {
            alert('File size should be less than 3MB.');
            $('#file').val('');
            if ('function' == typeof pCallback) {
                pCallback(false);
            }
            return;
        }

        $('#file_upload').val(filename);

    });

</script>

@stop

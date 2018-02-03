@extends($app_template['client.backend'])

@section('content')
    <section class="content" id="financial-annual-report">
        <div class="row head-search">
            <div class="col-sm-6">
                <h2 style="margin:0;">Upload Annual Report</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! Form::open(array('route' => 'package.admin.financial-annual-reports.save', 'id' => 'frm-reports', 'files'=> true)) !!}
                    <div class="form-group">
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('file_upload', 'Upload PDF') !!}
                        <div class="form-group">
                            <input type="file" class="hidden" accept="application/pdf" id="file" name="myfile">
                            <div class="row">
                                <div class="col-md-8 upload-file">
                                    {!! Form::text('file_upload', Input::old('file_upload'), ['class' => 'file-upload-input form-control', 'placeholder' => 'Support only pdf', 'id' => 'file_upload', 'readonly' => '']) !!}
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('file_upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                            {!! Form::label('description', 'Description') !!}
                            {!! Form::text('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                            {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                    </div>
                    <?php $year = array('' => 'Select financial year', 2011, 2012, 2013, 2014, 2015, 2016) ?>
                    <div class="form-group">
                            {!! Form::label('financial_year', 'Financial year') !!}
                            {!! Form::select('financial_year', $year, 'S', array('class' => 'form-control')); !!}
                            {!! $errors->first('financial_year', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cover_image', 'Upload cover image') !!}
                                <div class="cover_image"></div>
                                <input type="file"  class="hidden" id="file_image" name="file_image">
                                <input type='hidden' id="cover_image" value="{{Input::old('cover_image')}}" name="cover_image"> 
                                {!! $errors->first('cover_image', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top:50px;">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{route('package.admin.financial-annual-reports')}}">{!! Form::button('Cancel', ['class' => 'btn btn-danger']) !!}</a>
                    </div>
                {!! Form::close() !!}
            </div>
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
    $('#date, #public-date').datetimepicker({
        format: 'DD/MM/YYYY'
    });

    $('.upload-file').click(function(){
        $('#file').click();
    });

    $('.cover_image').click(function(){
        $('#file_image').click();
    });

    $('#file').change(function(e) {
        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;

        var match = ["application/pdf"];
        if (!((filetype == match[0]))) {
            alert('Please select pdf file.');
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

        var formData = new FormData($('#frm-reports')[0]);
        $.ajax({
            url: baseUrl + '/admin/financial-annual-reports/temp-upload-pdf',
            processData: false,
            contentType: false,
            type: "POST",
            data: formData,
            success: function(data) {
                console.log(data);
                $('#file_upload').val(data);
            }
        });
    });

    $('#file_image').change(function() {
        
        var formData = new FormData($('#frm-reports')[0]);
        $.ajax({
            url: baseUrl + '/admin/financial-annual-reports/temp-cover-image',
            processData: false,
            contentType: false,
            type: "POST",
            data: formData,
            success: function(data) {
                console.log(data);
                $('#cover_image').val(data);
                $('.cover_image').html("<img src='/"+data+"' style='width:100%;'>");
            }
        });
    });

    var cover_image = $('#cover_image').val();
    if(cover_image != ''){
        $('.cover_image').html("<img src='/"+cover_image+"' style='width:100%;'>");
    }
</script>

@stop

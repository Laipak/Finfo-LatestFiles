@extends($app_template['client.backend'])
@section('title')
Annual Report
@stop
@section('content')
    <section class="content" id="financial-annual-report">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Annual Report</lable>
            </div>
        </div>
        <div class="row">
            {!! Form::open(array('route' => ['package.admin.financial-annual-reports.save', $data['id']], 'method' => 'post','id' => 'myform', 'files'=> true)) !!}
            <div class="col-md-6">
                    <div class="form-group">
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', $data['title'], ['class' => 'form-control', 'placeholder' => 'Title', 'minlength' => 5 , 'maxlength'=>50,]) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                    </div>
                    <input type="hidden" id="report_id" name="id" value="{{$data['id']}}">
                    <div class="form-group">
                        {!! Form::label('file_upload', 'Upload PDF') !!}
                        <div class="form-group">
                            <input type="file" class="hidden" accept="application/pdf" id="file" name="myfile">
                            <div class="row">
                                <div class="col-xs-8 upload-file">
                                    {!! Form::text('file_upload', $data['file_pdf'], ['class' => 'file-upload-input form-control', 'placeholder' => 'Support only pdf', 'id' => 'file_upload', 'readonly' => '']) !!}
                                </div>
                                <div class="col-xs-4 div-sel-file">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('file_upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                            {!! Form::label('description', 'Description') !!}
                            {!! Form::textarea('description', $data['description'], ['class' => 'form-control', 'placeholder' => 'Description', 'minlength' => 5 , 'maxlength'=>100,]) !!}
                            {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                    </div>
                    
                    <div class="form-group">
                            {!! Form::label('financial_year', 'Financial year') !!}
                            {!! Form::select('financial_year', $year, $data['financial_year'], array('class' => 'form-control')); !!}
                            {!! $errors->first('financial_year', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                            {!! Form::label('publish_date', 'Publish date') !!}
                                {!! Form::text('publish_date', Input::old('publish_date'), 
                                    ['class' => 'form-control publish_date', 'placeholder' => 'Publish date', 'minlength' => 5 , 'maxlength'=>50,])
                                !!}
                            {!! $errors->first('publish_date', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cover_image', 'Upload cover image') !!}
                                <a class="text-right text-red remove-cover" href="#" title="Remove"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a>
                                <div class="cover_image"></div>
                                <input type="file"  class="hidden" id="file_image" name="file_image" accept="image/gif, image/jpeg, image/png">
                                <input type='hidden' id="cover_image" value="{{$data['cover_image']}}" name="cover_image"> 
                                {!! $errors->first('cover_image', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                        <a href="{{route('package.admin.financial-annual-reports')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                        <input type="button" class='btn btn-primary btn-overwrite-cancel' id="button" value="Preview">
                        
                         <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/annual-report";  ?>

                    </div>
            </div>
            
            
            {!! Form::close() !!}
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/financial-annual-report.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') !!}
@stop

@section('script')

<meta name="_token" content="{!! csrf_token() !!}"/>
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}









<script>
$(document).ready(function(){
function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    rv[i] = arr[i];
  return rv;
}
  $("#button").click(function(e){
        e.preventDefault();
  		var form = jQuery('#myform');
        var dataString = form.serializeArray();
         dataString.push({'name': 'preview','value': 'value'});
        var formAction = form.attr('action');

        $.ajax({
                type: "POST",
                url : formAction,
                data : dataString,
               
                success : function(data){
                  
                window.open('<?php echo  $actual_link ?>'); 
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert(msg);
                },
            });
    
      });
});
</script>













<script type="text/javascript">
    $(function(){
        $('.publish_date').datepicker({
            dateFormat: "dd M yy"
        });
    });
    $('.active').removeClass('active');
    $('.fin_ann_rp').addClass('active');
    //$('.fin_ann_rp_form').addClass('active');

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
        $('#file_upload').val('');
        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;

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

        $('#file_upload').val(filename);
    });

    $('#file_image').change(function() {
        var file = this.files[0];
        var imagefile = file.type;


        var match = ["image/jpeg", "image/png", "image/jpg"];
        // validate file extension
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            alert('Please select image file.');
            $('#file_image').val('');
            return false;
        }

        var formData = new FormData($('#myform')[0]);
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

    $("#frm-reports").validate({
        rules: {
            'title': 'required',
            'file_upload': 'required',
            'financial_year': 'required'
        },

        submitHandler: function(form) {
            form.submit();
        }
    });
    $('.remove-cover').click(function(e){
        e.preventDefault();
        $('#cover_image').val('');
        $('.cover_image img').remove();
    });
</script>

@stop
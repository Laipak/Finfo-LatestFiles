@extends($app_template['client.backend'])
@section('title')
Press Release
@stop
@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Press Release</lable>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! Form::open(array('route' => ['package.admin.press-releases.save', $data->id], 'method' => 'post','id' => 'form', 'files'=> true)) !!}
                 <input type="hidden" id='action' value='{{ route("package.admin.data.preview")}}'/>
                    <div class="form-group">
                      <!--  <input type="checkbox" name='checkbox' class='checkbox-financial' {{$data->financial_apply == 1 ? 'checked' : "" }}/>
                        
                        <input type="hidden" id='action' value='{{ route("package.admin.data.preview")}}'/>
                        {!! Form::label('checkbox', 'I want to attach this press release to a financial result', array('class'=>'lable-financial')) !!}
                    </div>
                    <div class="form-group checked-apply-financial">
                           {!! Form::label('quarter', 'Quarter') !!}
                           {!! Form::select('quarter', $quarter, $data->quarter, array('class' => 'form-control checked-quarter','required' => 'true', 'disabled' => 'disabled')); !!}
                           {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                   </div>
                   <div class="form-group checked-apply-financial">
                           {!! Form::label('year', 'Year') !!}
                           {!! Form::select('year', $year, $data->year, array('class' => 'form-control checked-year', 'disabled' => 'disabled')); !!}
                           {!! $errors->first('year', '<span class="help-block">:message</span>') !!}
                   </div> -->
                   
                   <div class="form-group" style="display:none">
                           {!! Form::label('quarter', 'Quarter') !!}
                           {!! Form::select('quarter', $quarter, $data->quarter, array('class' => 'form-control checked-quarter','required' => 'true')); !!}
                           {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                   </div>
                   <div class="form-group" style="display:none">
                           {!! Form::label('year', 'Year') !!}
                           {!! Form::select('year', $year, $data->year, array('class' => 'form-control checked-year')); !!}
                           {!! $errors->first('year', '<span class="help-block">:message</span>') !!}
                   </div>
                    <div class="form-group">
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', $data->title, ['class' => 'form-control','required' => 'true', 'placeholder' => 'Title', 'minlength' => 5 , 'maxlength'=>100]) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                            {!! Form::label('date', 'Date') !!}
                            
                            <div id="date" class="input-group date">
                                {!! Form::text('date', date('d F, Y', strtotime( $data['press_date'])), ['class' => 'form-control','required' => 'true']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                            {!! Form::label('publish_date', 'Publish date') !!}
                            <div id="publish_date" class="input-group date">
                                {!! Form::text('publish_date', date('d F, Y', strtotime( $data['publish_date'])), ['class' => 'form-control','required' => 'true', 'placeholder' => 'Publish date', 'id' => 'publish_date']) !!}
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                            {!! $errors->first('publish_date', '<span class="help-block">:message</span>') !!}
                    </div>
                  <!--  <div class="form-group">
                        {!! Form::radio('editor-option', 'wysiwyg', $data['option_selected'] == 'wysiwyg' ? true: "", array('class'=>'radio-option')) !!} {!! Form::label('wysiwyg', 'WYSIWYG') !!}
                        {!! Form::radio('editor-option', 'pdf',  $data['option_selected'] == 'pdf' ? true : "", array('class'=>'radio-option')) !!} {!! Form::label('pdf', 'PDF') !!}
                    </div> -->
                    
                    <div class='form-group'>
                        {!! Form::label('body', 'Description') !!}
                        {!! Form::textarea('body', $data['description'], ['class' => 'form-control']) !!}
                        {!! $errors->first('body', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Upload document</label>
                        <!--<p>Max file size 3MB </p>-->
                        <div class="form-group">
                            <input type="file" class="hidden" accept="application/pdf, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation" id="file" name="myfile">
                            <div class="row">
                                <div class="col-xs-8 upload-file">
                                    {!! Form::text('upload', $data->upload, ['class' => 'form-control', 'placeholder' => 'Support only (pdf, ppt, pptx, doc, docx)', 'id' => 'upload', 'readonly']) !!}
                                </div>
                                <div class="col-xs-4 div-sel-file">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                  <!--  <div class='option-wysiwyg'>
                        {!! Form::label('body', 'Body') !!}
                        {!! Form::textarea('body', $data['description'], ['class' => 'form-control',  'id' => 'bodyEditor']) !!}
                        {!! $errors->first('body', '<span class="help-block">:message</span>') !!}
                    </div> -->
                    <div class="form-group">
                        {!! Form::label('status', 'Status') !!}
                       <!-- {!! Form::select('status', array(0 => 'Unpublish'), $data['is_active'] , array('class' => 'form-control')); !!}-->
                        {!! Form::select('status', array( 1=>'Publish', 0 => 'Unpublish'), $data['is_active'] , array('class' => 'form-control')); !!}
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                        <a href="{{route('package.admin.press-releases')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                        
                                            <input type="button" class='btn btn-primary btn-overwrite-cancel' id="submit" value="Preview">
                        
                        
                        <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/pressrelease/preview";  ?>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('css/finfo/summernote.css') !!}
     <style>
        @if($data['option_selected'] == 'wysiwyg' ) 
            .option-wysiwyg {
                display: block;
            }
            .option-pdf{
                display: none;
            }
        @else
            .option-wysiwyg{
                display:none;
            }
            .option-pdf {
                display:block;
            }
        @endif
    </style>
@stop

@section('script')
<meta name="_token" content="{!! csrf_token() !!}"/>
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}
{!! Html::script('js/finfo/summernote.min.js') !!}







<script type="text/javascript">
$.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});
</script>
<script>
    $(document).ready(function(){
        // click on button submit
        $("#submit").on('click', function(){
            // send ajax
            
           var form = jQuery('#form');
             var formAction = jQuery('#action').val();
                var formvalid = $("#form").valid();
  		var form = jQuery('#form');
            
            if(formvalid)
            
            
            
            
            $.ajax({
                url : formAction, // url where to submit the request
                type : "POST", // type of action POST || GET
                
                
                dataType : 'json', // data type
                data : $("#form").serialize(), // post data || get data
                success : function(result) {
                    
                    
                     window.open('<?php echo  $actual_link ?>'); 
                    // you can see the result from the console
                    // tab of the developer tools
                 
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
            })
        });
    });

</script>









<script type="text/javascript">
    $(function() {
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                url: "/admin/webpage/move-upload-image",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data){
                    $('.note-editable').append('<img src="/'+data+'" >');
                    $('#bodyEditor').summernote("insertImage", data, 'filename');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus+" "+ errorThrown);
                }
            });
        }
        $('#bodyEditor').summernote({
                onImageUpload: function(files, editor, $editable) {
                    sendFile(files[0],editor,$editable);
                },      
                height: 200
        });
        var dateToday = new Date(); 
        $('#date, #publish_date').datetimepicker({
            format: 'DD MMMM, YYYY',
        });
         $('.radio-option').change(function(){
            if ($(this).val() == 'wysiwyg') {
                $('.option-wysiwyg').show();
                $('.option-wysiwyg').removeAttr('disabled');
                $('.option-pdf').hide();
                $('.option-pdf').attr('disabled','disabled');
            }else{
                $('.option-pdf').show();
                $('.option-pdf').removeAttr('disabled');
                $('.option-wysiwyg').hide();
                $('.option-wysiwyg').attr('disabled', 'disabled');
            }
        });
    })
    $('.active').removeClass('active');
    $('.press_re').addClass('active');
    //$('.press_re_list').addClass('active');

    $('.upload-file').click(function(){
        $('#file').click();
    });

    //enable public or disable
    $("#press-release").on("click","#check-public:checked",function(){
        $('#public-date').prop('disabled', false);
    });

    $("#press-release").on("click","#check-public:not(:checked)",function(){
        $('#public-date').val('');
        $('#public-date').prop('disabled', true);
    });

    $('#file').change(function(e) {
        $('#upload').val('');
        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;

        var match = ["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation"];
        if (!(filetype == match[0] || filetype == match[1] || filetype == match[2] || filetype == match[3] || filetype == match[4] || filetype == match[5] || filetype == match[6])) {
            alert('Please select pdf, ppt, pptx, doc, docx file.');
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
        $('#upload').val(filename);

    });

    $("#frm-press-release").validate({
        rules: {
            'title': 'required',
            'date': 'required',
            'upload': 'required'
        },

        submitHandler: function(form) {
            form.submit();
        }
    });
    if ($('.checkbox-financial').is(':checked')) {
        $('.checked-quarter').removeAttr('disabled');
        $('.checked-year').removeAttr('disabled');
        $('.checked-apply-financial').show();
    }
    $('.checkbox-financial').change(function() {
        if ($(this).is(':checked')) {
            $('.checked-quarter').removeAttr('disabled');
            $('.checked-year').removeAttr('disabled');
            $('.checked-apply-financial').show();
        }else{
            $('.checked-quarter').attr('disabled', 'disabeld');
            $('.checked-year').attr('disabled', 'disabeld');
            $('.checked-apply-financial').hide();
        }
    });
</script>

@stop

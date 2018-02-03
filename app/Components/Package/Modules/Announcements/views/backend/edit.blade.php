@extends($app_template['client.backend'])
@section('title')
Announcement
@stop
@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Announcement</lable>
            </div>
        </div>
        <div class='row'>
             <div class="col-md-12">
                @if(session()->has('global'))              
                    <div class="alert alert-success">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      {{ session('global') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! Form::open(array('route' => array('package.admin.announcements.update', $getAnnouncementData->id ),  'method' => 'post', 'files'=> true, 'id' => 'form' )) !!}
                   <input type="hidden" id='action' value='{{ route("package.admin.data.preview")}}'/>
                
                 <!--   <div class="form-group">
                        <input type="checkbox" name='checkbox' class='checkbox-financial' {{$getAnnouncementData->financial_apply == 1 ? 'checked' : "" }}/>
                        
                        
                        
                        <input type="hidden" id='action' value='{{ route("package.admin.data.preview")}}'/>
                        {!! Form::label('checkbox', 'I want to attach this announcement to a financial result', array('class'=>'lable-financial')) !!}
                    </div> 
                    <div class="form-group checked-apply-financial">
                            {!! Form::label('quarter', 'Quarter') !!}
                            {!! Form::select('quarter',
                                            $quarter, 
                                            $getAnnouncementData->quarter, array('id' => 'input', 'class'=> 'form-control checked-quarter', 'disabled' => 'disabled')) 
                            !!}
                            {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group checked-apply-financial">
                            {!! Form::label('year', 'Year') !!}
                            {!! Form::select('year',
                                            $year, 
                                            $getAnnouncementData->year, array('id' => 'input', 'class'=> 'form-control checked-year','disabled' => 'disabled')) 
                            !!}
                            {!! $errors->first('year', '<span class="help-block">:message</span>') !!}
                    </div> -->
                    
                    
                     <div class="form-group" style="display:none">
                            {!! Form::label('quarter', 'Quarter') !!}
                            {!! Form::select('quarter',
                                            $quarter, 
                                            $getAnnouncementData->quarter, array('id' => 'input', 'class'=> 'form-control checked-quarter')) 
                            !!}
                            {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group" style="display:none">
                            {!! Form::label('year', 'Year') !!}
                            {!! Form::select('year',
                                            $year, 
                                            $getAnnouncementData->year, array('id' => 'input', 'class'=> 'form-control checked-year')) 
                            !!}
                            {!! $errors->first('year', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', $getAnnouncementData->title, ['class'=>'form-control', 'required' => 'true','required' => 'true', 'placeholder'=>'Title', 'minlength' => 5, 'maxlength' => 100 ]) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('announce_at', 'Publish date') !!}
                            <div id="date" class="input-group date">
                                {!! Form::text('announce_at', date('d F, Y', strtotime($getAnnouncementData->announce_at)), ['class' => 'form-control','required' => 'true', 'placeholder' => 'Date']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
                    </div>
                 <!--   <div class="form-group">
                        {!! Form::radio('editor-option', 'wysiwyg', $getAnnouncementData->option_selected == 'wysiwyg' ? true: "", array('class'=>'radio-option')) !!} {!! Form::label('wysiwyg', 'WYSIWYG') !!}
                        {!! Form::radio('editor-option', 'pdf',  $getAnnouncementData->option_selected == 'pdf' ? true : "", array('class'=>'radio-option')) !!} {!! Form::label('pdf', 'PDF') !!}
                    </div> -->
                    
                    <div class='form-group'>
                        {!! Form::label('body', 'Description') !!}
                        {!! Form::textarea('body', $getAnnouncementData->description, ['class' => 'form-control']) !!}
                        {!! $errors->first('body', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Upload document</label>
                        <p>Max file size 3MB </p>
                        <div class="form-group option-pdf">
                            <input type="file" class="hidden" accept="application/pdf" id="file" name="upload_file">
                            <div class="row">
                                <div class="col-xs-8 upload-file">
                                    {!! Form::text('upload', $getAnnouncementData->file_upload, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Support only (pdf)', 'id' => 'upload', 'readonly']) !!}
                                </div>
                                <div class="col-xs-4 div-sel-file">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', array('Publish', 'Unpublish' ), $getAnnouncementData->status , array('class' => 'form-control')); !!}
                    </div
                    <div class="form-group" style="margin-top:30px;">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
                        <a href="{{route('package.admin.announcements')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                        
                         <input type="button" class='btn btn-primary btn-overwrite-cancel' id="submitpre" value="Preview">

                       <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/announcement/preview";  ?>
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
        @if($getAnnouncementData->option_selected == 'wysiwyg' ) 
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
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}
{!! Html::script('js/finfo/summernote.min.js') !!}





<script>
    $(document).ready(function(){
        // click on button submit
        $("#submitpre").on('click', function(){
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
    $('.active').removeClass('active');
    $('.announce').addClass('active');
    //$('.announce_form').addClass('active');

    jQuery(document).ready(function($) {
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
        $('#date, #public-date').datetimepicker({
           format: 'D MMMM, YYYY'
        });

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
            var file = this.files[0];
            var filetype = file.type;
            var filesize = file.size;
            var match = ["application/pdf"];
            if (!(filetype == match[0])) {
                alert('Please select pdf');
                $('#file').val('');
                if ('function' == typeof pCallback) {
                    pCallback(false);
                }
                return;
            }else if (3145728 < filesize) {
                alert('File size should be less than 3MB.');
                $('#file').val('');
                if ('function' == typeof pCallback) {
                    pCallback(false);
                }
                return;
            }else{
                $('#upload').val(this.files[0].name);
                //$('#file').val(this.files[0].name);
            }
        });

        $("#frm_announement").validate({
            rules: {
                'title': 'required',
                'announce_at': 'required',
                'upload': 'required',
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
    });
</script>

@stop

@extends($app_template['client.backend'])
@section('title')
Press Release
@stop
@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Add New Press Release</lable>
            </div>
        </div>
        <div class="row">
             <!--frm-press-release-->
            <div class="col-md-6">
                {!! Form::open(array('route' => 'package.admin.press-releases.save','method' => 'post','id' => 'form','files'=> true)) !!}
                
               <input type="hidden" id='action' value='{{ route("package.admin.data.preview")}}'/>  
               <!--  <div class="form-group">
                   <input type="checkbox" name='checkbox' class='checkbox-financial'/> 
                    
                    
                    <input type="hidden" id='action' value='{{ route("package.admin.data.preview")}}'/>
                    {!! Form::label('checkbox', 'I want to attach this press release to a financial result', array('class'=>'lable-financial','required' => 'true')) !!}
                </div> 
                    <div class="form-group checked-apply-financial">
                           {!! Form::label('quarter', 'Quarter') !!}
                           {!! Form::select('quarter', $quarter, Input::old('quarter'), array('class' => 'form-control checked-quarter', 'disabled' => 'disabled','required' => 'true')); !!}
                           {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                   </div>
                   <div class="form-group checked-apply-financial">
                           {!! Form::label('year', 'Year') !!}
                           {!! Form::select('year', $year, Input::old('year'), array('class' => 'form-control year checked-year', 'disabled' => 'disabled','required' => 'true' )); !!}
                   </div> -->
                   
                   <div class="form-group" style="display:none">
                           {!! Form::label('quarter', 'Quarter') !!}
                           {!! Form::select('quarter', $quarter, Input::old('quarter'), array('class' => 'form-control checked-quarter', 'required' => 'true')); !!}
                           {!! $errors->first('quarter', '<span class="help-block">:message</span>') !!}
                   </div>
                   <div class="form-group" style="display:none">
                           {!! Form::label('year', 'Year') !!}
                           {!! Form::select('year', $year, Input::old('year'), array('class' => 'form-control year checked-year', 'required' => 'true' )); !!}
                   </div>
                    <div class="form-group">
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control','required' => 'true', 'placeholder' => 'Title', 'minlength' => 5 , 'maxlength'=>100]) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                    </div>
                    
                    <div class="form-group">
                            {!! Form::label('date', 'Date') !!}
                            <div id="date" class="input-group date">
                                {!! Form::text('date', Input::old('date'), ['class' => 'form-control','required' => 'true', 'placeholder' => 'Date', 'id' => 'date-id']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                            {!! Form::label('publish_date', 'Publish date') !!}
                            <div id="publish_date" class="input-group date">
                                {!! Form::text('publish_date', Input::old('publish_date'), ['class' => 'form-control','required' => 'true', 'placeholder' => 'Publish date', 'id' => 'publish_date']) !!}
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                            
                            {!! $errors->first('publish_date', '<span class="help-block">:message</span>') !!}
                    </div>
                <!--    <div class="form-group">
                        {!! Form::radio('editor-option', 'wysiwyg',  Input::old('editor-option'), array('class'=>'radio-option')) !!} {!! Form::label('wysiwyg', 'WYSIWYG') !!}
                        {!! Form::radio('editor-option', 'pdf',  Input::old('editor-option'), array('class'=>'radio-option')) !!} {!! Form::label('pdf', 'PDF') !!}
                    </div> -->
                
                   <!-- <div class="form-group option-pdf"> -->
                   <div class="form-group">
                            {!! Form::label('body', 'Description') !!}
                            {!! Form::textarea('body', Input::old('body'), ['class' => 'form-control']) !!}
                            {!! $errors->first('body', '<span class="help-block">:message</span>') !!}
                    </div>
                        <div class="form-group">
                        <label>Upload document</label>
                      <!--  <p>Max file size 3MB </p>-->
                        <div class="form-group">
                            <input type="file" class="hidden" accept="application/pdf, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation" id="file" name="myfile">
                            <div class="row">
                                <div class="col-xs-8 upload-file">
                                    {!! Form::text('upload', Input::old('upload'), ['class' => 'form-control', 'placeholder' => 'Support only (pdf, ppt, pptx, doc, docx)', 'id' => 'upload', 'readonly']) !!}
                                </div>
                                <div class="col-xs-4 div-sel-file">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <!--<div class='option-wysiwyg'>
                        {!! Form::label('body', 'Body') !!}
                        {!! Form::textarea('body', Input::old('body'), ['class' => 'form-control', 'required' => 'true','required' => 'true', 'id' => 'bodyEditor']) !!}
                        {!! $errors->first('body', '<span class="help-block">:message</span>') !!}
                    </div> -->
                    <div class="form-group">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', array( 1=>'Publish', 0 => 'Unpublish'), Input::old('status') , array('class' => 'form-control')); !!}
                    </div>
                    
                  <div class="row">
                	<div class="input_fields_wrap">
                		<div class="col-md-12">
                			<div class="row">
                				<div class="col-xs-12 col-md-9 col-lg-7">
                					<div class=" col-xs-12 col-md-12">
                						<div class="row">
                							<div class="col-xs-12 col-md-12">
                								<div class="row">
                									<input type="checkbox" class="js-switch"  name="notify_it" value="1"/>
                									<b>Notify subscribers</b>
                								</div>
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>
                		</div>
                	</div>
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
        .option-pdf, .option-wysiwyg {
            display:none;
        }
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

	var elem = document.querySelector('.js-switch');
    var init = new Switchery(elem);


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
        
        $('#date, #publish_date').datetimepicker({
            format: 'DD MMMM, YYYY'
        });
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':'{!! csrf_token() !!}'
            }
        }); 
   
        $('.active').removeClass('active');
        $('.press_re').addClass('active');
        $('.press_re_form').addClass('active');
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

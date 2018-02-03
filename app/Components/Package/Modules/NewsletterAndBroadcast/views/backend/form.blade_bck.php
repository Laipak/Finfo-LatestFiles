@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
@stop
@section('content')
    <section class="content" id="newsletter">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Add New Newsletter</lable>
            </div>
        </div>
        <div class="row">
        {!! Form::open(array('route' => 'package.admin.newsletter-broadcast.save', 'id' => 'frm-letter', 'files'=> true)) !!}
            <div class="col-md-6">
                    <div class="form-group">
                            {!! Form::label('newsletter_type', 'NewsLetter type') !!}
                        <div class="row">
                            <div  class="col-md-6">
                                <div class="radio">
                                    <label>
                                      {!! Form::radio('newsletter_type', '1', true,[ 'class' => 'radio_type']) !!}
                                      PDF file
                                    </label>
                                </div>
                            </div>
                            <div  class="col-md-6">
                                <div class="radio">
                                    <label>
                                      {!! Form::radio('newsletter_type', '2', false,[ 'class' => 'radio_type']) !!}
                                      Template
                                    </label>
                                </div>
                            </div>                
                        </div>
                            {!! $errors->first('newsletter_type', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('subject', 'Subject') !!}
                            {!! Form::text('subject', Input::old('subject'), ['class' => 'form-control', 'placeholder' => 'Subject']) !!}
                            {!! $errors->first('subject', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group" id='div-upload'>
                        {!! Form::label('upload', 'Upload PDF') !!}
                        <div class="form-group">
                            <input type="file" class="hidden" accept="application/pdf" id="file" name="myfile">
                            <div class="row">
                                <div class="col-xs-8 upload-file">
                                    {!! Form::text('upload', Input::old('upload'), ['class' => 'file-upload-input form-control', 'placeholder' => 'Support only pdf', 'id' => 'file_upload', 'readonly' => '', 'required' => '']) !!}
                                </div>
                                <div class="col-xs-4 div-sel-file">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email_group_list', 'Email group list') !!}
                        <div class="clearfix"></div>
                        <div class="row">
                        <div class="col-md-4 col-lg-3">
                            <div class="checkbox">
                               <label>
                                  <input type="checkbox" name="email_group_list[]" value="1">Test lists
                               </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="checkbox">
                               <label>
                                  <input type="checkbox" name="email_group_list[]" value="2">All subscribe email
                               </label>
                            </div>
                        </div>
                        </div>
                        <div class="clearfix"></div>
                        <label id="email_group_list[]-error" class="error" for="email_group_list[]"></label>
                        {!! $errors->first('email_group_list', '<span class="help-block">:message</span>') !!}
                            
                    </div>

                    <div class="form-group">
                            {!! Form::label('sender_email', 'Sender email') !!}
                            {!! Form::text('sender_email', Input::old('sender_email'), ['class' => 'form-control', 'placeholder' => 'Sender email']) !!}
                            {!! $errors->first('sender_email', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('reply_to_name', 'Reply to name') !!}
                            {!! Form::text('reply_to_name', Input::old('reply_to_name'), ['class' => 'form-control', 'placeholder' => 'Reply to name']) !!}
                            {!! $errors->first('reply_to_name', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('reply_to_email', 'Reply to email') !!}
                            {!! Form::text('reply_to_email', Input::old('reply_to_email'), ['class' => 'form-control', 'placeholder' => 'Reply to email']) !!}
                            {!! $errors->first('reply_to_email', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                           <label>
                              <input type="checkbox" name="schedule_it" value="1" {{($reach_limit)? 'disabled': ''}} class="schedule_it">Schedule it
                           </label>
                        </div>
                    </div>
            </div>

            <div class="col-md-6 content-template hide">
                <div class="text-center" style="margin-top: 7px;"><strong>Template Content</strong></div> <br><br>
                <!--<div class="form-group">
                    {!! Form::label('title', 'Title') !!}
                    {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'title', 'id' => 'title']) !!}
                    {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                </div> !-->

                <!--<div class="form-group">

                    {!! Form::textarea('content', $template , ['class' => 'form-control', 'placeholder' => 'content', 'id' => 'bodyEditor']) !!}
                    {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                </div> !-->
                <div class="template-content">
                    @include('app.Components.Package.Modules.NewsletterAndBroadcast.views.template.new_template')
                </div>
                <input type="hidden" name="content" id='content'>
                
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                @if($reach_limit)
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        You are reach the limit of broadcast.
                    </div>
                @endif
                <div class="input-group input-group-sm">
                    <hr>
                    <span class="input-group-btn">
                        <h4 class="title">Broadcast Settings</h4>
                    </span>
                    <hr>
                </div>
                <p><strong>Number of Broadcast :</strong> {{$num_broadcast}} <strong>of {{$limit_bc['broadcasts_per_year']}}</strong></p>
                <br><br><br>
            </div>


            <div class="schedule-content">
                <div class="col-md-4">
                    <?php $status = array('' => 'Select status', 'draft', 'live') ?>
                    <div class="form-group">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', $status, '', array('class' => 'form-control', 'id' => 'status', 'disabled' => '')); !!}
                            {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('broadcast_date', 'Broadcast Date') !!}
                        {!! Form::text('broadcast_date', Input::old('broadcast_date'), ['class' => 'form-control', 'placeholder' => 'Broadcast date', 'id' => 'broadcast_date', 'disabled' => '']) !!}
                        {!! $errors->first('broadcast_date', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('broadcast_time', 'Broadcast time') !!}
                        {!! Form::text('broadcast_time', Input::old('broadcast_time'), ['class' => 'form-control', 'placeholder' => 'Broadcast time', 'id' => 'broadcast_time', 'disabled' => '']) !!}
                        {!! $errors->first('broadcast_time', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                <div class="form-group" style="margin-top:30px;">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                        <a href="{{route('package.admin.newsletter-broadcast')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
         @include('app.Components.Package.Modules.NewsletterAndBroadcast.views.includes.modal')
    </section>

   
@stop
@section('style')
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('css/finfo/summernote.css') !!}
    {!! Html::style('css/client/newsletter.css') !!}

    <style type="text/css">
        .schedule-content{
            margin-top: 67px;
        }

        .dis-none, .hide{
            display: none;
        }
        .icon-active{
            background: darkolivegreen !important;
        }
        .error{
            color: red;
        }
    </style>
@stop

@section('script')
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}
{!! Html::script('js/finfo/summernote.min.js') !!}
{!! Html::script('js/client/newsletter-builder.js') !!}


<script type="text/javascript">
    $('.active').removeClass('active');
    $('.news_brod').addClass('active');
    $('.news_brod_form').addClass('active');

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':'{!! csrf_token() !!}'
        }
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

        var match = ["application/pdf"];
        if (!((filetype == match[0]))) {
            alert('Please select pdf file.');
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

    var dateToday = new Date();
    $('#broadcast_date').datetimepicker({
        //format: 'YYYY/MM/DD',
        format: 'DD MMM YYYY',
        minDate: dateToday,
    });

    $('#broadcast_time').datetimepicker({
        format: 'LT'
    });

    $("#newsletter").on("click",".schedule_it:checked",function(){
        $('#status').prop("disabled", false);
        $('#broadcast_date').prop("disabled", false);
        $('#broadcast_time').prop("disabled", false);
    });

    $("#newsletter").on("click",".schedule_it:not(:checked)",function(){
        $('#status').prop("disabled", true);
        $('#broadcast_date').prop("disabled", true);
        $('#broadcast_time').prop("disabled", true);
    });

    $('.radio_type').click(function(){
        var value = $('input[name=newsletter_type]:checked', '#frm-letter').val()
        if(value == 1){
            $('#file_upload').attr("required","required");
            $('.content-template').addClass('hide');
            $('#title').removeAttr("required");
            $('#content').removeAttr("required");
            $('#div-upload').removeClass('hide');
        }else{
            $('#title').attr("required","required");
            $('#content').attr("required","required");
            $('#file_upload').removeAttr("required");
            $('.content-template').removeClass('hide');
            $('#div-upload').addClass('hide');
        }
    });
    
    $("#frm-letter").validate({
        rules: {
            'newsletter_type': 'required',
            'subject': 'required',
            'sender_email':{
                required : true,
                email : true
            },
            'reply_to_name': 'required',
            'reply_to_email':{
                required : true,
                email : true
            },
            'email_group_list[]': 'required'
        },
        messages:{
            'email_group_list[]': 'Please select at least one.'
        },

        submitHandler: function(form) {
            var content = $('.template-content').html();
            $('#content').val(content);
            form.submit();
        }
    });

    $('#bodyEditor').summernote({
            height: 500
    });
</script>
@stop

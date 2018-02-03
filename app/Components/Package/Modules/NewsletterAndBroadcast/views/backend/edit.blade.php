@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
@stop
@section('content')
    <section class="content" id="newsletter">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Edit Newsletter</lable>
            </div>
        </div>
        <div class="row">
        {!! Form::open(array('route' => ['package.admin.newsletter-broadcast.save', $news->id], 'id' => 'frm-letter', 'files'=> true)) !!}
            <div class="col-md-6">
                    <div class="form-group">
                            {!! Form::label('newsletter_type', 'NewsLetter type') !!}
                        <div class="row">
                            <div  class="col-md-6">
                                <div class="radio">
                                    <label>
                                      {!! Form::radio('newsletter_type', '1', ($news->newsletter_type == 1)? true : false,[ 'class' => 'radio_type']) !!}
                                      PDF file
                                    </label>
                                </div>
                            </div>
                            <div  class="col-md-6">
                                <div class="radio">
                                    <label>
                                      {!! Form::radio('newsletter_type', '2', ($news->newsletter_type == 2)? true : false,[ 'class' => 'radio_type']) !!}
                                      Template
                                    </label>
                                </div>
                            </div>                          
                        </div>
                            {!! $errors->first('newsletter_type', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('subject', 'Subject') !!}
                            {!! Form::text('subject', $news->subject, ['class' => 'form-control', 'placeholder' => 'Subject']) !!}
                            {!! $errors->first('subject', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('upload', 'Upload PDF') !!}
                        <div class="form-group">
                            <input type="file" class="hidden" accept="application/pdf" id="file" name="myfile">
                            <div class="row">
                                <div class="col-xs-8 upload-file">
                                    {!! Form::text('upload', $news->upload, ['class' => 'file-upload-input form-control', 'placeholder' => 'Support only pdf', 'id' => 'file_upload', 'readonly' => '', 'required' => '']) !!}
                                </div>
                                <div class="col-xs-4 div-sel-file">
                                    <button type="button" class="btn btn-upload upload-file">Select File</button>
                                </div>
                            </div>
                            {!! $errors->first('upload', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                 <!--   <div class="form-group">
                        {!! Form::label('email_group_list', 'Email group list') !!}
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-4 col-lg-3">
                                <div class="checkbox">
                                   <label>
                                      <input type="checkbox" name="email_group_list[]" {{(in_array(1, $news->email_group_list))? 'checked': ''}} value="1">Test lists
                                   </label>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="checkbox">
                                   <label>
                                      <input type="checkbox" name="email_group_list[]" {{(in_array(2, $news->email_group_list))? 'checked': ''}} value="2">All subscribe email
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
                            {!! Form::text('sender_email', $news->sender_email, ['class' => 'form-control', 'placeholder' => 'Sender email']) !!}
                            {!! $errors->first('sender_email', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('reply_to_name', 'Reply to name') !!}
                            {!! Form::text('reply_to_name', $news->reply_to_name, ['class' => 'form-control', 'placeholder' => 'Reply to name']) !!}
                            {!! $errors->first('reply_to_name', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('reply_to_email', 'Reply to email') !!}
                            {!! Form::text('reply_to_email', $news->reply_to_email, ['class' => 'form-control', 'placeholder' => 'Reply to email']) !!}
                            {!! $errors->first('reply_to_email', '<span class="help-block">:message</span>') !!}
                    </div> -->

                    <div class="form-group">
                        <div class="checkbox">
                           <label>
                               <input type="checkbox" class="js-switch schedule_it"  name="schedule_it"  value="1" {{($reach_limit)? 'disabled': ''}} {{($news->schedule_it == 1)? 'checked': ''}} class="schedule_it">Schedule</b>
                            </label>
                        </div>
                    </div>
            </div>

            <div class="col-md-6 content-template hide">
                <div class="text-center"><strong>Template Content</strong></div> <br><br>
                <!--<div class="form-group">
                    {!! Form::label('title', 'Title') !!}
                    {!! Form::text('title', (isset($tp_content->title)? $tp_content->title : ''), ['class' => 'form-control', 'placeholder' => 'title', 'id' => 'title']) !!}
                    {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                </div> !-->

                <!--<div class="form-group">
                    {!! Form::textarea('content', (isset($tp_content->content)? $tp_content->content: ''), ['class' => 'form-control', 'placeholder' => 'content', 'id' => 'bodyEditor']) !!}
                    {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                </div>!-->
                <div class="template-content">
                @if(isset($tp_content->content))
                    {!!$tp_content->content!!}
                @else
                    @include('app.Components.Package.Modules.NewsletterAndBroadcast.views.template.new_template')
                @endif
                    
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
              <!--  <div class="col-md-4">
                    <?php $status = array('' => 'Select status', 'draft', 'live') ?>
                      <div class="form-group">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', $status, (isset($broadcast->status))? $broadcast->status: '', array('class' => 'form-control', 'id' => 'status', 'disabled' => '')); !!}
                            {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>-->
                <div class="col-md-2">
                    <div class="form-group">
                          {!! Form::text('broadcast_date', (isset($broadcast->broadcast_date)? date('d M Y', strtotime($broadcast->broadcast_date)): ''), ['class' => 'form-control', 'placeholder' => 'Broadcast date', 'id' => 'broadcast_date', 'disabled' => '']) !!}
                        {!! $errors->first('broadcast_date', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::text('broadcast_time', (isset($broadcast->broadcast_time)? $broadcast->broadcast_time : ''), ['class' => 'form-control', 'placeholder' => 'Broadcast time', 'id' => 'broadcast_time', 'disabled' => '']) !!}
                        {!! $errors->first('broadcast_time', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group" style="padding-right: -32px;margin-top: 5px;">
                        UTC +08  Schedule a date and time for when you want your newsletter to be broadcasted.
                        
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                <div class="form-group" style="margin-top:30px;">
                        {!! Form::submit('Send Test', ['class' => 'btn btn-warning warning', 'name' =>'submit', 'value' =>'1']) !!}
                        {!! Form::submit('Send Subscribers', ['class' => 'btn btn-success save', 'name' =>'submit', 'value' =>'2']) !!}
                        {!! Form::submit('Save Draft', ['class' => 'btn btn-primary primary', 'name' =>'submit', 'value' =>'2']) !!}
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
    {!! Html::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') !!}


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
    .warning{
                border-radius: 0;
                border: 0;
                color: #fff;
                text-transform: uppercase;
                padding: 8px 35px;
                background: #ffa827;
                margin: 5px;
             
        }
        
        .primary{
                border-radius: 0;
                border: 0;
                color: #fff;
                text-transform: uppercase;
                padding: 8px 35px;
                background: #3a78b0;
                margin: 5px;
             
        }
        .save {
                border-radius: 0;
                border: 0;
                color: #fff;
                text-transform: uppercase;
                padding: 8px 35px;
                background: #75b600;
                margin: 5px;
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
    //$('.news_brod_form').addClass('active');
    
    
    var elem = document.querySelector('.js-switch');
    var init = new Switchery(elem);

    $('.upload-file').click(function(){
        $('#file').click();
    });

    $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':'{!! csrf_token() !!}'
            }
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

    $('#broadcast_date').datepicker({
        dateFormat: 'd M yy',
        minDate: 0 
    });

    $('#broadcast_time').datetimepicker({
        format: 'LT'
    });

    if($(".schedule_it:checked").length > 0){
        $('#status').prop("disabled", false);
        $('#broadcast_date').prop("disabled", false);
        $('#broadcast_time').prop("disabled", false);
    }

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

    var value = $('input[name=newsletter_type]:checked', '#frm-letter').val()
    checkRadio(value);

    $('.radio_type').click(function(){
        var value = $('input[name=newsletter_type]:checked', '#frm-letter').val()
        checkRadio(value);
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
            }
        },

        submitHandler: function(form) {
            var content = $('.template-content').html();
            $('#content').val(content);
            form.submit();
        }
    });

    function checkRadio(value)
    {
        if(value == 1){
            $('#file_upload').attr("required","required");
            $('.content-template').addClass('hide');
            $('#title').removeAttr("required");
            $('#content').removeAttr("required");
        }else{
            $('#title').attr("required","required");
            $('#content').attr("required","required");
            $('#file_upload').removeAttr("required");
            $('.content-template').removeClass('hide');
        }
    }


    $('#bodyEditor').summernote({
            height: 500
    });
    </script>
@stop

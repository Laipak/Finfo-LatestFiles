@extends($app_template['client.backend'])
@section('title')
Company Info
@stop
@section('content')
    <section class="content" id="list-page">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Add New Page</lable>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-12">
                <div class="box">
                    <div class="box-body">
                    {!! Form::open(array('route' => 'client.webpage.backend.store','method' => 'post','id' => 'form','class' => 'myform')) !!}
                    <div class="col-sm-12 col-md-6">
                        <h2>Page Information</h2>
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            
                            
                                 <input type="hidden" id='action' value='{{ route("client.webpage.backend.preview")}}'/>
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control title', 'placeholder' => 'Title', 'required' => 'true']) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="form-group required{{ $errors->has('title') ? ' has-error' : '' }}">
                            <p class='url_link'></p>
                        </div>
                        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                            {!! Form::label('body', 'Body') !!}
                            {!! Form::textarea('body', Input::old('body'), ['class' => 'form-control', 'required' => 'true', 'id' => 'bodyEditor']) !!}
                            {!! $errors->first('body', '<span class="help-block">:message</span>') !!}
                        </div>
                        <h2>SEO(Search Engine Optimize) (Optional)</h2>
                        <div class="form-group">
                            {!! Form::label('meta', 'Meta') !!}
                            {!! Form::text('meta', Input::old('meta'), ['class' => 'form-control','placeholder' => 'Keyword']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('description', 'Meta Description') !!}
                            {!! Form::textarea('description', Input::old('slug'), ['class' => 'form-control','placeholder' => 'Meta Description']) !!}
                        </div>
                        
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <h2>Settings</h2>
                        <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
                            {!! Form::label('order', 'Order') !!}
                            {!! Form::select('order', array('1' => 1, '2' => 2, '3' => 3,'4' => 4,'5' => 5 ), Input::old('order'), ['class' => 'form-control'] ) !!}
                            {!! $errors->first('order', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="form-group{{ $errors->has('tempate') ? ' has-error' : '' }}">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', array('1' => 'Live', '0' => 'Draft' ), null, ['class' => 'form-control'] ) !!}
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', 'Page Url') !!}
                            {!! Form::text('name', Input::old('name'), ['class' => 'form-control name', 'placeholder' => 'Slug']) !!}
                            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']); !!}





                            <a href="{{route('client.webpage.backend.list')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>

                         <input type="button" class='btn btn-primary btn-overwrite-cancel' id="submit" value="Preview">
                           

 <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/preview/temp";  ?>

                        </div>
                    </div>
                    <input type='hidden' id="baseUrl" value="{{url()}}"/>
                    {!! Form::close() !!}



                </div>
            </div>
        </div>
        </div>
    </section>






@stop
@section('style')
    {!! Html::style('css/finfo/summernote.css') !!}
    {!! Html::style('css/client/webpage.css') !!}
    {!! Html::style('css/client/customize.css') !!}
    <style type="text/css">
        .error{
            color: red;
            font-weight: 500;
        }
    </style>
@stop
@section('script')
<meta name="_token" content="{!! csrf_token() !!}"/>
   {!! Html::script('js/finfo/summernote.min.js') !!}
    {!! Html::script('js/client/upload-feature-image.js') !!}
    
    
    
    
    
    
    
    
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
            
            {
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
            }
        });
    });

</script>

    
    
    
    
    
    
    
    
    
    
<script>
    $(document).ready(function() {


     

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
        $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });
        $('#bodyEditor').summernote({

           onImageUpload: function(files, editor, $editable) {
               sendFile(files[0],editor,$editable);
           },      
           height: 200
        });
        $('.title').blur(function(){
            var page_name = $(this).val();
            if ($('.name').val() !== "") {
                page_name = $('.name').val();
            }
            $('.url_link').html('URL: '+ $('#baseUrl').val()+ '/' + page_name);
        });
//        $('.name').blur(function(){
//            var page_name = $(this).val(); 
//            $('.url_link').html('URL: '+ $('#baseUrl').val()+ '/' + page_name);
//        });

        var feature_image = $('#feature_image').val();

        if(feature_image != ""){
            $('.feature-image').html('<img src="/img/client/webpage/feature-images/'+data+'" class="feature-img" style="width:100%;position: absolute; margin: auto;top: 0;left: 0;right: 0;bottom: 0;" >');
        }

        $('.feature-image').click(function(){
            $('#file_feature_image').click();
        });
        $('.active').removeClass('active');
        $('.company_info').addClass('active');








    });
</script>

@stop

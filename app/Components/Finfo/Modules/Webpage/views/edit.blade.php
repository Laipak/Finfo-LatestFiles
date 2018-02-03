@extends($app_template['backend'])

@section('content')
	<section class="content" id="list-user">
		<div class="row head-search">
			<div class="col-sm-6">
				<h2 style="margin:0;">Edit Page</h2>
			</div>
			
      	</div>
            
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('page_success_updated'))
                    <div class="col-md-12 alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ trans(Session::get('page_success_updated')) }}
                    </div>
                @endif  
            </div>
            <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            {!! Form::open(array('route' => ['finfo.webpage.backend.update', $pageData->id ], 'id' => 'frm_edit_page')) !!}
                            <div class="col-sm-12 col-md-6">
                                <h2>Page Information</h2>
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    {!! Form::label('title', 'Title') !!}
                                    {!! Form::text('title', $pageData->title, ['class' => 'form-control title', 'placeholder' => 'Title']) !!}
                                    {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="form-group{{ $errors->has('tempate') ? ' has-error' : '' }}">
                                    <p class='url_link'>{!! 'URL: '.url($pageData->name)!!}</p>
                                </div>
                                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                    {!! Form::label('body', 'Body') !!}
                                    {!! Form::textarea('body', $pageData->content_description, ['class' => 'form-control',  'id' => 'bodyEditor']) !!}
                                    {!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
                                </div>
                                <h2>SEO(Search Engine Optimize) (Optional)</h2>
                                <div class="form-group">
                                    {!! Form::label('meta', 'Meta') !!}
                                    {!! Form::text('meta',  $pageData->meta_keyword, ['class' => 'form-control', 'placeholder' => 'Keyword']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('description', 'Meta Description') !!}
                                    {!! Form::textarea('description', $pageData->meta_description, ['class' => 'form-control', 'placeholder' => 'Meta Description']) !!}
                                </div>
                                
                                <div class="form-group">
                                    {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save ']); !!}
                                    <a href="{{route('finfo.webpage.backend.list')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>
                                </div>
                            </div>
                            <div class="col-sm-12 col-sm-6">
                                <h2>Settings</h2>
                                <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
                                    {!! Form::label('order', 'Order') !!}
                                    {!! Form::text('order', $pageData->ordering, ['class' => 'form-control', 'placeholder' => '0']) !!}
                                    {!! $errors->first('order', '<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="form-group{{ $errors->has('tempate') ? ' has-error' : '' }}">
                                    {!! Form::label('status', 'Status') !!}
                                    {!! Form::select('status', array('1' => 'Live', '0' => 'Draft' ), $pageData->is_active, ['class' => 'form-control'] ) !!}
                                </div>
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    {!! Form::label('name', 'Page Url') !!}
                                    {!! Form::text('name', $pageData->name, ['class' => 'form-control name', 'placeholder' => 'Slug']) !!}
                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                </div>
                                <input type='hidden' id="baseUrl" value="{{url()}}"/>
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('backend/css/finfo/summernote.css') !!}
    {!! Html::style('backend/css/finfo/customize.css') !!}
    <style type="text/css">
        .error{
            color: red;
            font-weight: 500;
        }
    </style>
@stop

@section('script')
<script>
    $(document).ready(function(){
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                url: "/backend/admin/webpage/move-upload-image",
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
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':'{!! csrf_token() !!}'
            }
        });
        $('.title').blur(function(){
            var page_name = $(this).val();
            if ($('.name').val() !== "") {
                page_name = $('.name').val();
            }
            $('.url_link').html('URL: '+ $('#baseUrl').val()+ '/' + page_name);
        });
        $('.name').blur(function(){
            var page_name = $(this).val(); 
            $('.url_link').html('URL: '+ $('#baseUrl').val()+ '/' + page_name);
        });
    });
</script>
{!! Html::script('backend/js/finfo/summernote.min.js') !!}
@stop

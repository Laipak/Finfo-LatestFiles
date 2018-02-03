@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
   <section class="content" id="setting">
        @if (session()->has('successSettings'))
            <div class="row">
               <div class="col-sm-12">
                   <div class='alert alert-success'>
                       {{session('successSettings')}}
                   </div>
               </div>
            </div>
        @endif
	<div class="row head-search">
	    <div class="col-sm-6">
	        <lable class="label-title">Add media access file</lable>
	    </div>	    
	</div>
       {!! Form::open(array('route' => 'package.admin.media-access.do.createfile.form',  'method' => 'post', 'files' => true, 'id' => 'frm_create_media' )) !!}
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Title</h5>
                                <input type="text" name="title" class="form-control" value="{{Input::old('upload')}}"/>
                                 {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <h5>Description</h5>
                                <textarea name="description" cols="5" rows="5" class="form-control">{{Input::old('description')}}</textarea>
                                {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <h5>Upload file</h5>
                                <div class="form-group">
                                    <input type="file" class="hidden" accept="application/pdf" id="file" name="upload_file">
                                    <div class="row">
                                        <div class="col-xs-8 upload-file">
                                            {!! Form::text('upload', Input::old('upload'), ['class' => 'form-control', 'placeholder' => 'Support only (pdf), and max file size 3MB ', 'id' => 'upload', 'readonly']) !!}
                                        </div>
                                        <div class="col-xs-4 div-sel-file">
                                            <button type="button" class="btn btn-upload upload-file">Select File</button>
                                        </div>
                                    </div>
                                    {!! $errors->first('upload_file', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5>Category</h5>
                                <select name="category" class="form-control">
                                    @if (isset($getMediaAccessCategory) && !empty($getMediaAccessCategory)) {
                                        @foreach($getMediaAccessCategory as $category)
                                            @if ($category->id == Input::old('status')) 
                                                <option value='{{$category->id}}' selected>{{$category->category_name}}</option>
                                            @else
                                                <option value='{{$category->id}}'>{{$category->category_name}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value='0'>None</option>
                                    @endif
                                </select>
                                
                            </div>
                            <div class="form-group">
                                <h5>Status</h5>
                                {!! Form::select('status',
                                        array('0' => 'Publish', '1' => 'Unpublish'), 
                                        Input::old('status'), array('id' => 'input', 'class'=> 'form-control')) 
                                !!}
                            </div>
                            <div class="form-group">
                                <h5>Expire Date</h5>
                                <div id="expire_date" class="input-group date">
                                    <input type="text" name="expire_date" class="form-control"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                {!! $errors->first('expire_date', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <h5>Permission access</h5>                              
                                <div class="form-grop">
                                    <select name="permission[]" class="form-control permission" multiple>
                                        @if (isset($getMediaAccessUsers) && !empty($getMediaAccessUsers)) 
                                            @foreach($getMediaAccessUsers as $mediaUser) 
                                                <option value='{{$mediaUser->id}}'>{{$mediaUser->email}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            {!! $errors->first('permission', '<span class="help-block">:message</span>') !!}
                        </div>	
                    </div>
                    <div class="row">
                        <div class='col-sm-12'>
                            <input type="submit" class="btn btn-primary btn-save" value="Save">
                            <a href="{{route('package.admin.media-access')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
</section>
@stop
@section('style')
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    <style>
        .btn-media-cancel{
            margin-top: 20px;
            margin-left: 0px;
            width: 130px;
            border-radius: 0px;
        }
        .btn-setting-save {
            background: #75b600;
            margin-top: 20px;
            margin-left: 0px;
            width: 130px;
        }
        #frm_create_media label.error{
            font-weight: 500;
        }
        #frm_create_media span.help-block,
        #frm_create_media label.error
        {
            color: red;
        }
    </style>
@stop
@section('script')
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}
<script type="text/javascript">
    $(function () {
        $("#frm_create_media").validate({
            rules: {
                'title': 'required',
                'description': 'required',
                'upload': 'required',
                'permission[]': 'required',
                'category': 'required'
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        
        $('#expire_date').datetimepicker({
            format: 'D MMMM, YYYY'
        });
        $('.upload-file').click(function(){
            $('#file').click();
        });
        $('#file').change(function(e) {
            $('#upload').val('');
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
            }
        });
    });
    $('.active').removeClass('active');
    $('.media_acc').addClass('active');
    $('.media-form').addClass('active');
</script>
@stop
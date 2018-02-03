@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
	<section class="content">
                @if(session()->has('categoryUpdated'))        
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('categoryUpdated') }}
                    </div>
                @endif
		{!! Form::open(array('route' => 'package.admin.media-access.update-category', 'method' => 'post', 'id'=> 'frm_media_category_edit')) !!}
			<div class="row" id="finan-highlight">
                            <div class="col-md-12">
                                <lable class="label-title">Edit media category</lable>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h5>Category Name</h5>
                                        <div class="form-group">
                                            <input type='text' name='category_name' value='{{$mediaAccessCategoryInfo[0]->category_name}}' class="form-control"/>
                                            {!! $errors->first('category_name', '<span class="help-block">:message</span>') !!}
                                             {!! $errors->first('categoryInfo', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <button class="btn btn-primary btn-save">Update</button>
                                        <a href="{{route('package.admin.media-access.list-category')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <input type='hidden' name='categoryInfo' value='{{\Crypt::encrypt($mediaAccessCategoryInfo[0]->id)}}'/>
		{!! Form::close() !!}
	</section>
@stop

@section('style')
<style>
    #frm_media_category_edit .btn{
        border-radius: 0px;
    }
    #frm_media_category_edit .btn-danger{
        min-width: 100px;
    }
    #frm_media_category_edit .help-block {
        color:red;
    }
</style>
@stop
@section('script')
<script>
    $('.active').removeClass('active');
    $('.media_acc').addClass('active');
    $('.media-category').addClass('active');
</script>
@stop

@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
	<section class="content">
                @if(session()->has('success'))        
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('success') }}
                    </div>
                @endif
		{!! Form::open(array('route' => 'package.admin.media-access.create-category', 'method' => 'post', 'id'=> 'frm_media_category')) !!}
			<div class="row" id="finan-highlight">
                            <div class="col-md-12">
                                <lable class="label-title">Add new media category</lable>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h5>Category Name</h5>
                                        <div class="form-group">
                                            <input type='text' name='category_name' value='{{Input::old('category_name')}}' class="form-control"/>
                                            {!! $errors->first('category_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <button class="btn btn-primary btn-save">Save</button>
                                        <a href="{{route('package.admin.media-access.list-category')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
		{!! Form::close() !!}
	</section>
@stop

@section('style')
<style>
    #frm_media_category .btn{
        border-radius: 0px;
    }
    #frm_media_category .btn-danger{
        min-width: 100px;
    }
    #frm_media_category .help-block {
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

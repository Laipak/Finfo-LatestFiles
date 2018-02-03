@extends($app_template['backend'])

@section('content')
	<section class="content" id="list-menus">
           
        {!! Form::open(array('id' => 'form', 'method' => 'post')) !!}
		<div class="row head-search">
                     <div class='col-sm-12'>
                @if(Session::has('global'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('global') }}
                    </div>
                @elseif(Session::has('global-deleted'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('global-deleted') }}
                    </div>
                @endif
            </div>
            <div class="col-sm-6">
                <h2 style="margin:0;">List of Menus</h2>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">
                    <a href="{{route('finfo.menus.backend.create')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    <i class="fa fa-plus"></i> New Menus
                    </a>
                    <button class="btn btn-success btn-flat btn-publish-all" style="padding: 4px 10px;">
                        <i class="fa fa-eye"></i> Publish
                    </button>
                    <button class="btn btn-warning btn-flat btn-unpublish-all" style="padding: 4px 10px;">
                        <i class="fa fa-eye-slash"></i> Unpublish
                    </button>
                    <button class="btn btn-danger btn-flat btn-delete-all" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </button>

                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-12">
                <div class="box">
                    <div id="box-user" class="box-body">
                        <table id="table-user" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header">
                                    <th class="hid"><input class="check-all" type="checkbox"></th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Oder</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $menu)
                                    <tr>
                                        <td width="15px" class="text-center"><input class="check-user" name="check[]" value="{{$menu->id}}" type="checkbox"></td>
                                        <td>{{$menu->title}}</td>
                                        <td>{{$menu->link}}</td>
                                        <td>{{$menu->ordering}}</td>
                                        <td>{!!$controller->getStatus($menu->status)!!}</td>
                                        <td class="text-center"><a href="{{route('finfo.menus.backend.create', $menu->id)}}"><i class="fa fa-edit fa-lg"></i></a> | <a class="btn-delete" href="{{route('finfo.menus.backend.menu.delete', $menu->id)}}"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="url" value="{{route('finfo.menus.backend.list')}}">
        {!! Form::close()!!}
    </section>
@stop

@section('style')
<style type="text/css">
    .head-search{
        margin-bottom: 30px;
        margin-top: 30px;
    }
</style>
@stop
@section('script')
<script type="text/javascript">
        $('.btn-delete').click(function(){
            if(confirm('Are you sure you want to delete this one?')){
                window.location = $(this).attr('href');
            }
        });
	$("#table-user").on("click",".check-all:checked",function(){
            $(".check-user:checkbox:not(:checked)").click(); 
	});

	$('.check-all:not(:checked)').click(function(){
		
            $(".check-user:checkbox:checked").click(); 
	});

	$("#table-user").on("click",".check-user:not(:checked)",function(){
            if($(".check-user:checkbox:checked").length <= 0){
                $('.check-all').prop('checked', false);
            }	
	});
</script>
@stop
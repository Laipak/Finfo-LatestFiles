@extends($app_template['client.backend'])
@section('title')
Financial Highlights
@stop
@section('content')
	<section class="content financial-result" id="list-page">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('global'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ Session::get('global') }}
                        </div>
                    @elseif(Session::has('deleted'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ Session::get('deleted') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row head-search">
                <div class="col-sm-6 title-mobile">
                        <lable class="label-title">Archive Financial Highlights</lable>
                </div>
                <div class="col-sm-6"> 
                    <div class="pull-right">
                        <button class="btn btn-danger btn-flat btn-delete-all" style="padding: 4px 10px;">
                            <i class="fa fa-trash"></i> Deleted
                        </button>
                        <button class="btn btn-success btn-flat btn-publish-all" style="padding: 4px 10px;">
                            <i class="fa fa-eye"></i> Publish
                        </button>
                        <button class="btn btn-warning btn-flat btn-unpublish-all" style="padding: 4px 10px;">
                            <i class="fa fa-eye-slash"></i> Unpublish
                        </button>
                    </div>
                </div>			
            </div>
        <div class="row">
            
            {!! Form::open(array('id' => 'form', 'method' => 'post')) !!}
            <div class="col-md-12">
	            <div class="box">
	            	<div id="box-table-latest-financial" class="box-body table-responsive">
	            		<table id="table-latest-financial" class="table table-bordered table-striped">
	                        <thead>
	                        <tr class="table-header">
                                    <th class="hid"><input class="check-all" type="checkbox"></th>
	                            <th>Title</th>
	                            <th>Financial Term</th>
                                    <th>Stauts</th>
                                    <th style="width: 160px;">Quick Actions</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        @if($financialHighlights)
	                        	@foreach($financialHighlights as $data)
	                            <tr>
	                            	<td width="15px" class="text-center"><input name='check[]' class="check-latest-financial" type="checkbox" value='{{$data->id}}'></td>
	                                <td>{{$data->title}}</td>
	                                <td>{{$data->quarterName}}, {{$data->year}}</td>
                                        <th>{{($data->status == 0) ? "Publish" : "Unpublish"}}</th>
                                        <td class="text-center">
                                        <a href="{{route('package.admin.latest-financial-highlights.form.id', $data->id)}}">View</a> | 
                                        <a title="delete" class="btn-action-delete" href="{{ URL::route('package.admin.latest-financial-highlights.deleted.id', $data->id)}}"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
	                            </tr>
	                            @endforeach
	                       @endif
	                        </tbody>
	                    </table>
	            	</div>
	            </div>
            </div>
            
            <input type="hidden" id="url" value="{{route('package.admin.latest-financial-highlights')}}">
            {!! Form::close() !!}
        </div>
        

@stop
@section('style')
	{!! Html::style('css/client/list-finan.css') !!}
        {!! Html::style('css/client/finan-highlight.css') !!}
	{!! Html::style('css/dataTables.bootstrap.min.css') !!}
        <style>
            #list-page th {
                color: #A4A2A2;    
            }
            #list-page tbody td{
                color: #A4A2A2
            } 
        </style>
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    $('.active').removeClass('active');
    $('.last_fin_hig').addClass('active');
    $('.last_fin_hig_list_archive').addClass('active');

	$(document).ready(function(){
            var screen = $(window).width();
            if(screen < 770){
                    $('#box-table-latest-financial').addClass('table-responsive');
            }else{
                    $('#box-table-latest-financial').removeClass('table-responsive');
            }
                
            $('.btn-action-delete').click(function(){
                if(confirm('Are you sure you want to delete this one?')){
                    window.location = $(this).attr('href');
                }
            });
            $("#table-latest-financial").dataTable({
        	aoColumnDefs: [
				  {
				     bSortable: false,
				     aTargets: [ 0, 4 ]
				  }
				]
        });
	});
	$("#table-latest-financial").on("click",".check-all:checked",function(){
            $(".check-latest-financial:checkbox:not(:checked)").click(); 
	});
	$('.check-all:not(:checked)').click(function(){
            $(".check-latest-financial:checkbox:checked").click(); 
	});
        $("#table-latest-financial").on("click",".check-latest-financial:not(:checked)",function(){
            if($(".check-latest-financial:checkbox:checked").length <= 0){
                $('.check-all').prop('checked', false);
            }	
        });
</script>

@stop
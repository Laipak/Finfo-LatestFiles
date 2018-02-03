@extends($app_template['client.backend'])

@section('content')
	<section class="content" id="list-user">
		<div class="row head-search">
			<div class="col-sm-6">
				<h3 style="margin:0;">All Financial Highlights</h3>
			</div>
			<div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="{{ URL::to('admin/latest-financial-highlights/form') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> Add
                    </a>
                    <button class="btn btn-danger btn-flat btn-delete-all" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </button>
                    <button class="btn btn-success btn-flat btn-publish-all" style="padding: 4px 10px;">
                        <i class="fa fa-eye"></i> Publish
                    </button>
                    
                </div>
            </div>			
      	</div>
        <div class="row">
        	@if(Session::has('global'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('global') }}
                </div>
            @endif
            <div class="col-md-12">
	            <div class="box">
	            	<div id="box-user" class="box-body">
	            		<table id="table-user" class="table table-bordered table-striped">
	                        <thead>
	                        <tr class="table-header">
	                        	<th class="hid"><input class="check-all" type="checkbox"></th>
	                            <th>Title</th>
	                            <th>Financial Term</th>
	                            <th>Status</th>
	                            <th style="width: 160px;">Quick Actions</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        
	                            <tr>
	                            	<td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
	                                <td>Lorem ipsum</td>
	                                <td>Apr - Jun, 2015</td>
	                                <td>Draft</td>
	                                <td class="text-center"><a href="#">Quick Edit</a> | <a href="#">Edit</a> | <a href="#" title="delete"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
	                            </tr>

	                            <tr>
	                            	<td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
	                                <td>Lorem ipsum</td>
	                                <td>Apr - Jun, 2015</td>
	                                <td>Draft</td>
	                                <td class="text-center"><a href="#">Quick Edit</a> | <a href="#">Edit</a> | <a href="#" title="delete"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
	                            </tr>

	                            <tr>
	                            	<td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
	                                <td>Lorem ipsum</td>
	                                <td>Jan - Mar, 2015</td>
	                                <td>Draft</td>
	                                <td class="text-center"><a href="#">Quick Edit</a> | <a href="#">Edit</a> | <a href="#" title="delete"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
	                            </tr>

	                            <tr>
	                            	<td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
	                                <td>Lorem ipsum</td>
	                                <td>Full Year, 2014</td>
	                                <td>Draft</td>
	                                <td class="text-center"><a href="#">Quick Edit</a> | <a href="#">Edit</a> | <a href="#" title="delete"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
	                            </tr>
	                       
	                        </tbody>
	                    </table>
		                <div class="paginate pull-right">
		                    <?php //echo $data['user']->render(); ?>
		                </div>
	            	</div>
	            </div>
            </div>
        </div>
        

@stop
@section('style')
	{!! Html::style('css/client/list-finan.css') !!}
	{!! Html::style('css/dataTables.bootstrap.min.css') !!}
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
	
	$( window ).resize(function() {
		var screen = $(window).width();
		if(screen < 770){
			$('#box-user').addClass('table-responsive');
		}else{
			$('#box-user').removeClass('table-responsive');
		}
	});

	$(document).ready(function(){
		$("#table-user").dataTable({
        	aoColumnDefs: [
				  {
				     bSortable: false,
				     aTargets: [ 0, 4 ]
				  }
				]
        });
	});


	$("#table-user").on("click",".check-all:checked",function(){
		$(".check-user:checkbox:not(:checked)").click(); 
	});

	$('.check-all:not(:checked)').click(function(){
		
		$(".check-user:checkbox:checked").click(); 
	});

	$('.btn-delete').click(function(){
		alert('Delete');
	});
</script>

@stop
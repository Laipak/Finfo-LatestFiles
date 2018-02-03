@extends($app_template['backend'])

@section('content')
	<section class="content" id="list-user">
		<div class="row head-search">
			<div class="col-sm-6">
				<h2 style="margin:0;">Invoice List</h2>
			</div>
			<div class="col-sm-6">
				<div class="pull-right">
					<a href="#" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    	<i class="fa fa-pencil"></i> New Client
                	</a>
                	<a href="#" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    	<i class="fa fa-pencil"></i> Deleted
                	</a>
                	<a href="#" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    	<i class="fa fa-pencil"></i> Publish
                	</a>
                	<a href="#" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    	<i class="fa fa-pencil"></i> Unpublish
                	</a>
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
	                            <th>Client Name</th>	                            
	                            <th>Payment Method</th>
	                            <th>Invoice Date</th>
	                            <th>Due Date</th>
	                            <th style="width: 160px;">Quick Actions</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        @foreach($invoices as $invoice)
	                            <tr>
	                            	<td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
	                                <td></td>	                                                              
	                                <td>{{ $invoice->payment_method_id }}</td>
		                            <td>{{ $invoice->invoice_date }}</td>
		                            <td>{{ $invoice->due_date }}</td>
		                            <td class="text-center"> <a href="{{ URL::to('/admin/clients/invoices/detail/'.$invoice->id)}}">View</a> | <a href="#">Edit</a> | <a href="#"><i class="fa fa-trash-o fa-lg"></i></a></td>		                                
	                               
	                            </tr>
	                     	@endforeach
	                        </tbody>
	                    </table>
		                <div class="paginate pull-right">
		                    <?php //echo $data['client']->render(); ?>
		                </div>
	            	</div>
	            </div>
            </div>
        </div>

    </section>
@stop
@section('style')
	{!! Html::style('css/finfo/list-user.css') !!}
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

	$(function () {
        $("#table-user").dataTable();
    });
    
	$("#table-user").on("click",".check-all:checked",function(){
		$(".check-user:checkbox:not(:checked)").click(); 
	});

	$('.check-all:not(:checked)').click(function(){
		
		$(".check-user:checkbox:checked").click(); 
	});

</script>

@stop
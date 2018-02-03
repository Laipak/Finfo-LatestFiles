@extends($app_template['client.backend'])
@section('title')
Billing
@stop
@section('content')
	<section class="content" id="list-user">
		@if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">{{($status == 0)? "List of Upcoming Invoices" : "List of Past Invoices"}}</lable>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
	            <div class="box">
	            	<div id="box-user" class="box-body">
	            		<table id="table-user" class="table table-bordered table-striped">
	                        <thead>
	                        <tr class="table-header">
	                            <th>Invoices number</th>
	                            <th>Invoices date</th>
	                            <th>Due date</th>
                                    <th>Start date</th>
                                    <th>Expired Date</th>
	                            <th>Total</th>
								<th>Status</th>
								@if($status == 0)
								<th>Action</th>
								@endif
	                        </tr>
	                        </thead>
	                        <tbody>
		                        @foreach($data as $invoice)
		                            <tr>
                                                <td>
                                                	<a class="invoice-number" href="{{route('client.invoices.backend.detail', $invoice->id)}}">
                                                		{{ $invoice->invoice_number}}
                                                	</a>
                                                </td> 
                                                <td>{{ date('d-M-Y', strtotime($invoice->invoice_date))}}</td>
                                                <td>{{ date('d-M-Y', strtotime($invoice->due_date))}}</td>
                                                <td>{{ date('d-M-Y', strtotime($invoice->start_date))}}</td>
                                                <td>{{ date('d-M-Y', strtotime($invoice->expire_date))}}</td>
                                                <td>{{ $controller->PriceWithEx($invoice->amount , $invoice->curr_id)}}</td>
                                                <td>{!! $controller->getInovoiceStatus($invoice->status)!!}</td>
                                                @if($status == 0)
                                                    <td><a href="{{route('client.invoices.backend.checkout', $invoice->id)}}"><span class="label label-success">Pay Now</span></a></td>
		                            	@endif
		                            </tr>
		                        @endforeach
	                        </tbody>
	                    </table>
	            	</div>
	            </div>
            </div>
        </div>
    </section>
@stop
@section('style')
	{!! Html::style('css/client/list-user.css') !!}
	{!! Html::style('css/dataTables.bootstrap.min.css') !!}
	<style>
		.invoice-number{
			color: green !important;
		}
	</style>
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
	var	status = "{{$status}}";
	$('.active').removeClass('active');
	$('#my-invoice').addClass('active');
	if(status == 0){
		$('#m-upcoming').addClass('active');
	}else{
		$('#m-invoice-past').addClass('active');
	}
	
	$( window ).resize(function() {
		var screen = $(window).width();
		if(screen < 770){
			$('#box-user').addClass('table-responsive');
		}else{
			$('#box-user').removeClass('table-responsive');
		}
	});

	$(document).ready(function(){
		var screen = $(window).width();
		if(screen < 770){
			$('#box-user').addClass('table-responsive');
		}else{
			$('#box-user').removeClass('table-responsive');
		}
		$("#table-user").dataTable({
        	aoColumnDefs: [
				  {
				     bSortable: false,
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

	$("#table-user").on("click",".check-user:not(:checked)",function(){
		if($(".check-user:checkbox:checked").length <= 0){
			$('.check-all').prop('checked', false);
		}
		
	});
</script>

@stop
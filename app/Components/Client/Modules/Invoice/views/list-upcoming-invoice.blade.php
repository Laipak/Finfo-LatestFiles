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
                <lable class="label-title">List of Upcoming Invoices</lable>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
	            <div class="box">
	            	<div id="box-user" class="box-body">
	            		<table id="table-user" class="table table-bordered table-striped">
	                        <thead>
	                        <tr class="table-header">
                                    <th>Expire Date</th>
<!--	                            <th>Due date</th>-->
	                            <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
	                        </tr>
	                        </thead>
	                        @if($invoice_type == 'ever_paid')
		                        <tbody>
	                                    <tr>
	                                        @if (!empty($data))
	                                            <td>{{ date('d-M-Y', strtotime($data->expire_date)) }}</td>
	                                            <td>{{$exchage_rate['symbol']}} {{ number_format( round($data->amount * $exchage_rate['exchange_rate']), 2)}}</td>
	                                            <td>Unpaid</td>
	                                            <td><a href="{{ route('client.admin.upgrade.package', $data->package_id)}}" title='pay now' class="pay-now">Pay now</a></td>
	                                        @else
	                                            <td></td>
	                                            <td></td>
	                                            <td></td>
	                                            <td></td>
	                                        @endif
	                                    </tr>
		                        </tbody>
	                        @else
		                        <tbody>
	                                    <tr>
	                                        @if (!empty($data))
	                                            <td>{{ date('d-M-Y', strtotime($data->start_date)) }}</td>
	                                            <td>{{$exchage_rate['symbol']}} {{ number_format( round($data->amount * $exchage_rate['exchange_rate']), 2)}}</td>
	                                            <td>Unpaid</td>
	                                            <td><a href="{{ route('client.admin.upgrade.package', $data->package_id)}}" title='pay now' class="pay-now">Pay now</a></td>
	                                        @else
	                                            <td></td>
	                                            <td></td>
	                                            <td></td>
	                                            <td></td>
	                                        @endif
	                                    </tr>
		                        </tbody>
	                        @endif
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
		.pay-now{
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
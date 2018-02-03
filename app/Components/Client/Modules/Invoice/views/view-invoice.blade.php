
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FINFO - Invoice #{{$data['invoice']['invoice_number']}}</title>
	
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
    {!! Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    {!! Html::style('css/client/view-invoice.css') !!}
    <style type="text/css">
    	.table-h, .table-price{
    		border: 1px solid;
    		margin-top: 30px;
    	}
    	.th-h th, .table-price th{
    		background-color: #ccc;
    		border: 1px solid !important;
    	}
    	.table-h td, .table-price td{
    		border: 1px solid !important;
    	}
    	.table-date{
    		margin-top: 30px;
    	}
    	.table-date td{
    		padding: 0px !important;
    	}
    	.table-price th{
    		border: 1px solid black !important;
    		color: #ffffff;
    		font-weight: 600;
    	}
    	.price-foot{
    		background-color: #ccc;
    		text-align: right;
    	}
    	.table-price .content-price{
    		min-height: 230px;
    		padding-left: 30px;
    	}


    </style>
</head>
<body>
	<div class="container-fluid invoice-container" id="view-invoice">
		<div class="row">
			<img src="/img/finfo/imgs/finfo-logo-normal.png">

			<table class="table table-h">
				<tr class="th-h">
					<th>Invoiced To:</th>
					<th>Pay To:</th>
				</tr>
				<tr class="th-h">
					<td width="50%">
						<p><strong>{{$data['company']['company_name']}}</strong></p>
						<p>{{@$data['company']['address']}}</p>
					</td>
					<td width="50%">
						<p><strong>Finfo Pte Ltd</strong></p>
						<p>648A Geylang Roads</p>
						<p>Singapore 389578</p>
					</td>
				</tr>
			</table>

			<table class="table table-date">
				<tr>
					<td>
						<p><strong>Invoice #{{$data['invoice']['invoice_number']}}</strong></p>
						<p>Invoice Date: {{\Carbon\Carbon::parse($data['invoice']['invoice_date'])->format('d/M/Y')}}</p>
						<p>Due Date: {{\Carbon\Carbon::parse($data['invoice']['due_date'])->format('d/M/Y')}}</p>
					</td>
				</tr>
			</table>
		</div>

		<div class="row">

			<table class="table table-price">
				<tr class="th">
					<th style="padding-left: 38px;">Description</th>
					<th style="text-align: right;">Total ({{$data['currency']['code']}})</th>
				</tr>
				<tr class="th">
					<td width="80%">
						<div class="content-price">
							<p>{{ucfirst($data['package']['package_name'])}} Plan</p>
							<p>One month subscription:</p>
							<p>{{\Carbon\Carbon::parse($data['csp']['start_date'])->format('d/M/Y')}} till {{\Carbon\Carbon::parse($data['csp']['expire_date'])->format('d/M/Y')}}</p>
						</div>
					</td>
					<td width="20%" style="text-align: right;">
						<p>
						@if(isset($data['currency']['symbol']))
							
							@if($data['currency']['id'] == 11)
								{{$data['currency']['symbol']}} {{number_format(round($data['package']['package_price']), 2 , ".", ",")}}
							@else
								{{$data['currency']['symbol']}} {{number_format(round($data['package']['package_price'] * $data['currency']['exchange_rate']), 2 , ".", ",")}}

							@endif
						@else
							$ {{number_format(round($data['package']['package_price']),2)}}
						@endif
						</p>
					</td>
				</tr>
				<tr class="price-foot">
					<td>
						<p>Total({{$data['currency']['code']}})</p>
					</td>
					<td>
						@if(isset($data['currency']['symbol']))
							@if($data['currency']['id'] == 11)
								{{$data['currency']['symbol']}} {{number_format(round($data['package']['package_price']), 2 , ".", ",")}}
							@else
								{{$data['currency']['symbol']}} {{number_format(round($data['package']['package_price'] * $data['currency']['exchange_rate']), 2 , ".", ",")}}

							@endif
							
						@else
							$ {{number_format(round($data['package']['package_price']),2)}}
						@endif
					</td>
				</tr>
			</table>
			<br>
			<p>For billings enquiries please email Finfo at billings@finfo.com</p>

		</div>

		<br><br>

		
		<div class="pull-right btn-group btn-group-sm hidden-print">
			<a href="javascript:window.print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
			<a href="{{\Route::getRoutes()->hasNamedRoute($data['dl_route_name'])?route($data['dl_route_name'], $data['invoice']['id']):'#'}}" class="btn btn-default"><i class="fa fa-download"></i> Download</a>
		</div>
	</div>
	<p class="text-center hidden-print"><a href="{{$data['url_back']}}">&laquo; Back </a></a></p>
</body>
</html>

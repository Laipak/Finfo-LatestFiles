@extends($app_template['backend'])

@section('content')
	<section class="content" id="list-user">
	
		<div class="row head-search">
			<div class="col-sm-6">
				<h2 style="margin:0;">List of Revenue on {{date(($view == 'month')?'F Y':'Y', strtotime($data['filter']['current_date']))}}</h2>
			</div>
			<div class="col-sm-6">
					<div class="col-md-7 content-view">
						<div  class="form-group">
							<lable class="col-xs-4 control-labe" style="margin-top: 6px; padding-left:0px;padding-right:0px;"><strong>Filter By:</strong></lable>
							<div class="col-xs-8" style="padding-right: 0px;">
								<select class="form-control" onchange="location = this.options[this.selectedIndex].value;">
								  <option {{($view == 'month')?'selected':''}} value="{{route('finfo.admin.revenue.list', 'month')}}">Month</option>
								  <option {{($view == 'year')?'selected':''}} value="{{route('finfo.admin.revenue.list', 'year')}}">Year</option>
								</select>
							</div>
							<div class="clearfix"></div>
						</div>
		      		</div>
					<div class="col-md-5 content-export">
						<a class="btn btn-default" href="{{route('finfo.admin.revenue.export-excel', [$data['filter']['current_date'], $view])}}"><i class="fa fa-download fa-lg"></i> Export to Excel</a>
	                </div>
				</div>
   
      	</div>

      	<div class="row" style="margin-top: 30px;margin-bottom: 30px;">
      		<div class="col-xs-6">
      			<a class="link-next" href="{{route('finfo.admin.revenue.list', $view)}}?date={{$data['filter']['prevouis']}}">
      				<i class="fa fa-arrow-left"></i> {{date(($view == 'month')?'F':'Y', strtotime($data['filter']['prevouis']))}}
      			</a>
      		</div>
      		<div class="col-xs-6">
      			<a class="link-next pull-right" href="{{route('finfo.admin.revenue.list', $view)}}?date={{$data['filter']['next']}}">
      				{{date(($view == 'month')?'F':'Y', strtotime($data['filter']['next']))}} <i class="fa fa-arrow-right"></i>
      			</a>
      		</div>
      	</div>
      	<div class="row">
      		<div class="col-md-12">
      			<div id="chart_div" style="width: 100%; height: 500px;"></div>
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
	                            <th>Date</th>
	                            <th>New Invoice</th>
	                            <th>Paid Invoice</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        @foreach($data['value'] as $invoice)
	                            <tr>
	                            	<td width="15px" class="text-center"><input class="check-user" name="check[]" value="" type="checkbox"></td>
	                            	@if($view == 'year')
	                            		<td>{{date('F', mktime(0, 0, 0, $invoice->date, 10))}}</td>
	                            	@else
 										<td>{{date("D, d/m/Y", strtotime($invoice->date))}}</td>
	                            	@endif
	                                
	                                <td>{{$invoice->unpaid}}</td>
	                                <td>{{$invoice->paid}}</td>
	                            </tr>
	                        @endforeach
	                        </tbody>
	                    </table>
	            	</div>
	            </div>
      		</div>
      	</div>
      	
      	<input type="hidden" name="date" id="date" value="{{$data['filter']['current_date']}}">
      	<input type="hidden" name="view" id="view" value="{{$view}}">
    </section>
@stop
@section('style')
	{!! Html::style('css/finfo/list-user.css') !!}
	{!! Html::style('css/dataTables.bootstrap.min.css') !!}
	<style type="text/css">
		.link-next{
			text-decoration: none;
			cursor: pointer;
			font-size: 20px;
		}
		.content-export, .content-view{
			text-align: right;
			padding-left: 0px;
			padding-right: 0px;
		}
		@media (max-width: 991px){
			.content-export, .content-view{
				margin-top: 30px;
			}
			.link-next{
				font-size: 17px;
			}
		}

	</style>
@stop

@section('script')
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    
    function getData(){
    	var result = '';
    	var date = $('#date').val();
    	var view = $('#view').val();
    	$.ajax({
	        url: '/admin/revenue/ajax-get-data',
	        async: false,
	        data: { 'date': date, 'view': view},
	        success: function(data) {
	        		result = data;
	        		//callback(result);
	        	}
    	});
    	return result;
    }

    google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
      	var value = getData();
      	console.log(value);
      	// value = [
      	// 		['date', 'paid', 'unpaid', 'overdue', 'cancel'],
      	// 		[0,0,0,0,0]

      	// 	];
		var data = google.visualization.arrayToDataTable(value);
        var options = {
          // title: 'Company Performance',
          // hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
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
				    aTargets: [ 0],
				  }
				],
			"pageLength": 50,
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

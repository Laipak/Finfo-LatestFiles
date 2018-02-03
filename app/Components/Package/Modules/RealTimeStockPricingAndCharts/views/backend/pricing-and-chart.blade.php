@extends($app_template['client.backend'])
@section('title')
Pricing and Charts
@stop
@section('content')
<meta http-equiv="refresh" content="300000">
	<section class="content" id="pricing">
        @if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
    
        <div class="row head-search">
            <div class="col-sm-12">
                <lable class="label-title">Pricing and Charts</lable>
            </div>
        </div>

    <h4 class="sub-title">Data API & Chart API</h4>
    <label>Insert new Data API <a href="" data-toggle="tooltip" data-placement="right" title="This API gets the latest stock information from SGX."><i class="fa fa-question-circle"></i></a></label>
    {!! Form::open(array('route' => 'package.admin.stock.save-new-api')) !!}
    	<div class="row">
	        <div class="form-group">
	            <div class="col-md-12 col-sm-12">
	                <input type="text" class="form-control" value="{{$setting->api_url}}" name="api_url" id="" >
	                {!! $errors->first('api_url', '<span class="help-block">:message</span>') !!}
	            </div>
	        </div>
	    </div>
     <label>Insert new Chart API <a href="" data-toggle="tooltip1" data-placement="right" title="This API will display the stock chart from SGX."><i class="fa fa-question-circle"></i></a></label>
  	    <div class="row">
	        <div class="form-group">
	            <div class="col-md-12 col-sm-12">
	                <input type="text" class="form-control" value="{{$setting->stock_url}}" name="api_charturl" id="" >
	                {!! $errors->first('api_charturl', '<span class="help-block">:message</span>') !!}
	            </div>
	        </div>
	    </div>
 	   <div class="row">
	        <div class="form-group">
	           
	            <div class="col-md-3 col-sm-3">
	                <button type="submit" class="btn btn-primary btn-confirm">Save</button>
	            </div>
	        </div>
	    </div>
 {!! Form::close() !!}
    
    <h4 class="sub-title">Stock Chart</h4>
      <div class="row">
        <div class="col-md-12">
        <!--    <div id="chartContainer" style=" width: 100%;">
            </div>-->
            <div id="" style="overflow:scroll;">
            <iframe frameBorder="0" height="465" width="955" style="overflow: hidden;margin-left: 130px;" src="<?php echo $setting->stock_url; ?>"></iframe>
            </div>
        </div>
    </div>

  <!--  <h4 class="sub-title">Stock Pricing</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Price</label>
                <input type="text" disabled="" class="form-control" id="stock" value="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Volume</label>
                <input type="text" disabled="" class="form-control" id="volume" value="">
            </div>
        </div>
        <!--   <div class="col-md-4">
            <div class="form-group">
                <label>Change</label>
                <input type="text" disabled="" class="form-control" id="" value="{{(isset($stock_value[0]['Movement'])) ? $stock_value[0]['Movement'] : '00.00' }}">
            </div>
        </div> -->

      


    </div> -->
  <!--  <label>Last Update: {{(isset($stock_value[0]['AsAt']))? date("D, d-M-Y | h:i a", strtotime($stock_value[0]['AsAt'])) : ''}}</label>

    <div class="row">
        <div class="form-group">
            <div class="col-md-9 col-sm-9">
                <hr>
            </div>
            <div class="col-md-3 col-sm-3">
	                <button type="submit" class="btn btn-primary btn-confirm">Confirm</button>
	            </div>
        </div>
    </div>

    <h4 class="sub-title">Stock ID</h4>
    <label>Stock ID<i class="fa fa-question-circle"> </i></label>
    {!! Form::open(array('route' => 'package.admin.stock.stockid')) !!}
	    <div class="row">
	        <div class="form-group">
	            <div class="col-md-9 col-sm-9">
	                <input type="text" class="form-control" name="stockid" id=""  value="{{$setting->stockid}}" >
	                {!! $errors->first('stockid', '<span class="help-block">:message</span>') !!}
	            </div>
	            <div class="col-md-3 col-sm-3">
	                <button type="submit" class="btn btn-primary btn-confirm">Confirm</button>
	            </div>
	        </div>
	    </div> -->
    {!! Form::close() !!}








    </section>
@stop
@section('style')
	{!! Html::style('/css/client/pricing-charts.css') !!}
        <style>
/*            #frm_remove_api .btn {
                border-radius: 0px !important;
            }*/
        </style>
@stop

@section('script')
    {!! Html::script('js/canvasjs.min.js') !!}
    
    
<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
     $('[data-toggle="tooltip1"]').tooltip(); 
});
</script>    
    
    
<script type="text/javascript">
    $('.active').removeClass('active');
    $('.real_time_st').addClass('active');
    $('.real_time_st_list').addClass('active');
    
    window.onload = function () {
        $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });
        loadChart();

        setInterval(function(){
          loadChart();
        }, 1200000);

    }

    function loadChart(){
        var chart_type = "{{$setting->chart_template}}";
        var chart_color = "{{$setting->highlight_color}}";
        var text_color = "{{$setting->text_color}}";
        var filter_value = "{{$setting->view_option}}";
        var price_value = [];
        var volume_value = [];
        $.ajax({
            url: '/admin/stock/get-data',
            async: false,
            type: "POST",
            data: { 'filter_value': filter_value},
                success: function(data) {
                    //console.log(data);
                    //value = data;
                    var price_arr = [];
                    var volume_arr = [];
                    for (var i in data){
                        price_arr = {
                                'x' : new Date(data[i].x),
                                'y' : data[i].price
                                };
                        volume_arr = {
                                'x' : new Date(data[i].x),
                                'y' : data[i].volume
                                };
                        price_value[i] = price_arr; 
                        volume_value[i] = volume_arr; 
                  
                         $('#stock').val(data[i].price);
                         $('#volume').val(data[i].volume);
                        
                    }
                    
                    //console.log(price_arr);
                    }
            });
        var chart = new CanvasJS.Chart("chartContainer",
            {      
              // title:{
              //   text: "Twitter Mentions for iPhone and Samsung"
              // },
              zoomEnabled: true, 
              animationEnabled: true,
              axisY:{ 
                title: "Price",
                //includeZero: false,
                suffix : "$",
                lineColor: '#9CCFD7',    
              },
              axisY2:{ 
                title: "Volume",
                //includeZero: false,
                suffix : " m",
                lineColor: "#E1A4A2"
              },
              axisX:{ 
                gridThickness: 1,
                gridColor: "lightgrey",
                labelFontColor: text_color,
              },

              toolTip: {
                shared: "true"
              },
              data: [
              {        
                type: chart_type, 
                name: "Stock",
                color: '#9CCFD7',
                markerSize: 0,        
                dataPoints: price_value,
              },
              {        
                type: chart_type, 
                name: "Volume",
                color: '#E1A4A2',
                axisYType: "secondary",
                markerSize: 0,        
                dataPoints: volume_value,
              },
             
              ]
            });
        chart.render();
    }

 $('#btn-refresh').click(function(){
 	location.reload();
 });
 $('#remove_api').click(function(){
    if(confirm('Are you sure you want to remove this API?')){
        $('#frm_remove_api').submit();
    }
 });

 </script>

@stop
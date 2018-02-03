@extends($app_template['client.frontend'])
@section('content')
  @if($app_template['client.frontend'] == 'resources.views.templates.client.template2.frontend')
  <div data-backdrop="static" class="modal modal-loading" id="modal-loading">
    <div class="modal-dialog">
        <div class="modal-content loading-content">
            <div class="modal-body">
                <img id="img-loader" src="/img/profile/loader.gif">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    <section class="content" id="stock1">
        <div class="row main-title">
            <div class="col-md-12">
                Stock Info
            </div>
        </div>

        <div class="row">
            <div class="content-stock">

                <div class="stock-pri">
                    Stock price ({{strtoupper($symbol)}}) &nbsp;&nbsp;&nbsp; 20 min delay

                    <div class="pull-right refresh">

                    </div>
                </div>
                <div class="col-sm-12 col-md-10 col-md-offset-1 stock-chart table-responsive">
                  <div class="loading hidden">
                    <img id="img-loader" src="/img/loader.gif">
                  </div>
                    <div class="text-header">
                        <div class="text" style="color: #272c30;">
                            Zoom
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month text-active" data-id="3">
                            3 months
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="6">
                            6 months
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="12">
                            1 year
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="24">
                            2 years
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="60">
                            5 years
                          </a>
                        </div>
                    </div>
                    
                    <div id="chartContainer" style="height: 300px; min-width: 500px;">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="stock-number">
                    <div class="row">
                        <p class="title">Stock price</p>
                        <div class="col-md-4">
                            <div class="box-num-pr">
                                <h2 class="l-close">${{(isset($stock[0]['Close']))? $stock[0]['Close'] : '00.00'}}</h2>
                            </div>
                            <div class="box-num-pr text-center">
                                <label class="rate"><i class="icon-move fa fa-caret-{{(isset($stock[0]['Movement']) && $stock[0]['Movement'] < 0)? 'down' : 'up' }}"></i> <span class="l-movement"> {{(isset($stock[0]['Movement']))? str_replace('-', '',$stock[0]['Movement']) : '00.00' }}</span></label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="number-left">
                                <div class="row">
                                    <p>Today <label class="l-date">{{(isset($stock[0]['AsAt']))? date("d M Y h:i a", strtotime($stock[0]['AsAt'])) : ''}}</label></p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 content-box-num">
                                        <div class="box-number"><label>High:</label></div>
                                        <div class="box-number l-high">${{(isset($stock[0]['High']))? $stock[0]['High'] : '00.00'}}</div>
                                        <div class="box-number"><label>Low:</label></div>
                                        <div class="box-number l-low">${{(isset($stock[0]['Low']))? $stock[0]['Low'] : '00.00'}}</div>
                                    </div>
                                    <div class="col-md-6 content-box-num">
                                        <div class="box-number"><label>Volume:</label></div>
                                        <div class="box-number l-volume">{{(isset($stock[0]['Volume']))? number_format($stock[0]['Volume'] / 1000000, 2) : '00.00'}} M</div>
                                        <div class="box-number"><label>Currency:</label></div>
                                        <div class="box-number">$AUD</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <input type="hidden" id="filter_value" value="3">
    </section>   
  @else
    <section class="content" id="stock">
        <h3 class="main-title">Stock Information</h3>

        <div class="row">
            <div class="col-sm-10">
                <div class="finfo-box">
                    <table class="table black-table stock-list">
                    <tr>
                        <th>Last</th>
                        <th>Change</th>
                        <th>Volume</th>
                        <th>High</th>
                        <th>Low</th>
                    <tr>
                    <tr>
                        <td><div class="l-close">${{(isset($stock[0]['Close']))? $stock[0]['Close'] : '00.00'}}</div></td>
                        <td><label class="rate"><i class="icon-move fa fa-caret-{{(isset($stock[0]['Movement']) && $stock[0]['Movement'] < 0)? 'down' : 'up' }}"></i> <span class="l-movement"> {{(isset($stock[0]['Movement']))? str_replace('-', '',$stock[0]['Movement']) : '00.00' }}</span></label></td>
                        <td><div class="l-volume">{{(isset($stock[0]['Volume']))? number_format($stock[0]['Volume'] / 1000000, 2) : '00.00'}} M</div></td>
                        <td><div class="l-high">${{(isset($stock[0]['High']))? $stock[0]['High'] : '00.00'}}</div></td>
                        <td><div class="l-low">${{(isset($stock[0]['Low']))? $stock[0]['Low'] : '00.00'}}</div></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
        <label class="stock-date l-date">{{(isset($stock[0]['AsAt']))? date("d M Y h:i a", strtotime($stock[0]['AsAt'])) : ''}}</label>
        

        <div class="row content-stock">
            <div class="col-sm-10">
                <div class="finfo-box">
                    <div class="finfo-box-header with-bg">
                        Stock Chart
                    </div>
                    <div class="col-sm-12 stock-chart table-responsive">
                    <div class="text-header">
                        <div class="text" style="color: #272c30;">
                            Zoom
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month text-active" data-id="3">
                            3 months
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="6">
                            6 months
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="12">
                            1 year
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="24">
                            2 years
                          </a>
                        </div>
                        <div class="text">
                          <a href="#" class="filter_month" data-id="60">
                            5 years
                          </a>
                        </div>
                    </div>
                    
                    <div id="chartContainer" style="height: 300px; min-width: 500px;">
                    </div>
                </div>
                </div>
            </div>
        </div>

    </section>

  @endif
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Stock information</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
	{!! Html::style('css/client/stock.css') !!}
  <style type="text/css">
      .filter_month{
        color: <?php echo $chart_setting->button_color ?> !important;
      }
      #stock1 .stock-chart .text-header .text a.text-active{
        color: <?php echo $chart_setting->highlight_color ?> !important;
      }
      .content-stock{
        background-color: <?php echo $chart_setting->background_color ?> !important;
      }
  </style>
@stop

@section('script')
{!! Html::script('js/canvasjs.min.js') !!}
<script type="text/javascript">
    $('.menu-active').removeClass('menu-active');
    $('#stock').addClass('menu-active');
</script>
<script type="text/javascript">
  var refresh_frequency = "{{$chart_setting->refresh_frequency}}";

  loadChart('3');

  $('.filter_month').click(function(){
      showLoading();
      $('.text-active').removeClass('text-active');
      $(this).addClass('text-active');
      var value = $(this).data('id');
      $('#filter_value').val(value);
      setTimeout(function() { loadChart(value); }, 1000);
  });

  
  setInterval(function(){
    value = $('#filter_value').val();
    loadChart(value);
  }, 1200000);

  setInterval(function(){
    loadData();
  }, refresh_frequency);

  $('.refresh').click(function(){
      value = $('#filter_value').val();
      showLoading();
      setTimeout(function() { loadChart(value); }, 1000);
  });


  function loadChart(filter_value){


    //var filter_value = $('#filter_value').val();
    var chart_type = "{{$chart_setting->chart_template}}";
    var chart_color = "{{$chart_setting->highlight_color}}";
    var background_color = "{{$chart_setting->background_color}}";
    var text_color = "{{$chart_setting->text_color}}";
    var border_thickness = "{{$chart_setting->border_thickness}}";
    var price_value = [];
    var volume_value = [];
    $.ajax({
      url: '/admin/stock/get-data',
      async: false,
      type: "POST",
      data: { 'filter_value' : filter_value},
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
              }
            }
      });

    var chart = new CanvasJS.Chart("chartContainer",
    {      
      // title:{
      //   text: "Twitter Mentions for iPhone and Samsung"
      // },
      animationEnabled: true,
      zoomEnabled: true, 
      backgroundColor: background_color,
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
        gridThickness: border_thickness,
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
    hideLoading();
  }

  function loadData(){
    $.ajax({
      url: '/admin/stock/get-price',
      async: false,
      type: "POST",
      data: {},
          success: function(data) {
            if(data.Close){
              $('.icon-move').removeClass('fa-caret-up');
              $('.icon-move').removeClass('fa-caret-down');

              //console.log(data);
              $('.l-close').html('$'+data.Close);
              $('.l-movement').html(data.Movement);
              $('.l-date').html(data.AsAt);
              $('.l-high').html('$'+data.High);
              $('.l-low').html('$'+data.Low);
              $('.l-volume').html(data.Volume+' M');
              $('.icon-move').addClass('fa-caret-'+data.move_icon);
            }
          }
      });
  }

  function showLoading()
  {
    $('.loading').removeClass('hidden');
    $('#modal-loading').modal('show');
  }

  function hideLoading()
  {
    $('.loading').addClass('hidden');
  }
 </script>
@stop
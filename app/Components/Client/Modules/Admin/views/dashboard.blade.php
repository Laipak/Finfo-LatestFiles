
@extends($app_template['client.backend'])

@section('title')
Dashboard
@stop
@section('content')
	<section class="content" id="dashboard">
        @if($is_expire)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Your account have been expired. Please renew your account.
            </div>
        @endif
      <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="content-visit">
              <div class="col-xs-6 col-sm-3 col-xs-12 box-visit">
                <div class="small-box-head">
                  <!-- small box -->
                  <h4>First Time Visitor</h4>
                  <div class="visitor">
                    <h1><b>1</b></h1>
                    <!-- <h4>02/03</h4> !-->
                  </div>
                  <div class="col-sm-12 btn-view">
                    <a href="#"><h5>view site stats</h5></a> 
                  </div>
                </div>
              </div><!-- ./col -->
              
              <div class="col-xs-6 col-sm-3 col-xs-12 box-visit">
                <div class="small-box-head">
                  <!-- small box -->
                  <h4>Subscribers</h4>
                  <h1><b>{{(isset($subscribe['all_subscribe']) && !empty($subscribe['all_subscribe']))? $subscribe['all_subscribe'] : "-"}}</b></h1>
                  <h4>Total Subscribers</h4>
                  <h1><b>{{(isset($subscribe['daily_subscribe']) && !empty($subscribe['daily_subscribe']))? $subscribe['daily_subscribe'] : "-"}}</b></h1>
                  <h4>Daily Subscribers</h4>
                  <div class="col-sm-12 btn-blast btn-sub">
                    <!--<a href=""><h5>blast newsletter</h5></a> !-->
                    @if (Session::get('set_package_id') == 3)
                        <a href="{{route('package.admin.email-alerts')}}"><h5>Subscribers detail</h5></a>
                    @else
                        <a href="#"><h5>Subscribers detail</h5></a>
                    @endif
                  </div>
                </div>
              </div><!-- ./col -->
             
              <div class="col-xs-6 col-sm-3 col-xs-12 box-visit">
                <div class="small-box-head">
                  <!-- small box -->
                  <h4>Subscription Details</h4>
                  <h4><b>-</b></h4>
                </div>
              </div><!-- ./col -->
             
              <div class="col-xs-6 col-sm-3 col-xs-12 box-visit">
                <div class="small-box-head">
                  <!-- small box -->
                  <h4>Account Details</h4>
                  <h1><b>{{(Session::has('package_name'))?Session::get('package_name'): ''}}</b></h1>
                  <h4>Package</h4>
         
                  @if($is_expire)
                    <div class="col-sm-12 btn-renew">
                      <a href="{{(isset($package_id))? route('client.admin.upgrade.package', $package_id) : ''}}"><h5>renew subscription</h5></a>
                    </div>
                  @else
                    @if(Session::has('package_name') && Session::get('package_name') == 'Enterprise')
                        <div class="col-sm-12 btn-renew btn-upgrade-disabled">
                          <a href="{{route('client.admin.package')}}"><h5>Package detail</h5></a>
                        </div>
                    @else
                        <div class="col-sm-12 btn-renew btn-upgrade-disabled">
                          <a href="{{route('client.admin.package')}}"><h5>upgrade</h5></a>
                        </div>
                    @endif
                  @endif
                </div>
              </div><!-- ./col -->
            </div>
          </div><!-- /.row -->

         
          <div class="row">
               @if(isset($setting->api_url) && !empty($setting->api_url) && Session::get('set_package_id') != 1 )
            <!-- Stock Pricing -->
            <div class="col-md-4">
              <div class="box box-solid box-stock">
                <div class="box-header with-border">
                  <h4 class="box-title">Stock Pricing</h4>
                  <a href="/admin/stock"><div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div></a>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <p class="l-date">{{(isset($stock[0]['AsAt']))? date("d M Y | h:i a", strtotime($stock[0]['AsAt'])) : ''}}</p>
                  <h1 class="l-close">PRICE: ${{$result}}</h1>
                  <hr>
                  <p>Volume</p>
                  <h1 class="l-volume">{{$total}} M</h1>
                  <hr>
                  <p>Change</p>
                  <h1 class="change-rate"><!--<i class="icon-move fa fa-caret-{{(isset($stock[0]['Movement']) && $stock[0]['Movement'] < 0)? 'down' : 'up' }}"></i>--> <span class="l-movement">{{$changes}}</span></h1>
                  <hr>
                  <div class="row">
                    <div class="col-xs-6">
                      <p>High</p>
                      <h1 class="l-high">${{$high}}</h1>
                    </div>
                    <div class="col-xs-6 low-stock">
                      <p>Low</p>
                      <h1 class="l-low">${{$low}}</h1>
                    </div>
                  </div>
                </div><!-- /.box-body -->
              </div>
            </div><!-- End Stock Pricing -->
             @else
            <!-- Stock Pricing -->
            <div class="col-md-4">
              <!-- Stock Pricing -->
                <div class="box box-solid">
                  <div class="box-header with-border">
                    <h4 class="box-title">Stock Chart</h4>
                    <a href="/admin/stock"><div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div></a>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                    <!--<div id="chartContainer" style="width: 100%;">-->
                    <p style="padding-top:100px;padding-bottom:140px;">Data API is not configured, please configure here <br><br>
                    <a class="btn btn-primary btn-overwrite-cancel" href="{{route('package.admin.stock')}}">Click here</a></p>
                
                    </div>
                  </div><!-- /.box-body -->
              </div><!-- End Stock Pricing -->
              @endif
              
              @if(isset($setting->stock_url) && !empty($setting->stock_url) && Session::get('set_package_id') != 1 )
            <div class="col-md-8">
              <!-- Stock Pricing -->
                <div class="box box-solid">
                  <div class="box-header with-border">
                    <h4 class="box-title">Stock Chart</h4>
                    <a href="/admin/stock"><div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div></a>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                    <!--<div id="chartContainer" style="width: 100%;">-->
                    <iframe frameborder="0" height="330" width="675" style="overflow: hidden;" src="<?php echo $setting->stock_url; ?>"></iframe>
                    </div>
                  </div><!-- /.box-body -->
              </div><!-- End Stock Pricing -->
           
          </div><!-- /.row -->
          @else
         
            <!-- Stock Pricing -->
            <div class="col-md-8">
              <!-- Stock Pricing -->
                <div class="box box-solid">
                  <div class="box-header with-border">
                    <h4 class="box-title">Stock Chart</h4>
                    <a href="/admin/stock"><div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div></a>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                    <!--<div id="chartContainer" style="width: 100%;">-->
                    <p style="padding-top:100px;padding-bottom:140px;">Chart API is not configured, please configure here <br><br>
                    <a class="btn btn-primary btn-overwrite-cancel" href="{{route('package.admin.stock')}}">Click here</a></p>
                
                    </div>
                  </div><!-- /.box-body -->
              </div><!-- End Stock Pricing -->
          @endif

          <div class="row">
            <div class="col-md-8">
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title">Visitor Chart</h4>
                  <a href="#"><div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div></a>
                </div>
                <!-- /.box-header -->
  
                <div class="box-body table-responsive">
                    <div id="embed-api-auth-container"></div>
                    <div id="chart-container"></div>
                    <div id="view-selector-container"></div>
<!--                    <div id="chartContainer1" style="height: 300px; width: 100%;"></div>-->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          <div class="col-md-4">
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title">Upcoming invoice</h4>&nbsp;
                  <a href="/admin/invoices/0">
                    <div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div>
                  </a>
                </div><!-- /.box-header -->
                <div class="box-body upcoming-invoice">
                  <div  style="height:300px;overflow:auto;">
                    <table width="100%">
                      <tr>
                          <th>Due Date</th>
                          <th>Price</th>
                      </tr>
                      @if(isset($upcoming_invoice->expire_date))
                      <tr>
                        <td>
                          {{date('d F, Y', strtotime($upcoming_invoice->expire_date))}}
                        </td>
                        <td>$ {{ number_format($upcoming_invoice->amount, 2)}}</td>
                      </tr>
                      @endif
                       
                    </table>
                  </div>
                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
          </div>
         </div>
         <div class="row">
            <div class="col-md-6">
                <div class="box box-solid">
                  <div class="box-header with-border">
                    <h4 class="box-title">Broadcast schedule</h4>&nbsp;
                    <a href="{{route('package.admin.newsletter-broadcast.email-seed-list')}}">
                      <div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div>
                    </a>
                  </div><!-- /.box-header -->
                  <div class="box-body upcoming-invoice">
                    <div  style="height:300px;overflow:auto;">
                      <table width="100%">
                        <tr>
                            <th>Subject</th>
                            <th>Newsletter type</th>
                            <th>Broadcast date</th>
                            <th>Broadcast time</th>
                        </tr>
                        @foreach($broadcast as $bro)
                        <tr>
                          <td><a href="{{route('package.admin.newsletter-broadcast.detail', $bro->newsletter_id)}}"> {{$bro->subject}} </a></td>
                          <td>{{$broadcastc_controller->getNewsType($bro->newsletter_type)}}</td>
                          <td>
                            {{ date('d F, Y', strtotime($bro->broadcast_date))}}
                          </td>
                          <td>{{ date('h:i A', strtotime($bro->broadcast_time))}}</td>
                        </tr>
                        @endforeach
                         
                      </table>
                    </div>
                   
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
         </div>
         <div>
    </section>
@stop
@section('style')
	{!! Html::style('css/client/dashboard.css') !!}
    <style type="text/css">
        .notification {
          padding: 0;
        }

        .notification ul {
          text-align: left;
        }

        .notification td {
          padding: 10px;
        }

        .notification .td-id {
          color:#aaa;
          background-repeat:no-repeat;
        }

        .box-body ::-webkit-scrollbar {
            width: 8px;
        }
         
        /* Handle */
        .box-body ::-webkit-scrollbar-thumb {
            background: rgba(102,102,102,0.8); 
        }

        .box-body ::-webkit-scrollbar-track-piece  {
          background: #aaa;
        }
        .canvasjs-chart-credit{
          display: none;
        }
        td{
          text-align: left;
        }
        .btn-upgrade-disabled{
          color: #fff;
          background-color: #5bc0de;
          border-color: #46b8da;
        }
    </style>
@stop

@section('script')
	{!! Html::script('js/canvasjs.min.js') !!}
  <script>
      $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':'{!! csrf_token() !!}'
            }
        });
      
        $(document).ready(function(){
            var refresh_frequency = "{{$setting->refresh_frequency}}";
            
            LoadChart();

            setInterval(function(){
              LoadChart();
            }, 1200000);

            setInterval(function(){
              loadData();
            }, refresh_frequency);
        });

        function LoadChart(){
          var chart_type = "{{$setting->chart_template}}";
          var chart_color = "{{$setting->highlight_color}}";
          var filter_value = "{{$setting->view_option}}";
          var text_color = "{{$setting->text_color}}";
          var border_thickness = "{{$setting->border_thickness}}";
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
                      }
                      
                      //console.log(value);
                      }
              });

            var chart = new CanvasJS.Chart("chartContainer",
                {
                                   // title:{
                  //   text: "Twitter Mentions for iPhone and Samsung"
                  // },
                  animationEnabled: true,
                  zoomEnabled: true, 
                  axisY:{ 
                    title: "Price",
                    //includeZero: false,
                    suffix : "$",
                    lineColor: "#9CCFD7",       
                  },
                  axisY2:{ 
                    title: "Volume",
                    //includeZero: false,
                    suffix : " m",
                    lineColor: "#E1A4A2",
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
                    $('.l-close').html('PRICE: $'+data.Close);
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
    </script>

@stop

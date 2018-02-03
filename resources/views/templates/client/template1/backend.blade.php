<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Admin| {{ucfirst(Session::get('company_name'))}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="icon" type="image/png" href="/{{$favicon}}">

  



    {!! Html::style('css/client/admin.css') !!}
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
    {!! Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    {!! Html::style('css/skins/_all-skins.min.css') !!}
    {!! Html::style('css/client/customize.css') !!}
    
    {!! Html::style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/css/select2.min.css') !!}
    
     {!! HTML::style('client/css/switchery.min.css') !!}
    
   

    @yield('style')
    
    <script type="text/javascript">
        var baseUrl = "{{ URL::to('/') }}";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
      (function(w,d,s,g,js,fs){
        g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
        js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
        js.src='https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
      }(window,document,'script'));
    </script>
    <script type="text/javascript">
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', '{!! isset($setting->google_analytic) ? $setting->google_analytic : $companySetting->google_analytic !!}', 'auto');
      ga('send', 'pageview');
    </script>
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper">
	
      @include('resources.views.templates.client.template1.includes.header')
      @include('resources.views.templates.client.template1.includes.menu-left') 
	  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       
        <section class="content-header main-header">
           
            <nav class="navbar contain-nav" role="navigation">
              <div class="col-xs-8 col-sm-8 col-md-8 nav-icon">
                <a href="#" class="sidebar-toggle big-menu" data-toggle="offcanvas" role="button" style="text-decoration: none;">

                </a>
                <h2 class="title-top">
                  @yield('title')
                </h2>
              </div>

              <div class="col-xs-4 col-sm-4 col-md-4 pull-right nav-noti">
                <div class="navbar-custom-menu">
                  <ul class="nav navbar-nav">
 
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user noti-icon"></i>
                        <i class="fa fa-caret-down noti-icon"></i>
                      </a>
                      <ul class="dropdown-menu" id="user-profile">
                        <!-- User image -->
                        <li class="user-header">
                          @if(Auth::check())
                            @if(Auth::user()->profile_picture == '')
                              <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                            @else
                              <img src="/{{Auth::user()->profile_picture}}" class="img-circle" alt="User Image">
                            @endif

                            <p>
                              {{Auth::user()->first_name." ".Auth::user()->last_name}}
                            </p>
                          @endif
                          
                        </li>
                       
                        <!-- Menu Footer-->
                        <li class="user-footer">
                          <div class="pull-left">
                            <a href="{{route('client.admin.profile')}}" class="btn btn-default btn-flat">Profile</a>
                          </div>
                          <div class="pull-right">
                            <a href="{{route('client.logout')}}" class="btn btn-default btn-flat">Sign out</a>
                          </div>
                        </li>
                      </ul>
                    </li>

                  </ul>
                </div>
              </div>
            </nav><!-- ./col -->
        </section>

        @yield('content')
    </div>

    <!-- jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/js/select2.min.js"></script>



    {!! Html::script('js/jquery-ui.min.js') !!}

    <!-- bootstrap-->
    {!! Html::script('js/bootstrap.min.js') !!}

    <!-- AdminLTE App -->
    {!! Html::script('js/app.min.js') !!}
    
    {!! Html::script('client/js/switchery.min.js') !!}
 
   
   
     {!! Html::script('js/script.js') !!}
      {!! Html::script('js/bootstrap-switch.js') !!}
     {!! Html::script('js/jquery.validate.min.js') !!}
      @yield('script')
     <script>
       gapi.analytics.ready(function() {
            gapi.analytics.auth.authorize({
                container: 'embed-api-auth-container',
                clientid: '{{ isset($setting->google_client_id) ? $setting->google_client_id : $companySetting->google_client_id }}'
            });
            var viewSelector = new gapi.analytics.ViewSelector({
            container: 'view-selector-container'
            });
            viewSelector.execute();
            var dataChart = new gapi.analytics.googleCharts.DataChart({ 
              query: {
                metrics: 'ga:sessions',
                dimensions: 'ga:date',
                'start-date': '30daysAgo',
                'end-date': 'yesterday'
              },
              chart: {
                container: 'chart-container',
                type: 'LINE',
                options: {
                  width: '100%'
                }
              }
            });
            viewSelector.on('change', function(ids) {
                dataChart.set({query: {ids: ids}}).execute();
            });
        });
     </script>

  </body>
</html>

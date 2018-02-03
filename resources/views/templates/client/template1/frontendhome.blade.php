<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Admin| FINFO</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="/{{$favicon}}">
    {!! Html::style('css/client/admin.css') !!}
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
    {!! Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}

    {!! Html::style('css/client/frontend-styles1.css') !!}
    <link rel="icon" type="image/png" href="/{{$favicon}}">
     @if(count($setting) >=1 )
        <style type="text/css">

            @if($setting->font_family == "Verdana" || $setting->font_family == "Arial Black")              
                {!! "ul.nav li a {font-size: 12px !important;}" !!}
            @elseif($setting->font_family == "Lucida Console")
                {!! "ul.nav li a {font-size: 10px !important;}"  !!}
            @endif
            
            body {
                background-color: {{ $setting->background_color }} !important; 
                font-family: {{ $setting->font_family }} !important;
            } 
            p, li, td, label, span {                 
                color: {{ $setting->font_color }};
            }

            .container ,.content-wrapper{
                background-color: {{ $setting->container_color }} !important;
            }

            .navbar-inverse,
            .btn-download,
            .btn-customize , .navbar-static-top{
                background-color: {{ $setting->theme_color }};
            }  
            .announcement td.announcement-title,
            .press-release td.press-release-title
            {
                font-weight: normal !important;
                color: {{ isset($setting->font_color) ? $setting->font_color : '#e53840' }} ;
            }
        </style>
        @endif
    <style type="text/css">
      .content{
          padding-right: 30px;
          padding-left: 30px;
      }
    </style>
    @yield('style')
    <script type="text/javascript">
        var baseUrl = "{{ URL::to('/') }}";
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header top-m-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">PATTIES</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
            @if($company_logo != '')
              <img src="/{{$company_logo}}" style="max-height: 45px; max-width: 100%;" alt="company_logo">
            @else 
              <h1>Company Logo</h1>
            @endif     
          </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle top-nav" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <p class="nav-title">{{ucfirst(Session::get('company_name'))}} FINFO</p>
        </nav>
      </header>
      @include('resources.views.templates.client.template1.includes.frontend-menu-left')
      <div class="content-wrapper">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <a href="{{route('finfo.sitemap')}}" title="Sitemap"></a><b style="padding-right:200px color:#000;">Sitemap</b>
        </div>
        <strong>Copyright &copy; 2016 {{ucfirst(Session::get('company_name'))}}.</strong> All rights reserved.

       
      </footer>
    </div>

    <!-- jQuery-->
    {!! Html::script('js/jquery.min.js') !!}
    {!! Html::script('js/jquery-ui.min.js') !!}

    <!-- bootstrap-->
    {!! Html::script('js/bootstrap.min.js') !!}

    <!-- AdminLTE App -->
    {!! Html::script('js/app.min.js') !!}
    <script type="text/javascript">
      $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });
    </script>

    @yield('script')
  </body>
</html>

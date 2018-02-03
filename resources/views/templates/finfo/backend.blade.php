<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>FINFO Administrator</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    {!! Html::style('css/client/admin.css') !!}
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
    {!! Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    {!! Html::style('css/skins/_all-skins.min.css') !!}
    {!! Html::style('css/finfo/customize1.css') !!}


    @yield('style')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper">
      @include('resources.views.templates.finfo.includes.header')
      @include('resources.views.templates.finfo.includes.menu-left')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        @yield('content')
      </div>
    </div>

    <!-- jQuery-->
    {!! Html::script('js/jquery.min.js') !!}
    {!! Html::script('js/jquery-ui.min.js') !!}

    <!-- bootstrap-->
    {!! Html::script('js/bootstrap.min.js') !!}

    <!-- AdminLTE App -->
    {!! Html::script('js/app.min.js') !!}

    @yield('script')
      {!! Html::script('js/script.js') !!}

  </body>
</html>

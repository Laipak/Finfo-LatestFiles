<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>404 Error</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
    {!! Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}

    <style type="text/css">
        .skin-blue .main-header .navbar {
          @if(Session::has('theme_color'))
            background-color: {{ Session::get('theme_color') }};
          @else
            background-color: #e63841;
          @endif
        }
        .content {
            padding: 15px;
            margin-right: auto;
            margin-left: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        .headline {
            font-size: 100px;
            font-weight: 300;
            margin-top: -10px;
            float: right;
        }
        .text-yellow {
            color: #f39c12 !important;
        }
        .div-error{
          margin-top: 50px;
        }

        @media (max-width: 767px){
          .headline {
            float: left;
          }
        }


    </style>

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
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
        </nav>
      </header>

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content">

            <!-- Main content -->
            <section class="content">
            <div class="row">
              <div class="col-sm-12 col-md-6 col-md-offset-3 div-error">
                <div class="col-md-6 col-sm-6 col-xs-12" >
                  <h2 class="headline text-yellow" style="text-align:center;"> 404</h2>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="error-content">
                      <h3><i class="fa fa-warning text-yellow"></i> Oops!</h3>
                      <h3>Page not found.</h3>
                      <p>
                        We could not find the page you were looking for.
                        Meanwhile, you may <a href='{{URL::to('/')}}'>return to home.</a>
                      </p>
                    </div><!-- /.error-content -->
                </div>
              </div><!-- /.error-page -->
            </div>
            </section><!-- /.content -->
        </div>

      </div>
    </div>
  </body>
      <!-- jQuery-->
    {!! Html::script('js/jquery.min.js') !!}
    {!! Html::script('js/jquery-ui.min.js') !!}
      <!-- bootstrap-->
    {!! Html::script('js/bootstrap.min.js') !!}
</html>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
    {!! Html::style('css/finfo/bootstrap.min.css') !!}
    {!! Html::style('css/finfo/freelancer.css') !!}
    {!! Html::style('css/finfo/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('https://fonts.googleapis.com/css?family=Montserrat:400,700') !!}
    {!! Html::style('https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic') !!}
    <style type="text/css">
        section h2{ padding-top:50px;}
        .navbar-default, footer .footer-below {
            background-color: #16366C;
        }
        
        footer .footer-above {
            background-color: #033873;
            /*background-color: #326195;*/

        }
        .btn-success{
            background-color: #00ff00;
            border-color: #00ff00;
        }
        .btn-success:hover, .btn-success.active, .btn-success.focus{
            background-color: #6AD46A;
            border-color: #6AD46A;
        }
    </style>
    
    @yield('style')
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-72254320-1', 'auto');
      ga('send', 'pageview');
    </script>
  </head>
  <body id="page-top" class="index">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ URL::to('/') }}">FINFO</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    @if (isset($menus) && !empty($menus))
                        @foreach($menus as $menu)
                            <li class="page-scroll">
                                <a href="{{$menu->link}}">{{$menu->title}}</a>
                            </li>
                        @endforeach
                    @endif
                    @if (Auth::check() )
                        <li class="page-scroll">
                            <a href="{{ URL::to('admin/logout') }}">Sign out</a>
                        </li>
                    @else
                        <li class="page-scroll">
                            <a href="{{ URL::to('register/subscriptions') }}">Sign Up</a>
                        </li>
                        <li class="page-scroll">
                            <a href="{{ URL::to('admin/login') }}">Sign In</a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    

      <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- Footer -->
    <footer class="text-center main-footer">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-6">
                        <h3>Location</h3>


                        <p>648A Geylang Road <br>Singapore 389578</p>
                    </div>
                    <div class="footer-col col-md-6">
                        <h3>Around the Web</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; Your Website 2014
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery-->
    {!! Html::script('js/jquery.min.js') !!}

     <!-- bootstrap-->
    {!! Html::script('js/bootstrap.min.js') !!}
    
    <!-- Plugin JavaScript -->
    {!! Html::script('http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js') !!}
    {!! Html::script('js/finfo/classie.js') !!}
    {!! Html::script('js/finfo/cbpAnimatedHeader.min.js') !!}

    <!-- Contact Form JavaScript -->
    {!! Html::script('js/finfo/jqBootstrapValidation.js') !!}

    <!-- Custom Theme JavaScript -->
    {!! Html::script('js/finfo/freelancer.js') !!}

    @yield('script')
  </body>
</html>

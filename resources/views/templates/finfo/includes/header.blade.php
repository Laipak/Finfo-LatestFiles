      <header class="main-header top-m-header">
        <!-- Logo -->
        <a href="/" target="_brank" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src="{{asset('img/finfo.png')}}" alt="" style="width: 45px;"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="{{asset('img/finfo.png')}}" alt=""></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle top-nav" data-toggle="offcanvas" role="button" style="text-decoration: none;">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu noti-top">
            <ul class="nav navbar-nav">
              
              <li class="dropdown user user-menu small-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user noti-icon"></i>
                  <i class="fa fa-caret-down noti-icon"></i>
                </a>
                <ul class="dropdown-menu" style="width: 100px;">
                    <li><a href="{{route('finfo.user.profile')}}">Profile</a></li>
                    <li><a href="{{route('finfo.admin.logout')}}">Sign Out</a></li>
                </ul>
              </li>

            </ul>
          </div>
        </nav>
      </header>

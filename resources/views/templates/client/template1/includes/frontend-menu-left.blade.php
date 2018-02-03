      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <ul class="sidebar-menu">
            <li class="treeview menu-active" id="home">
              <a href="{{route('client.home')}}">
                <span>Overview</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="leadership">
              <a href="{{route('client.company_info')}}">
                <span>Leadership</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="stock">
              <a href="{{route('package.stock-information')}}">
                <span>Stock Information</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="financial-result">
              <a href="{{route('package.financial-result')}}">
                <span>Financial Results</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="annual-rp">
              <a href="{{route('package.annual-report')}}">
                <span>Annual Reports</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="press-release">
              <a href="{{ URL::to('/press-releases') }}">
                <span>Press Releases</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="announcements">
              <a href="{{ URL::to('/announcements') }}">
                <span>Announcements</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="ir-calendar">
              <a href="{{ URL::to('/investor-relations-calendar') }}">
                <span>Investor Relations Calendar</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="email-alerts">
              <a href="{{ URL::to('/email-alerts') }}">
                <span>Email Alerts</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>

            <li class="treeview" id="media-access">
              <a href="{{route('package.media-access') }}">
                <span>Media Access</span>
                <i class="fa fa-caret-right pull-right icon-right"></i>
              </a>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

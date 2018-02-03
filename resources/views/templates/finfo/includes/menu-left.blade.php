      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="row">
              <div class="col-md-12">
                <!-- <div class="image">
                  <img src="/img/logo-com.png" class="img-responsive" alt="logo" />
                </div>  -->
                          
              </div>
            </div>
          </div>
          
          <ul class="sidebar-menu" id="menu">
            <li class="treeview" id="dashboard">
              <a href="{{route('finfo.admin.dashboard')}}" id="dash-border-left">
                  <i class="fa fa-dashboard"></i>  
                <span>Dashboard</span>
              </a>              
            </li>
            @if(\Auth::check() && \Auth::user()->user_type_id == 3)
            <li class="treeview" id="user">
              <a href="#" id="lfh-border-left">
                <i class="fa fa-users"></i> 
                <span>User Manager</span>
                <i class="fa fa-angle-down pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{route('finfo.user.backend.list')}}">List</a></li>
                <li><a href="{{route('finfo.user.backend.create')}}"> Add New</a></li>
              </ul>
            </li>
              @endif
            <li class="treeview" id="page">
              <a href="{{route('finfo.webpage.backend.list')}}" id="dash-border-left">
                 <i class="fa fa-external-link-square"></i> 
                 <span>Web page</span>
              </a>
            </li>
            <li class="treeview" id="menu">
              <a href="{{route('finfo.menus.backend.list')}}" id="dash-border-left">
                 <i class="fa fa-bars"></i>
                <span>Menus</span>
              </a>
            </li>
            <li class="treeview" id="client">
              <a href="{{route('finfo.admin.clients.list')}}" id="lfh-border-left">
                   <i class="fa fa-user"></i>
                <span>Client</span>
              </a>
            </li>
            <li class="treeview" id="billing">
              <a href="#" id="dash-border-left">
                <i class="fa fa-money"></i>
                <span>Billing</span>
                <i class="fa fa-angle-down pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="m-invoice-1"><a href="{{route('finfo.admin.billing.invoice', 1)}}">Invoice Paid</a></li>
                <li id="m-invoice-0"><a href="{{route('finfo.admin.billing.invoice', 0)}}">Unpaid</a></li>
                <li id="m-invoice-2"><a href="{{route('finfo.admin.billing.invoice', 2)}}">Overdue</a></li>
                <li id="m-invoice-3"><a href="{{route('finfo.admin.billing.invoice', 3)}}">Cancelled</a></li>
              </ul>
              </a>
            </li>
            <li class="treeview" id="revenue">
              <a href="{{route('finfo.admin.revenue.list')}}" id="lfh-border-left">
                  <i class="fa fa-line-chart"></i>
                <span>Revenue/Profit</span>
              </a>
            </li>
            @if(Auth::check() && \Auth::user()->user_type_id == 3)
            <li class="treeview" id="setting">
              <a href="{{route('finfo.admin.setting')}}" id="lfh-border-left">
                 <i class="fa fa-cogs"></i>
                <span>Setting</span>
              </a>
            </li>
            <li class="treeview" id="theme">
              <a href="{{route('finfo.admin.theme')}}" id="lfh-border-left">
                 <i class="fa fa-sliders"></i>  
                <span>Themes</span>
              </a>
            </li>
            @endif
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

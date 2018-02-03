<nav class="navbar navbar-inverse navbar-no-bg" role="navigation" data-toggle="collapse" data-target="#top-navbar-1">
    <div class="navbar-header">

            <div class="navbar-toggle collapsed" >
                <div class="row">
                    <div class="col-xs-6">
                     <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                         <span class="icon-bar"></span>
                     </div>
                     <div class="col-xs-6" style="padding:0">
                         <div style="color: #fff">Menu</div>
                     </div>
                </div>
             </div>
          </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="top-navbar-1">
            <ul class="nav navbar-nav navbar-left">
  
                <li class="menu" id="home"><a href="{{route('client.home')}}">Home</a></li>
      

                @if (Session::get('package_id') != 1)

                    <li class="menu dropdown" id="leadership"><a href="{{route('client.company_info')}}" >Company Info</a>
                        @if(isset($contents) && !empty($contents))
                            <ul id="sub-menu">
                                @foreach($contents as $content)
                                    <li><a href="{{route('client.company_info.slug', $content->name)}}">{{$content->title}}</a></li>
                                @endforeach
                            </ul>
                         @endif     
        
                    </li>
                @endif
                @foreach($frontend_menus as $menu)
                   @if (in_array($menu->id,$menu_pers))
                    @if (!empty($menu->route_frontend))
                        <li class="menu" id="{{$menu->css_id}}"><a href="{{route(trim($menu->route_frontend))}}">{{$menu->nav_frontend}}</a></li>
                    @endif
                    @endif
                @endforeach
            </ul>
        </div>
    
</nav>

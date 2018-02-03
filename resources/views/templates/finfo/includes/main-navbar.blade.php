<div class="navbar navbar-default default navbar-fixed-top navbar-shrink tLight " role="navigation">
             
            <!-- BEGIN: NAV-CONTAINER -->
            <div class="nav-container container">
                <div class="navbar-header">
                    
                    <!-- BEGIN: TOGGLE BUTTON (RESPONSIVE)-->
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <style>
                        @media (max-width: 479px) {
                            .navbar-shrink .logo { margin-top: 0 !important; }
                        }
                    </style>
                    <!-- BEGIN: LOGO -->
                    <!--a class="navbar-brand nav-to logo" href="{{URL::to('/')}}" title="FINFO"-->
					<a class="navbar-brand nav-to logo" href="{{URL::to('/')}}" title="FINFO">
                       <!-- <img src="{{ asset('img/finfo/imgs/finfo-logo-normal.png') }}"  data-at2x="{{ asset('img/finfo/imgs/finfo-logo-normal.png') }}" alt="FINFO" /> -->
                       
                       <img style="max-width: 10%;position: absolute;" src="https://wizwerx.info/themes/massive-dynamic/img/finfo-logo.svg" alt="FINFO">
                    </a>

               <!-- BEGIN: WPML MENU --> 

                </div>
                <div class="icons-style-mobile">      
                </div>               
                <div class="menu-dikka-one-page-menu-container">
                        <div class="collapse navbar-collapse ">
                            <ul class="nav navbar-nav navbar-right sm">
                            @if(Request::is('/'))                                  <!--                             
                                <li ><a class="nav-to  menu-item menu-item-type-custom menu-item-object-custom"  href="{{ URL::route('finfo.home') }}#what-is-finfo">About Us</a></li>
                                <li ><a class="nav-to  menu-item menu-item-type-custom menu-item-object-custom"  href="{{ URL::route('finfo.contact') }}">Contact Us</a></li>
                                -->
                                <li ><a class="nav-to  menu-item menu-item-type-custom menu-item-object-custom"  href="{{ URL::route('finfo.pricing') }}">Pricing / Sign Up</a></li>
                            @else
                                {{-- <li ><a href="{{ URL::route('finfo.home') }}#what-is-finfo">About Us</a></li>
                                <li ><a href="{{ URL::route('finfo.contact') }}">Contact Us</a></li>
                                <li ><a href="{{ URL::route('finfo.pricing') }}">Pricing / Sign Up</a></li> --}}
                                <!--li ><a href="{{ URL::route('finfo.about') }}">About Us</a></li>
                                <li ><a href="{{ URL::route('finfo.pricing') }}">Pricing</a></li-->
								<li ><a href="{{ URL::route('finfo.home') }}">About Us</a></li>
                                <li ><a href="{{ URL::route('finfo.pricing') }}">Pricing</a></li>
								<!--li ><a href="https://wizwerx.info/news/">News</a></li-->
                            @endif
                            </ul>
                        </div>
                </div> 
                <!-- END: MENU -->
            </div>         

<!--END: NAV-CONTAINER -->
</div>
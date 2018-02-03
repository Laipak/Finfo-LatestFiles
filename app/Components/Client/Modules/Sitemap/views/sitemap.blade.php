




@extends('resources.views.templates.client.template2.frontendhome')
@section('content')

<?php 
$actual_link ="$_SERVER[REQUEST_URI]";

?>

<style type="text/css">
.selectclass {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}
</style>
    <!-- Navigation -->
    <nav class="pge-nvbar navbar navbar-expand-lg thm-bgclr">
      <div class="container">
	     <div>
		 <?php
            if (!empty($setting->company_logo))
            {
            if ($actual_link == '/announcements')
            { ?>
               <a class="navbar-brand" href="{{url('/')}}"><img class="site-logo" src="{{!empty($setting->company_logo) ? $setting->company_logo : "/img/banner.jpg"}}"/ ></a>
            <?php } else { ?>
               <a class="navbar-brand" href="{{url('/')}}"><img class="site-logo" src="../{{!empty($setting->company_logo) ? $setting->company_logo : "../img/banner.jpg"}}"/ ></a>   
            <?php } } ?>		
			<ul class = "fullmenu">
              <li> <a id="snavvy">
                <div id="nav-icon"> <span></span> <span></span> <span></span> <span></span> </div>
                </a> </li>
              <div class="snavvy-links" id="snavvy-links">
                <ul class="links">
                  <li> <a href="{{url('/')}}"> Home </a> </li>
                    <li> <a class="sub-ul-lnk"> Company Info  <i class="material-icons">expand_more</i> </a>
					<ul class="sub-ul" style="display:none;">
				    @if (isset($title) && !empty($title))
                      @foreach($title as $titles)
                       <li> <a href="{{url('company-info/'.$titles->name)}}">{{$titles->title}} </a> </li>
                      @endforeach
                    @endif 
					</ul>
				  </li>
                @if (isset($menuPermissions) && !empty($menuPermissions))
	 
            	  	@foreach($menuPermissions as $menuPer)
            	  	
            	  	@if ($menuPer->name == 'Financials')
  
                   <li> <a href="{{url('/financial-result')}}"> Financials </a> </li>
                   
                   @endif
                   @if ($menuPer->name == 'Stock Info')
                  <li> <a href="{{url('/stock-information')}}"> Stock Info </a> </li>
                  @endif
                  @if ($menuPer->name == 'Annual Reports')
                  <li> <a href="{{url('/annual-report')}}"> Annual Reports </a> </li>
                  @endif
                  @if ($menuPer->name == 'Events')
                  <li> <a href="{{url('/investor-relations-events')}}"> Events </a> </li>
                  @endif
                  @if ($menuPer->name == 'Press Releases')
                  <li> <a href="{{url('/press-releases')}}"> Press Releases </a> </li>
                  @endif
                  @if ($menuPer->name == 'Announcements')
                  <li> <a href="{{url('/announcements')}}"> Announcements </a> </li>
                  @endif
                  
                 
                @endforeach
                @endif
                </ul>
              </div>
            </ul>				
		</div>		
		@if (isset($menuPermissions) && !empty($menuPermissions))
          	@foreach($menuPermissions as $menuPer)
          	  	@if ($menuPer->name == 'Stock Info')
          	  	@if (isset($resultid) && !empty($resultid))
          		<div class="head-stk-info">
        		{{$resultid}}  <i class="material-icons">keyboard_arrow_up</i>  {{$result}}
          		</div>
            	@endif	
       	 	@endif
            @endforeach
            @endif
      </div>
    </nav>
	<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3>Sitemap</h3>  
          </div>          
        </div>        
      </div>
    </header>

    <!--cmpy Info -->
    <section class="page-cnt-sec">
      <div class="container">
	<div class="page-cnt-sec-lay">
	   <div class="page-cnt-sec-inr">						
	     <div class="ance-pge-sec">
	 	<div class="row">
		  
		         <div class="col-md-12" id="table">
        <div class="content-data sitemap">
            <ul>
                <li><a href='/' title='Home'>Home</a></li>
                @if (Session::get('set_package_id') != 1) 
                    <li>
                        <a href='{{route('client.company_info.slug')}}' title='Home'>Company info</a>
                        <ul class="sub-title">
                            @foreach($sitemap['pages'] as $sitemapPages)
                                <li><a href='{{route('client.company_info.slug',$sitemapPages->name)}}' title='{{$sitemapPages->title}}'>{{$sitemapPages->title}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @foreach($sitemap['modules'] as $sitemapModules)
                    @if (!empty($sitemapModules->route_frontend))
                        <li><a href='{{route($sitemapModules->route_frontend)}}' title='{{$sitemapModules->name}}'>{{$controller->getMenuNavigation($sitemapModules->route_frontend)}}</a></li>
                    @endif
                @endforeach
                
            </ul>
        </div>
    </div>
	
					
		</div>
	  </div>
	  </div>
	  </div>
	  </div>
    </section>	
	

    <!-- Footer -->
      <footer>
      <div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<p class="cpy-txt">Copyright &copy; 2017 {{$setting->company_name}} (SINGAPORE) PTE LTD. All rights reserved.</p>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
			<form class="form-inline">
			  <div class="form-group col-12 col-sm-3 col-md-3 col-lg-3">				
				<a href="{{ route('client.sitemap') }}" class="sit-map-txt hme-anc-itm-lnk">Sitemap</a>
			  </div>
			  <div class="form-group col-8 col-sm-8 col-md-8 col-lg-8 nws-subs-sec">				
				<input type="email" class="form-control subemail" id="email" placeholder="Enter email to subscribe to newsletter">
			  </div>
			  <div class="form-group col-3 col-sm-1 col-md-1 col-lg-1">	
				<div class="row"><button type="submit" class="btn subs-btn"><i class="material-icons">send</i></button></div>
			</div>
			</form>		
			</div>
		</div>
      </div>
      <!-- /.container -->
    </footer>






@stop






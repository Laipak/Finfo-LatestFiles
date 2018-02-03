<style>
a {
    text-decoration: none!important;
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
               <a class="navbar-brand" href="{{url('/')}}"><img class="site-logo" src="../../../{{!empty($setting->company_logo) ? $setting->company_logo : "../img/banner.jpg"}}"/ ></a>   
            <?php } } ?>		
			<ul class = "fullmenu">
              <li> <a id="snavvy">
                <div id="nav-icon"> <span></span> <span></span> <span></span> <span></span> </div>
                </a> </li>
              <div class="snavvy-links" id="snavvy-links">
                <ul class="links">
                  <li> <a href="{{url('/')}}"> Home </a> </li>
                    <li> <a class="sub-ul-lnk"> Company Info  <i class="material-icons">expand_more</i> </a>
					<ul class="sub-ul" style="display:none;max-height: 200px;overflow-y:scroll">
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
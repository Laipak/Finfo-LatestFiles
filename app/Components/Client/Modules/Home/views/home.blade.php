<?php 

//error_reporting(E_ALL & ~E_NOTICE);
?>
@extends('resources.views.templates.client.template2.frontendhome')
@section('content')
 <style type="text/css">
 
 .footer-color {
                 background: {{ $setting->footer_color }}
               }
            
 .footer-text {
                 color: {{ $setting->footer_text }}
              }    
 .primaryBackground {
                color: {{ $setting->container_color }} !important;
            }    
 .secondaryBackground {
                color: {{ $setting->font_color }} !important;
            }
  .slider-color {
              color: {{ $setting->description_color }} !important;
           }  
           
 .scolor{
     
     
     color:  {{ $setting->description_color }} !important;
     
 }
 
 #result{
     
     padding-left: 145px;
     color: red;
     
 }
 
 .loading{
  font-size:0;
  width:30px;
  height:30px;
  margin-top:5px;
  border-radius:15px;
  padding:0;
  border:3px solid #FFFFFF;
  border-bottom:3px solid rgba(255,255,255,0.0);
  border-left:3px solid rgba(255,255,255,0.0);
  background-color:transparent !important;
  animation-name: rotateAnimation;
  -webkit-animation-name: wk-rotateAnimation;
  animation-duration: 1s;
  -webkit-animation-duration: 1s;
  animation-delay: 0.2s;
  -webkit-animation-delay: 0.2s;
  animation-iteration-count: infinite;
  -webkit-animation-iteration-count: infinite;
}

@keyframes rotateAnimation {
    0%   {transform: rotate(0deg);}
    100% {transform: rotate(360deg);}
}
@-webkit-keyframes wk-rotateAnimation {
    0%   {-webkit-transform: rotate(0deg);}
    100% {-webkit-transform: rotate(360deg);}
}


.done{
  color:#ffffff;
  font-size:18px !important;
  position:absolute;
  left:65%;
  top:50%;
  margin-left:-9px;
  margin-top:-9px;
  -webkit-transform:scaleX(0) !important;
  transform:scaleX(0) !important;
}


.failed{
  color:#ffffff;
  font-size:18px !important;
  position:absolute;
  left:50%;
  top:50%;
  margin-left:-9px;
  margin-top:-9px;
  -webkit-transform:scaleX(0) !important;
  transform:scaleX(0) !important;
}


.finish{
  -webkit-transform:scaleX(1) !important;
  transform:scaleX(1) !important;
}
.hide-loading{
  opacity:0;
  -webkit-transform: rotate(0deg) !important;
  transform: rotate(0deg) !important;
  -webkit-transform:scale(0) !important;
  transform:scale(0) !important;
}
 
 @media screen and (max-width: 550px) and (min-width: 350px)
.res-site {
    margin-left: 20px!important;
}
 

 </style>

<?php

$color = $setting->container_color;
$theme = $setting->background_color;
$content = $setting->theme_color;
?>


 @if (isset($menuPermissions) && !empty($menuPermissions))
 <?php $myArray = array(); ?>
	@foreach($menuPermissions as $menuPer)
	
	 <?php $myArray[] = $menuPer->name; ?>
	 
	    	@if(($menuPer->name == 'Financials'))
			   <?php 
			   $Per = 'style=background-color:'.$theme.'!important;';
			   $Col = 'style=color:'.$color.'!important';
			   $Btn = 'style=background-color:'.$content.'!important';
			   
			   ?>
		 	@else
			   <?php $Per = '';  $Col = ''; $Btn = ''; ?>
			@endif
	 @endforeach
 @endif


     <!-- Navigation -->
    <nav class="hme-navbar navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
	     <div>
	      <?php 
          if(!empty($setting->company_logo)){
          ?>
			<a class="navbar-brand" href="{{url('/')}}"><img class="site-logo" src="{{!empty($setting->company_logo) ? $setting->company_logo : "/img/banner.jpg"}}"/ ></a>
		  <?php } ?>	
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
                   
                   <?php if(Session::get('set_package_id') != 1){ ?>
                   
                   @if ($menuPer->name == 'Stock Info')
                  <li> <a href="{{url('/stock-information')}}"> Stock Info </a> </li>
                  @endif
                  
                  <?php } ?>
                  
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

    <header>
     
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        
        <div class="carousel-inner" role="listbox">
          
          <?php $i=0; ?>
           @foreach($sliders as $slides)
          
          <?php if($i == 0) { ?>
              
               <div class="carousel-item active" style="background-image: url('{{!empty($slides->banner_img) ? $slides->banner_img : "/img/banner.jpg"}}')">
              
         <?php }else { ?>
          <div class="carousel-item" style="background-image: url('{{!empty($slides->banner_img) ? $slides->banner_img : "/img/banner.jpg"}}')">
         <?php } ?>
            <div class="carousel-caption d-none d-md-block">
              <h3 class="scolor">{{$setting->slider_description}}</h3>              
            </div>
          </div>
          
          <?php $i++; ?>
        
        @endforeach
        </div>  
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
     
     
    </header>

    <!--cmpy Info -->
    <section class="cmpy-info">
      <div class="container">
		<div class="cmpy-info-lay">
			<div class="cmpy-info-inr thme-clr">
		     @if (isset($dataContents) && !empty($dataContents))
               @foreach($dataContents as $contentPage)
			<h5 class="primaryBackground">{{$contentPage['title']}}</h5>	
			   @endforeach
             @endif
			<div class="row">	
		    <?php 
                  if(!empty($setting->company_info_img)){
                  ?>
				<div class="col-12 col-sm-12 col-md-6 col-lg-6">
					<img src="{{!empty($setting->company_info_img) ? $setting->company_info_img : "/img/banner.jpg"}}"/>
				</div>
				<?php 
                  }else{
                  ?>
                  <div class="col-12 col-sm-12 col-md-6 col-lg-6">
					<img src="{{"img/default-company-info.jpg"}}"/>
				</div>
				<?php  } ?>
    	        @if (isset($dataContents) && !empty($dataContents))
                    @foreach($dataContents as $contentPage)
                    
                    
                    <?php $info = $contentPage['content_description'];
                    if (strlen($info) > '420')
                        $abtus = substr($info, 0, 420). '';
                        else
                        $abtus = substr($info, 0, 420);
                     
                    
                    if (str_word_count($abtus, 0) > 53) {
                        $words = str_word_count($abtus, 2);
                        $pos = array_keys($words);
                        $text = substr($abtus, 0, $pos['53']) . '...';
                        }else{
                        $text = $abtus;   
                        } 
                    ?>
           			<div class="col-12 col-sm-12 col-md-6 col-lg-6 primaryBackground">
    					<p> {!! $text !!}</p>
    				</div>
    				 @endforeach
                @endif
			</div>		
			</div>
			 @if (isset($dataContentsTitle) && !empty($dataContentsTitle))
               @foreach($dataContentsTitle as $contentTitle)
			  @endforeach
            @endif
           	@if(!empty($text))
		
            <p class="cmpy-info-btn-sec "><a class="btn thme-clr primaryBackground" href="{{url('/company-info/'.$contentTitle['name'])}}" role="button">LEARN MORE</a></p>
            
           @endif
		</div>
	  </div>
    </section>
	
	
	<?php
	
	if($menuLink == 4){
	    
	    $menu = 'col-12 col-sm-3 col-md-3 col-lg-3';
	    
	}elseif($menuLink == 3){
	     $menu = 'col-12 col-sm-4 col-md-4 col-lg-4';
	    
	}elseif($menuLink == 2){
	     $menu = 'col-12 col-sm-6 col-md-6 col-lg-6';
	    
	}elseif($menuLink == 1){
	     $menu = 'col-12 col-sm-12 col-md-12 col-lg-12';
	    
	}
	
  
	
	?>
	
	
	
	<section class="hme-icon-sec content-clr">
		<div class="container">
			<div class="row">
			    
			     @if (isset($menuPermissions) && !empty($menuPermissions))
	        	  	@foreach($menuPermissions as $menuPer)
            	  	@if ($menuPer->name == 'Financials')
				<div class="{{$menu}}">
				    <a href="{{url('/financial-result')}}">
					<i class="fa fa-bar-chart thme-clr-font" aria-hidden="true"></i>
					<h5 class="secondaryBackground">Financials</h5>
					</a>
				</div>
				@endif
				@endforeach
				@endif
				
				
			  <?php if(Session::get('set_package_id') != 1){ ?>
				@if (isset($menuPermissions) && !empty($menuPermissions))
	        	  	@foreach($menuPermissions as $menuPer)
            	  	@if ($menuPer->name == 'Stock Info')
				<div class="{{$menu}}">
				    <a href="{{url('/stock-information')}}">
					<i class="fa fa-line-chart thme-clr-font" aria-hidden="true"></i>
					<h5 class="secondaryBackground">Stock Info</h5>
					</a>
				</div>
				@endif
				@endforeach
				@endif
				<?php } ?>
				
				@if (isset($menuPermissions) && !empty($menuPermissions))
	        	  	@foreach($menuPermissions as $menuPer)
            	  	@if ($menuPer->name == 'Annual Reports')
				<div class="{{$menu}}">
				   <a href="{{url('/annual-report')}}">
					<i class="fa fa-pie-chart thme-clr-font" aria-hidden="true"></i>
					<h5 class="secondaryBackground">Annual Reports</h5>
					</a>
				</div>
				@endif
				@endforeach
				@endif
				
				
				@if (isset($menuPermissions) && !empty($menuPermissions))
	        	  	@foreach($menuPermissions as $menuPer)
            	  	@if ($menuPer->name == 'Events')
				<div class="{{$menu}}">
				   <a href="{{url('/investor-relations-events')}}">
					<i class="fa fa-calendar thme-clr-font" aria-hidden="true"></i>
					<h5 class="secondaryBackground">Events</h5>
					</a>
				</div>
		        @endif
				@endforeach
				@endif			
			</div>
		</div>
	</section>
	
	
	<!--cmpy Info -->
	
	<?php if($setting->company_id != '265') { ?>
	 <!--section class="hme-nws-sec">
		<div class="hme-nws-sec-bg thme-clr-aftr">
		<h3 class="primaryBackground">IN THE NEWS</h3>
		<div class="clearfix"></div>
		<div class="loop owl-carousel owl-theme">
			<div class="item"> 
				<img class="hme-nws-sec-itm-img" src="{{"img/default-company-info.jpg"}}"/>
				<div class="hme-nws-sec-itm-lay">
					<div class="hme-nws-sec-itm-inr">
						<h4>Nulla sagittis felis eget dolor </h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. </p>
					</div>
					<p class="hme-nws-sec-lnk"> 
					<span class="hme-nws-sec-lnk-lt"><a href="#">bloomberg.com</a></span>
					<span class="hme-nws-sec-lnk-rt"><i class="fa fa-external-link" aria-hidden="true"></i> OPEN 
					<i class="material-icons">share</i> SHARE </span></p> 
					</div>				
			</div>
			<div class="item"> 
				<img class="hme-nws-sec-itm-img" src="{{"img/default-company-info.jpg"}}"/>
				<div class="hme-nws-sec-itm-lay">
					<div class="hme-nws-sec-itm-inr">
						<h4>Lorem ipsum dolor sit amet,consectetur adipiscing elit</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
					</div>
					<p class="hme-nws-sec-lnk"><span class="hme-nws-sec-lnk-lt"><a href="#">yahoo.com</a></span>
					<span class="hme-nws-sec-lnk-rt"><i class="fa fa-external-link" aria-hidden="true"></i> OPEN 
					<i class="material-icons">share</i> SHARE </span></p>
				</div>
			</div>
			<div class="item"> 
				<img class="hme-nws-sec-itm-img" src="{{"img/default-company-info.jpg"}}"/> 
				<div class="hme-nws-sec-itm-lay">
					<div class="hme-nws-sec-itm-inr">
						<h4>Nunc in blandit urna, sed tincidunt libero</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
					</div>
					<p class="hme-nws-sec-lnk"><span class="hme-nws-sec-lnk-lt"><a href="#">yahoo.com</a></span>
					<span class="hme-nws-sec-lnk-rt"><i class="fa fa-external-link" aria-hidden="true"></i> OPEN 
					<i class="material-icons">share</i> SHARE </span></p>
				</div>
			</div>
			<div class="item"> 
				<img class="hme-nws-sec-itm-img" src="{{"img/default-company-info.jpg"}}"/> 
				<div class="hme-nws-sec-itm-lay">
					<div class="hme-nws-sec-itm-inr">
						<h4>Vivamus scelerisque dignissim egestas</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
					</div>
					<p class="hme-nws-sec-lnk"><span class="hme-nws-sec-lnk-lt"><a href="#">cna.com</a></span>
					<span class="hme-nws-sec-lnk-rt"> <i class="fa fa-external-link" aria-hidden="true"></i> OPEN 
					<i class="material-icons">share</i> SHARE </span></p>
				</div>
			</div>			
			<div class="item"> 
				<img class="hme-nws-sec-itm-img" src="{{"img/default-company-info.jpg"}}"/> 
				<div class="hme-nws-sec-itm-lay">
					<div class="hme-nws-sec-itm-inr">
						<h4>Nulla sagittis felis eget dolor hendrerit varius</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
					</div>
					<p class="hme-nws-sec-lnk"><span class="hme-nws-sec-lnk-lt"><a href="#">bloomberg.com</a></span>
					<span class="hme-nws-sec-lnk-rt"> <i class="fa fa-external-link" aria-hidden="true"></i> OPEN 
					<i class="material-icons">share</i> SHARE </span></p>
				</div>
			</div>	
		</div>			
		</div>
		<p class="cmpy-info-btn-sec"><a class="btn thme-clr primaryBackground" href="#" role="button">VIEW FULL LIST</a></p>	
	 </section-->
	 
	 
	 <?php } ?>
	 <div class="clearfix"></div>
	 
	  @if (isset($menuPermissions) && !empty($menuPermissions))
	 
	  	@foreach($menuPermissions as $menuPer)
	  	
	  	@if ($menuPer->name == 'Announcements')
	  	
	 
	  @if (isset($announcement) && !empty($announcement))
			<?php $j = 0; ?>
				@foreach($announcement as $announ)
			<?php  $j++; ?>	
	    	@if(($j == 3))
			   <?php $ann = 'col-12 col-sm-12 col-md-4 col-lg-4'; ?>
		 	@elseif(($j <= 3 ) && ($j == 2))
			   <?php $ann = 'col-12 col-sm-12 col-md-4 col-lg-4 offset-lg-1'; ?>
		    @elseif(($j == 1 ))
			    <?php $ann = 'col-12 col-sm-12 col-md-4 col-lg-4 offset-lg-4'; ?>
			@endif
	 @endforeach
	 @endif
	 <section class="hme-anc-sec thme-clr" {{$Per}}>
		<div class="container">
			<h4 class="primaryBackground" {{$Col}}>ANNOUNCEMENTS</h4>
			<div class="row">
			 @if (isset($announcement) && !empty($announcement))
				@foreach($announcement as $announ)
			      <div class="col-12 col-sm-12 col-md-4 col-lg-4 {{$ann}}">
					<div class="hme-anc-sec-itm">
						<p class="hme-anc-itm-dte">{{date("d M Y", strtotime($announ->announce_at))}}</p>
						<div class="hme-anc-itm-info">
						    
					   <?php $title = html_entity_decode(strip_tags($announ->title));
             
                        if (str_word_count($title, 0) > 5) {
                            $words = str_word_count($title, 2);
                            $pos = array_keys($words);
                            $title = substr($title, 0, $pos['5']) . '';
                            }else{
                             
                             $title =  $title;  
                                
                            }    
                            
                         
                        ?> 
						    
						   <div style="min-height: 55px;"> 
							<h4 class="hme-anc-itm-tit">{{$title}}</h4>
						   </div>	
						@if(!empty($announ->description))
						
						<?php $anno = html_entity_decode(strip_tags($announ->description));
                          
                       if (str_word_count($anno, 0) > 35) {
                            $words = str_word_count($anno, 2);
                            $pos = array_keys($words);
                            $textannou = substr($anno, 0, $pos['35']) . '';
                            }else{
                             
                             $textannou =  $anno;  
                                
                            }
                          
                        ?>
						<div style="min-height: 145px;">
						<p style="min-height: 170px;">{{$textannou}}</p>
						</div>	
						@else
							<p class="hme-anc-itm-des">Nunc magna eros, porta tincidunt pharetra quis, tristique sed libero. Quisque consequat...</p>
						@endif	
						</div>
						 @if(!empty($announ->file_upload))
						<p class="hme-anc-itm-lnk"><i class="fa fa-link" aria-hidden="true"></i><a href="/files/announcements/{{$announ->file_upload}}" target="_brank">&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><!--<i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE --></p>
					   @else
					   <p class="hme-anc-itm-lnk"><i class="fa fa-link" aria-hidden="true"></i><a href="#">&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					  @endif
					 
					</div>
				</div>
				 @endforeach
			 @else
				 <div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="hme-anc-sec-itm">
						<p class="hme-anc-itm-dte">04 Oct 2017</p>
						<div class="hme-anc-itm-info">
							<h4 class="hme-anc-itm-tit">Curabitur eu dui tortor</h4>
							<p class="hme-anc-itm-des">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
						</div>
						<p class="hme-anc-itm-lnk"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="hme-anc-sec-itm">
						<p class="hme-anc-itm-dte">04 Oct 2017</p>
						<div class="hme-anc-itm-info">
							<h4 class="hme-anc-itm-tit">Curabitur eu dui tortor</h4>
							<p class="hme-anc-itm-des">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
						</div>
						<p class="hme-anc-itm-lnk"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="hme-anc-sec-itm">
						<p class="hme-anc-itm-dte">04 Oct 2017</p>
						<div class="hme-anc-itm-info">
							<h4 class="hme-anc-itm-tit">Curabitur eu dui tortor</h4>
							<p class="hme-anc-itm-des">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam fermentum nibh non justo dignissiam vestibulum. Aliquam erat...</p>
						</div>
						<p class="hme-anc-itm-lnk"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					</div>
				</div>
			 @endif
			</div>
			<p class="cmpy-info-btn-sec"><a class="btn thme-clr content-clr" {{$Btn}} href="{{url('/announcements')}}" role="button">view all announcements</a></p>
		</div>
	</section>	
    
    @endif
    @endforeach
    @endif




  @if (isset($menuPermissions) && !empty($menuPermissions))
	 
 	@foreach($menuPermissions as $menuPer)
	  	
  	@if ($menuPer->name == 'Press Releases')
  	
	@if (isset($press_release) && !empty($press_release))
			<?php $j = 0; ?>
				@foreach($press_release as $press)
			<?php  $j++; ?>	
	    	@if(($j == 3))
			   <?php $pres = 'col-12 col-sm-12 col-md-4 col-lg-4'; ?>
		 	@elseif(($j <= 3 ) && ($j == 2))
			   <?php $pres = 'col-12 col-sm-12 col-md-4 col-lg-4 offset-lg-1'; ?>
		    @elseif(($j == 1 ))
			    <?php $pres = 'col-12 col-sm-12 col-md-4 col-lg-4 offset-lg-4'; ?>
			@endif
	 @endforeach
	 @endif 
	<section class="hme-prels-sec" {{$Per}}>
		<div class="container">
			<h4 {{$Col}}>PRESS RELEASES</h4>
			<div class="row">	
			 @if (isset($press_release) && !empty($press_release))
				@foreach($press_release as $press)
				@if(!empty($press->upload))
				  <?php $upload = $press->upload; ?>
				@else
				  <?php  $upload = ''; ?>
				@endif
				<div class="col-12 col-sm-12 col-md-4 col-lg-4 {{$pres}}">
					<div class="hme-prels-sec-itm">
						<p class="hme-prels-itm-dte">{{date("d M Y", strtotime($press->press_date))}}</p>
						<div class="hme-prels-itm-info">
							<h4 class="hme-prels-itm-tit">{{$press->title}}</h4>
							@if(!empty($press->description))
							
							<?php $press = html_entity_decode(strip_tags($press->description));
                            if (strlen($press) > '249')
                                $presss = substr($press, 0, 249). '';
                                else
                                $presss = substr($press, 0, 249);
                             
                            ?>
								<p>{{$presss}}</p>
							@else
							<p class="hme-prels-itm-des">Nunc magna eros, porta tincidunt pharetra quis, tristique sed libero. Quisque consequat...</p>
							@endif
						</div>
				 
				     @if(!empty($upload))
						<p class="hme-prels-itm-lnk"> <i class="fa fa-link" aria-hidden="true"></i><a href="/{{$upload}}" target="_brank">&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></p>
					 @else
					    <p class="hme-prels-itm-lnk"> <i class="fa fa-link" aria-hidden="true"></i><a href="#">&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></p>
				    @endif  
					</div>
				</div>
				 @endforeach
			 @else
				<div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="hme-prels-sec-itm">
						<p class="hme-prels-itm-dte">06 Oct 2017</p>
						<div class="hme-prels-itm-info">
							<h4 class="hme-prels-itm-tit">Mauris consectetur efficitur justo, non varius lectus viverra</h4>
							<p class="hme-prels-itm-des">Nunc magna eros, porta tincidunt pharetra quis, tristique sed libero. Quisque consequat massa enim, at...</p>
						</div>
						<p class="hme-prels-itm-lnk"> <i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="hme-prels-sec-itm">
						<p class="hme-prels-itm-dte">10 Oct 2017</p>
						<div class="hme-prels-itm-info">
							<h4 class="hme-prels-itm-tit">Sed lobortis nunc nec velit mattis, a efficitur lacus lacinia</h4>
							<p class="hme-prels-itm-des">Suspendisse sollicitudin imperdiet fringilla. Aliquam sit amet imperdiet arcu, id blandit justo...</p>
						</div>
					<p class="hme-prels-itm-lnk"> <i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					</div>
				</div>	
				<div class="col-12 col-sm-12 col-md-4 col-lg-4">
					<div class="hme-prels-sec-itm">
						<p class="hme-prels-itm-dte">10 Oct 2017</p>
						<div class="hme-prels-itm-info">
							<h4 class="hme-prels-itm-tit">Sed lobortis nunc nec velit mattis, a efficitur lacus lacinia</h4>
							<p class="hme-prels-itm-des">Suspendisse sollicitudin imperdiet fringilla. Aliquam sit amet imperdiet arcu, id blandit justo...</p>
						</div>
					<p class="hme-prels-itm-lnk"> <i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;READ MORE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp;SHARE</p>
					</div>
				</div>
		     @endif
			</div>
			<p class="cmpy-info-btn-sec"><a class="btn thme-clr primaryBackground" {{$Btn}} href="{{url('/press-releases')}}" role="button">view all PRESS RELEASES</a></p>
		</div>
	</section>  
	 
	@endif
    @endforeach
    @endif 
				
	

   <!-- Footer -->
    <footer class="fxd-ftr footer-color">
      <div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-6 res-cpy">
				<p class="cpy-txt footer-text res-form-copy1">Copyright &copy; 2017 {{$setting->company_name}} (SINGAPORE) PTE LTD. All rights reserved.</p>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
			<div class="form-inline">
			  <?php $col = ''; $sit = ''; $right = ''; ?>
			   @if (isset($menuPermissions) && !empty($menuPermissions))
             	    	@foreach($menuPermissions as $menuPer)
                     	  	@if ($menuPer->name == 'Newsletter')
                     	  	
                     	  <?php 
                     	  $sit='stemap-normal';
                     	  $col = 'col-12 col-sm-3 col-md-3 col-lg-3 res-site';
                     	  $right = ''; 
                     	  ?>
                     	  @else
                     	  <?php
                     	  $sit='cpy-txt-stemap';
                     	  $col = 'col-12 col-sm-12 col-md-12 col-lg-12';
                     	  $right = 'text-align: right'; 
                     	  ?>
                     	  
                	@endif
                    @endforeach
                    @endif     	  	
                     	  	
			  <div class="form-group {{$col}}">				
				<a href="{{ route('client.sitemap') }}" class="sit-map-txt hme-anc-itm-lnk footer-text {{$sit}} res-form-site1" style="{{$right}}">Sitemap</a>
			  </div>
			  
			  
			  	@if (isset($menuPermissions) && !empty($menuPermissions))
             	    	@foreach($menuPermissions as $menuPer)
                     	  	@if ($menuPer->name == 'Newsletter')
            	  <div class="form-group col-8 col-sm-8 col-md-8 col-lg-8 nws-subs-sec res-form-per1">				
				<input type="email" class="form-control subemail" id="email" placeholder="Enter email to subscribe to newsletter">
			  </div>
			  <div class="form-group col-3 col-sm-1 col-md-1 col-lg-1">	
			  
			 
				<div class="row">
				     <div class="fa fa-check done"></div>
                     <div class="fa fa-close failed"></div>
                    
				    <button type="submit" class="btn thme-clr subs-btn submit1" onclick="validation()"><i class="material-icons submit">send</i></button>
				    
				    </div>
			</div>
            		
            	   	@endif
                    @endforeach
                    @endif

				<span id ="result">
			   </span>		
			</div>
		</div>
      </div>
      <!-- /.container -->
    </footer>

    <a href="#" class="scrollToTop"><i class="material-icons">keyboard_arrow_up</i></a>
	

@stop
@section('script')

<script type="text/javascript">

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}


function validation(){ 
    
 
 $("#result").html("");
  var email = $("#email").val();
  if (validateEmail(email)) {
    
      var formData = email;
      $.ajax({
            url: '/email',
            type: "POST",
            data: {"formData": formData,"_token":"{{csrf_token()}}"},
            success: function(data) {
               
               if(data == 1){
               //$('.submit1').hide();
                 $(".submit").addClass("loading");
                setTimeout(function() {
                  $(".submit").addClass("hide-loading");
                  // For failed icon just replace ".done" with ".failed"
                  $(".done").addClass("finish");
                }, 2000);
                setTimeout(function() {
                  $(".submit").removeClass("loading");
                  $(".submit").removeClass("hide-loading");
                  $(".done").removeClass("finish");
                  $(".failed").removeClass("finish");
                 //  $('.submit1').show();   
                }, 3000);
                
                
                 setTimeout(function() {          
              $("#result").html("Subscribe to Newsletter Email send successfully"); } , 3000);
              
               $("#result").css("color", "green");
               $('#email').attr('style','border:2px solid #o27e28');
               $('#email').focus();
               document.getElementById("email").value = "";
                   
               }else{
                   
                    $("#result").html("Sorry , Given Email ID is already exist");
                     $("#result").css("color", "red");
                      $('#email').attr('style','border:2px solid #ed1717');
                     return false;
                   
               }
               
               
            
            }
        });
      
   } else {
    $("#result").html(" Please enter the correct email");
    $("#result").css("color", "red");
     $('#email').attr('style','border:2px solid #ed1717');
    return false;
  }
  return false;
}    

</script>


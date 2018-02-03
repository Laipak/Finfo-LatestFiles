<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
         <title>{{$setting->company_name}}</title>
        @yield('seo')
        <link rel="icon" type="image/png" href="/{{$favicon}}">
        <!-- CSS -->
      
        {!! Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
        
      
        
        
        {{-- Start of new style for home  --}}
        {!! HTML::style('client/css/bootstrap.min.css') !!}
        {!! HTML::style('client/css/owl.carousel.css') !!}
        {!! HTML::style('client/css/owl.theme.default.css') !!}
        {!! HTML::style('client/css/font-awesome.css') !!}
        {!! HTML::style('client/css/menu.css') !!}
        {!! HTML::style('client/css/full-slider.css') !!}
        {!! HTML::style('client/css/responsive.css') !!}
        {!! HTML::style('client/css/page-style.css') !!}
        {!! HTML::style('client/css/snavvy.css') !!}
        {!! HTML::style('client/css/switchery.min.css') !!}
        {!! Html::style('https://fonts.googleapis.com/icon?family=Material+Icons') !!}
        
        {{-- End of new style for home  --}}
        
      <style type="text/css">

           .primaryBackground {
                color: {{ $setting->container_color }} !important;
            }    
            .secondaryBackground {
               color: {{ $setting->font_color }} !important;
            }
            
            .thme-clr {
                background-color: {{ $setting->background_color }} !important; 
               
            } 
            
          .thme-clr-aftr:after {
                background-color: {{ $setting->background_color }} !important; 
               
            } 

            
             .thme-clr-font {
                color: {{ $setting->background_color }} !important; 
               
            } 
            
            footer {
                
                border-top: 7px solid {{ $setting->background_color }} !important;
                
            }
            
            .content-clr {
                background-color: {{ $setting->theme_color }} !important; 
               
            } 
            
            .thme-clr-aftr:before {
                background-color: {{ $setting->theme_color }} !important; 
               
            } 
            
            .img {
               width: 138px !important; 
               height: 42px !important; 
               
            } 
            
            
            .thm-bgclr
            {
            	background:{{ $setting->theme_color }} !important; 
            }
            .thm-lnk-clr, .thm-lnk-clr:hover
            {
            	color:{{ $setting->theme_color }} !important; 
            }
            
            .thm-bdrclr{
            	border-color:{{ $setting->background_color }} !important;
            }
            .thm-hgt-bgclr
            {
            	background:{{ $setting->background_color }} !important;
            }
            .thm-hgt-clr
            {
            	color:{{ $setting->background_color }} !important; 
            }
            .thm-hgt-bgclr-hvr:hover
            {
            	background:{{ $setting->theme_color }} !important; 
            }
            .thm-hgt-bgclr-hvr-itm:hover .thm-hgt-bgclr-hvr-itm-hvr {
            	background:{{ $setting->background_color }} !important; 
            }
           
        </style>   
        
        @yield('style')
   
    </head>

    <body>
      <div class="menu-overlay"></div>
       
    
        @yield('content')
      

 

        @yield('script')
       {{-- Start of new style for home  --}}
      
       {!! Html::script('client/js/jquery-1.12.4.min.js') !!}
       {!! Html::script('client/js/bootstrap.bundle.min.js') !!}
       {!! Html::script('client/js/owl.carousel.min.js') !!}
       {!! Html::script('client/js/site.js') !!}
       {!! Html::script('client/js/jquery.fatNav.js') !!}
       {!! Html::script('client/js/snavvy.js') !!}
       {!! Html::script('client/js/switchery.min.js') !!}
      
       {{-- Start of new style for home  --}}


<script type="text/javascript">
    $('#snavvy').snavvy({
		menuItems: '#snavvy-links'
	});	
	
      jQuery(document).ready(function(){

      jQuery(".cmpy-info-hd-ul-lt li.active").prev().addClass("prev");
      jQuery(".cmpy-info-hd-ul-lt li").on("click", function(){
      jQuery(".cmpy-info-hd-ul-lt li").removeClass("active").prev().removeClass("prev");
      jQuery(this).addClass("active").prev().addClass("prev");
      
      });
      
      jQuery(".cmpy-info-hd-ul-rgt li.active").next().addClass("next");
      jQuery(".cmpy-info-hd-ul-rgt li").on("click", function(){
      jQuery(".cmpy-info-hd-ul-rgt li").removeClass("active").next().removeClass("next");
      jQuery(this).addClass("active").next().addClass("next");
	  

   	});
    });
	
	
	$(document).ready(function(){
	  
	  $('.loop').owlCarousel({
		center: true,
		 margin: 20,
		loop:true,
		autoplay: true,		
        responsive: {
          0: {
            items: 1
          },
          700: {
            items: 2
          },
          1000: {
            items: 4
          }
        }
	});
	  
	});
	$(document).ready(function(){	
		//Check to see if the window is top if not then display button
		$(window).scroll(function(){
			if ($(this).scrollTop() > 100) {
				$('.scrollToTop').fadeIn();
			} else {
				$('.scrollToTop').fadeOut();
			}
		});
		
		//Click event to scroll to top
		$('.scrollToTop').click(function(){
			$('html, body').animate({scrollTop : 0},800);
			return false;
		});	
	});
	</script>

    </body>
</html>
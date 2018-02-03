@extends('resources.views.templates.client.template2.frontendhome')
@section('content')
    
    
    <?php 

$actual_link ="$_SERVER[REQUEST_URI]";

?>
     @include('resources.views.templates.client.template2.header')
    
	<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3 class="secondaryBackground">Company Info</h3>  
          </div>          
        </div>        
      </div>
    </header>

    <!--cmpy Info -->
    <section class="page-cnt-sec">
      <div class="container">
		<div class="page-cnt-sec-lay">
			<div class="page-cnt-sec-inr">						
				<div class="row">			
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">	
						<h5>About Us</h5>	
					</div>
					<div class="col-12 col-sm-6 col-md-6 col-lg-6">
					    <?php if(!empty($setting->company_info_img)){  ?>
						<p><img src="{{!empty($setting->company_info_img) ? $setting->company_info_img : "/img/banner.jpg"}}"/></p>
						<?php  }else{ ?>
						<p><img src="{{"img/default-company-info.jpg"}}"/></p>
						<?php  } ?>
					</div>
					<div class="col-12 col-sm-6 col-md-6 col-lg-6">
						<div class="abt-cnt-sec">
						    @if (isset($dataContents) && !empty($dataContents))
                             @foreach($dataContents as $contentPage)
                             
                             <?php $info = html_entity_decode(strip_tags($contentPage['content_description']));
                                if (strlen($info) > '420')
                             ?>
                                
							<p style="font-family: 'source_sans_proregular'">  {!! $info !!}</p>
						     @endforeach
                            @endif 	
						</div>
					</div>
				</div>		
			</div>			
		</div>
	  </div>
    </section>	
	

   

@include('resources.views.templates.client.template2.footer')
    <!-- Footer -->


 
@stop

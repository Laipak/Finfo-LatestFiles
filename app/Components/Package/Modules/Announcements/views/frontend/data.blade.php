



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
 @include('resources.views.templates.client.template2.header')
	<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3>Announcements</h3>  
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
		  
		        
		<section class="content" id="leadership">
         @if (isset($data) && !empty($data))
                <div class="row main-title">
                    <div class="col-md-12">                        
                      <b>  @if ($active_theme == "default")
                            {!! $data['title']!!}
                        @endif
                        </b>
                    </div>
                </div>
                 <div class="content-data">{!!html_entity_decode($data['body']) !!} </div>
        @endif
    </section>
					
		</div>
	  </div>
	  </div>
	  </div>
	  </div>
    </section>	
	

  
@include('resources.views.templates.client.template2.footer')
    <!-- Footer -->
 







@stop









@extends('resources.views.templates.client.template2.frontendhome')
@section('content')
     
<?php 

$actual_link ="$_SERVER[REQUEST_URI]";
	
$link = substr($actual_link, 14);

?>
     @include('resources.views.templates.client.template2.header')
   <style type="text/css">
   .page-cnt-sec h5.cmpy-info-hd
    {
    	padding-bottom:30px;
    }
    .cmpy-info-hd a
    {
    	font-size: 45px;
    	position: relative;
    	top: -20px;
    }
    .cmpy-info-hd a.cmpy-info-hd-lft
    {
    	float: left;
    	left: -13px;
        top: -18px
    	
    }
    .cmpy-info-hd a.cmpy-info-hd-rgt
    {
    	float:right;
    	right: -10px;
        top: -16px;
    }
   </style>
     
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
					     
					     @if (isset($slugContents) && !empty($slugContents))
                           @foreach($slugContents as $page)	
						<h5 class="cmpy-info-hd">
						    <ul class="cmpy-info-hd-ul-lt">
						  	@if (isset($title) && !empty($title))
                              @foreach($title as $titles)
                              
                               @if($link == $titles->name)
                                <?php $linkcss ="active"; ?>
                               @else
                                <?php $linkcss =""; ?> 
                               @endif
                              
                               <li class ={{$linkcss}} >
                              <a href="{{url('company-info/'.$titles->name)}}" class="cmpy-info-hd-lft thm-hgt-clr" style="position:absolute">&#8592;</a>
                                   </li>
              	              @endforeach
				            @endif
				            </ul>  
						    {!! $page['title']!!}
						    
						   <ul class="cmpy-info-hd-ul-rgt">
						  	@if (isset($title) && !empty($title))
                              @foreach($title as $titles)
                              
                               @if($link == $titles->name)
                                <?php $linkcss ="active"; ?>
                               @else
                                <?php $linkcss =""; ?> 
                               @endif
                              
                               <li class ={{$linkcss}} >
						    <a href="{{url('company-info/'.$titles->name)}}" class="cmpy-info-hd-rgt thm-hgt-clr" style="position:absolute">&#8594;
						    </a>  
						    </li>
              	              @endforeach
				            @endif
				            </ul>  
						    
						    </h5>
						  @endforeach  
                        @endif
						 
				 
                    
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="abt-cnt-sec">
						     @if (isset($slugContents) && !empty($slugContents))
                                 @foreach($slugContents as $page)
                    
                        		<p style="font-family: 'source_sans_proregular'">  {!! $page['content_description'] !!}</p>
						     @endforeach
                            @endif 	
						</div>
					</div>
				</div>		
			</div>			
		</div>
	  </div>
    </section>	

<script type="text/javascript">

 


</script>

@include('resources.views.templates.client.template2.footer')
    <!-- Footer -->

 
@stop





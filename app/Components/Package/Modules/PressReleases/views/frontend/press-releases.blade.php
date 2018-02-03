@extends('resources.views.templates.client.template2.frontendhome')

@section('content')

<?php 
$actual_link ="$_SERVER[REQUEST_URI]";
$year = date("Y");
$currently_selected = date('Y'); 
$earliest_year = 2015; 
$latest_year = date('Y');

if($actual_link == '/press-releases'){
   $selectedyear = $year;    
}else{
   $selectedyear = $selected_year;    
}
?>


<style type="text/css">
.selectclass {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}
</style>

   <?php 

$actual_link ="$_SERVER[REQUEST_URI]";

?>
     @include('resources.views.templates.client.template2.header')
   
	<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3 class="secondaryBackground">Press Releases</h3>  
          </div>          
        </div>        
      </div>
    </header>

    <!--cmpy Info -->
    <section class="page-cnt-sec">
      <div class="container">
		<div class="page-cnt-sec-lay">
			<div class="page-cnt-sec-inr">						
				<div class="prsrls-pge-sec">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-4">
							<div class="prsrls-pge-info-sec-inr">
								<h2>View Press Releases  <br> listed in <span class="ance-pge-btn thm-hgt-clr"> </span> <br>								
								 {!! Form::open(array('route' => 'package.admin.press-releases.filter', 'method' => 'post')) !!}   
								 
                                 @if($selectedyear == 'All Year') 
                        		     <?php $j = 0; ?>
                        			 @foreach ($years as $i )
                        			 <?php  $j++; ?>
                        			 @endforeach 
                        			  @if($j > 1 )
                        			  <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" style="border: 0px;outline:0px;"class="selectcolor thm-hgt-clr">
                        		  	  <option id="dropdownMenuButton" style="font-size: 25px;" selected>
                        			 All Year  </option>
                        			
                        			 @else
                        			 <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" class="selectclass thm-hgt-clr" style="border: 0px;outline:0px;">
                        			 @endif  
                        	
                        			 
                        		     <?php $j = 0; ?>
                        			 @foreach ($years as $i )
                        			 <?php  $j++; ?>
                        			 	 @if($j > 1 )
                        			 
                        			 <?php $d = date("Y", strtotime($i->press_date)); ?>
                        			 @if($d == $selectedyear)
                                     <option style="font-size: 25px;" value="{{date("Y", strtotime($i->press_date))}}"  >{{date("Y", strtotime($i->press_date))}}</option>
                                     @else
                        			 <option style="font-size: 25px;" value="{{date("Y", strtotime($i->press_date))}}">{{date("Y", strtotime($i->press_date))}}</option>
                        			 @endif 
                        			 
                        			  @else
                        			   <option style="font-size: 25px;" value="{{date("Y", strtotime($i->press_date))}}"  >{{date("Y", strtotime($i->press_date))}}</option>
                        			  
                        			 @endif  
                        			 @endforeach
                        		     </select>
                        		     
                        		     @else
                        		     
                        		    <?php $j = 0; ?>
                        			 @foreach ($years as $i )
                        			 <?php  $j++; ?>
                        			 @endforeach 
                        			  @if($j > 1 )
                        			  <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" style="border: 0px;outline:0px;"class="selectcolor thm-hgt-clr">
                        		  	  <option id="dropdownMenuButton" style="font-size: 25px;" selected>
                        			 All Year  </option>
                        			
                        			 @else
                        			 <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" class="selectclass thm-hgt-clr" style="border: 0px;outline:0px;">
                        			 @endif  
                        	
                        			 
                        		     <?php $j = 0; ?>
                        			 @foreach ($years as $i )
                        			 <?php  $j++; ?>
                        			 	 @if($j > 1 )
                        			 
                        			 <?php $d = date("Y", strtotime($i->press_date)); ?>
                        			 @if($d == $selectedyear)
                                     <option style="font-size: 25px;" value="{{date("Y", strtotime($i->press_date))}}" selected >{{date("Y", strtotime($i->press_date))}}</option>
                                     @else
                        			 <option style="font-size: 25px;" value="{{date("Y", strtotime($i->press_date))}}">{{date("Y", strtotime($i->press_date))}}</option>
                        			 @endif 
                        			 
                        			  @else
                        			   <option style="font-size: 25px;" value="{{date("Y", strtotime($i->press_date))}}" selected >{{date("Y", strtotime($i->press_date))}}</option>
                        			  
                        			 @endif  
                        			 @endforeach
                        		     </select>
                        		   @endif
                        		   
                    		    {!! Form::close() !!}  
								</h2>
								<p>To view listing for specific year, change “Any Year” from the dropdown.	</p>
							</div>
						</div>	
						<?php $i = 0; ?>
						 @foreach($data as $press)
						<?php  $i++; ?> 
						@if(!empty($press->upload))
        				  <?php $upload = $press->upload; ?>
        				@else
        				  <?php  $upload = ''; ?>
        				@endif
						<div class="col-12 col-sm-12 col-md-6 col-lg-4">
							<div class="prsrls-pge-sec-inr">
								<p class="prsrls-pge-sec-dte">{{date("d M Y", strtotime($press->press_date))}}</p>
								<div class="prsrls-pge-sec-inr-lay">
									<h3>{{$press->title}}</h3>
									@if(!empty($press->description))
								<?php $press = html_entity_decode(strip_tags($press->description));
                                if (strlen($press) > '249')
                                    $presss = substr($press, 0, 249). '..........';
                                    else
                                    $presss = substr($press, 0, 249);
                                 
                                ?>
								<p>{{$presss}}</p>
									@else
									<p>Nunc magna eros, porta tincidunt pharetra quis, tristique sed libero. Quisque consequat...</p>
									@endif
								</div>
								<p class="prsrls-prels-itm-lnk">
								 @if(!empty($upload))
									<span><i class="fa fa-link" aria-hidden="true"></i><a href="/{{$upload}}" target="_brank">&nbsp;&nbsp;READ MORE</a></span>
								  @else
								      <span><i class="fa fa-link" aria-hidden="true"></i><a href="#">&nbsp;&nbsp;READ MORE</a></span>
								  @endif  
									<span> </span>
								</p>
							</div>
						</div>
						@endforeach
						@if(empty($press))
    				 	<div class="col-12 col-sm-12 col-md-12 col-lg-8">
    					    <div class="ance-pge-sec-inr">
    						<p>No Press 
							   Releases </p>
    					    </div>
    					</div>
					    @else
					    @if($actual_link == '/press-releases')
					    @if($i > 5 )
					   	<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					   	    {!! Form::open(array('route' => 'package.admin.press-releases.filter', 'method' => 'post')) !!}
					   	  <input type="hidden" name="year" value="{{$year}}">
					   	  <p class="page-btn-sec"><button type="submit" class="page-btn thm-hgt-bgclr" style="border: none;">Load more</button></p>
    					  {!! Form::close() !!} 
    					</div>
    					@endif
    					@endif
				        @endif
					</div>	
				</div>		
			</div>			
		</div>
	  </div>
    </section>	
	

  
@include('resources.views.templates.client.template2.footer')
    <!-- Footer -->

@stop
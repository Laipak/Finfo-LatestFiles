@extends('resources.views.templates.client.template2.frontendhome')
@section('content')

<?php 
$actual_link ="$_SERVER[REQUEST_URI]";
$year = date("Y");
$currently_selected = date('Y'); 
$earliest_year = 2015; 
$latest_year = date('Y');

if($actual_link == '/announcements'){
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

.selectcolor{
    
    color: {{ $setting->background_color }} !important;
    
}
</style>
  
  @include('resources.views.templates.client.template2.header')
	<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3 class="secondaryBackground">Announcements</h3>  
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
		  <div class="col-12 col-sm-12 col-md-6 col-lg-4">
		    <div class="ance-pge-info-sec-inr">
			<h2>View <br>
			Announcements  <br> listed in <span class="ance-pge-btn thm-hgt-clr"> </span> <br>								
		    <div>
		
			    {!! Form::open(array('route' => 'package.announcements.filter', 'method' => 'post')) !!} 
			    
			@if($selectedyear == 'All Year') 
			    
		     <?php $j = 0; ?>
			 @foreach ($announc as $i )
			 <?php  $j++; ?>
			 @endforeach 
			  @if($j > 1 )
			  <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" style="border: 0px;outline:0px;"class="selectcolor">
		  	  <option id="dropdownMenuButton" style="font-size: 25px;" selected>
			 All Year  </option>
			
			 @else
			 <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" class="selectclass thm-hgt-clr" style="border: 0px;outline:0px;">
			 @endif  
	
			 
		     <?php $j = 0; ?>
			 @foreach ($announc as $i )
			 <?php  $j++; ?>
			 	 @if($j > 1 )
			 
			 <?php $d = date("Y", strtotime($i->announce_at)); ?>
			 @if($d == $selectedyear)
             <option style="font-size: 25px;" value="{{date("Y", strtotime($i->announce_at))}}"  >{{date("Y", strtotime($i->announce_at))}}</option>
             @else
			 <option style="font-size: 25px;" value="{{date("Y", strtotime($i->announce_at))}}">{{date("Y", strtotime($i->announce_at))}}</option>
			 @endif 
			 
			  @else
			   <option style="font-size: 25px;" value="{{date("Y", strtotime($i->announce_at))}}"  >{{date("Y", strtotime($i->announce_at))}}</option>
			  
			 @endif  
			 @endforeach
		     </select>
		     
		     @else
		     
		    <?php $j = 0; ?>
			 @foreach ($announc as $i )
			 <?php  $j++; ?>
			 @endforeach 
			  @if($j > 1 )
			  <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" style="border: 0px;outline:0px;"class="selectcolor">
		  	  <option id="dropdownMenuButton" style="font-size: 25px;" selected>
			 All Year  </option>
			
			 @else
			 <select name="year" aria-labelledby="dropdownMenuButton"  onchange="this.form.submit()" class="selectclass thm-hgt-clr" style="border: 0px;outline:0px;">
			 @endif  
	
			 
		     <?php $j = 0; ?>
			 @foreach ($announc as $i )
			 <?php  $j++; ?>
			 	 @if($j > 1 )
			 
			 <?php $d = date("Y", strtotime($i->announce_at)); ?>
			 @if($d == $selectedyear)
             <option style="font-size: 25px;" value="{{date("Y", strtotime($i->announce_at))}}" selected >{{date("Y", strtotime($i->announce_at))}}</option>
             @else
			 <option style="font-size: 25px;" value="{{date("Y", strtotime($i->announce_at))}}">{{date("Y", strtotime($i->announce_at))}}</option>
			 @endif 
			 
			  @else
			   <option style="font-size: 25px;" value="{{date("Y", strtotime($i->announce_at))}}" selected >{{date("Y", strtotime($i->announce_at))}}</option>
			  
			 @endif  
			 @endforeach
		     </select>
		     
		     
		     
		     
		     
		     @endif
		     
		     
		     
		     
		     
		     
		    {!! Form::close() !!}  
		</div>
		  </h2>
		<p>To view listing for specific year, change “All Year” from the dropdown.	</p>
	   </div>
	</div>
	                    
	                    <?php $i = 0; ?>
						@foreach($data as $announ)
					     <?php  $i++; ?>
						<div class="col-12 col-sm-12 col-md-6 col-lg-4">
							<div class="ance-pge-sec-inr">
								<p class="ance-pge-sec-dte">{{date("d M Y", strtotime($announ->announce_at))}}</p>
								<div class="ance-pge-sec-inr-lay">
								    
								    <?php $title = html_entity_decode(strip_tags($announ->title));
                                        if (strlen($title) > '90')
                                            $title = substr($title, 0, 90). '';
                                            else
                                            $title = substr($title, 0, 90);
                                         
                                        ?> 
									<h3>{{$title}}</h3>
									
									
									@if(!empty($announ->description))
									<?php $anno = html_entity_decode(strip_tags($announ->description));
                                        if (strlen($anno) > '249')
                                            $annou = substr($anno, 0, 249). '...';
                                            else
                                            $annou = substr($anno, 0, 249);
                                    ?>               
									<p style="min-height: 260px;">{{$annou}}</p>
									@else
									<p>Nunc magna eros, porta tincidunt pharetra quis, tristique sed libero. Quisque consequat...</p>
									@endif
								</div>
								<p class="ance-prels-itm-lnk">
								 @if(!empty($announ->file_upload))
									<span><i class="fa fa-link" aria-hidden="true"></i><a href="/files/announcements/{{$announ->file_upload}}" target="_brank">&nbsp;&nbsp;READ MORE</a></span>
								 @else
								    <span><i class="fa fa-link" aria-hidden="true"></i><a href="#">&nbsp;&nbsp;READ MORE</a></span>
								 @endif    
								<!--	<span><i class="fa fa-share-alt" aria-hidden="true"></i>&nbsp;&nbsp; SHARE </span> -->
								</p>
							</div>
						</div>
					
				 	@endforeach
				 
				 	@if(empty($announ))
				 	<div class="col-12 col-sm-12 col-md-12 col-lg-8">
					    <div class="ance-pge-sec-inr">
						<p>No Announcements</p>
					    </div>
					</div>
					@else
					 @if($actual_link == '/announcements') 
					 @if($i > 5 )
				 	<div class="col-12 col-sm-12 col-md-12 col-lg-12">		
					 {!! Form::open(array('route' => 'package.announcements.filter', 'method' => 'post')) !!} 
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


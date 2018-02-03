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
              <h3 class="secondaryBackground">Financials</h3>  
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
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 fianc-cnt-sec">
						<div id="accordion" role="tablist" class="fianc-cnt-sec-acc thm-bdrclr">	
						
				<?php $i = 0; ?>	
				@if(!empty($getLastestInfoData))
				@foreach($getLastestInfoData as $getLastestInfoData)
				
				<?php $i++; ?>
			    <?php if($i == 1){
			        
			        $collaps = "";
			        $heading = "headingOne";
			        $coll    = "collapseOne";
			        $show    = "show";
			    }else {
			        
			        $collaps = "collapsed";
			        $heading = "headingOne".$i;
			        $coll    = "collapseOne".$i;
			        $show    = "";
			    }
			    ?>
			    
						<div class="card">
							<div class="card-header" role="tab" id={{$heading}}>
							  <h5 class="mb-0">
								<a class="{{$collaps}}" data-toggle="collapse" href="#{{$coll}}" aria-expanded="true" aria-controls={{$coll}}>
								    
								 Financial Results for Year {{$getLastestInfoData->year}} <i class="fa fa-chevron-down finac-icn thm-hgt-clr" aria-hidden="true"></i>
								</a>
							  </h5>
							</div>
							<div id={{$coll}} class="collapse {{$show}} fianc-cnt-acc-col" role="tabpanel" aria-labelledby={{$heading}} data-parent="#accordion">
			   
				   @foreach($getDataLastestInfoData as $getDataLastestInfoDatas)
				           @if($getLastestInfoData->year == $getDataLastestInfoDatas->year)
				           
				           <?php
    				           if($getDataLastestInfoDatas->quarter == 1){
    				                $period = 'First';
    				                
    				           }elseif($getDataLastestInfoDatas->quarter == 2){
    				                $period = 'Second';
    				                
    				           }elseif($getDataLastestInfoDatas->quarter == 3){
    				                $period = 'Third';
    				                
    				           }else{
    				                $period = 'Fourth';
    				           }
				          ?>
				           
				           
						     <div class="card-body">
								<h5>Financial Year {{$getDataLastestInfoDatas->year}}, {{$getDataLastestInfoDatas->title}} </h5>
								
									
								
								
							@foreach($getNewDetailFinancialInfoIsActive as $getNewDetailFinancialInfoIsActives)
							
							 @if($getNewDetailFinancialInfoIsActives->latest_financial_highlights_id == $getDataLastestInfoDatas->id)
								<ul class="thm-lnk-clr">
									<li><i class="fa fa-check-square-o fn-li-icn" aria-hidden="true"></i>
									
									@if($getNewDetailFinancialInfoIsActives->value != '0')    
									
									<a href ="{{$getNewDetailFinancialInfoIsActives->value}}" target="_blank" >{{$getNewDetailFinancialInfoIsActives->title}} </a>
									@else
									    <a>{{$getNewDetailFinancialInfoIsActives->title}}</a>
									    @endif
									
									</li>
								</ul>
							@endif		
							@endforeach	
								
							  </div>
						 	@endif			  
				    @endforeach
							</div>
						  </div>
				     			  
					@endforeach
					
				@else
					<div class="card">
							<div class="card-header" role="tab" >
							  <h5 class="mb-0" style="text-align: center;">
								
								    
								 No Data For Financial Results 
							  </h5>
							</div>
						
						  </div>
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
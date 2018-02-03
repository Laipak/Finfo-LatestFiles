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
              <h3 class="secondaryBackground">Events</h3>  
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
						<h5>Investor Relations Events</h5>	
						<div class="table-responsive">
							<table class="evnt-tble table table-striped">							
								<thead class="thm-bdrclr">
								  <tr>
									<th>Date</th>
									<th>Event</th>								
								  </tr>
								</thead>
								  <?php foreach($data as $even) { ?>
								<tbody>
								  <tr>
									<td>{{date("d F, Y", strtotime($even->event_datetime))}}</td>
									<td>{{$even->event_title}}</td>
								  </tr>
								  
								</tbody>
								 <?php } ?>
							</table>
						</div>
						<p class="page-btn-sec" style="display:none"><a class="page-btn thm-hgt-bgclr" href="#" role="button">vIEW earlier EVENTS</a></p>
					</div>						
				</div>		
			</div>			
		</div>
	  </div>
    </section>	
	

  
@include('resources.views.templates.client.template2.footer')
    <!-- Footer -->


@stop


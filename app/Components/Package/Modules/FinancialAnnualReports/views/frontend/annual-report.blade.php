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
              <h3 class="secondaryBackground">Annual Reports</h3>  
          </div>          
        </div>        
      </div>
    </header>

    <!--cmpy Info -->
    <section class="page-cnt-sec">
      <div class="container">
		<div class="page-cnt-sec-lay">
			<div class="page-cnt-sec-inr">						
				<div class="anrpt-pge-sec">
					<div class="row">
						
						<div class="col-12 col-sm-6 col-md-4 col-lg-3">
							<div class="anrpt-pge-info-sec-inr">
								<h2>List of Annual Reports</h2>
								<p>Click on respective cover to view the full report</p>							
							</div>
						</div>	
						<?php $i = 0; ?>
                         @foreach($data as $report)
        
						<div class="col-12 col-sm-6 col-md-4 col-lg-3">
							<div class="anrpt-pge-sec-inr thm-hgt-bgclr-hvr-itm">
								<div class="anrpt-pge-sec-inr-tp txt-center">
									<img class="anrpt-pge-img" src="{{asset(($report->cover_image != '')? $report->cover_image: 'img/client/annual-report/annual_report_2007.jpg')}}"/>
									<p class="anrpt-yr">
									    {{$report->title}}
									  
                                     </p>
								</div>
								<div class="anrpt-pge-sec-inr-btm thm-hgt-bgclr-hvr-itm-hvr">
									<p class="txt-center"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <span style="display:none;"><a href="{{$report->file_pdf}}" target="_blank" >View PDF</a></span></p>
								</div>
							</div>
						</div>
						 <?php $i++ ?>
          
                      @endforeach
					</div>	
				</div>		
			</div>			
		</div>
	  </div>
    </section>	
	


@include('resources.views.templates.client.template2.footer')
    <!-- Footer -->

 

@stop
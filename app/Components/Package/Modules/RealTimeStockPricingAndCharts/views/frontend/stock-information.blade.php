@extends('resources.views.templates.client.template2.frontendhome')
@section('content')

<?php 
$actual_link ="$_SERVER[REQUEST_URI]";
?>

<style>
    
 
    @media(max-width:800px)
    {
        .iframe-cvr
        {
            max-width:100%;
            overflow-x: scroll;
        }
        
    }
    
    
    @media(max-width:800px)
    {
        .col-lg-3 {
            -ms-flex: 0 0 100%!important;
            flex: 0 0 100%!important;
            max-width: 100%!important;
        }
        
        .res3 {
            
            margin-left: 65px!important;
        }
        
        .col-lg-7 {
            -ms-flex: 0 0 100%!important;
            flex: 0 0 100%!important;
            max-width: 100%!important;
        }
        
        .res5 {
            
            margin-left: 50px!important;
        }
        
    }
</style>


@include('resources.views.templates.client.template2.header')
<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3 class="secondaryBackground">Stock Info</h3>  
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
						<div id="accordion" role="tablist" class="" style="text-align:center;">	
						<div class="iframe-cvr">
				         <?php 
                        	foreach($stockURL as $stockURL){
                        	 if(!empty($stockURL->stock_url)){
                          ?>
                          <iframe frameBorder="0" height="465" width="676" style="overflow: hidden;" src="<?php echo $stockURL->stock_url; ?>"></iframe>
                          <?php    
                           }else{
                          ?>  
                          <div class="card">
                        	<div class="card-header" role="tab" >
                        	   <h5 class="mb-0" style="text-align: center;">
                        		 Stock API is not configured. 
                        	  </h5>
                        	</div>
                           </div>
                         <?php
                          } 
                          }
                         ?>
                         </div>
					    </div>
					    
					    <?php if(!empty($resultid)) { ?>
                       <div class="row">	
                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 fianc-cnt-sec-acc thm-bdrclr">
                        <div class="row res3" style="padding: 19px 0 0px;">
                        <b>{{$setting->company_name}} {{$resultid}}</b>
                        </div><div class="row res3" style="padding: 19px 0 0px;">
                        Currency : $SGD
                        </div><div class="row res3" style="padding: 19px 0 0px;font-size: 35px;margin-top: -15px;">
                        <b>${{$result}}</b><div class="row" style="font-size: 13px;padding: 20px;">
                        (${{$changes}})
                        </div>
                        </div>
                        </div>
                        <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                        
                        </div>
                        <div class="col-7 col-sm-7 col-md-7 col-lg-7 fianc-cnt-sec-acc thm-bdrclr">
                             <div class="row res5" style="padding: 19px 0 0px;">
                            <div class="col-8 col-sm-6 col-md-6 col-lg-6"><b>Last Updated</b>
                            </div> 
                            <div class="col-8 col-sm-6 col-md-6 col-lg-6">
                                {{$lastupdate}}
                            </div> 
                            </div>
                            <div class="row res5" style="padding: 19px 0 0px;">
                            <div class="col-8 col-sm-6 col-md-6 col-lg-6"><b>High  / Low</b>
                            </div> 
                            <div class="col-8 col-sm-6 col-md-6 col-lg-6">
                                {{$high}} / {{$low}}
                            </div> 
                            </div>
                            
                            <div class="row res5" style="padding: 19px 0 0px;">
                            <div class="col-8 col-sm-6 col-md-6 col-lg-6"><b>Total Volume</b>
                            </div> 
                            <div class="col-8 col-sm-6 col-md-6 col-lg-6">
                                {{$total}}
                            </div> 
                            </div>
                           
                        
                        </div>
                        </div> 
                    <?php } ?>    
                    
         	     	</div>					
				</div>		
			</div>			
		</div>
	  </div>
    </section>		
@include('resources.views.templates.client.template2.footer')
@stop
@extends('resources.views.templates.client.template2.frontendhome')
@section('content')

 <?php 

$actual_link ="$_SERVER[REQUEST_URI]";

$url = str_replace("/email-alerts/unsubscribe/", "", $actual_link);
    
$cmpid = substr($url, strpos($url, "/") + 1); 

$ret = explode('/', $url);

$email = $ret[0];

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
  
  
  
  
  @include('resources.views.templates.client.template2.headersub')
	<div class="clearfix"></div>
    <header class="pge-header thm-bgclr">
      <div class="container">        
        <div class="row">          
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">           
              <h3 class="secondaryBackground">Email Alerts</h3>  
          </div>          
        </div>        
      </div>
    </header>

  <section class="page-cnt-sec">
      
     <div class="container">
		<div class="page-cnt-sec-lay">
			<div class="page-cnt-sec-inr">	
     
<div class="row">


	<div class="col-12 col-sm-12 col-md-12 col-lg-12">
		@if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        @if(Session::has('global-danger'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global-danger') }}
            </div>
        @endif
        
        	<div class="col-md-12 left-col">
		<p class="description" style="text-align:center;">Are you sure you want to stop receiving emails from us?</p>
	</div>
		{!! Form::open(array('route' => 'package.email-alerts.post-unsubscribe', 'id' => 'frm-ir-calendar', 'class'=> 'form-horizontal')) !!}
			<div class="row">
				<div class="col-md-12 content-email-alert">
				<!--	<p><span>*</span>Required.</p> -->
	                <div class="form-group" style="display: none;">
	                    <label class="control-label col-sm-3 label-txt" for="email">Email</label>
	                    <div class="col-sm-9 col-md-10 required">
	                      <input name="email" type="text" value="{{$email}}" class="form-control">
	                      {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
	                      {!! (Session::has('email_erorr'))? '<span class="help-block">'.Session::get('email_erorr').'</span>' : ''!!}
	                    </div>
	                </div>

                <div class="form-group" style="display: none;">
                    <input name="cmpid" type="text" value="{{$cmpid}}" class="form-control">
                </div>

	                <div class="form-group" style="display: none;">
                            <label class="control-label col-md-10" for="password">Category</label>
                            <div class="col-md-10">
	                            @foreach($modules as $cat)
		                   <!--         @if($cat->name != 'Email Alerts' && $cat->name != 'Media Access'  && $cat->name != 'URL Manager - Webcast' && $cat->name != 'Press Releases' && $cat->name != 'Presentations') -->
			                            <div class="checkbox"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                               <label>
			                                  <input type="checkbox" name="category[]" value="{{$cat->id}}" checked >{{$controller->getNavigationByRouteName($cat->route_name, $cat->name )}} 
			                               </label>
			                            </div>
		                        <!--    @endif -->
	                            @endforeach
	                            {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                            	{{ (isset($error_recaptcha))? $error_recaptcha : ''}}
        					</div>
                    </div>               
	               
	
	                <br><br>
	                <div class="form-group" style="text-align:center;">
	                    <label class="control-label col-sm-3" for="email"></label>
	                    <div class="col-sm-12">
	                      <input type="submit" class="btn btn-customize" value="Unsubscribe" onclick="myFunction()" style="text-align:center;">
	                    </div>
	                </div>
	               
				</div>
			</div>

		</form>
	</div>
</div>
 
  </div>
  </div>
  </div>
     
     
    </section>	
    
    
    


@stop



@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Eamil alerts</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
@if ($active_theme == "default")
	{!! Html::style('css/package/general-style.css') !!}
	<style type="text/css">
	.error, .help-block{ 
		color: red;
	}
	.label-txt {
		padding-top: 11px !important;
	}
	.main-footer{
		border-top: 1px solid #DFDFDF;
	}
	.top-content {
	    padding-bottom: 0px;
	}
	</style>
@endif
@stop

@section('script')


<script>
function myFunction() {
    alert("Succesfully unsubscribed");
}
</script>


<script type="text/javascript">
    $('.menu-active').removeClass('menu-active');
    $('#email-alerts').addClass('menu-active');
</script>

@include('resources.views.templates.client.template2.footer')
@stop
	


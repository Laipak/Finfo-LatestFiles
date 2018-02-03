@extends($app_template['frontend'])

@section('content')
<div class="panel-grid" id="pg-453-8" >
	<div class="panel-row-style-default panel-row-style nav-one-page" style="background-color: #f7f7f7; padding-top: 100px; padding-bottom: 80px; " id="contact-us" ><div class="container">
                @if (session()->has('successSendToAdmin'))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('successSendToAdmin') }}
                            </div>
                        </div>
                    </div>
                @endif
		<div class="panel-grid-cell" id="pgc-453-7-0" >
			<div class="panel-builder widget-builder widget_origin_title panel-first-child" id="panel-453-7-0-0">
				<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-noraml">
					<h2  class="align-center">CONTACT US</h2>
				</div>
			</div>
			<div class="panel-builder widget-builder widget_origin_title" id="panel-453-7-0-1">
				<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-noraml">
					<h4  class="align-center">We'll love to hear from you. Get in touch with us!</h4>
				</div>
			</div>
			<div class="panel-builder widget-builder widget_origin_title" id="panel-453-7-0-2">
				<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-noraml">
					<h2  class="align-center"></h2>
					<div class="divider colored"></div>
				</div>
			</div>
			<div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-7-0-3">
				<div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank"><div style="margin-bottom:40px;"></div>
				</div>
			</div>
			<div class="panel-builder widget-builder widget_siteorigin-panels-contact panel-last-child" id="panel-453-7-0-4">
				<div role="form" class="wpcf7" id="wpcf7-f935-o1" lang="en-US" dir="ltr">
					<div class="screen-reader-response"></div>
                                        {!! Form::open(array('route' => 'finfo.contact.save', 'method' => 'post', 'class' => 'wpcf7-form', 'id' => 'frm_contact', 'novalidate'=>"novalidate")) !!}
						<div class="dikka-form-simple-center">
							<div class="f-name">
								<span class="wpcf7-form-control-wrap your-name">
                                                                    <input type="text" value="{{Input::old('name')}}" name="name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="NAME *" required minlength="2" />
                                                                </span>
                                                             {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
							</div>
							<div class="f-email">
								<span class="wpcf7-form-control-wrap your-email">
                                                                    <input type="email" name="email" value="{{Input::old('email')}}" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="EMAIL *" required email/>
                                                                </span>
                                                            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
							</div>
							<div class="f-subject">
								<span class="wpcf7-form-control-wrap your-subject">
                                                                    <input type="text" name="subject" value="{{Input::old('subject')}}" size="40" minlength="2" class="wpcf7-form-control wpcf7-text" aria-invalid="false" placeholder="SUBJECT" required/>
                                                                </span>
                                                            {!! $errors->first('subject', '<span class="help-block">:message</span>') !!}
							</div>
							<div class="f-message">
								<span class="wpcf7-form-control-wrap your-message">
                                                                    <textarea name="message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="MESSAGE *" required minlength="2">{{Input::old('message')}}</textarea>
                                                                </span>
                                                            {!! $errors->first('message', '<span class="help-block">:message</span>') !!}
							</div>
							<div class="bt-contact">
                                                            <a class="btn-color btn-color-1d tp-button darkgrey" href="javascript:;">
                                                                <input type="submit" value="SEND EMAIL" class="wpcf7-form-control wpcf7-submit" />
                                                            </a>
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<div class="panel-grid" id="pg-453-9" >
    <div class="panel-row-style-default panel-row-style" style="background-color: #033873; padding-top: 30px; padding-bottom: 63px; " >
	    <div class="container">
		    <div class="panel-grid-cell" id="pgc-453-8-0" >
			    <div class="well well-sm panel-builder widget-builder widget_sow-icons panel-first-child" id="panel-453-8-0-0">
			    	<div class="so-widget-sow-icons so-widget-sow-icons-base">        
				        <div class="sow-icons-list sow-icons-responsive">
				        	<div class="sow-icons-icon sow-icons-icon-last-row" style="width: 100%">        
					            <style>
					            #icon0{
					                background-color: transparent;
					                border: 1px solid transparent;
					                width:54px;
					                height:54px;
					                line-height:54px;
					            }
					            #icon0:hover{
					                background-color: transparent;
					                border: 1px solid transparent;
					            }
					            #icon0:after {
					                box-shadow: 0 0 0 1px transparent;
					            }
					            </style>

					            <div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >
					                <div id="icon0" class="hi-icon  animated bounceIn"  data-wow-delay="s">
					                     <div class="sow-icon-elegantline" data-sow-icon="&#xe025;" style="font-size: 54px; line-height: 54px; color: #00ff00"></div> 
					                </div>
					            </div>
						    </div>

						    </div>
						    </div>
						    </div>
					    <div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-8-0-1">
						    <div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank">
						    	<div style="margin-bottom:5px;"></div>
						    </div>
					    </div>					    
			</div>			
			<div class="col-md-12">
				<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-lightest">
		    		<h4 class="align-center">Visit our offices</h4>
		    	</div>
		    </div>
	    </div>
	    <div class="col-sm-6" id="panel-453-8-0-2">
		    <div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-lightest">
		    	<h4 class="align-center">648a Geylang Rd, Singapore 389578.</h4>
		    </div>
	    </div>
	    <div class="col-sm-6" id="panel-453-8-0-3">
		    <div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-lightest">
		    	<h4 class="align-center">Level 1, 10-16 Dorcas St, South Melbourne, Victoria 3205, Australia.</h4>
		    </div>
	    </div>
    </div>
</div>
<div class="panel-grid" id="pg-453-10" > 
    <div class="panel-row-style-wide panel-row-style nav-one-page" id="Melbourne_map" >
	   	<div class="panel-grid-cell col-sm-6" id="pgc-453-9-0">
	        <div class="panel-builder widget-builder widget_origin_map panel-first-child panel-last-child" id="panel-453-9-0-0">
	        	<div class="origin-widget origin-widget-map origin-widget-map-default-default">
		        	<div id="map"></div>
			    </div>
			</div>
	    </div>
	</div>
    <div class="panel-grid-cell col-sm-6" id="pgc-453-9-0" style="border-left: 2px solid; padding-left: 0px;">
        <div class="panel-builder widget-builder widget_origin_map panel-first-child panel-last-child" id="panel-453-9-0-0">
        	<div class="origin-widget origin-widget-map origin-widget-map-default-default">
	        	<div id="map_au"></div>		        	
	        </div>
	    </div>
    </div>
</div> 
</div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&amp;sensor=false"></script>
<script type="text/javascript">
  var geocoder;
  var map;
  var map2;
  var address ="648 Geylang Rd, 389578";
  var address2 ="10-16 Dorcas St, South Melbourne, Victoria 3205";

    function mapinitialize() {

        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var latlng2 = new google.maps.LatLng(-37.829916, 144.969942);

        var myOptions = {
            zoom: 14,
            center: latlng,
            scrollwheel: false,
            scaleControl: false,
            disableDefaultUI: false,
            panControl:true,
            zoomControl:true,
            mapTypeControl:true,
            scaleControl:true,
            streetViewControl:true,
            overviewMapControl:true,
            rotateControl:true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var myOptions2 = {
            zoom: 14,
            center: latlng2,
            scrollwheel: false,
            scaleControl: false,
            disableDefaultUI: false,
            panControl:true,
            zoomControl:true,
            mapTypeControl:true,
            scaleControl:true,
            streetViewControl:true,
            overviewMapControl:true,
            rotateControl:true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };


        map = new google.maps.Map(document.getElementById("map"), myOptions);
        map2 = new google.maps.Map(document.getElementById("map_au"), myOptions2);

	        if (geocoder) {
	          geocoder.geocode( { 'address': address}, function(results, status) {
	            if (status == google.maps.GeocoderStatus.OK) {
	              if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
	              map.setCenter(results[0].geometry.location);

	                var infowindow = new google.maps.InfoWindow(
	                    { content: '<b>Office Address:<br>648a Geylang Rd,<br>Singapore 389578</b>',
	                      size: new google.maps.Size(150,50)
	                    });

	                var image = "{{asset('img/finfo/imgs/map_icon.png')}}";

	                var marker = new google.maps.Marker({
	                    position: results[0].geometry.location,
	                    map: map, 
	                    title:address,
	                    icon: image,
	                }); 	              

	                google.maps.event.addListener(marker, 'click', function() {
	                    infowindow.open(map,marker);
	                });

	              } else {
	                alert("No results found");
	              }
	            } else {
	              alert("Geocode was not successful for the following reason: " + status);
	            }
	          });
	        }
	        if (geocoder) {
	          geocoder.geocode( { 'address': address2}, function(results, status) {
	            if (status == google.maps.GeocoderStatus.OK) {
	              if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
	              map2.setCenter(results[0].geometry.location);

	                var infowindow = new google.maps.InfoWindow(
	                    { content: '<b>Office Address:<br>Level 1, 10-16 Dorcas St <br>South Melbourne<br> Victoria 3205, Australia</b>',
	                      size: new google.maps.Size(150,50)
	                    });

	                var image = "{{asset('img/finfo/imgs/map_icon.png')}}";
	             
	                var marker2 = new google.maps.Marker({
	                    position: results[0].geometry.location,
	                    map: map2, 
	                    title:address,
	                    icon: image,
	                }); 

	                google.maps.event.addListener(marker2, 'click', function() {
	                    infowindow.open(map2,marker2);
	                });

	              } else {
	                alert("No results found");
	              }
	            } else {
	              alert("Geocode was not successful for the following reason: " + status);
	            }
	          });
	        }
        }
        mapinitialize();
    </script> 
    @stop
@section('seo')
    <title>Finfo | Contact</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('script')
    {!! Html::script('js/moment.min.js') !!}
    {!! Html::script('js/jquery.validate.min.js') !!}
    <script>
        $(document).ready(function($) {
             $("#frm_contact").validate({
                rules: {
                    'name': 'required'
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        })
    </script>
@stop
@section('style')
<style>
#frm_contact span.help-block,
#frm_contact label.error
{
    color:red;
    font-weight: normal;
}
#map_au{
	width:100%;
	height:350px;
	position: relative;
	max-width: 100%;
}
object, embed, video {
    max-width: 100%;
}

/* GRAYSCALE EFFECT */
#map_au{
	-webkit-filter: grayscale(100%);
	-moz-filter: grayscale(100%);
	-ms-filter: grayscale(100%);
	-o-filter: grayscale(100%);
	filter: grayscale(100%);
	filter: gray;
	-o-transition: all 0.2s linear;
	-webkit-transition: all 0.2s linear;
	-moz-transition: all 0.2s linear;
	transition: all 0.2s linear;
}
#map_au:hover{
	filter: none;
	-webkit-filter: grayscale(0%);
}
.panel-grid-cell {
	padding-right: 0px !important;
}
</style>
@stop
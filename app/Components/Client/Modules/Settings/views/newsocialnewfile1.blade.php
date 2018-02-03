<?php 

	function time_elapsed_string($datetime, $full = false) {
 
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
 
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
 
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
 
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
} 
                



?>

@extends($app_template['client.backend'])
@section('title')
Grapevine
@stop


@section('content')

<style>

.social-cnt-sec-graph{
    background: #fff;
    padding: 5px;
    border-bottom: 3px solid #dcdcdc;
    margin-top: 6px;
    -webkit-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    -moz-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    margin-bottom: 15px;
    height:auto;
}


 .canvasjs-chart-credit{
          display: none;
    }
/*
.canvasjs-chart-container {
    
    
    width:800px;
    height:600px !important;
    
    
}

.canvasjs-chart-canvas {
    
    width:600px;
   
    margin-left:110px;
    margin-top:10px;
    
}*/

.fa-facebook-square {
  background: white;
  color: #3B5998;
}

.fa-twitter {
  background: #55ACEE;
  color: white;
}

.fa-youtube-play {
  background: white;
  color: #bb0000;
}

.fa-instagram {
  background: white;
  color: #125688;
}

a {
    text-decoration: none !important;
}	


#loading-img {
    background: url(../../../img/social/loader.gif) center center no-repeat;
    height: 100px;
    z-index: 20;
}

.overlay {
    background: #e9e9e9;
    display: none;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 0.8;
}


span.markBlue {
  background-color: #FFE39B; color:#ED7356;
}

</style>

<section class="content">
    <div class="row head-search">

      
    <div class="col-md-6">   
   
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" id="feeds" href="#feed"> <i class="fa fa-bars"></i>&nbsp; Feed</a></li>
            <li><a data-toggle="tab" href="#reports" id="repor"> <i class="fa fa-area-chart"></i> &nbsp;Reports</a></li>
            <li><a data-toggle="tab" href="#export"> <i class="fa fa-download"></i> &nbsp;Export</a></li>
        
        </ul>
    
    </div>
        
        <div class="col-md-6" id="key_form"> 
        
              <form class="form-horizontal" method="post" id="srch_form"> 
              <div class="form-group">
                <div class="col-xs-9 col-sm-offset-2 col-sm-6">
                    <div class="row">
                        <input type="text" class="form-control social-scrch-inpt" placeholder="Enter a keyword to track" name="srch" id="srch">
                        
                        <div class="dropdown">
                            
                        <span style="margin-left:250px;">
                            
                            <a data-target="#dropdown"  data-toggle="collapse" class="dropdown-toggle" ><img src='../../../img/social/dots.png' width=20px height=20px style="margin-top:-65px" /></a>
                           
                            
                        <div id="dropdown" class="collapse dropdown-menu location-style inse">
                           
                            <p>
                                
                                Filter by Country : 
                                
                            </p>
                            
                            <p>
                                  <select id="tag_list" name="tag_list[]"  multiple class="form-control" style="width:245px"></select>
                                 
                                
                            </p>
                            
                            <p>
                                <input type="checkbox" name="sensitive" id="sensitive" value="1"/> &nbsp;All keys should be case sensitive
                            </p>
                        </div>
                            
                        </span>
                        
                       
                        </div>
                        
                    </div>
                </div>
                
                 <input type="hidden" id="key" name="key">
                   
                <div class="col-xs-3 col-sm-2">
                    <div class="row">
                        <button type="submit" id="srch_btn" name="srch_btn" class="btn btn-success social-scrch-btn">Save</button>
                    </div>
                </div>
              </div>
            <input type="hidden" id="first_key_id" name="first_key_id" />
            <input type="hidden" id="tab_value" name="tab_value" />
            <input type="hidden" id="filter_type" name="filter_type" />
             </form>
        
        
        </div>



    </div>
    
  
        
       
        
        

    
  <!-- <p class="social-xl-sec"><button id="export" class="btn btn-default"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel File</button> </p> -->
   
      <input type="hidden" id="min_count" name="min_count" />   
      <input type="hidden" id="max_count" name="max_count" />
      
      <input type="hidden" id="company_id" value="<?php echo Session::get('company_id');?>" />
      
      <input type="hidden" id="group_id" value="<?php echo $group_id; ?>" />
   
   <div style="margin-bottom: 65px;">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="col-sm-2 col-md-2 col-lg-2">
            Result For : 
        </div>
        <div class="col-sm-10 col-md-10 col-lg-10">
             <div class="col-sm-4 col-md-4 col-lg-4">
                    <span id="kname" style="font-weight: bold;">
                        
                        
                    </span>
                    
                     <a style="display:none;margin-left:110px; margin-top:-20px;cursor:pointer" id="show_edit" data-target="#updatekey"  data-toggle="collapse">Edit</a>
                    
            </div>
            
            <div class="col-sm-8 col-md-8 col-lg-8">
                
            </div>
             
        </div>
    </div> 
    
    <div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:15px">  
   
        <div id="updatekey" class="collapse upda">
        <div class="col-sm-2 col-md-2 col-lg-2">
            Filter by Country :
        </div>
        
        <div class="col-sm-10 col-md-10 col-lg-10">
            <select id="tag_list1" name="tag_list1[]"  multiple class="form-control" style="width:545px;height:35px"></select>
            
           
            
            <input type="hidden" id="al_loca" />
            
            <p id="difini">
                
              Added Locations - <span id="defin"> </span>  
            </p>
            
            <p style="margin-top:5px">
              <input type="checkbox" name="up_sensitive" id="up_sensitive" value="1"/> &nbsp;All keys should be case sensitive
            </p>
            
            
            <button id="k_update" class="btn btn-sm btn-success social-update-btn">Update</button>
            
        </div>
    
    
          <input type="hidden" id="edit_time" value="0" />
        </div>
        
        </div>
        <br />
    </div>
   
   
   
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="col-sm-2 col-md-2 col-lg-2">
            Saved Keywords :   
        </div>
        <div class="col-sm-10 col-md-10 col-lg-10" style="height:110px;overflow:auto">
          <span id="key_response" style="font-weight: bold;"></span>
        </div>
    </div> 
   
   <br />
 
 
 
     
    <div id="socials">
    
    <div class="row" style="margin-top:20px">
    
         
    <div class="tab-content">
        
       
         
        <div id="feed" class="tab-pane fade in active">
            <div class="col-sm-12 col-md-9 col-lg-9" id="facebook">
                
                <div id="respon" style="margin-left:400px;display:none"><img src='../../../img/social/loader.gif'/></div>
                
           
            <div class="overlay">
                    <div id="loading-img"></div>
            </div>
             <div class="social-cnt-sec-fb" id="t_response">
               
              
             </div>
           
            <div class="row" id="load_action">
            <div class="col-sm-12 col-md-12 col-lg-12">
           <p class="scl-load-sec"><a id="loadMore">Load More</a> </p> 
          
            </div>
            </div>
             
         </div>
         
         
        
        
        </div>
        
         <div id="respon" style="margin-left:230px;display:none"><img src='../../../img/social/loader.gif'/></div>
        
        <div id="reports" class="tab-pane fade">
           
            <div class="col-sm-12 col-md-9 col-lg-9" id="facebook">
                
                
            
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#influence"  id="in_report">  Influencers</a></li>
            <li><a data-toggle="tab" href="#mention" id="me_report"> Mentions</a></li>
            <li><a data-toggle="tab" href="#sentiment" id="sen_report">Sentiments </a></li>
        
        </ul>
    
                 
            <div class="tab-content">
                
                  
                <div id="influence" class="tab-pane fade in active">
                    
                <div class="overlay">
                    <div id="loading-img"></div>
                </div>
                     
                <div class="social-cnt-sec-fb-new-one" id="feed_reports">
                 
                    
                    
                
                </div>
                
                </div>
                
                
                <div id="mention" class="tab-pane fade">
                    
                 <div class="overlay">
                    <div id="loading-img"></div>
                </div>
                     
                <div class="social-cnt-sec-fb-new-one" id="mention_report">
                 
                     <div id="mention-reach">
                    <p class="by-reach">
                 
                        Top 5 Reach
                 
                    </p>
            
                    <div class="social-cnt-sec-fb-mention" id="mention_by_reach">
                    
                    </div>
                    
                    </div>
                    
                    <div id="mention-viral">
                    <p class="by-reach">
                 
                        Top 5 Virality
                 
                    </p>
            
                    <div class="social-cnt-sec-fb-mention" id="mention_by_virality">
                    
                    </div>
                    
                    </div>
                    
                    </div>
                    
                
                </div>
                
               
               
                
                <div id="sentiment" class="tab-pane fade">
               
                 <div class="overlay">
                    <div id="loading-img"></div>
                </div>
                
                <div class="social-cnt-sec-graph" id="sentiment_report">
                 
                 <p class="by-reach">
                 
                        Automated Sentiment  Distribution
                 
                    </p>
                    
                    <div id="chart" style="height: 300px; width: 100%;" >
                        
                        
                        
                        
                        
                    </div>
                    
                
                </div>
                
               
                
                </div>
                
            </div> 
                
            </div>
        </div>
        
        <div id="export" class="tab-pane fade">
            <div class="col-sm-12 col-md-9 col-lg-9" id="facebook">
                
            <div class="col-sm-12 col-md-12 col-lg-12">
              
              <div class="col-sm-3 col-md-3 col-lg-3">
                <button id="export_excel" class="btn btn-normal social-btn-sec">
                <i class="mmicon-file-excel"></i> <span>Excel</span> </button>
              </div>
              
              <div class="col-sm-2 col-md-2 col-lg-2"></div> 
              
              <div class="col-sm-3 col-md-3 col-lg-3">
                <button id="export_pdf" class="btn btn-normal social-btn-sec">
                <i class="mmicon-file-pdf"></i> <span>PDF</span> 
                </button>
              </div>
            </div>
              
                
            </div>
        </div>
    
    </div>
         
         
         
          <div class="col-sm-12 col-md-6 col-lg-3" id="keysorting">
            
             
            <div class="social-cnt-sec-fb-new" id="" style="font-size:12px">
                
                  <div class="container">
                
                    <p><b>SHOW ONLY</b></p>
                    
                    <p>
                      <input type="radio" name="showonly" value="all" checked onclick="get_filter_key()"> All
                    </p>
                    
                    <p>
                       <input type="radio" name="showonly" value="web" onclick="get_filter_key()"> Web
                    </p>
                    
                      <p>
                        
                        <input type="radio" name="showonly" value="facebook" onclick="get_filter_key()"> Facebook
                    </p>
                    
                      <p>
                        
                        <input type="radio" name="showonly" value="twitter" onclick="get_filter_key()"> Twitter
                    </p>
                    
                    <p>
                        
                       <input type="radio" name="showonly" value="youtube" onclick="get_filter_key()"> Youtube
                    </p>
                    
               
                    <p>
                        
                         <input type="radio" name="showonly" value="instagram" onclick="get_filter_key()"> Instagram
                    </p>
                    
                
                </div>
                <hr />
            </div>
        </div>
        
         
          
   
 </div>   
</section>



@stop

@section('style')


<link rel="stylesheet" type="text/css" href="{{ asset('css/client/social.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/color_picker/css/bootstrap-colorpicker.min.css') }}">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet" />

@stop

@section('script')

	{!! Html::script('js/canvasjs.min.js') !!}
	
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js" charset="UTF-8"></script>
	
	<script src="{{ asset('plugins/color_picker/js/bootstrap-colorpicker.min.js') }}"></script>
	
<script src="{{ asset('plugins/color_picker/js/bootstrap-colorpicker.min.js') }}"></script>


<script>  


$(function() {
    
  // $('#tag_list').val('');
 //  $('#tag_list1').val('');
   
    $('#tag_list').select2({
            placeholder: "Add Country...",
            minimumInputLength: 2,
            ajax: {
                url: baseUrl + '/admin/setting/social/country-list',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        
        
         $('#tag_list1').select2({
            placeholder: "Add Country...",
            minimumInputLength: 2,
            ajax: {
                url: baseUrl + '/admin/setting/social/country-list',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    
    
     
});

  $(function() {
      
     $("#from_date").datepicker(
        
            { dateFormat: 'dd-mm-yy', onSelect: getdaterange }
        
        );
        
    $("#to_date").datepicker(
        
         { dateFormat: 'dd-mm-yy' , onSelect: getdaterange }
        
        ).datepicker("setDate", new Date());
        
        
        
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    
    var target = $(e.target).attr("href");
    
    $('#tab_value').val(target);
    
    });
    
    
    
  
  });
  

$(document).ready(function(){ 
    
    $('#min_count').val('0');
    $('#max_count').val('100');
    
      var company_id = $('#company_id').val();
      
      var first_key_id = $('#first_key_id').val();
      
      var group_id = $('#group_id').val();
      
     
      get_all_keys(group_id);
      
    
    var dateToday = new Date();
   
	
	$('#srch_form').on("submit", function(event){
		    
		   $('.inse').collapse('hide');
		   
		   $('#t_response').hide();
		   
		   $('#loadMore').hide();
		  
		  event.preventDefault();
		  
		  var srch_trm = $('#srch').val();  
		     
		  var company_id = $('#company_id').val();
		  
		  var group_id = $('#group_id').val();
		  
		  var country = $('#tag_list').val();
		  
		  if (document.getElementById('sensitive').checked) {
          
                var sensitive = 1;
        
		      
		  } else {
            
                var sensitive = 0;
		      
		  }
		 
		  
           $.ajax({  
                url: baseUrl + '/admin/setting/social/storeKeyFeed',
                type: 'POST',
                data:{"srch_trm":srch_trm,"country":country,"sensitive":sensitive,"_token":"{{csrf_token()}}"},
                
                 beforeSend: function(){
              
                    $("#respon").show();
                },
                
                success:function(data){
                
                    var okay = get_all_keys(group_id);
                    
                    if(okay){
                 
				    $('#t_response').show();
				    
				    $('#tag_list').val('');
				  
				  var resp = data.split('^')[0];
				  
				  if(resp != ''){
				      	$('#t_response').html(resp);
				      	$('#loadMore').show();
				  }else{
				    	$('#t_response').html('Sorry, No Feed Found');      
				  }
				
					
					$('#kname').html(data.split('^')[1]);
					$('#first_key_id').val(data.split('^')[2]);
				   
				    
				    $('#feed_reports').show();
		     
		            $('#mention_report').show();
		            
		          
		          // $('p').highlight(data.split('^')[1]);
		          
	var keyword = data.split('^')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
		          
                    }
					
                },
                
                complete:function(data){
        
                     $("#respon").hide();
                }
        
           });  
		  
	   });
		
		
		
		

		
 });  
 
 
 
 $('#k_update').click(function(){
     
    $('#t_response').hide();
		   
	$('#loadMore').hide();
	

	var key_id = $('#first_key_id').val();  
		     
    var company_id = $('#company_id').val();
		  
	var group_id = $('#group_id').val();
	  
	var country = $('#tag_list1').val();
	

	var already_exit = $('#defin').html();
	
	if(already_exit == ''){
	    
	   var old_country = '';
	   
	}else{
	    
	    var old_country =   $('#defin').text();
	    
	    
	}
	var country = country;
	


	if (document.getElementById('up_sensitive').checked) {
          
        var up_sensitive = 1;
        
	} else {
            
        var up_sensitive = 0;
		      
	}
	
	   
    $.ajax({  
        url: baseUrl + '/admin/setting/social/updateKeyFeed',
        type: 'POST',
        data:{"key_id":key_id,"country":country,"sensitive":up_sensitive,"_token":"{{csrf_token()}}"},  
        
        beforeSend: function(){
        
            $("#respon").show();
        },
        
        success:function(data){
         
         
		    var okay = get_feed_key(data.split('^')[0]);
		    
		    if(okay){
		         $('.upda').collapse('hide');
		         $('#tag_list1').val('');
		    }
		    
			$('#kname').html(data.split('^')[1]);
			$('#first_key_id').val(data.split('^')[0]);
		  
		   
		    $('#feed_reports').show();
     
            $('#mention_report').show();
		    
           // $('p').highlight(data.split('^')[1]);
           
           	var keyword = data.split('^')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
			
			 $('.upda').collapse('hide');
        },  
        
        complete:function(data){
        
             $("#respon").hide();
        }
        
    });  
		  
	 
     
 });
 
 
 function get_all_keys(group){
     
     
     var group = group;
     

       $.ajax({  
                url: baseUrl + '/admin/setting/social/getKeys',
                type: 'POST',
                data:{"group":group,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
                    get_feed_key(res.split('($)')[1]);
                    
                    $('#key_response').html(res.split('($)')[0]);
				   
				    var total_keys = res.split('($)')[2];
				    
				    if(total_keys >= 2){
				        
				        $('#key_form').hide();
				        
				        
				    }else{
				        
				        $('#key_form').show();
				        
				    }
				  
                }  
           });  
     
 }
 
 

 function get_feed_key(keyid){
     
    $('.upda').collapse('hide');
  
	$('#t_response').hide();
		 
	$('#loadMore').hide();
    
    $('#srch').val('');
    
    $('#tag_list1').val('');
    
    $('#up_sensitive').checked= false;
     
    var show_only = $('input[name=showonly]:checked').val();
    
    var key_id = keyid;
    
    var report_type = $('#tab_value').val();
    
   
    if(report_type == '#influence' || report_type == '#reports'){
        
     //   $('#feed_reports').hide();
    $.ajax({  
        url: baseUrl + '/admin/setting/social/reports',
        type: 'POST',
        data:{"key_id":key_id,"_token":"{{csrf_token()}}"},  
        
        beforeSend: function(){
       
            $(".overlay").show();
       },
        
        success:function(res){
            
		     $('#feed_reports').show();
		   
			$('#feed_reports').html(res.split('^')[0]);
			
		    $('#first_key_id').val(res.split('^')[1]);
		    
		    $('#kname').html(res.split('^')[2]);
		
		    // $('p').highlight(res.split('^')[2]);
		    
	var keyword = res.split('^')[2],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
		
        },
        
        complete:function(data){
       
            $(".overlay").hide();
        }
    });  
        
        
        
        
    }else if(report_type == '#mention'){
        
         $('#mention_report').hide();
         
        
         
        if(show_only == 'all'){
        
             var types = 'normal';
             var data_s = {"key_id":key_id,"types":types,"_token":"{{csrf_token()}}"};
        
        }else{
            
            var types = 'filter';
            var data_s = {"key_id":key_id,"types":types,"show_only":show_only,"_token":"{{csrf_token()}}"};
          
        }
         
         
         
     $.ajax({  
        url: baseUrl + '/admin/setting/social/mentionreports',
        type: 'POST',
        data: data_s,  
        
        beforeSend: function(){
           
            $(".overlay").show();
        },
        
                    
        success:function(response){
            
		     $('#mention_report').show();
		   
		   	if(!response.split('^')[0]){
			   
			   $('#mention-reach').hide();
			    
			}else{
			    $('#mention-reach').show();
			  	$('#mention_by_reach').html(response.split('^')[0]);
			    
			}
		     
		
			
			if(!response.split('^')[1]){
			   
			   $('#mention-viral').hide();
			    
			}else{
			    $('#mention-viral').show();
			   $('#mention_by_virality').html(response.split('^')[1]);
			    
			}
			
			$('#kname').html(response.split('^')[3]);
		    $('#first_key_id').val(response.split('^')[2]);
		
		    //  $('p').highlight(response.split('^')[3]);
		    
	var keyword = response.split('^')[3],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
        },
        
        complete:function(data){
        
             $(".overlay").hide();
        }
        
    });  
        
        
        
        
    }else if (report_type == '#sentiment'){
        
         $('#sentiment_report').hide();
         
           if(show_only == 'all'){
        
             var types = 'normal';
             var data_s = {"key_id":key_id,"types":types,"_token":"{{csrf_token()}}"};
        
        }else{
            
            var types = 'filter';
            var data_s = {"key_id":key_id,"types":types,"show_only":show_only,"_token":"{{csrf_token()}}"};
          
        }
         
     $.ajax({  
        url: baseUrl + '/admin/setting/social/sentimentreports',
        type: 'POST',
        data: data_s,  
        
        beforeSend: function(){
           
            $(".overlay").show();
        },
        
        
        success:function(response){
            
		    $('#sentiment_report').show();
		    $('#kname').html(response.split('^')[2]);
	        $('#first_key_id').val(response.split('^')[1]);
		    
		         
	        var sentiments = response.split('^')[0];
	 
            var positive = sentiments.split('|')[0];
            var negative = sentiments.split('|')[1];
            var neutral = sentiments.split('|')[2];
		     
		     load_chart(positive, negative, neutral);
		   
		
        },
        
        complete:function(data){
        
            $(".overlay").hide();
        }
        
    });  
        
        
        
        
    }else{
        
        
        if(show_only == 'all'){
        
             var type = "single";
             var data_s = {"key_id":keyid,"type":type,"_token":"{{csrf_token()}}"};
        
        }else{
            
            var type = "sort";
            var data_s = {"key_id":key_id,"show_only":show_only,"type":type,"_token":"{{csrf_token()}}"};
          
        }
        
        
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getsingleKey',
                type: 'POST',
                data: data_s,  
                
               beforeSend: function(){
                
                     $("#respon").show();
               },
                
                success:function(res){
                    
				    $('#t_response').show();
				  
				    var resp = res.split('(^S^)')[0];
				    
				    if(resp != ''){
				        $('#t_response').html(resp);  
				        $('#loadMore').show();
				    }else{
				        $('#t_response').html("Sorry , No Feed Found");
				    }
				
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
				    $('#show_edit').css("display", "block");
				    
		
		
	var keyword = res.split('(^S^)')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
		
                },
                
                complete:function(data){
                
                    $("#respon").hide();
                }
           });  
     
     
    }
     
     
 }
 



function get_filter_key(){
    
    $('.upda').collapse('hide');
   
    var show_only = $('input[name=showonly]:checked').val();
    
    $('#filter_type').val(show_only);
    
    
    var key_id = $('#first_key_id').val();
    
    var report_type = $('#tab_value').val();
    
    if (report_type == '#mention'){
        
        
         var types = 'filter';
         
     $.ajax({  
        url: baseUrl + '/admin/setting/social/mentionreports',
        type: 'POST',
        data:{"key_id":key_id,"types":types,"show_only":show_only,"_token":"{{csrf_token()}}"}, 
        
          beforeSend: function(){
      
             $(".overlay").show();
        },
        
        success:function(response){
            
		     $('#mention_report').show();
		   
		    
		    if(!response.split('^')[0]){
			   
			   $('#mention-reach').hide();
			    
			}else{
			    $('#mention-reach').show();
			    $('#mention_by_reach').html(response.split('^')[0]);
			    
			}
		     
		
			
			if(!response.split('^')[1]){
			
			    $('#mention-viral').hide();
			    
			}else{
			    $('#mention-viral').show();
			    $('#mention_by_virality').html(response.split('^')[1]);
			}
			
			$('#kname').html(response.split('^')[3]);
		    $('#first_key_id').val(response.split('^')[2]);
		
		   // $('p').highlight(response.split('^')[3]);	  
		   
		   var keyword = response.split('^')[3],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
		   
		   
        },
        
        complete:function(data){
        
             $(".overlay").hide();
        }
        
        
    });  
        
        
        
        
    }else if (report_type == '#sentiment'){
        
      
         
         var types = 'filter';
         
     $.ajax({  
        url: baseUrl + '/admin/setting/social/sentimentreports',
        type: 'POST',
        data:{"key_id":key_id,"types":types,"show_only":show_only,"_token":"{{csrf_token()}}"},  
        
         beforeSend: function(){
      
             $(".overlay").show();
        },
        
        success:function(response){
            
		    $('#sentiment_report').show();
		    $('#kname').html(response.split('^')[2]);
	        $('#first_key_id').val(response.split('^')[1]);
		    
		         
	        var sentiments = response.split('^')[0];
	 
            var positive = sentiments.split('|')[0];
            var negative = sentiments.split('|')[1];
            var neutral = sentiments.split('|')[2];
		     
		     load_chart(positive, negative, neutral);
		   
		    
        },
        
        complete:function(data){
        
             $(".overlay").hide();
        }
        
        
    });  
        
        
        
        
    }else{
    
    var type = "sort";
    
    $('#t_response').hide();
  
    $('#loadMore').hide();
    
    $('#srch').val('');
     
    
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getFeedbysort',
                type: 'POST',
                data:{"key_id":key_id,"show_only":show_only,"type":type,"_token":"{{csrf_token()}}"},  
                
                 beforeSend: function(){
      
                     $("#respon").show();
                },
                        
                success:function(res){
                    
				     $('#t_response').show();
				   
				     
					$('#t_response').html(res.split('(^S^)')[0]);
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
				
					$('#loadMore').show();
					
					  //$('p').highlight(res.split('(^S^)')[1]);
					  
	var keyword = res.split('(^S^)')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
				
                },
                
                complete:function(data){
        
                     $("#respon").hide();
                }
        
           });  
     
     
    }
    
    
    
}


function refresh_key(){
    
    
    var key_id = $('#first_key_id').val();
    
 /*      $('#respon').html("<img src='../../../img/social/loader.gif'/>");
  //  var sort_by = $('input[name=sortby]:checked').val();
    
    var show_only = $('input[name=showonly]:checked').val();
    
   
   
    var type = "sort";
    
    $('#t_response').hide();
  
    $('#loadMore').hide();
    
    $('#srch').val('');
     
    
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getFeedbysort',
                type: 'POST',
                data:{"key_id":key_id,"show_only":show_only,"type":type,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
				     $('#t_response').show();
				   
				     
					$('#t_response').html(res.split('(^S^)')[0]);
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
					$('#respon').hide();
					$('#loadMore').show();
				
                }  
           });  
     
     
    
    */
    
    
    
    get_feed_key(key_id);
    
    
}

setInterval(refresh_key,600*1000);

      
      $('#export_excel').click(function(){
         
     
        var feed_data = $('#srch_form').serialize();
           
         
       
        window.open("{{URL::to('admin/setting/social/feedexcelexport')}}?"+ feed_data, "_blank");
         
      
      });
      
      
       $('#export_pdf').click(function(){
         
     
        var feed_data = $('#srch_form').serialize();
           
         
       
        window.open("{{URL::to('admin/setting/social/feedpdfexport')}}?"+ feed_data, "_blank");
         
      
      });
      
      
$('#loadMore').click(function(){
    
    var key_id = $('#first_key_id').val();
   
    var min_count = $('#min_count').val();
    var max_count = $('#max_count').val();
    
    var type = "paginate";
    
    var sum = 100;
    
    var min_count = ( + min_count )  + ( +sum ) ;
    var max_count = ( +max_count ) + ( +sum) ;
    
    //$('#t_response').hide();
   
    $('#loadMore').hide();
    
    $('#srch').val('');
     
    
       $.ajax({  
                url: baseUrl + '/admin/setting/social/loadmoreKeyFeed',
                type: 'POST',
                data:{"key_id":key_id,"min_count":min_count,"max_count":max_count,"type":type,"_token":"{{csrf_token()}}"}, 
                
                 beforeSend: function(){
      
                     $(".overlay").show();
                },
                
                success:function(res){
                    
                    
				     //$('#t_response').show();
				    
				     if(!res.split('(^S^)')[0]){
				         
				         	$('#loadMore').hide();
				     }else{
					$('#t_response').append(res.split('(^S^)')[0]);
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
					$('#respon').hide();
					$('#loadMore').show();
				    }	
					$('#min_count').val(min_count+100);
                    $('#max_count').val(max_count+100);
				
				   // $('p').highlight(res.split('(^S^)')[1]);
				   
	var keyword = res.split('(^S^)')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
					
                },
                
                complete:function(data){
        
                     $(".overlay").hide();
                }
        
           });  
     
     
    
    
    
    
}); 


function getdaterange() {
    
    
     var key_id = $('#first_key_id').val();
   
     var type = "daterange";
    
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    
var from = new Date(from_date.split("-").reverse().join("-")).getTime();
var to = new Date(to_date.split("-").reverse().join("-")).getTime();

    
    $('#t_response').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    
    $('#loadMore').hide();
    
    $('#srch').val('');
     
     $('#respon').html("<img src='../../../img/social/loader.gif'/>");
     
  
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getFeedDate',
                type: 'POST',
                data:{"key_id":key_id,"from":from,"to":to,"type":type,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
                    
				     $('#t_response').show();
				     $('#keywords').show();
				     $('#keysorting').show();
				     
					$('#t_response').html(res.split('(^S^)')[0]);
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
					$('#respon').hide();
					$('#loadMore').show();
					
				
                }  
          });  
     
     
   
    
    
    
}


function delete_feed_key(key){
    
    var key_id = key;
    
    var group_id = $('#group_id').val();
    
    
    if(confirm("Are you sure you want to delete this?"))
    {    
    
    $('#t_response').hide();
   
    
    $('#loadMore').hide();
    
       $.ajax({  
                url: baseUrl + '/admin/setting/social/deletesingleKeyFeed',
                type: 'POST',
                data:{"key_id":key_id,"_token":"{{csrf_token()}}"},  
                
                 beforeSend: function(){
      
                     $("#respon").show();
                },
                
                success:function(res){
                    
                     var okay = get_all_keys(group_id);
                    
                    if(okay){
                        
                        $('#t_response').show();
				  
				        $('#loadMore').show();
                    
                        
                    }
				    
					
                },
                
                complete:function(data){
        
                     $("#respon").hide();
                }
        
           });  
     
    }
    
    
}


$('#repor').click(function(){
    
  $('#feed_reports').hide();
  $('#mention_report').hide();    
  $('#sentiment_report').hide();    
    
    var key_id = $('#first_key_id').val();
    
   
    $.ajax({  
        url: baseUrl + '/admin/setting/social/reports',
        type: 'POST',
        data:{"key_id":key_id,"_token":"{{csrf_token()}}"}, 
        
         beforeSend: function(){
      
             //$("#feed_reports").html("<img src='../../../img/social/loader.gif' margin-left='40px' />");
             $('.overlay').show();
        },
        
        success:function(res){
            
		     $('#feed_reports').show();
		   
		     
			$('#feed_reports').html(res.split('^')[0]);
			$('#kname').html(res.split('^')[2]);
		    $('#first_key_id').val(res.split('^')[1]);
		
		
        },
        
        complete:function(data){
        
             $(".overlay").hide();
        }
        
    });  
   
   
   
    
});


$('#in_report').click(function(){
    
   
     $('#feed_reports').hide();
   var key_id = $('#first_key_id').val();
   
    $.ajax({  
        url: baseUrl + '/admin/setting/social/reports',
        type: 'POST',
        data:{"key_id":key_id,"_token":"{{csrf_token()}}"},  
         beforeSend: function(){
      
              // $("#feed_reports").html("<img src='../../../img/social/loader.gif' margin-left='40px;'/>");
              $('.overlay').show();
        },
        
        success:function(res){
            
		     $('#feed_reports').show();
		   
		     
			$('#feed_reports').html(res.split('^')[0]);
			$('#kname').html(res.split('^')[2]);
		    $('#first_key_id').val(res.split('^')[1]);
		
		   // $('p').highlight(res.split('^')[2]);
		   
		   	var keyword = res.split('^')[2],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
		
        },
        
        complete:function(data){
        
             $(".overlay").hide();
        }
        
    });  
   
    
});


$('#me_report').click(function(){
    
   $('#mention_report').hide();
   var key_id = $('#first_key_id').val();
   
   var show_only = $('input[name=showonly]:checked').val(); 
   
    if(show_only == 'all'){
        
             var types = 'normal';
             var data_s = {"key_id":key_id,"types":types,"_token":"{{csrf_token()}}"};
        
    }else{
            
            var types = 'filter';
            var data_s = {"key_id":key_id,"types":types,"show_only":show_only,"_token":"{{csrf_token()}}"};
          
    }
   
    $.ajax({  
        url: baseUrl + '/admin/setting/social/mentionreports',
        type: 'POST',
        data: data_s,  
         beforeSend: function(){
      
              $(".overlay").show();
        },
        success:function(response){
            
		     $('#mention_report').show();
		   
		    
		    if(!response.split('^')[0]){
			   
			   $('#mention-reach').hide();
			    
			}else{
			    $('#mention-reach').show();
			  $('#mention_by_reach').html(response.split('^')[0]);
			    
			}
		     
		    
			if(!response.split('^')[1]){
			
			   $('#mention-viral').hide();
			    
			}else{
			    $('#mention-viral').show();
			    $('#mention_by_virality').html(response.split('^')[1]);
			}
		
			$('#kname').html(response.split('^')[3]);
		    $('#first_key_id').val(response.split('^')[2]);
		
		    //$('p').highlight(response.split('^')[3]);
		    
		    	var keyword = response.split('^')[3],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
		
        },
        complete:function(data){
        
               $(".overlay").hide();
        }
        
    });  
   
    
});


$('#sen_report').click(function(){
    
    $('#sentiment_report').hide();
  
   var key_id = $('#first_key_id').val();
   
   var show_only = $('input[name=showonly]:checked').val();    
   
    if(show_only == 'all'){
        
             var types = 'normal';
             var data_s = {"key_id":key_id,"types":types,"_token":"{{csrf_token()}}"};
        
        }else{
            
            var types = 'filter';
            var data_s = {"key_id":key_id,"types":types,"show_only":show_only,"_token":"{{csrf_token()}}"};
          
        }
   
  
   
    $.ajax({  
        url: baseUrl + '/admin/setting/social/sentimentreports',
        type: 'POST',
        data: data_s,  
        
         beforeSend: function(){
    
            $(".overlay").show();
            
        },
        
        success:function(response){
            
		$('#sentiment_report').show();
		   
		   
		$('#kname').html(response.split('^')[2]);
		$('#first_key_id').val(response.split('^')[1]);
		    
		         
	 var sentiments = response.split('^')[0];
	 
     var positive = sentiments.split('|')[0];
     var negative = sentiments.split('|')[1];
     var neutral = sentiments.split('|')[2];
     

		load_chart(positive, negative, neutral)
		
        },
        
        complete:function(data){
        
             $(".overlay").hide();
        }
        
    });  
    
    
    
});


function load_chart(positive, negative, neutral){
    
    var positive = positive;
    var negative = negative;
    var neutral = neutral;
    
    var all_three = parseInt(positive) + parseInt(negative) + parseInt(neutral);
    
    var positive_per = ((parseInt(positive) / parseInt(all_three) ) * parseInt(100)).toFixed(2);
    
     var negative_per = ((parseInt(negative) / parseInt(all_three) ) * parseInt(100)).toFixed(2);
     
      var neutral_per = ((parseInt(neutral) / parseInt(all_three) ) * parseInt(100)).toFixed(2);

    
var chart = new CanvasJS.Chart("chart", {
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	animationEnabled: true,
    
	data: [{
		type: "pie",
		showInLegend: "true",
		toolTipContent: "{label} :  {perc} % ( {y} )",
       	legendText: "{label}",
		indexLabelFontSize: 16,
		indexLabel: "{label}",
		dataPoints: [
		    
			{ y: neutral, label: "Neutral" , color: "#8F8F8F" , perc: neutral_per },
			{ y: negative, label: "Negative" , color:"#FF7D6F" , perc: negative_per },
		    { y: positive, label: "Positive" , color: "#5CD9C4" , perc: positive_per}
		
		]
	}]
});
chart.render();
    

}


$('#feeds').click(function(){
    
   
     var key_id = $('#first_key_id').val();
     
    $('#t_response').hide();
		 
	$('#loadMore').hide();
    
    $('#srch').val('');
    
    $('#tag_list1').val('');
    
    $('#up_sensitive').checked= false;
     
    var show_only = $('input[name=showonly]:checked').val();
    
    
    
      if(show_only == 'all'){
        
             var type = "single";
             var data_s = {"key_id":key_id,"type":type,"_token":"{{csrf_token()}}"};
        
        }else{
            
            var type = "sort";
            var data_s = {"key_id":key_id,"show_only":show_only,"type":type,"_token":"{{csrf_token()}}"};
          
        }
        
        
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getsingleKey',
                type: 'POST',
                data: data_s,  
                
               beforeSend: function(){
                
                     $("#respon").show();
               },
                
                success:function(res){
                    
				    $('#t_response').show();
				  
				    var resp = res.split('(^S^)')[0];
				    
				    if(resp != ''){
				        $('#t_response').html(resp);  
				        $('#loadMore').show();
				    }else{
				        $('#t_response').html("Sorry , No Feed Found");
				    }
				
				     
				
				    
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
				    $('#show_edit').css("display", "block");
				    
				  //  $('p').highlight(res.split('(^S^)')[1]);
				  
	var keyword = res.split('(^S^)')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
				    
				    
                },
                
                complete:function(data){
                
                    $("#respon").hide();
                }
           });  
     
    
    
    
});


function change_sentiment(mentions,type,sentiment){
    
   var mentions = mentions, type = type , sentiment = sentiment;
   
   var key_id = $('#first_key_id').val();
   
    $.ajax({  
                url: baseUrl + '/admin/setting/social/changesentiment',
                type: 'POST',
                data: {"key_id":key_id,"mentions":mentions,"type":type,"sentiment":sentiment,"_token":"{{csrf_token()}}"},  
                
                 beforeSend: function(){
                     $('#t_response').hide();
                     $('#loadMore').hide();
                     $("#respon").show();
                 },
                success:function(res){
                    
				 	$('#kname').html(res.split('^')[1]);
				    
				    $('#first_key_id').val(res.split('^')[0]);
				    
				    get_feed_key(res.split('^')[0]);
				    
				     // $('p').highlight(res.split('^')[1]);
				     
	var keyword = res.split('^')[1],
      options = {
        "element": "span",
        "className": "markBlue",
        "separateWordSearch": true
      },
      $ctx = $('p');
    $ctx.unmark({
      done: function() {
        $ctx.mark(keyword, options);
      }
    });
				   
                }
           });  
     
    
    
    
}



$('#show_edit').click(function(){
    
     $('#defin').html('');
     
     $('#difini').hide();
     
     $('#up_sensitive').prop('checked', false);
     
     $('#tag_list1').val('');
     
    var edit_time = $('#edit_time').val();
    
     if(edit_time == 0){ 
   
     var key_id = $('#first_key_id').val();
   
    $.ajax({  
                url: baseUrl + '/admin/setting/social/getdefinition',
                type: 'POST',
                data: {"key_id":key_id,"_token":"{{csrf_token()}}"}, 
                
                beforeSend: function(){
                    $("#respon").show();
                 },
                
                success:function(res){
                    
				 	$('#kname').html(res.split('^')[3]);
				    
				    $('#first_key_id').val(res.split('^')[2]);
				    
			
				   
				   var is_case = res.split('^')[1];
				   
				  
				   var definit = res.split('^')[0];
				   
				   if(definit == ''){
				       
				       $('#difini').hide();
				   }else{
				     
        				        
        if ($('#tag_list1').find("option[value='SG']").length){
            
            $('#tag_list1').val("SG").trigger('change');
            
        } else { 
            
            
            var all_locations = definit.split(',');
            var text = "";
            var i;
            for (i = 0; i < all_locations.length; i++) {
         
             text = all_locations[i];
            
             var newOption = new Option(text,text, true, true);
            
          
            $('#tag_list1').append(newOption).trigger('change');
            
                
            }
   
        } 
				        
		     
		

				        
				}
				
				   if(is_case == 1){
				       
				        $('#up_sensitive').prop('checked', true);
				       
				   }else{
				       
				        $('#up_sensitive').prop('checked', false);
				   }
				   
				   $('#edit_time').val('1');
				   $('.upda').collapse('show');
                },
                
                 complete:function(data){
                
                    $("#respon").hide();
                }
           });  
   
    }else{
        
         $('.upda').collapse('hide');
         $('#edit_time').val('0');
        
    }
    
});



 </script>	
	
@stop
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
Social Listening
@stop

@section('content')



<section class="content">
    
    
   <p class="social-xl-sec"><button id="export" class="btn btn-default"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel File</button> </p>
   
      <input type="hidden" id="min_count" name="min_count" />   
      <input type="hidden" id="max_count" name="max_count" />
      
      <input type="hidden" id="company_id" value="<?php echo Session::get('company_id');?>" />
      
     
      
              
    <div class="row head-social-scrch">
        <div class="col-sm-12">
            <form class="form-horizontal" method="post" id="srch_form"> 
              <div class="form-group">
                <div class="col-xs-9 col-sm-offset-2 col-sm-6">
                    <div class="row">
                        <input type="text" class="form-control social-scrch-inpt" placeholder="Enter the keyords to search" name="srch" id="srch">
                    </div>
                </div>
                
                 <input type="hidden" id="key" name="key">
                 
                <div class="col-xs-3 col-sm-2">
                    <div class="row">
                        <button type="submit" id="srch_btn" name="srch_btn" class="btn btn-default social-scrch-btn">Search</button>
                    </div>
                </div>
              </div>
               <input type="hidden" id="first_key_id" name="first_key_id" value="<?php echo $first_key_id;?>" />
      
           
            </form>
        </div>
    </div>
    
     <span id="respon" style="margin-left:340px;"></span>
     
     <div id="socials">
    
    <div class="row">
        
        
        <div class="col-sm-12 col-md-6 col-lg-3" id="keywords">
             <div class="social-hd-sec social-hd-sec-fb"><span style="font-weight:bold"><?php if(!empty($group_name)) { echo $group_name; }else{ echo "Searched Keywords"; } ?></span>
           
             
             </div>
             
            <div class="social-cnt-sec-fb" id="key_response">
        
                 
            </div>
        </div>
        
         <div class="col-sm-12 col-md-6 col-lg-6" id="facebook">
             <div class="social-hd-sec social-hd-sec-fb"> Recent Activity for  the feed - <span id="kname" style="font-weight: bold;"></span></div>
             
             <div class="social-cnt-sec-fb" id="t_response">
                 
               
	
	           
             </div>
             
         </div>
         
          <div class="col-sm-12 col-md-6 col-lg-3" id="keysorting">
             <div class="social-hd-sec social-hd-sec-fb"> Sorting Keywords </div>
             
            <div class="social-cnt-sec-fb" id="">
                
                
                 <div class="container">
                
                    <p><b>Set Date Range </b></p>
                    
                    <p>
                      From
                    </p>
                    
                    <p>
                        
                        <input type="text" name="from_date" id="from_date" />
                    </p>
                    <p>
                        Until
                    </p>
                    
                    <p>
                        <input type="text" name="to_date" id="to_date" />
                    </p>
                    
                </div>
                <hr />
                
                <div class="container">
                
                    <p><b>Sort By </b></p>
                    
                    <p>
                      <input type="radio" name="sortby" value="time" checked onclick="get_filter_key()"> Time
                    </p>
                    
                    <p>
                       <input type="radio" name="sortby" value="reach" onclick="get_filter_key()"> Reach
                    </p>
                    
                    <p>
                        <input type="radio" name="sortby" value="virality" onclick="get_filter_key()"> Virality
                    </p>
                    
                </div>
                <hr />
                
                  <div class="container">
                
                    <p><b>Show Only</b></p>
                    
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
                        
                        <input type="radio" name="showonly" value="forum" onclick="get_filter_key()"> Forum
                    </p>
                    
                    <p>
                        
                         <input type="radio" name="showonly" value="comment" onclick="get_filter_key()"> Comment
                    </p>
                    
                      <p>
                        
                       <input type="radio" name="showonly" value="youtube" onclick="get_filter_key()"> Youtube
                    </p>
                    
                      <p>
                        
                        <input type="radio" name="showonly" value="disqus" onclick="get_filter_key()"> Disqus
                    </p>
                    
                      <p>
                        
                         <input type="radio" name="showonly" value="instagram" onclick="get_filter_key()"> Instagram
                    </p>
                    
                     <p>
                        
                         <input type="radio" name="showonly" value="vkontakte" onclick="get_filter_key()"> Vkontakte
                    </p>
                    
                    <p>
                        
                         <input type="radio" name="showonly" value="print" onclick="get_filter_key()"> Print
                    </p>
                    
                </div>
                <hr />
                
                 
                 <div class="container">
                
                    <p><b> Reports </b></p>
                    
                    <p>
                        
                        <a href="#" id="from_date" onclick="get_reports('influence')"> Influencers Report </a>
                    </p>
                    
                    <p>
                          <a href="#"  id="from_date" onclick="get_reports('sentiment')"> Sentiment Report </a>
                    </p>
                    
                </div>
                <hr />
                 
            </div>
        </div>
        
         
          <div class="row" id="load_action">
       <div class="col-sm-12 col-md-12 col-lg-12">
           <p class="scl-load-sec"><a id="loadMore">Load More</a> </p> 
          
       </div>
    </div>
   
 </div>   
</section>



@stop

@section('style')

<link rel="stylesheet" type="text/css" href="{{ asset('css/client/social.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/color_picker/css/bootstrap-colorpicker.min.css') }}">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@stop

@section('script')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="{{ asset('plugins/color_picker/js/bootstrap-colorpicker.min.js') }}"></script>


<script>  

  $( function() {
    $("#from_date").datepicker(
        
            { dateFormat: 'dd-mm-yy', onSelect: getdaterange }
        
        );
        
    $("#to_date").datepicker(
        
         { dateFormat: 'dd-mm-yy' , onSelect: getdaterange }
        
        ).datepicker("setDate", new Date());
  } );

$(document).ready(function(){ 
    
    $('#min_count').val('0');
    $('#max_count').val('100');
    
    
      var company_id = $('#company_id').val();
      
      var first_key_id = $('#first_key_id').val();
      
     
      get_all_keys(company_id);
      
      get_feed_key(first_key_id);
      
      
    var dateToday = new Date();
   
	
	
	$('#srch_form').on("submit", function(event){
		    
		   $('#respon').html("<img src='../../img/social/loader.gif'/>");
		   
		   $('#facebook').hide();
		   $('#keywords').hide();
		   $('#keysorting').hide();
		   $('#loadMore').hide();
		  
		  event.preventDefault();
		  
		    var srch_key = $('#key').val();  
		    
		    var srch_trm = $('#srch').val();  
		     
		    var company_id = $('#company_id').val();
				   
           $.ajax({  
                url: baseUrl + '/admin/setting/social/getKeyFeed',
                type: 'POST',
                data:{"srch_trm":srch_trm,"_token":"{{csrf_token()}}"},  
                
                success:function(data){
                    
				    $('#facebook').show();
				    $('#keywords').show();
				    $('#keysorting').show();
				    
					$('#t_response').html(data.split('^')[0]);
					
					$('#kname').html(data.split('^')[1]);
					$('#first_key_id').val(data.split('^')[2]);
				    $('#respon').hide();
				    $('#loadMore').show();
				    
				    
				    get_all_keys(company_id);
					
                }  
           });  
		  
	   });
		
		
		
		

		
 });  
 
 
 function get_all_keys(company){
     
     
     var company = company;
     

       $.ajax({  
                url: baseUrl + '/admin/setting/social/getallKeys',
                type: 'POST',
                data:{"company":company,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
				  $('#key_response').html(res);
				   
				  
                }  
           });  
     
 }
 
 
 function get_feed_key(keyid){
     
    $('#facebook').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    $('#loadMore').hide();
    
    $('#srch').val('');
     
     $('#respon').html("<img src='../../img/social/loader.gif'/>");
     
     var key_id = keyid;
     
     var type = "single";
     
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getsingleFeed',
                type: 'POST',
                data:{"key_id":keyid,"type":type,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
                    
				     $('#facebook').show();
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
 



function get_filter_key(){
    
    
    var sort_by = $('input[name=sortby]:checked').val();
    
    var show_only = $('input[name=showonly]:checked').val();
    
    var key_id = $('#first_key_id').val();
    
 
    var type = "sort";
    
    $('#facebook').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    $('#loadMore').hide();
    
    $('#srch').val('');
     
     $('#respon').html("<img src='../../img/social/loader.gif'/>");
     
  
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getsingleFeed',
                type: 'POST',
                data:{"key_id":key_id,"sort_by":sort_by,"show_only":show_only,"type":type,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
				     $('#facebook').show();
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


function refresh_key(){
    
    
    var key_id = $('#first_key_id').val();
     get_feed_key(key_id);
    
    
}

setInterval(refresh_key,600*1000);

      
      $('#export').click(function(){
         
     
        var tweet_data = $('#srch_form').serialize();
            
       
        window.open("{{URL::to('admin/setting/social/feedexcelexport')}}?"+ tweet_data, "_blank");
          
      
      });
      
      
$('#loadMore').click(function(){
    
    var key_id = $('#first_key_id').val();
   
    var min_count = $('#min_count').val();
    var max_count = $('#max_count').val();
    
    var type = "paginate";
    
    var sum = 100;
    
    var min_count = ( + min_count )  + ( +sum ) ;
    var max_count = ( +max_count ) + ( +sum) ;
    
    $('#facebook').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    
    $('#loadMore').hide();
    
    $('#srch').val('');
     
     $('#respon').html("<img src='../../img/social/loader.gif'/>");
     
  
       $.ajax({  
                url: baseUrl + '/admin/setting/social/loadmoreFeed',
                type: 'POST',
                data:{"key_id":key_id,"min_count":min_count,"max_count":max_count,"type":type,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
                    
				     $('#facebook').show();
				     $('#keywords').show();
				     $('#keysorting').show();
				     
					$('#t_response').html(res.split('(^S^)')[0]);
					$('#kname').html(res.split('(^S^)')[1]);
				    
				    $('#first_key_id').val(res.split('(^S^)')[2]);
					$('#respon').hide();
					$('#loadMore').show();
					
					$('#min_count').val(min_count+100);
                    $('#max_count').val(max_count+100);
				  
					
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

    
    $('#facebook').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    
    $('#loadMore').hide();
    
    $('#srch').val('');
     
     $('#respon').html("<img src='../../img/social/loader.gif'/>");
     
  
       $.ajax({  
                url: baseUrl + '/admin/setting/social/getFeedDate',
                type: 'POST',
                data:{"key_id":key_id,"from":from,"to":to,"type":type,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
                    
				     $('#facebook').show();
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
    
    if(confirm("Are you sure you want to delete this?"))
    {    
    
    $('#facebook').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    
    $('#loadMore').hide();
    
       $.ajax({  
                url: baseUrl + '/admin/setting/social/deletesingleFeed',
                type: 'POST',
                data:{"key_id":key_id,"_token":"{{csrf_token()}}"},  
                
                success:function(res){
                    
                   // alert(res);
                    $('#facebook').show();
				    $('#keywords').show();
				    $('#keysorting').show();
				    $('#loadMore').show();
                   $('#first_key_id').val(res);
                    location.reload();
				  
					
                }  
           });  
     
    }
    
    
}



function get_reports(type){
    
    
    
    alert(type);
    
}
   
 
 </script>	
	
@stop
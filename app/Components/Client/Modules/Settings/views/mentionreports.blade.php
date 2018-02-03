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

<style>
.social-cnt-sec-fb-new{
    background: #fff;
    padding: 15px;
    border-bottom: 3px solid #dcdcdc;
    margin-top: 6px;
    -webkit-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    -moz-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    margin-bottom: 15px;
   
    
}

.by-reach, .by-virality{
    background: #fff;
    padding: 15px;
    border-bottom: 3px solid #dcdcdc;
    margin-top: 6px;
    -webkit-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    -moz-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    margin-bottom: 15px;
   
    
}
</style>

<section class="content">
    <div class="row head-search">

       <div class="col-sm-2"> 
         <h4 class="title-top">
           <i class="fa fa-bars"></i>
              Feed
          </h4>
        </div>
         <div class="col-sm-2"> 
         <h4 class="title-top">
           <i class="fa fa-area-chart"></i>
              Reports
          </h4>
        </div>
        <div class="col-sm-2"> 
         <h4 class="title-top">
           <i class="fa fa-download"></i>
              Export
          </h4>
        </div>
        
        
        <div class="col-sm-6"> 
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

   
      <input type="hidden" id="min_count" name="min_count" />   
      <input type="hidden" id="max_count" name="max_count" />
      
      <input type="hidden" id="company_id" value="<?php echo Session::get('company_id');?>" />
      
     
      
  
    
   <div style="margin-bottom: 65px;">
    <div class="col-sm-12 col-md-12 col-lg-12">
    Result For : <span id="kname" style="font-weight: bold;"></span>
    </div> 
   </div>
   

   
     <span id="respon" style="margin-left:340px;"></span>
     
     <div id="socials">
         
       
    
    <div class="row">
        
        
         <div class="col-sm-12 col-md-9 col-lg-9" id="">
             
               <p class="by-reach">
                 
                 Top 5 Reach
                 
             </p>
            
             <div class="social-cnt-sec-fb">
               <?php   
                $output_t= '';
if(!empty($byreach->data->response)){
foreach($byreach->data->response as $reach){
	
	$created_time = $reach->insert_time;
    $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
    $posted_on = time_elapsed_string($converted_date_time);
	    
   
    $social_icon = $reach->type;
    
    if($social_icon == 'twitter'){
        
        $social_icon = "<i class='fa fa-twitter' aria-hidden='true'></i>
";
    }else if($social_icon == 'web'){
        $social_icon = "<i class='fa fa-forumbee' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'comment'){
        $social_icon = "<i class='fa fa-comments' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'facebook'){
        $social_icon = "<i class='fa fa-facebook' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'instagram'){
        $social_icon = "<i class='fa fa-instagram' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'youtube'){
        $social_icon = "<i class='fa fa-youtube-play' aria-hidden='true'></i>
";
        
    }
    else{
         $social_icon = "<i class='fa fa-globe' aria-hidden='true'></i>";
    }
    
    
    if(!empty($reach->title)){
        
        $title = $reach->title;
        
    }else{
        
        $title = '';
    }
    
	?>
 <div class='social-cnt-sec-fb-itm'>
	                <div class='fb-icon'>
	                    
	                    <img src=<?php echo $reach->image ?> class='img-thumbnail' />
	                    
	                </div>
	              
                     <h3>
                  	<a href=<?php echo $reach->url ?> target='_blank'><?php echo $title ?></a>
                    </h3>
                  
                     <p class='social-cnt-sec-fb-athr'><?php echo $social_icon  . ' | '  . $reach->from  . ' |   ' . $posted_on ?> </p>
                     <br />
                     <p class='social-cnt-sec-fb-des'><?php echo $reach->mention ?></p>
                  
                      <p class='social-cnt-sec-fb-athr' style='margin-top:10px'> <?php echo "REACH :" .   $reach->reach  . " " ?>   |  <?php echo "INFLUENCE SCORE :" .   $reach->influence_score . " "  ?>   |      <?php echo " Languages :  [ " . implode(",", $reach->languages) . " ] " ?></p>
                 </div>
	
<?php 	
	
}
}
?>	
	           
             </div>
             
              <p class="by-reach">
                 
                 Top 5 Virality
                 
             </p>
             
             
              <div class="social-cnt-sec-fb">
               <?php   
               
if(!empty($byvirality->data->response)){
foreach($byvirality->data->response as $virality){
	
	$created_time = $virality->insert_time;
    $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
    $posted_on = time_elapsed_string($converted_date_time);
	    
   
    $social_icon = $virality->type;
    
    if($social_icon == 'twitter'){
        
        $social_icon = "<i class='fa fa-twitter' aria-hidden='true'></i>
";
    }else if($social_icon == 'web'){
        $social_icon = "<i class='fa fa-forumbee' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'comment'){
        $social_icon = "<i class='fa fa-comments' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'facebook'){
        $social_icon = "<i class='fa fa-facebook' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'instagram'){
        $social_icon = "<i class='fa fa-instagram' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'youtube'){
        $social_icon = "<i class='fa fa-youtube-play' aria-hidden='true'></i>
";
        
    }
    else{
         $social_icon = "<i class='fa fa-globe' aria-hidden='true'></i>";
    }
    
    
    if(!empty($virality->title)){
        
        $title = $virality->title;
        
    }else{
        
        $title = '';
    }
    
	?>
 <div class='social-cnt-sec-fb-itm'>
	                <div class='fb-icon'>
	                    
	                    <img src=<?php echo $virality->image ?> class='img-thumbnail' />
	                    
	                </div>
	              
                     <h3>
                  	<a href=<?php echo $virality->url ?> target='_blank'><?php echo $title ?></a>
                    </h3>
                  
                     <p class='social-cnt-sec-fb-athr'><?php echo $social_icon  . ' | '  . $virality->from  . ' |   ' . $posted_on ?> </p>
                     <br />
                     <p class='social-cnt-sec-fb-des'><?php echo $virality->mention ?></p>
                  
                      <p class='social-cnt-sec-fb-athr' style='margin-top:10px'> <?php echo "REACH :" .   $virality->reach  . " " ?>   |  <?php echo "INTERACTION :" .   $virality->interaction  . " " ?>   | <?php echo "INFLUENCE SCORE :" .   $virality->influence_score . " "  ?>   |      <?php echo " Languages :  [ " . implode(",", $virality->languages) . " ] " ?></p>
                 </div>
	
<?php 	
	
}
}
?>	
	           
             </div>
             
         </div>
         
          <div class="col-sm-12 col-md-6 col-lg-3" id="keysorting">
            
             
            <div class="social-cnt-sec-fb-new" id="">
                
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
		    
		   $('#respon').html("<img src='../../../img/social/loader.gif'/>");
		   
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
                    
				  $('#key_response').html(res)
				   
				  
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
    
    /*
    var sort_by = $('input[name=sortby]:checked').val();
    
    var show_only = $('input[name=showonly]:checked').val();
    
    var key_id = $('#first_key_id').val();
    
 
    var type = "sort";
    
    $('#facebook').hide();
    $('#keywords').hide(); 
    $('#keysorting').hide();
    $('#loadMore').hide();
    
    $('#srch').val('');
     
     $('#respon').html("<img src='../../../img/social/loader.gif'/>");
     
  
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
     
     
    
    */
    
    
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
     
     $('#respon').html("<img src='../../../img/social/loader.gif'/>");
     
  
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
     
     $('#respon').html("<img src='../../../img/social/loader.gif'/>");
     
  
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
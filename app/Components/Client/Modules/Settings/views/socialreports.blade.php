


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
.social-cnt-sec-fb-new-one{
    background: #fff;
    padding: 5px;
    border-bottom: 3px solid #dcdcdc;
    margin-top: 6px;
    -webkit-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    -moz-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    margin-bottom: 15px;
    
    overflow:auto;
}

.social-cnt-sec-fb-side{
    
    
    background: #fff;
    padding: 5px;
    border-bottom: 3px solid #dcdcdc;
    margin-top: 6px;
    -webkit-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    -moz-box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    box-shadow: 0px 0px 10px -2px rgba(209,207,209,0.69);
    margin-bottom: 15px;
    
}

.influence-report{
    width:250px;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 60%;
}

td, th {
    border: 0px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
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
    
  <!-- <p class="social-xl-sec"><button id="export" class="btn btn-default"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel File</button> </p> -->
   
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
        
        
         <div class="col-sm-12 col-md-9 col-lg-9" id="facebook">
            
            <div class="social-cnt-sec-fb-new-one">
               
            
            
<?php



$output1 = $output2 = $output3 = $output4 = $output5 = $output6 = $output7 = $output8 = $output9 = $output10 = $output11 = $output12 = '';

$res = $res1 = $res2 = $res3 = '';



foreach($tweetarray->data as $twt){
    
    
    
    	$output4 .='<div class="influence-report">
  
  <p>Most frequent sources </p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_count->web as $web){ 
	    
	      if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	    
	if($i <= 10) { 
         $output4 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank"   title="'.$web->pretty_name .' ( ' .$web->count .' Mentions )">'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output4 .='</tbody>
	</table></div>';






	$output5 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_count->twitter as $tweet){ 
	    
	      if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	if($i <=10){
        $output5 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' . $tweet->count.' Mentions )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output5 .='</tbody>
	</table></div>';	
	
	
		$output6 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_count->facebook as $facebook){ 
	    
	       if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	    
	if($i<=10){
        $output6 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name . ' ( '.$facebook->count.' Mentions ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output6 .='</tbody>
	</table></div>';	
	

	$res1 .='<table>
	        <tbody><tr>
	        <td>'.$output4.'</td><td>'.$output5.'</td><td>'.$output6.'</td></tbody></table>';
	        
	        
	        echo $res1;
	        
	    echo "<br>";
	    
	    
	    	$output7 .='<div class="influence-report">
  
  <p>Top influencers by average reach </p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_reach->web as $web){ 
	    
	      if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	    
	if($i <= 10) { 
         $output7 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank" title = " ' . $web->pretty_name . ' ( ' .$web->reach_average.' Reach )" >'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output7 .='</tbody>
	</table></div>';






	$output8 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_reach->twitter as $tweet){ 
	    
	      if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	if($i <=10){
        $output8 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' .$tweet->reach_average.' Reach ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output8 .='</tbody>
	</table></div>';	
	
	
		$output9 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_reach->facebook as $facebook){ 
	    
	   if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	if($i<=10){
        $output9 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name .' ( ' .$facebook->reach_average.' Reach )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output9 .='</tbody>
	</table></div>';	
	

	$res2 .='<table>
	        <tbody><tr>
	        <td>'.$output7.'</td><td>'.$output8.'</td><td>'.$output9.'</td></tbody></table>';
	        
	        
	        echo $res2;
	        
	    
	 echo "<br>";
	    
	    
	    	$output10 .='<div class="influence-report">
  
  <p>Top influencers by average virality</p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_virality->web as $web){ 
	    
	    if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	       
	    
	    
	if($i <= 10) { 
         $output10 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank" title = " ' . $web->pretty_name .' ( '. $web->virality_average.' Virality )">'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output10 .='</tbody>
	</table></div>';






	$output11 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_virality->twitter as $tweet){ 
	    
	     if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	    
	if($i <=10){
        $output11 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' . $tweet->virality_average.' Virality )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output11 .='</tbody>
	</table></div>';	
	
	
		$output12 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_virality->facebook as $facebook){ 
	    
	     if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	    
	    
	if($i<=10){
        $output12 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name . ' ( ' .$facebook->virality_average.' Virality )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output12 .='</tbody>
	</table></div>';	
	

	$res3 .='<table>
	        <tbody><tr>
	        <td>'.$output10.'</td><td>'.$output11.'</td><td>'.$output12.'</td></tbody></table>';
	        
	        
	        echo $res3;
	        
	   	echo "<br />";
    
    
    
    
	$output1 .='<div class="influence-report">
  
  <p>Top influencers by average interactions </p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_interactions->web as $web){ 
	    
	      if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	    
	if($i <= 10) { 
         $output1 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank" title = " ' . $web->pretty_name . ' ( ' .$web->interaction_average.' Interactions ) ">'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output1 .='</tbody>
	</table></div>';






	$output2 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_interactions->twitter as $tweet){ 
	    
	      if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	if($i <=10){
        $output2 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' . $tweet->interaction_average.' Interactions ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output2 .='</tbody>
	</table></div>';	
	
	
		$output3 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_interactions->facebook as $facebook){ 
	    
	       if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	if($i<=10){
        $output3 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name . '( ' . $facebook->interaction_average.' Interactions ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output3 .='</tbody>
	</table></div>';	
	

	$res .='<table>
	        <tbody><tr>
	        <td>'.$output1.'</td><td>'.$output2.'</td><td>'.$output3.'</td></tbody></table>';
	        
	        
	        echo $res;
	        
	        
	        
		echo "<br />";
		
		
	
		

		
}
?>
      </div>      
             
         </div>
         
          <div class="col-sm-12 col-md-6 col-lg-3" id="keysorting">
            
             
            <div class="social-cnt-sec-fb-side" id="">
                
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




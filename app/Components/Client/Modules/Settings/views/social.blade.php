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
Social Media
@stop

@section('content')

<style>
  
	#t_response .tw{ display:none;
}

  	#f_response .fb{ display:none;
}

	#l_response .ln{ display:none;
}
  
  </style>
  
  
  
  <?php if(!empty($tweetarray) || !empty($facebookarray) || !empty($linkedinarray)) { ?>

<section class="content">
    
    
   <p class="social-xl-sec"><button id="export" class="btn btn-default"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel File</button> </p>
   
      <input type="hidden" id="twitter_excel" name="twitter_excel" />   
      <input type="hidden" id="facebook_excel" name="facebook_excel" />
              
    <div class="row head-social-scrch">
        <div class="col-sm-12">
            <form class="form-horizontal" method="post" id="srch_form"> 
              <div class="form-group">
                <div class="col-xs-9 col-sm-offset-2 col-sm-6">
                    <div class="row">
                        <input type="text" class="form-control social-scrch-inpt" placeholder="Enter the keyords to search" name="srch" id="srch">
                    </div>
                </div>
                <div class="col-xs-3 col-sm-2">
                    <div class="row">
                        <button type="submit" id="srch_btn" name="srch_btn" class="btn btn-default social-scrch-btn">Search</button>
                    </div>
                </div>
              </div>
              
           
            </form>
        </div>
    </div>
    
     <span id="respon" style="margin-left:340px;"></span>
     
     <div id="socials">
    
    <div class="row">
        
         <div class="col-sm-12 col-md-6 col-lg-4" id="facebook">
             <div class="social-hd-sec social-hd-sec-fb"> Recent Activity <img class="social-hd-sec-img" src="../../img/social/fb-logo.png"/></div>
             
             <div class="social-cnt-sec-fb" id="f_response">
                 
                 <?php
                 if(isset($facebookarray->data)) { 
                 foreach($facebookarray->data as $resu){
	
		
        			$message = $link = '';
        		
            		if(isset($resu->link)){
            			
            			$link = $resu->link;	
            			
            		}
        		
        		
        	    	$type = $resu->type;
        		
        	    	$post_id = $resu->id;
        		
            		if(isset($resu->message)){
            			$message  = $resu->message;
            		}
            		
            // when it was posted
            $created_time = $resu->created_time;
            
            $converted_date_time = date( 'Y-m-d H:i:s', strtotime($created_time));
            
            $ago_value = time_elapsed_string($converted_date_time);
        		
        			if($type=="status"){
                        $link="https://www.facebook.com/".$page_id."/posts/".$post_id;
                    }
                    
                       // get tweet text
                $tweet_text=$message;
                 
                // make links clickable
                $tweet_text=preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $tweet_text);
                
                
                

	?>
	
	            <div class="social-cnt-sec-fb-itm">
	                <div class="fb-icon">
	                    
	                    <img src='<?php echo $profile_photo_src; ?>' class='img-thumbnail' />
	                    
	                </div>
                     <h3>
                    <?php  echo $resu->from->name . " Shared a " . "<a href=".$link." target='_blank'>".$type."</a>"; ?>
                    </h3>
                     <p class="social-cnt-sec-fb-athr"><?php echo $ago_value; ?></p>
                     <p class="social-cnt-sec-fb-des"><?php echo $tweet_text; ?> </p>
                 </div>
                 
                 <?php } 
                 
                    }else{ 
                ?>
                
                <span> Sorry , cannot fetch any data ... </span>
                
                <?php } ?>
         
             </div>
             
         </div>
         
         <div class="col-sm-12 col-md-6 col-lg-4" id="twitter">
             <div class="social-hd-sec social-hd-sec-twt"> Recent Tweets <img class="social-hd-sec-img" src="../../img/social/twit-logo.png"/></div>
            
             <div class="social-cnt-sec-twit" id="t_response">
                 
             <?php 
                if(isset($tweetarray->errors)) {
                 ?>   
                        <span> Sorry,  cannot fetch any data ...</span>
                    
            <?php     }else{    
                    
                 foreach($tweetarray as $mytweets){

			$created_time = $mytweets->created_at;
			$converted_date_time = date( 'Y-m-d H:i:s', strtotime($created_time));
			$posted_on = time_elapsed_string($converted_date_time);
			
			// get tweet text
                $tweet_text=$mytweets->text;
                 
            // make links clickable
                $tweet_text=preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $tweet_text);
	  
	      ?>
                 
                 <div class="social-cnt-sec-twit-itm">
                     
                     <div class="twt-icon">
	                    
	                    <img src='<?php echo $mytweets->user->profile_image_url_https; ?>' class='img-thumbnail' />
	                    
	                </div>
                     <h3><?php echo "<a href=https://twitter.com/".$mytweets->user->screen_name.">".$mytweets->user->name."</a>"; ?><span class="social-cnt-sec-twit-athr">@<?php echo $mytweets->user->screen_name; ?></span> <span class="social-cnt-sec-twit-dte"><?php echo $posted_on;?></span></h3>
                     <p class="social-cnt-sec-twit-des"><?php echo $tweet_text;?> </p>
                 </div>
              <?php }
              
                    }
              ?>   
               
                 
             </div>
             
         </div>
         
        
         <div class="col-sm-12 col-md-6 col-lg-4">
             <div class="social-hd-sec social-hd-sec-lkn"> Recent Updates <img class="social-hd-sec-img" src="../../img/social/linked-logo.png"/>
             </div>
              <div class="social-cnt-sec-lnkin" id="l_response">
                 
                 
                 
                 
                 
             <?php 
                if(isset($linkedinarray->errors)) {
                 ?>   
                        <span> Sorry,  cannot fetch any data ...</span>
                    
            <?php     }else{
                
                if(isset($linkedinarray->values)){
                    
                foreach($linkedinarray->values as $linked){
	
		$ac_name = $linked->updateContent->company->name;
		
		$message = $linked->updateContent->companyStatusUpdate->share->comment;
		
		$timestamp = $linked->updateContent->companyStatusUpdate->share->timestamp;
	  
    $converted_date_time = date('Y-m-d H:i:s',$timestamp / 1000);
    $ago_value = time_elapsed_string($converted_date_time);
		
		 $message=preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $message);
		
	      ?>
                 
                  <div class="social-cnt-sec-lnkin-itm">
                     
                     <h3><?php echo "<a href=https://www.linkedin.com/company/".$company_id.">".$ac_name."</a>"; ?></h3>
                     
                     <p class="social-cnt-sec-lnkin-athr"><?php echo $ago_value;?></p>
                     
                       <p class="social-cnt-sec-lnkin-des"><?php echo $message;?></p>
                 </div>
               
              <?php }
              
                    }
            }
              ?>   
               
                 
             </div>
                 
               
               
                
                 
                 
             </div>
         </div>
         
    </div>
    
    
    
    <div class="row" id="load_action">
       <div class="col-sm-12 col-md-12 col-lg-12">
           <p class="scl-load-sec"><a id="loadMore">Load More</a> | <a id="showLess">Show Less</a></p> 
          
       </div>
    </div>
 </div>   
</section>

<?php }else{ ?>

<section class="content">
    <p>
    <h3>Social Listening is not Configured.Please Click <a href="{{route('client.admin.setting')}}">Here</a> to Configure</h3>
    </p>
</section>


<?php } ?>


@stop

@section('style')

<link rel="stylesheet" type="text/css" href="{{ asset('css/client/social.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/color_picker/css/bootstrap-colorpicker.min.css') }}">

@stop

@section('script')

	<script src="{{ asset('plugins/color_picker/js/bootstrap-colorpicker.min.js') }}"></script>


<script>  
 $(document).ready(function(){  
     
     	$('#export').hide();
     	$('#load_action').hide();
     	  
      $('#srch_form').on("submit", function(event){
		 
		$('#respon').html("<img src='../../img/social/loader.gif'/>");
		$('#socials').hide();
	
		event.preventDefault();
        var srch_term = $('#srch').val();  
				   
           $.ajax({  
               
                url: baseUrl + '/admin/setting/social/feed',
                type: 'POST',
                data:{"srch_trm":srch_term,"_token":"{{csrf_token()}}"}, 
            
                success:function(data){  
                    
                    $('#respon').hide();
                    $('#socials').show();
                    $('#export').show();
                    
                    $('#load_action').show();
                    
                   	var tweets = data.split('^')[0];
					var faces = data.split('^')[1];
					var linkeds = data.split('^')[6];
					
					var tweets_c = data.split('^')[4];
					var facebook_c = data.split('^')[5];
					var linked_c = data.split('^')[7];
					
				//	var tweet_excel = data.split('^')[2];
					
				//	var face_excel = data.split('^')[3];
				
			/*    if(tweets != ''){
			        $('#t_response').html(tweets);
			    }else{
			        $('#t_response').html("Sorry, No Feed Found");
			    }
					
				if(faces != ''){
				    $('#f_response').html(faces);
				}else{
				    $('#f_response').html("Sorry, No Feed Found");    
				}
				
				if(linkeds != ''){
				    $('#l_response').html(linkeds);
				}else{
				     $('#l_response').html("Sorry, No Feed Found");
				}*/
				
			
					
			$('#t_response').html(tweets);
			$('#f_response').html(faces);
			$('#l_response').html(linkeds);
			 
					
				     size_tw = tweets_c;
					 size_fb = facebook_c;
					 size_ln = linked_c;
                x=3;
            	
                $('#t_response .tw:lt('+x+')').show();
            	$('#f_response .fb:lt('+x+')').show();
            	$('#l_response .ln:lt('+x+')').show();
            	
                $('#loadMore').click(function () {
                    x= (x+5 <= size_tw) ? x+5 : size_tw;
            		y= (x+5 <= size_fb) ? x+5 : size_fb;
            		z= (x+5 <= size_ln) ? x+5 : size_ln;
            		
                    $('#t_response .tw:lt('+x+')').show();
            		$('#f_response .fb:lt('+y+')').show();
            		$('#l_response .ln:lt('+z+')').show();
            		
                });
                $('#showLess').click(function () {
                    x=(x-5<0) ? 3 : x-5;
            		y=(x-5<0) ? 3 : x-5;
                    z=(x-5<0) ? 3 : x-5;
            		
            		$('#t_response .tw').not(':lt('+x+')').hide();
            		$('#f_response .fb').not(':lt('+y+')').hide();
            		$('#l_response .ln').not(':lt('+z+')').hide();
                });
					 
					
				//	$('#twitter_excel').val(tweet_excel);
				//	$('#facebook_excel').val(face_excel);
					
                }  
           });  
      }); 
      
      
      $('#export').click(function(){
         
     
       var tweet_data = $('#srch_form').serialize();
            
        window.open("{{URL::to('admin/setting/social/excelexport')}}?"+ tweet_data, "_blank");
          
      
      });
   
 });  
 </script>	
	
@stop
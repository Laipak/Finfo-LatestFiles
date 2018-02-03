<?php

namespace App\Components\Client\Modules\Settings;


use Illuminate\Http\Request;

use App\Http\Requests;
use PDF;
use App\Http\Controllers\Controller;
use App\Components\Client\Modules\Settings\Setting;
use App\Components\Client\Modules\Settings\Slider;
use App\Components\Client\Modules\Company\Company as Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Image;
use File;
use DateTime;
use Excel;

use App\Components\Client\Modules\Settings\TwitterAPIExchange;


class SocialController extends Controller
{



    public function getSocialFeedNew(){
        
        
        
         $company_id = Session::get('company_id');
         
         $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
     
        
        $group_id = $get_group_details->group_id;
        $group_name = $get_group_details->group_name;
        
        
        $get_first_key = DB::table('feed_details as t1')->join('feed_group_details as t2','t2.id','=','t1.group_id')->where('t2.company_id',$company_id)->first();
        
        if(!empty($get_first_key)){
        
            $first_key_id = $get_first_key->keyword_id;
        
        }else{
            
            $first_key_id = '';
        }
         
        return $this->view('newsocialnewfile')->with('access_token', $access_token)->with('company_id',$company_id)->with('organization_id', $organization_id)->with('group_id', $group_id)->with('first_key_id', $first_key_id)->with('group_name',$group_name);
    
     }
     
     
     /* Load  Social View File */
     
      public function getSocialFeedNew1(){
        
        $company_id = Session::get('company_id');
         
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        
        $organization_id = $get_toolkit_details->organization_id;
        
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
     
     $group_id = $group_name = '';
        if(isset($get_group_details)){
        
        $group_id = $get_group_details->group_id;
        
        $group_name = $get_group_details->group_name;
       
        }
       
        return $this->view('newsocialnewfile1')->with('access_token', $access_token)->with('company_id',$company_id)->with('organization_id', $organization_id)->with('group_id', $group_id)->with('group_name',$group_name);
    
     }
     
     
     
     
     /* End of Loading Social View File */
     
     
     /* Storing Key Details */
     
     
    public function getSocialFeedKeyDetails(){
            
          
    $curl = curl_init();


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


   if(isset($_POST['srch_trm'])){
	 
	    ini_set('display_errors', 1);

        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = $get_group_details->group_id;
         
        $group_name = $get_group_details->group_name;


        $name = $_POST['srch_trm'];
        
	    $key_value = $_POST['srch_trm'];
	    
	    $case_sensitive  = $_POST['sensitive'];
	    
	   
	    
	    $location = $_POST['country'];
	    
	    if(!empty($location)){
	        
	         $country = [];
            
            foreach($location as $countr){
                
                $country[] = "'$countr'";
                
            }
            
         
            
         $locations = implode(",", $country);
            
            
        if($case_sensitive == 1){
	        
	         $keyword = "{ may_locations : [
    ".$locations."
 ],natural_query : '".$key_value."/cs' }";
	        
	    }else{
	        
	        $keyword = "{ may_locations : [
    ".$locations."
 ],natural_query : '".$key_value."' }";
	        
	    }
            
    
	        
	    }else{
	        
	        
	             
        if($case_sensitive == 1){
	        
	         $keyword = "{natural_query : '".$key_value."/cs'}";
	        
	    }else{
	        
	        $keyword = "{natural_query : '".$key_value."'}";
	        
	        
	    }
            
	        
	      
	        
	    }



    	$data = array("access_token" => $access_token, "name" => $name, "keyword" => $keyword);                                                                    

	    $data_string = json_encode($data);                                      
	    
	
    	$ch = curl_init('https://api.mediatoolkit.com/organizations/'.$organization_id.'/groups/'.$group_id.'/keywords');                                                                      

    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     

    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                          

    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

	    	'Content-Type: application/json',                                                                                

	    	'Content-Length: ' . strlen($data_string))                                                                       

    	);                                                                                                                   

	$result = curl_exec($ch);

	$socialData = json_decode($result);

    if(isset($socialData->data->id)){
	$key_id = $socialData->data->id;
    }

         
     }



if(isset($key_id)){
    
    
   /*  $insert =array('group_id' => $get_group_details->id,
                    'keyword' => $_POST['srch_trm'],
                    'keyword_id' => $key_id 
                 );
                              
        $insert_info = DB::table('feed_details')->insert($insert);*/
    
    $token = $get_toolkit_details->oauth_token;
    $organization_id = $get_toolkit_details->organization_id;
    $group_id = $get_group_details->group_id;

    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?access_token=".$token;


    curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "Content-Type: application/json"  
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
         // echo $response;
    }

    $tweetarray = json_decode($response);


    $output_t = '';

    foreach($tweetarray->data->response as $twt){
	
	    $created_time = $twt->insert_time;
        $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
        $posted_on = time_elapsed_string($converted_date_time);
	    
        $social_icon = $twt->type;
        
         $sentiment= '';
        
      if(!empty($twt->auto_sentiment) && isset($twt->auto_sentiment)){
      
     if($twt->auto_sentiment == 'neutral'){
          $span_class = "background-color:#CDCDCD;color:white;padding:5px;margin-left:20px;";
          
          $icon_clas = "<i class='fa fa-meh-o' aria-hidden='true'></i>";
          
          $border_color = "border-right: 3px solid #CDCDCD";
          
          if(isset($twt->sentiment) && $twt->sentiment == 'positive'){
              
              $positive_span = "background-color:#43C0AB;color:white;padding:5px;margin-left:20px;";
              
          }else{
          
                $positive_span = "padding:5px;margin-left:20px";
              
          }
          
          
          if(isset($twt->sentiment) && $twt->sentiment == 'negative'){
              
               $negative_span = "background-color:#E76456;color:white;padding:5px;margin-left:20px;";
          }else{
          
                $negative_span = "padding:5px;margin-left:20px";
              
          }
          
          
          $sentiment = '
      <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","positive")><span style="'.$positive_span.'"><i class="fa fa-smile-o" aria-hidden="true"></i> Positive </span></a></p>
      <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","neutral")><span style="'.$span_class.'">'.$icon_clas.'  ' . $twt->auto_sentiment .'</span></a></p>
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","negative")><span style="'.$negative_span.'"><i class="fa fa-frown-o" aria-hidden="true"></i> Negative </span></a></p>';
          
      }else if($twt->auto_sentiment == 'positive'){
          
          $span_class = "background-color:#43C0AB;color:white;padding:5px;margin-left:20px;";
          
          $icon_clas = "<i class='fa fa-smile-o' aria-hidden='true'></i>";
          
           $border_color = "border-right: 3px solid #43C0AB";
           
           
        if(isset($twt->sentiment) && $twt->sentiment == 'neutral'){
              
              $neutral_span = "background-color:#CDCDCD;color:white;padding:5px;margin-left:20px;";
              
          }else{
          
                $neutral_span = "padding:5px;margin-left:20px";
              
          }
          
          
          if(isset($twt->sentiment) && $twt->sentiment == 'negative'){
              
               $negative_span = "background-color:#E76456;color:white;padding:5px;margin-left:20px;";
          }else{
          
                $negative_span = "padding:5px;margin-left:20px";
              
          }
        
        $sentiment = '
   
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","positive")><span style="'.$span_class.'">'.$icon_clas.'  ' . $twt->auto_sentiment .'</span></a></p>
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","neutral")><span style="'.$neutral_span.'"><i class="fa fa-meh-o" aria-hidden="true"></i> Neutral </span></a></p>
          <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","negative")><span style="'.$negative_span.'"><i class="fa fa-frown-o" aria-hidden="true"></i> Negative </span></a></p>';
        
           
      }else{
         $span_class = "background-color:#E76456;color:white;padding:5px;margin-left:20px;";
         
         $icon_clas = "<i class='fa fa-frown-o' aria-hidden='true'></i>";
         
          $border_color = "border-right: 3px solid #E76456";
          
           if(isset($twt->sentiment) && $twt->sentiment == 'neutral'){
              
              $neutral_span = "background-color:#CDCDCD;color:white;padding:5px;margin-left:20px;";
              
          }else{
          
                $neutral_span = "padding:5px;margin-left:20px";
              
          }
          
          
          if(isset($twt->sentiment) && $twt->sentiment == 'postive'){
              
               $positive_span = "background-color:#43C0AB;color:white;padding:5px;margin-left:20px;";
          }else{
          
                $positive_span = "padding:5px;margin-left:20px";
              
          }
          
            $sentiment = '
      <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","positive")><span style="'.$positive_span.'"><i class="fa fa-smile-o" aria-hidden="true"></i> Positive </span></a></p>
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","neutral")><span style="'.$neutral_span.'"><i class="fa fa-meh-o" aria-hidden="true"></i> Neutral </span></a></p>
        <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","negative")><span style="'.$span_class.'">'.$icon_clas.'  ' . $twt->auto_sentiment .'</span></a></p>';
      }
      
    
        
    }
   
  
        $social_media = '';
        
         $modal_type = "#".$twt->id;
    
    if($social_icon == 'twitter'){
        
        $social_icon = "<i class='fa fa-twitter' aria-hidden='true'></i>
";

        $social_media = "<div class='container'>
                            <div class='row'> <p> Retweets :  ".$twt->retweet_count."</p>
                            <p> Favorites : ". $twt->favorite_count ."</p>
                          
                            </div>
                          
        
        </div>";
                            

    }else if($social_icon == 'web'){
        $social_icon = "<i class='fa fa-forumbee' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'comment'){
        $social_icon = "<i class='fa fa-comments' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'facebook'){
        $social_icon = "<i class='fa fa-facebook-square' aria-hidden='true'></i>
";
        
        
         $social_media = "<div class=''>
                                <div class='col-md-12'>
                                <div class='col-md-6'>
                                 <p><i class='em em---1'></i> :  ".$twt->like_count."</p>
                                 <p><i class='em em-heart'></i> : ". $twt->love_count ."</p>
                                 <p><i class='em em-dizzy_face'></i> : ". $twt->wow_count." </p>
                                 </div>
                                 
                                 <div class='col-md-6'>
                                 <p>
                                 <i class='em em-grinning'></i> :  ".$twt->haha_count . "
                                 </p>
                                 
                                 <p>
                                <i class='em em-disappointed_relieved'></i> : ". $twt->sad_count . "
                                 </p>
                                 
                                 <p>
                                 <i class='em em-angry'></i> : ". $twt->angry_count . "
                                 </p>
                                 
                                 </div></div>
            
        </div>";
        
        
    }else if($social_icon == 'instagram'){
        $social_icon = "<i class='fa fa-instagram' aria-hidden='true'></i>
";

           $social_media = "<div class='container'>
                           <div class='row'> <p> Likes :  ".$twt->like_count."</p>
                            <p> comment : ". $twt->comment_count ."</p>
                            <p> view : ". $twt->view_count ."</p>
                            </div>
                          
        
        </div>";
        
        
    }else if($social_icon == 'youtube'){
        $social_icon = "<i class='fa fa-youtube-play' aria-hidden='true'></i>
";
        
    }
    else{
         $social_icon = "<i class='fa fa-globe' aria-hidden='true'></i>";
    }
    
    $date = date('d.m.Y', $twt->database_insert_time);
    $time = date('H:i', $twt->database_insert_time);
    
    
      $reachc = $influence = $interact = $engagement = 'N/A';
     if(isset($twt->reach) && !empty($twt->reach)){
         
         $reachc = $twt->reach;
         
         
     }
     
     if(isset($twt->interaction) && !empty($twt->interaction)){
         
         $interact = $twt->interaction;
         
     }
     
     if(isset($twt->influence_score) && !empty($twt->influence_score)){
         
         $influence = $twt->influence_score;
         
     }
     
      if(isset($twt->engagement_rate) && !empty($twt->engagement_rate)){
         
         $engagement = $twt->engagement_rate;
         
     }
     
     
    
    
	
 $output_t .="
	   <div class='social-cnt-sec-fb-itm' style='".$border_color."'>
	                <div class='fb-icon'>
	                    
	                    <img src='".$twt->image."' class='img-circle1' />
	                    
	                </div>
                     <h3>
                  	<a href=".$twt->url." target='_blank'>".$twt->title."</a>
                    </h3>
                     <p class='social-cnt-sec-fb-athr'>".$social_icon . '  |  '. $twt->from  . '  |  '  . $posted_on . "</p>
                    
                      <button data-toggle='collapse' data-target='".$modal_type."' class='accordion'> 
                     <p class='social-cnt-sec-fb-des'>". $twt->mention. "</p>
                     </button>
                      <p class='social-cnt-sec-fb-athr-1' style='margin-top:10px'> REACH  : ".  $reachc  . " | INTERACTION : ".  $interact  .  " | ENGAGEMENT RATE : ".  $engagement  . " | INFLUENCE SCORE : ".  $influence . " |        LOCATION : [  " . implode(",", $twt->locations) . " ]</p>
                 </div>
                 
                <div id='".$twt->id."' class='collapse' id='panel'>  
                 <div class='row' style='padding:10px;margin-left:25px'>
                 <div class='col-sm-4'>
                    <p> Date : ".$date."</p>
                    <p> Time : ".$time."</p>
                 
                 </div>
                 
                 <div class='col-sm-4'>
                        
                        <h4> Social Media </h4>    
                        
                        ".$social_media."
                 </div>
                 
                <div class='col-sm-4'>
                 
                        <h4> Sentiment </h4>
                        ". $sentiment ."
                        
                </div>
                </div>
               </div>
                 ";
	
	
	
	
}

echo $output_t .'^'. $_POST['srch_trm'] .'^'. $key_id;


}    
            
   
}
 
     
     /* End of Storing Key Details */
     
     
     
     /* Get All Keywords */
     
 public function getallKeys(){
         
     if(isset($_POST['group'])){
         
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        
        $organization_id = $get_toolkit_details->organization_id;
            
        $group_id = $_POST['group'];
                
     
        $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."?access_token=".$access_token;

         $curl = curl_init();
         
    curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "Content-Type: application/json"  
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
         // echo $response;
    }

    $keynames = json_decode($response);
                
        $result = $key_only = [];
        
        if(!empty($keynames)){        
        foreach($keynames->data->keywords as $allkey){
                    
                        $result[] ='<a onClick="get_feed_key('.$allkey->id.')" style="cursor:pointer">'.$allkey->name.'</a>
                        <a onclick="delete_feed_key('.$allkey->id.')" style="cursor:pointer">
                          <img width="20px" height="20px" src="../../../img/social/minus.png"/></a>
                                   ';
                                   
                        $key_only[] = $allkey->id;
                    
                    
        }
        
        } 
        
        $total_keys = count($result);
        
        
        $result = rtrim(implode(' | ', $result), ' |  ');
        
        $recent_key = end($key_only);
        
        echo $result .'($)'. $recent_key .'($)'. $total_keys; 
    
             
                
    }         
         
   
}
     
     
     /* End of get all keywords*/
     
     
     
    /* Get Single Keyword Feed */
    
    
    public function getsingleFeed(){
         
    
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
         
         
      if(isset($_POST['key_id'])){
          
        $key_id = $_POST['key_id'];
        
        $company_id = Session::get('company_id');
        
       
        $type = $_POST['type'];
        
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = $get_group_details->group_id;
        
        
        $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        
       

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }
  

$curl = curl_init();       


if($type == 'paginate'){
    
    if(isset($_POST['min_count']) && isset($_POST['max_count'])){
        
        
         $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?offset=".$_POST['min_count']."&count=".$_POST['max_count']."&access_token=".$access_token;
        
        
        
    }
    
    
    
}else if($type == 'sort'){ 

if(isset($_POST['show_only'])){
    
    
    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?type=".$_POST['show_only']."&access_token=".$access_token;
    
    
}   
    
}else if($type == 'single'){
    
    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?access_token=".$access_token;
    
    
}else if($type == 'daterange'){
    
    if(isset($_POST['from']) && isset($_POST['to'])){
        
        $from_time = $_POST['from'] / 1000 ;
        $to_time = $_POST['to'] / 1000;
        
      $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=".$from_time."&to_time=".$to_time."&access_token=".$access_token;
    
    }
}else{
    
      $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?access_token=".$access_token;
    
    
}
   

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json"  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

$tweetarray = json_decode($response);



$output_t= '';

if(!empty($tweetarray->data->response)){
foreach($tweetarray->data->response as $twt){
	
	$created_time = $twt->insert_time;
    $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
    $posted_on = time_elapsed_string($converted_date_time);
	    
    $social_media = '';
    
    
    $sentiment= $border_color = '';
        
    if(!empty($twt->auto_sentiment) && isset($twt->auto_sentiment)){
      
     if($twt->auto_sentiment == 'neutral'){
          $span_class = "background-color:#CDCDCD;color:white;padding:5px;margin-left:20px;";
          
          $icon_clas = "<i class='fa fa-meh-o' aria-hidden='true'></i>";
          
          $border_color = "border-right: 3px solid #CDCDCD";
          
          if(isset($twt->sentiment) && $twt->sentiment == 'positive'){
              
              $positive_span = "background-color:#43C0AB;color:white;padding:5px;margin-left:20px;";
              
          }else{
          
                $positive_span = "padding:5px;margin-left:20px";
              
          }
          
          
          if(isset($twt->sentiment) && $twt->sentiment == 'negative'){
              
               $negative_span = "background-color:#E76456;color:white;padding:5px;margin-left:20px;";
          }else{
          
                $negative_span = "padding:5px;margin-left:20px";
              
          }
          
          
          $sentiment = '
      <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","positive") style="cursor:pointer"><span style="'.$positive_span.'"><i class="fa fa-smile-o" aria-hidden="true"></i> Positive </span></a></p>
      <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","neutral") style="cursor:pointer"><span style="'.$span_class.'">'.$icon_clas.'  ' . $twt->auto_sentiment .'</span></a></p>
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","negative") style="cursor:pointer"><span style="'.$negative_span.'"><i class="fa fa-frown-o" aria-hidden="true"></i> Negative </span></a></p>';
          
      }else if($twt->auto_sentiment == 'positive'){
          
          $span_class = "background-color:#43C0AB;color:white;padding:5px;margin-left:20px;";
          
          $icon_clas = "<i class='fa fa-smile-o' aria-hidden='true'></i>";
          
           $border_color = "border-right: 3px solid #43C0AB";
           
           
        if(isset($twt->sentiment) && $twt->sentiment == 'neutral'){
              
              $neutral_span = "background-color:#CDCDCD;color:white;padding:5px;margin-left:20px;";
              
          }else{
          
                $neutral_span = "padding:5px;margin-left:20px";
              
          }
          
          
          if(isset($twt->sentiment) && $twt->sentiment == 'negative'){
              
               $negative_span = "background-color:#E76456;color:white;padding:5px;margin-left:20px;";
          }else{
          
                $negative_span = "padding:5px;margin-left:20px";
              
          }
        
        $sentiment = '
   
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","positive") style="cursor:pointer"><span style="'.$span_class.'">'.$icon_clas.'  ' . $twt->auto_sentiment .'</span></a></p>
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","neutral") style="cursor:pointer"><span style="'.$neutral_span.'"><i class="fa fa-meh-o" aria-hidden="true"></i> Neutral </span></a></p>
          <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","negative") style="cursor:pointer"><span style="'.$negative_span.'"><i class="fa fa-frown-o" aria-hidden="true"></i> Negative </span></a></p>';
        
           
      }else{
         $span_class = "background-color:#E76456;color:white;padding:5px;margin-left:20px;";
         
         $icon_clas = "<i class='fa fa-frown-o' aria-hidden='true'></i>";
         
          $border_color = "border-right: 3px solid #E76456";
          
           if(isset($twt->sentiment) && $twt->sentiment == 'neutral'){
              
              $neutral_span = "background-color:#CDCDCD;color:white;padding:5px;margin-left:20px;";
              
          }else{
          
                $neutral_span = "padding:5px;margin-left:20px";
              
          }
          
          
          if(isset($twt->sentiment) && $twt->sentiment == 'postive'){
              
               $positive_span = "background-color:#43C0AB;color:white;padding:5px;margin-left:20px;";
          }else{
          
                $positive_span = "padding:5px;margin-left:20px";
              
          }
          
            $sentiment = '
      <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","positive") style="cursor:pointer"><span style="'.$positive_span.'"><i class="fa fa-smile-o" aria-hidden="true"></i> Positive </span></a></p>
       <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","neutral") style="cursor:pointer"><span style="'.$neutral_span.'"><i class="fa fa-meh-o" aria-hidden="true"></i> Neutral </span></a></p>
        <p><a onclick=change_sentiment("'.$twt->id.'","'.$twt->type.'","negative") style="cursor:pointer"><span style="'.$span_class.'">'.$icon_clas.'  ' . $twt->auto_sentiment .'</span></a></p>';
      }
      
    
        
    }
    
   
    $social_icon = $twt->type;
    
    $modal_type = "#".$twt->id;
  
    
    if($social_icon == 'twitter'){
        
        $social_icon = "<i class='fa fa-twitter' aria-hidden='true'></i>
";

             $social_media = "<div class='container'>
                            <div class='row'> <p> Retweets :  ".$twt->retweet_count."</p>
                            <p> Favorites : ". $twt->favorite_count ."</p>
                          
                            </div>
                          
        
        </div>";
        

    }else if($social_icon == 'web'){
        $social_icon = "<i class='fa fa-forumbee' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'comment'){
        $social_icon = "<i class='fa fa-comments' aria-hidden='true'></i>
";
        
    }else if($social_icon == 'facebook'){
        $social_icon = "<i class='fa fa-facebook-square' aria-hidden='true'></i>
";
        
            $social_media = "<div class=''>
                                <div class='col-md-12'>
                                <div class='col-md-6'>
                                 <p><i class='em em---1'></i> :  ".$twt->like_count."</p>
                                 <p><i class='em em-heart'></i> : ". $twt->love_count ."</p>
                                 <p><i class='em em-dizzy_face'></i> : ". $twt->wow_count." </p>
                                 </div>
                                 
                                 <div class='col-md-6'>
                                 <p>
                                 <i class='em em-grinning'></i> :  ".$twt->haha_count . "
                                 </p>
                                 
                                 <p>
                                <i class='em em-disappointed_relieved'></i> : ". $twt->sad_count . "
                                 </p>
                                 
                                 <p>
                                 <i class='em em-angry'></i> : ". $twt->angry_count . "
                                 </p>
                                 
                                 </div></div>
            
        </div>";
        
    }else if($social_icon == 'instagram'){
        $social_icon = "<i class='fa fa-instagram' aria-hidden='true'></i>
";

         $social_media = "<div class='container'>
                           <div class='row'> <p> Likes :  ".$twt->like_count."</p>
                            <p> comment : ". $twt->comment_count ."</p>
                            <p> view : ". $twt->view_count ."</p>
                            </div>
                          
        
        </div>";
        
        
    }else if($social_icon == 'youtube'){
        $social_icon = "<i class='fa fa-youtube-play' aria-hidden='true'></i>
";
        
    }
    else{
         $social_icon = "<i class='fa fa-globe' aria-hidden='true'></i>";
    }
    
    
    if(!empty($twt->title)){
        
        $title = $twt->title;
        
    }else{
        
        $title = '';
    }
    
    
    $date = date('d.m.Y', $twt->database_insert_time);
    $time = date('H:i', $twt->database_insert_time);
    
    $reachc = $influence = $interact = $engagement = 'N/A';
     if(isset($twt->reach) && !empty($twt->reach)){
         
         $reachc = $twt->reach;
         
         
     }
     
     if(isset($twt->interaction) && !empty($twt->interaction)){
         
         $interact = $twt->interaction;
         
     }
     
     if(isset($twt->influence_score) && !empty($twt->influence_score)){
         
         $influence = $twt->influence_score;
         
     }
     
      if(isset($twt->engagement_rate) && !empty($twt->engagement_rate)){
         
         $engagement = $twt->engagement_rate;
         
     }
     
     
    
    
	
 $output_t .="<div class='social-cnt-sec-fb-itm' style='".$border_color."'>
	                <div class='fb-icon'>
	                    
	                    <img src='".$twt->image."' class='img-circle1' />
	                    
	                </div>
	              
	              <div class='txt'>
                     <h3>
                  	<a href=".$twt->url." target='_blank'>".$title."</a>
                    </h3>
                  
                     <p class='social-cnt-sec-fb-athr'>".$social_icon .  '  |    '. $twt->from  . '  |   '  . $posted_on . "</p>
                  
                    <button data-toggle='collapse' data-target='".$modal_type."' class='accordion'> 
                     <p class='social-cnt-sec-fb-des ancor'>". $twt->mention. "</p>
                    </button>
                       <p class='social-cnt-sec-fb-athr-1' style='margin-top:10px'> REACH  : ".  $reachc  . " | INTERACTION : ".  $interact  .  " | ENGAGEMENT RATE : ".  $engagement  . " | INFLUENCE SCORE : ".  $influence . " |        LOCATION : [  " . implode(",", $twt->locations) . " ]</p>
                 </div>
                 </div>
                
                  <div id='".$twt->id."' class='collapse' id='panel'>  
                 <div class='row' style='padding:10px;margin-left:25px'>
                 <div class='col-sm-4'>
                    <p> Date : ".$date."</p>
                    <p> Time : ".$time."</p>
                 
                 </div>
                 
                 <div class='col-sm-4'>
                        
                        <h4> Social Media </h4>    
                        
                        ".$social_media."
                 </div>
                 
                <div class='col-sm-4'>
                
                        <h4> Sentiment </h4>
                          <br />
                        ". $sentiment ."      
                
                <br />
                  <a onclick=change_sentiment('".$twt->id."','".$twt->type."','true') style='cursor:pointer'>Mark as Irrelavant</a>
                  
             
                      
                  
                 </div>       
                </div>
                </div>
               </div>
                 ";
	

	
}
}
    echo $output_t .'(^S^)'. $get_key_name .'(^S^)'. $key_id;    
   
 
 }
         
}
  
    /* End of single keyword Feed*/ 
    
    
    /* Delete an Keyword */
    
    public function deletesingleFeed(){
        
        
        if(isset($_POST['key_id'])){
          
         $key_id = $_POST['key_id'];
        
         $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
         $access_token = $get_toolkit_details->oauth_token;
         $organization_id = $get_toolkit_details->organization_id;
        
         $company_id = Session::get('company_id');
        
         $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = $get_group_details->group_id;
        
        
        $curl = curl_init();          


    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id;
    

	$data = array("access_token" => $access_token);                                                 

	$data_string = json_encode($data);                                                                                   

    	$ch = curl_init('https://api.mediatoolkit.com/organizations/'.$organization_id.'/groups/'.$group_id.'/keywords/'.$key_id);                                                                      

    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");                                                                     

    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                          

    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

	    	'Content-Type: application/json',                                                                                

	    	'Content-Length: ' . strlen($data_string))                                                                       

    	);                                                                                                                   


$response = curl_exec($ch);
$err = curl_error($ch);

curl_close($ch);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

$tweetarray = json_decode($response);
   
    $status =  $tweetarray->message;
   
    if($status == 'OK'){
        
     
                echo '1';
        
        
         }
    
     }
         
 
 }
    
    
    
    /* End of delete an Keyword */
      
     
     
     public function getSocialFeedReport(){
         
        if(isset($_POST['key_id'])) { 
            
            
        $company_id = Session::get('company_id');
         
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
     
        
        $group_id = $get_group_details->group_id;
        $group_name = $get_group_details->group_name;
        
           $get_first_key = DB::table('feed_details as t1')->join('feed_group_details as t2','t2.id','=','t1.group_id')->where('t2.company_id',$company_id)->first();
        
       $key_id = $_POST['key_id'];
       
       
       
            
        $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        
       

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url1,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }
       
       
       
       
        
        $curl = curl_init();
        
        
$url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/reports?last24h=false&response=false&fields=%5Binfluencers_by_count%2Ccount%2Cinfluencers_by_reach%2Cinfluencers_by_interactions%2Cinfluencers_by_virality%5D&access_token=".$access_token;

curl_setopt_array($curl, array(
  
  CURLOPT_URL => $url,
  
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json",  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}


$tweetarray = json_decode($response);



$output1 = $output2 = $output3 = $output4 = $output5 = $output6 = $output7 = $output8 = $output9 = $output10 = $output11 = $output12 = '';

$res = $res1 = $res2 = $res3 = '';


if(!empty($tweetarray->data)){

foreach($tweetarray->data as $twt){
    
   
 	if(!empty($twt->influencers_by_count->web)){    
 	    
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
}


	if(!empty($twt->influencers_by_count->twitter)){ 


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
	    
	    if(!empty($tweet)){
        $output5 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' . $tweet->count.' Mentions )">'.$name. '</a> </td></tr>';
	    }else{
	        $output5 .='<tr><td>-</td></tr>';
	        
	    }
	   
        $i++;
	}
	}
	
	
	$output5 .='</tbody>
	</table></div>';	
	
	}
	
	if(!empty($twt->influencers_by_count->facebook)){ 
	    
		$output6 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;width:235px">
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
	    
	    if(!empty($facebook->unique_name) || !empty($facebook->pretty_name) || !empty($facebook->count)){
	        
         $output6 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name . ' ( '.$facebook->count.' Mentions ) ">'.$name. '</a> </td></tr>';
         
	    }else{
	        $output6 .='<tr><td>-</td></tr>';
	        
	    }
	    
	    
      
        $i++;
	}
	}
	
	
	$output6 .='</tbody>
	</table></div>';	
	} 

	$res1 .='<table>
	        <tbody><tr>
	        <td style="vertical-align:top">'.$output4.'</td><td style="vertical-align:top">'.$output5.'</td><td style="vertical-align:top">'.$output6.'</td></tbody></table>';
	        
	        
	        echo $res1;
	        
	    echo "<br>";
	    
	    	if(!empty($twt->influencers_by_reach->web)){ 
	    
	    	$output7 .='<div class="influence-report">
  
  <p>Top influencers by average reach </p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_reach->web as $web){ 
	    
	    if(isset($web->reach_average)){
	        $reach_avg = $web->reach_average;
	    }else{
	        $reach_avg = '0';
	    }
	    
	      if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	    
	if($i <= 10) { 
         $output7 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank" title = " ' . $web->pretty_name . ' ( ' .$reach_avg.' Reach )" >'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output7 .='</tbody>
	</table></div>';

}



	if(!empty($twt->influencers_by_reach->twitter)){ 
    

	$output8 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_reach->twitter as $tweet){ 
	    
	     if(isset($tweet->reach_average)){
	        $reach_avg = $tweet->reach_average;
	    }else{
	        $reach_avg = '0';
	    }
	    
	      if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	if($i <=10){
        $output8 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' .$reach_avg.' Reach ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output8 .='</tbody>
	</table></div>';	
	
}
	
	if(!empty($twt->influencers_by_reach->facebook)){ 
	    
		$output9 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;width:235px">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_reach->facebook as $facebook){ 
	    
	     if(isset($facebook->reach_average)){
	        $reach_avg = $facebook->reach_average;
	    }else{
	        $reach_avg = '0';
	    }
	    
	   if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	if($i<=10){
        $output9 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name .' ( ' .$reach_avg.' Reach )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output9 .='</tbody>
	</table></div>';	
    
	}	

	$res2 .='<table>
	        <tbody><tr>
	        <td style="vertical-align:top">'.$output7.'</td><td style="vertical-align:top">'.$output8.'</td><td style="vertical-align:top">'.$output9.'</td></tbody></table>';
	        
	        
	        echo $res2;
	        
	    
	 echo "<br>";
	  
	  	if(!empty($twt->influencers_by_virality->web)){   
	    
	    	$output10 .='<div class="influence-report">
  
  <p>Top influencers by average virality</p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_virality->web as $web){ 
	    
	     if(isset($web->virality_average)){
	        $viral_avg = $web->virality_average;
	    }else{
	        $viral_avg = '0';
	    }
	    
	    if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	       
	    
	    
	if($i <= 10) { 
         $output10 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank" title = " ' . $web->pretty_name .' ( '. $viral_avg.' Virality )">'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output10 .='</tbody>
	</table></div>';

}


	if(!empty($twt->influencers_by_virality->twitter)){ 


	$output11 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_virality->twitter as $tweet){ 
	    
	    
	     if(isset($tweet->virality_average)){
	        $viral_avg = $tweet->virality_average;
	    }else{
	        $viral_avg = '0';
	    }
	    
	     if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	    
	if($i <=10){
        $output11 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' . $viral_avg.' Virality )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output11 .='</tbody>
	</table></div>';	
	
	
	}
	
	
	
	if(!empty($twt->influencers_by_virality->facebook)){ 
	    
		$output12 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;width:235px">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_virality->facebook as $facebook){ 
	    
	     if(isset($facebook->virality_average)){
	        $viral_avg = $facebook->virality_average;
	    }else{
	        $viral_avg = '0';
	    }
	    
	     if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	    
	    
	if($i<=10){
        $output12 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name . ' ( ' .$viral_avg.' Virality )">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output12 .='</tbody>
	</table></div>';	
	
	}
	

	$res3 .='<table>
	        <tbody><tr>
	        <td style="vertical-align:top">'.$output10.'</td><td style="vertical-align:top">'.$output11.'</td><td style="vertical-align:top">'.$output12.'</td></tbody></table>';
	        
	        
	        echo $res3;
	        
	   	echo "<br />";
    
    
    	if(!empty($twt->influencers_by_interactions->web)){ 
    
	$output1 .='<div class="influence-report">
  
  <p>Top influencers by average interactions </p>            
  <table class="table">
    <thead><th style="width:2%;"> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_interactions->web as $web){ 
	    
	     if(isset($web->interaction_average)){
	        $inte_avg = $web->interaction_average;
	    }else{
	        $inte_avg = '0';
	    }
	    
	      if(strlen($web->pretty_name) > 30) { 
	        
	       $name = substr($web->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $web->pretty_name;
	       
	    }
	    
	if($i <= 10) { 
         $output1 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank" title = " ' . $web->pretty_name . ' ( ' .$inte_avg.' Interactions ) ">'.$name. ' </a></td></tr>';
        
        $i++;
	}
	}
	
	
	$output1 .='</tbody>
	</table></div>';


}

	if(!empty($twt->influencers_by_interactions->twitter)){ 

	$output2 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_interactions->twitter as $tweet){ 
	    
	     if(isset($tweet->interaction_average)){
	        $inte_avg = $tweet->interaction_average;
	    }else{
	        $inte_avg = '0';
	    }
	    
	      if(strlen($tweet->pretty_name) > 30) { 
	        
	       $name = substr($tweet->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $tweet->pretty_name;
	        
	    }
	    
	if($i <=10){
        $output2 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank" title = " ' . $tweet->pretty_name . ' ( ' . $inte_avg.' Interactions ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output2 .='</tbody>
	</table></div>';	
	
	}
	
	if(!empty($twt->influencers_by_interactions->facebook)){ 
	    
		$output3 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:30px;width:235px">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_interactions->facebook as $facebook){
	    
	       if(isset($facebook->interaction_average)){
	        $inte_avg = $facebook->interaction_average;
	    }else{
	        $inte_avg = '0';
	    }
	    
	       if(strlen($facebook->pretty_name) > 30) { 
	        
	       $name = substr($facebook->pretty_name, 0, 30);
	       $name = $name . '....';
	   
	    }else{
	        
	        $name = $facebook->pretty_name;
	        
	       
	    }
	    
	if($i<=10){
        $output3 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank" title = " ' . $facebook->pretty_name . '( ' . $inte_avg.' Interactions ) ">'.$name. '</a> </td></tr>';
        $i++;
	}
	}
	
	
	$output3 .='</tbody>
	</table></div>';	
	
}

	$res .='<table>
	        <tbody><tr>
	        <td style="vertical-align:top">'.$output1.'</td><td style="vertical-align:top">'.$output2.'</td><td style="vertical-align:top">'.$output3.'</td></tbody></table>';
	        
	        
   
	        
	        
}	        

}
        echo $res .'^'. $key_id .'^'. $get_key_name;  
         
}
     
}  
     
    public function getSocialMentionReport(){
        
        
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
         
        
         
         if(isset($_POST['key_id'])) {  
         
        $company_id = Session::get('company_id');
         
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
     
        
        $group_id = $get_group_details->group_id;
        $group_name = $get_group_details->group_name;
        
      $key_id = $_POST['key_id'];
      
      $type = $_POST['types'];
      
      
      
      /* Get Key Name */
        
        $url3 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        

        $curl3 = curl_init();
         
         curl_setopt_array($curl3, array(
                 CURLOPT_URL => $url3,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response3 = curl_exec($curl3);
        $err = curl_error($curl3);

        curl_close($curl3);
        
        $keyname = json_decode($response3);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }

        
     
      /* End of key Name get */ 
         
        $curl = curl_init();
        
        if($type == 'filter'){
        
         $which = $_POST['show_only'];   
            $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?sort=reach&type=".$which."&count=5&access_token=".$access_token;    
            
        }else{
        
        $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?sort=reach&count=5&access_token=".$access_token;
        
        }

    curl_setopt_array($curl, array(
  
    CURLOPT_URL => $url,
  
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json",  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}


$byreach = json_decode($response);


 $output_reach = $keyword_name = '';
 
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
        $social_icon = "<i class='fa fa-facebook-square' aria-hidden='true'></i>
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
    
    
    $reachc = $influence = $interact = $engagement = 'N/A';
     if(isset($reach->reach) && !empty($reach->reach)){
         
         $reachc = $reach->reach;
         
         
     }
     
     if(isset($reach->interaction) && !empty($reach->interaction)){
         
         $interact = $reach->interaction;
         
     }
     
     if(isset($reach->influence_score) && !empty($reach->influence_score)){
         
         $influence = $reach->influence_score;
         
     }
     
      if(isset($reach->engagement_rate) && !empty($reach->engagement_rate)){
         
         $engagement = $reach->engagement_rate;
         
     }
     
    
    $border_color = '';
    if(!empty($reach->auto_sentiment) && isset($reach->auto_sentiment)){
      
      if($reach->auto_sentiment == 'neutral'){
         
            $border_color = "border-right: 3px solid #CDCDCD";
          
      }else if($reach->auto_sentiment == 'positive'){
         
          $border_color = "border-right: 3px solid #43C0AB";
          
      }else{
       
         $border_color = "border-right: 3px solid #E76456";
         
      }
      
    
    }
    
    
    
    $output_reach .="
	   <div class='social-cnt-sec-fb-itm' style='".$border_color."'>
	                <div class='fb-icon'>
	                    
	                    <img src='".$reach->image."' class='img-circle1' />
	                    
	                </div>
                     <h3>
                  	<a href=".$reach->url." target='_blank'>".$reach->title."</a>
                    </h3>
                     <p class='social-cnt-sec-fb-athr'>".$social_icon . '  |  '. $reach->from  . '  |  '  . $posted_on . "</p>
                     <p class='social-cnt-sec-fb-des'>". $reach->mention. "</p>
                     
                      <p class='social-cnt-sec-fb-athr-1' style='margin-top:10px'> REACH  : ".  $reachc  . " |  INTERACTION : ".  $interact  .  " |  ENGAGEMENT RATE : ".  $engagement  . " |  INFLUENCE SCORE : ".  $influence . " |        LOCATION : [  " . implode(",", $reach->locations) . " ]</p>
                 </div>";
                 
                 
	
}

    
}


 $type = $_POST['types'];
 
 if($type == 'filter'){

  $which = $_POST['show_only'];   
    $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=1483833600&sort=virality&type=".$which."&count=5&access_token=".$access_token;


 }else{  
 
$url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=1483833600&sort=virality&count=5&access_token=".$access_token;

  } 
 

 
 $curl1 = curl_init();

    curl_setopt_array($curl1, array(
  
    CURLOPT_URL => $url1,
  
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json",  
  ),
));

$response1 = curl_exec($curl1);
$err = curl_error($curl1);

curl_close($curl1);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}


$byvirality = json_decode($response1);

$output_virality = '';

if(!empty($byvirality->data->response)){
    
foreach($byvirality->data->response as $virality){
	
	$created_time = $virality->insert_time;
    $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
    $posted_on = time_elapsed_string($converted_date_time);

    $keyword_name = $virality->keyword_name;	    
   
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

$reachc = $influence = $interact = $engagement = 'N/A';
     if(isset($virality->reach) && !empty($virality->reach)){
         
         $reachc = $virality->reach;
         
         
     }
     
     if(isset($virality->interaction) && !empty($virality->interaction)){
         
         $interact = $virality->interaction;
         
     }
     
     if(isset($virality->influence_score) && !empty($virality->influence_score)){
         
         $influence = $virality->influence_score;
         
     }
     
      if(isset($virality->engagement_rate) && !empty($virality->engagement_rate)){
         
         $engagement = $virality->engagement_rate;
         
     }
     
     
       if(!empty($virality->auto_sentiment) && isset($virality->auto_sentiment)){
      
      if($virality->auto_sentiment == 'neutral'){
         
            $border_color = "border-right: 3px solid #CDCDCD";
          
      }else if($virality->auto_sentiment == 'positive'){
         
          $border_color = "border-right: 3px solid #43C0AB";
          
      }else{
       
         $border_color = "border-right: 3px solid #E76456";
         
      }
      
    
    }
    
      
       $output_virality .="
	   <div class='social-cnt-sec-fb-itm' style='".$border_color."'>
	                <div class='fb-icon'>
	                    
	                    <img src='".$virality->image."' class='img-circle1' />
	                    
	                </div>
                     <h3>
                  	<a href=".$virality->url." target='_blank'>".$virality->title."</a>
                    </h3>
                     <p class='social-cnt-sec-fb-athr'>".$social_icon . '  |  '. $virality->from  . '  |  '  . $posted_on . "</p>
                     <p class='social-cnt-sec-fb-des'>". $virality->mention. "</p>
                     
                     <p class='social-cnt-sec-fb-athr-1' style='margin-top:10px'> REACH  : ".  $reachc  . " | INTERACTION : ".  $interact  .  " | ENGAGEMENT RATE : ".  $engagement  . " | INFLUENCE SCORE : ".  $influence . " |       LOCATION : [  " . implode(",", $virality->locations) . " ]</p>
                 </div>";
                 
                 
	
}

    
} 
      
        echo $output_reach .'^'. $output_virality .'^'. $key_id . '^' . $get_key_name; 
         
     }
     
     
     
    }
    
    
    
    
     public function getSocialSentimentReport(){
        
        if(isset($_POST['key_id'])) {  
         
        $company_id = Session::get('company_id');
         
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
     
        
        $group_id = $get_group_details->group_id;
        $group_name = $get_group_details->group_name;
        
        $key_id = $_POST['key_id'];
        
        
               
        $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        
       

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url1,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }
       
       
       
      
        $type = $_POST['types'];
        
     
        $curl = curl_init();
        
        if($type == 'filter'){
        
         $which = $_POST['show_only'];   
        $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/reports?last24h=false&aggregation=false&fields=[auto_sentiment]&type=".$which."&access_token=".$access_token;    
         
        }else{
        
         $which = 'all';  
         
        $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/reports?last24h=false&aggregation=false&fields=[auto_sentiment]&type=".$which."&access_token=".$access_token;
        
       
        
        }
     

    
   curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json"  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

$graphresponse = json_decode($response);

$all_positive_count = $all_negative_count = $all_neutral_count = 0;

if(isset($graphresponse->data->response)){
foreach($graphresponse->data->response as $grp){
	
	if(isset($grp->$which)){
	 
	 if(isset($grp->$which->auto_sentiment->positive)){
	    $all_positive_count += $grp->$which->auto_sentiment->positive;     
	 }   
	
	 if(isset($grp->$which->auto_sentiment->negative)){
	    $all_negative_count += $grp->$which->auto_sentiment->negative;     
	 }   
	 
	  if(isset($grp->$which->auto_sentiment->neutral)){
	    $all_neutral_count += $grp->$which->auto_sentiment->neutral;     
	 }   
	
    }
	

	
}
}

 $sentiment_result = $all_positive_count .'|'. $all_negative_count .'|'. $all_neutral_count;
 

  echo $sentiment_result .'^'. $key_id . '^' . $get_key_name; 

}
    
    
}   


public function updateSocialFeedKeyDetails(){

 $curl = curl_init();

   if(isset($_POST['key_id'])){
	 
	    ini_set('display_errors', 1);

        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = $get_group_details->group_id;
         
        $group_name = $get_group_details->group_name;


        $key = $_POST['key_id'];
        
	 
	  $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key."?access_token=".$access_token;
    
      $curl1 = curl_init();
         
     curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url1,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }
    
    
      $case_sensitive  = $_POST['sensitive'];
	    
	   
	    
	    $location = $_POST['country'];
	    
	 /*   $old_country = explode(",", $_POST['old_country']);
	    
	   
	    
	    if(empty($old_country)){
	    
	    $location = $_POST['country'];
	    
	    }else{
	        
	         $location = array_merge($location, $old_country);
	        
	    }*/
	    
	    if(!empty($location)){
	        
	         $country = [];
            
            foreach($location as $countr){
                
                $country[] = "'$countr'";
                
            }
            
         
            
         $locations = implode(",", $country);
         
         
        
        if($case_sensitive == 1){
	        
	$keyword = "{ may_locations : [
    ".$locations."
 ],natural_query : '".$get_key_name."/cs' }";
	        
	    }else{
	        
	$keyword = "{ may_locations : [
    ".$locations."
 ],natural_query : '".$get_key_name."' }";
	        
	    }
            
    
	        
	    }else{
	        
	        
	             
        if($case_sensitive == 1){
	        
	         $keyword = "{natural_query : '".$get_key_name."/cs'}";
	        
	    }else{
	        
	        $keyword = "{natural_query : '".$get_key_name."'}";
	        
	        
	    }
            
	        
	      
	        
	    }



    	$data = array("access_token" => $access_token, "name" => $get_key_name, "keyword" => $keyword);                                                                    

	    $data_string = json_encode($data);     
	    
	   

    	$ch = curl_init('https://api.mediatoolkit.com/organizations/'.$organization_id.'/groups/'.$group_id.'/keywords/'.$key);                                                                      

    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     

    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                          

    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

	    	'Content-Type: application/json',                                                                                

	    	'Content-Length: ' . strlen($data_string))                                                                       

    	);                                                                                                                   

	$result = curl_exec($ch);

	$socialData = json_decode($result);
	
	

    $key_id = '';
    if(isset($socialData->data->id)){
        
	    $key_id = $socialData->data->id;
	    
    }
    
    echo $key_id .'^'. $get_key_name;
         
     }

}
      
      
   public function getcountries(Request $request){
        
        
         $term = trim($request->q);

        if (empty($term)) {
            return \Response::json([]);
        }

        $tags = DB::table('apps_countries')
		->where('country_name', 'LIKE', '%'.$term.'%')
		->limit(10)
		->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->country_code, 'text' => $tag->country_name];
        }

        return \Response::json($formatted_tags);
        
      
   
        
    }      
      
      
      
    public function changeFeedSentiment(){
        
        
        if(isset($_POST['mentions']) && isset($_POST['sentiment']) && isset($_POST['type'])){
            
	 
	    ini_set('display_errors', 1);

        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = $get_group_details->group_id;
         
        $group_name = $get_group_details->group_name;


        $mention_id = $_POST['mentions'];
        
	    $sentiment = $_POST['sentiment'];
	    
	    $type  = $_POST['type'];
	    
	    $key_id = $_POST['key_id'];
	    
	    $curl = curl_init();
	    
	    if($sentiment == 'true'){
	        
	        $data = array("access_token" => $access_token, "irrelevant" => $sentiment);        
	        
	        
	    }else{
	    
	        $data = array("access_token" => $access_token, "sentiment" => $sentiment);            
	        
	    }
	    
	                                                                

	    $data_string = json_encode($data);                                                                                   

    	$ch = curl_init('https://api.mediatoolkit.com/organizations/'.$organization_id.'/groups/'.$group_id.'/keywords/'.$key_id.'/mentions/'.$type.'/'.$mention_id.'/meta');                                                                      

    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     

    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                          

    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

	    	'Content-Type: application/json',                                                                                

	    	'Content-Length: ' . strlen($data_string))                                                                       

    	);                                                                                                                   

    	$result = curl_exec($ch);

	    
        $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }
	    
        
        
        echo $key_id .'^'. $get_key_name;
        
        
    }


        
        
}



 public function feedexportexcel(){
      
      
      $key_id = Input::get('first_key_id');
      
      $filter_type = Input::get('filter_type');
      
      
      $current_date = date('Y-m-d');
      $new_date = strtotime('-3 month', strtotime($current_date));
      $new_date = date('Y-m-d', $new_date);
      
     $from_time = strtotime($new_date);
     
      if(isset($key_id)){
          
       
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = '';
        
        if(isset($get_group_details)){
        $group_id = $get_group_details->group_id;
        }
      
$curl = curl_init();          

if(!empty($filter_type)){
    
    
    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=".$from_time."&type=".$filter_type."&access_token=".$access_token;
    
    
    
    
}else{
    
    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=".$from_time."&access_token=".$access_token;
    
    
}
      



curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json"  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

$tweetarray = json_decode($response);


	    
        $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url1,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }


        $file_name = "SocialExport";
        
        $title = 'Social Feeds with Search Term ' . $get_key_name;
        
  
        
     Excel::create('SocialExport', function($excel) use($tweetarray,$title) {
				$excel->sheet('Social Listening Export', function($sheet) use($tweetarray,$title) {
					$sheet->loadView('app.Components.Client.Modules.Settings.export', array('tweetarray' => $tweetarray,'term'=> $title))->setAutoFilter();	
				});
				
		 })->export('xls');



}
         
      
      
      
      
      
  }   
     
     
  
  
  
  
   public function feedexportpdf(){
       
       
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
      
      
      $key_id = Input::get('first_key_id');
      
      $filter_type = Input::get('filter_type');
      
       $current_date = date('Y-m-d');
      $new_date = strtotime('-3 month', strtotime($current_date));
      $new_date = date('Y-m-d', $new_date);
      
     $from_time = strtotime($new_date);
     
      
      if(isset($key_id)){
          
       
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id= '';
        if(isset($get_group_details)){
        
        $group_id = $get_group_details->group_id;
        
        }
      
$curl = curl_init();          

if(!empty($filter_type)){
    
    
    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=".$from_time."&type=".$filter_type."&access_token=".$access_token;
    
    
    
    
}else{
    
    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."/mentions?from_time=".$from_time."&access_token=".$access_token;
    
    
}
      



curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json"  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

$tweetarray = json_decode($response);


	    
        $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url1,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }


        $title = 'Social Feeds with Search Term - ' . $get_key_name;
        
 
  

   $html ='<!DOCTYPE html><html><head><title>Social Feed</title><style>
table {
    font-family: arial, sans-serif;
    font-size:8px;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style></head><body>';
 
 $html .='<table><tr><th colspan="5">'.$title.'</th></tr><tr><th>Id</th><th>Type</th><th>Title</th><th>Mentions</th><th>Created At</th></tr>';
 
 $i=1;
 
   if(!empty($tweetarray) && isset($tweetarray->data->response)){
      foreach($tweetarray->data->response as $twt) {
          
        $created_time = $twt->insert_time;
        $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
        $posted_on = time_elapsed_string($converted_date_time);
           
 $html .='<tr><td>'.$i.'</td><td>'.$twt->type.'</td><td>'.$twt->title.'</td><td>'.$twt->mention.'</td><td>'.$posted_on.'</td></tr>';
 
 $i++;
      }
   }else{
       
       
        $html .='<tr><td colspan="5">No Data Found</td></tr>';
       
       
   }
  
  
  $html .='</table></body></html>';
  

       
   $pdf = PDF::loadHTML($html);
      
      
    return $pdf->download('SocialListening.pdf');
      
    /* $pdf = PDF::loadView('app.Components.Client.Modules.Settings.views.feed-pdf', compact('tweetarray'));
     
    return $pdf->stream('SocialListening.pdf');
*/
}
         
      
      
      
      
      
  }  
  
  
  
  public function getFeeddefinition(){
      
      
       $key_id = Input::get('key_id');
      
    
      
      if(isset($key_id)){
          
       
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
         
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
        
        $company_id = Session::get('company_id');
        $get_group_details = DB::table('feed_group_details')->where('company_id',$company_id)->first();
        
        $group_id = $get_group_details->group_id;
        
      
$curl = curl_init();          


    $url = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
    

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "Content-Type: application/json"  
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

$definitions = json_decode($response);


	    
        $url1 = "https://api.mediatoolkit.com/organizations/".$organization_id."/groups/".$group_id."/keywords/".$key_id."?access_token=".$access_token;
        

        $curl1 = curl_init();
         
         curl_setopt_array($curl1, array(
                 CURLOPT_URL => $url1,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "Content-Type: application/json"  
                ),
        ));

        $response1 = curl_exec($curl1);
        $err = curl_error($curl1);

        curl_close($curl1);
        
        $keyname = json_decode($response1);
        
      
        
        $get_key_name = '';
        
        if(!empty($keyname->data->name)){
        
            $get_key_name = $keyname->data->name;
            
        }


$location = $is_case = '';

$result = [];

if(isset($definitions->data->definition->may_locations)){
    
   $location =  implode(",",  $definitions->data->definition->may_locations);
    
 
}



if(isset( $definitions->data->definition->query->phrase->case_sensitive)){    

    $is_case =  $definitions->data->definition->query->phrase->case_sensitive;

}     
    echo $location .'^'. $is_case .'^'. $key_id .'^'. $get_key_name; 
     
      
      }  
      
  }
      
   
   
   
   public function generateOrganization(){
       
       
    //$companies = array(293,294,295,296,297);
    
    
$companies  = array();
if(!empty($companies)){ 

foreach($companies as $company){

$company_id = $company;

  $get_company_details = DB::table('company')->where('id',$company_id)->first();
        
        $get_toolkit_details = DB::table('Media_tool_settings')->first();
        
        $access_token = $get_toolkit_details->oauth_token;
        $organization_id = $get_toolkit_details->organization_id;
            
        $group_name = $get_company_details->company_name;
            
                
    $data = array("access_token" => $access_token, "name" => $group_name, "public" => "true");                                                                    

    $data_string = json_encode($data);                                                         
    $group_url = 'https://api.mediatoolkit.com/organizations/'.$organization_id.'/groups';

    $ch = curl_init($group_url);                                                                      

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

    'Content-Type: application/json',                                                                                

    'Content-Length: ' . strlen($data_string))                                                                       

    );                                                                                                                   

    $result = curl_exec($ch);

    $socialData = json_decode($result);

    $group_id = $socialData->data->id;


    $insert =array('company_id' => $company_id,
                    'group_name' => $group_name,
                    'group_id' => $group_id
                 );
                              
        $insert_info = DB::table('feed_group_details')->insert($insert);



}   
       
       
}       
 
}
   
      
   
}

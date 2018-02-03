<style>
table {
    font-family: arial, sans-serif;
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
</style>

<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mediatoolkit.com/organizations/39834/groups/67487/keywords/6202543/reports?last24h=false&response=false&fields=%5Binfluencers_by_count%2Cinfluencers_by_reach%2Cinfluencers_by_interactions%2Cinfluencers_by_virality%5D&access_token=rz0wjfvo1hul0djeq8th23b894oinpd4g1czx8p7s0o50dkkp7",
  
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




foreach($tweetarray->data as $twt){
    
    
    
    	$output4 .='<div class="influence-report">
  
  <p>Most frequent sources </p>            
  <table class="table">
    <thead><th> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_count->web as $web){ 
	    
	    
	if($i <= 10) { 
         $output4 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank">'.$web->pretty_name. ' </a>( ' . $web->count.' Mentions )</td></tr>';
        
        $i++;
	}
	}
	
	
	$output4 .='</tbody>
	</table></div>';






	$output5 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_count->twitter as $tweet){ 
	if($i <=10){
        $output5 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank">'.$tweet->pretty_name. '</a> ( ' . $tweet->count.' Mentions )</td></tr>';
        $i++;
	}
	}
	
	
	$output5 .='</tbody>
	</table></div>';	
	
	
		$output6 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_count->facebook as $facebook){ 
	if($i<=10){
        $output6 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank">'.$facebook->pretty_name. '</a> ( ' . $facebook->count.' Mentions )</td></tr>';
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
    <thead><th> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_reach->web as $web){ 
	    
	    
	if($i <= 10) { 
         $output7 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank">'.$web->pretty_name. ' </a>( ' . $web->reach_average.' Reach )</td></tr>';
        
        $i++;
	}
	}
	
	
	$output7 .='</tbody>
	</table></div>';






	$output8 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_reach->twitter as $tweet){ 
	if($i <=10){
        $output8 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank">'.$tweet->pretty_name. '</a> ( ' . $tweet->reach_average.' Reach )</td></tr>';
        $i++;
	}
	}
	
	
	$output8 .='</tbody>
	</table></div>';	
	
	
		$output9 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_reach->facebook as $facebook){ 
	if($i<=10){
        $output9 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank">'.$facebook->pretty_name. '</a> ( ' . $facebook->reach_average.' Reach )</td></tr>';
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
    <thead><th> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_virality->web as $web){ 
	    
	    
	if($i <= 10) { 
         $output10 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank">'.$web->pretty_name. ' </a>( ' . $web->virality_average.' Virality )</td></tr>';
        
        $i++;
	}
	}
	
	
	$output10 .='</tbody>
	</table></div>';






	$output11 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_virality->twitter as $tweet){ 
	if($i <=10){
        $output11 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank">'.$tweet->pretty_name. '</a> ( ' . $tweet->virality_average.' Virality )</td></tr>';
        $i++;
	}
	}
	
	
	$output11 .='</tbody>
	</table></div>';	
	
	
		$output12 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_virality->facebook as $facebook){ 
	if($i<=10){
        $output12 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank">'.$facebook->pretty_name. '</a> ( ' . $facebook->virality_average.' Virality )</td></tr>';
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
    <thead><th> Number</th>
    <th>Web Resources</th>
   
    </thead>
    <tbody>';
    $i=1;
    
	foreach($twt->influencers_by_interactions->web as $web){ 
	    
	    
	if($i <= 10) { 
         $output1 .='<tr><td>'.$i.'</td><td><a href="'.$web->unique_name.'" target="_blank">'.$web->pretty_name. ' </a>( ' . $web->interaction_average.' Interactions )</td></tr>';
        
        $i++;
	}
	}
	
	
	$output1 .='</tbody>
	</table></div>';






	$output2 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Twitter</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_interactions->twitter as $tweet){ 
	if($i <=10){
        $output2 .='<tr><td><a href="'.$tweet->unique_name.'" target="_blank">'.$tweet->pretty_name. '</a> ( ' . $tweet->interaction_average.' Interactions )</td></tr>';
        $i++;
	}
	}
	
	
	$output2 .='</tbody>
	</table></div>';	
	
	
		$output3 .='<div class="influence-report">
  <p> </p>   
  <table class="table"  style="margin-top:50px;">
    <thead>
    <th>Facebook</th>
   
    </thead>
    <tbody>';
    
    $i=1;
	foreach($twt->influencers_by_interactions->facebook as $facebook){ 
	if($i<=10){
        $output3 .='<tr><td><a href="'.$facebook->unique_name.'" target="_blank">'.$facebook->pretty_name. '</a> ( ' . $facebook->interaction_average.' Interactions )</td></tr>';
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
		
		
	
		
	/*	echo "<pre>";
		print_r($twt); */
		
}
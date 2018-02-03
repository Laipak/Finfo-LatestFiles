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

<table>
   <thead>
       <tr>
           <th></th>
           <th></th>
          <th> <?php echo $term; ?></th>
           
           
       </tr>
       <tr>
           
       </tr>
      <tr>
         <th>ID</th>
         <th>Type</th>
         <th>Title</th>
         <th>Mentions</th>
         <th>Created At</th>
         
      </tr>
   </thead>
   <tbody>
       
       <?php  $i = 1; ?>
     @if(!empty($tweetarray) && isset($tweetarray->data->response))
      @foreach($tweetarray->data->response as $twt)
      <tr>
         <td><?php echo $i; ?></td>
         <td>{{ $twt->type }}</td>
         <td>
          {{ $twt->title }}
         </td>
         <td>{{ $twt->mention }}</td>
         
         <?php 
         
             	$created_time = $twt->insert_time;
                 $converted_date_time = date( 'Y-m-d H:i:s', $created_time);
                $posted_on = time_elapsed_string($converted_date_time);
         
         ?>
         <td>{{ $posted_on }}</td>
        <?php $i++; ?>
      </tr>
          @endforeach
          
          
      @else
      <tr>
         <td colspan="7">No Records Found</td>
      </tr>
      @endif
    
   </tbody>
</table>
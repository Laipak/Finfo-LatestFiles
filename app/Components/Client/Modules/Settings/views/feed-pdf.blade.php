<?php 



$html ='<html><head><style type="text/css">body{font-family:font-family:Arial, Helvetica, sans-serif;font-size:10px;text-transform:uppercase;}h1{font-size:15px;}h2{font-size:12px;}table {border-collapse: collapse;}</style>
	</head><body><div class="container"><div class="content-wrapper" style="height:auto;"><table width="100%" border="0" style="font-size:8px !important;" cellpadding="2"><thead><tr><th colspan="5" style="font-size:20px;" class="report-name"><span>Social Listening Feed Reports</span></th></tr><tr><th colspan="5" style="font-size:20px;" class="report-name">&nbsp;</th></tr><tr style="" valign="top"><th>ID</th><th>Type</th><th>Title</th><th>Mentions</th><th>Created At</th></tr><tr><th colspan="5"><hr style="color: #ccc;background-color: #ccc;"></th></tr>
						  </thead>
						  <tbody>';                                  
							
							if(!empty($tweetarray))
							{
							$j =1;
							$i =1;
							foreach($tweetarray->data->response as $twt)
							{
								$tr_class=($i % 2 == 0)?'even':'odd';
								 $i++;  
							                         
							$html.='<tr class="'.$tr_class.'">                                     
										<td class="table_tr">'.$j.'</td>
										<td class="table_tr">'.$twt->type.'</td>
										<td class="table_tr">'.$twt->title.'</td>
										<td class="table_tr">'.$twt->mention.'</td>
										<td class="table_tr">'.$twt->insert_time.'</td>
									
							      </tr>';
							      
							      $j++;
							}
							}
						  $html .='
								</table> 
						      </div>								
						    </div>
					    
					    </body>
					   </html>';
					 
					 
					 
					 return $html;
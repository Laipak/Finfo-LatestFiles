<?php 
 require 'PHPMailer/PHPMailerAutoload.php';

 
$servername = "localhost";
$username = "finfo_user";
$password = "#$;)OEDhB]vR";
$database = "finfo_db";


$con = mysqli_connect($servername, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}




$sql = "SELECT company_id,lnk_token_date FROM `social_data`";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {

   foreach($result as $social) {
       
       $token_date = $social['lnk_token_date'];
       
       $company_id = $social['company_id'];
       
       
        $now = time(); // or your date as well
        $token_date = strtotime($token_date);
        $datediff = $now - $token_date;

        $difference_date = floor($datediff / (60 * 60 * 24));
        
       
       if($difference_date >= 56 ){
           
           
           $company_details = "SELECT * FROM company WHERE id = '".$company_id."' AND is_active = 1";
           
           $cdetails = mysqli_query($con, $company_details);	
           
           $row = mysqli_fetch_object($cdetails);
          
           $email = $row->email_address;
           $first_name = $row->company_name;
           
        
           $subject ='ReGenerate Linkedin Access Token ';

                                    	$message.="<html><body>";
										$message.='<style>
													#sucess-table
													{
														margin-top:10px;
													}

													table {
														border: 1px solid #ddd;
														background-color: transparent;
														width: 100%;
														max-width: 100%;
														text-align:center;	
														margin-bottom: 20px;
														border-spacing: 0;
														border-collapse: collapse;
													}
													#sucess-table th, #sucess-table td {
														text-align: center;
													}
													</style>';
										$message .= '<div style="max-width:650px;border: 2px solid #ddd; padding:20px;"><center><img src="" /></center>';
										$message .= '<h3>Remainder for  Regenerate Access Token !!</h3>';
										$message .= 'Hello ' . $first_name . ',';
										$message .= '<p>Your Registered Linked in Access Token is get expired soon. Please Reset  the Client Id, Client Secret and Redirect URL to continue the service</p></br>';
									
		
		send_mail($email, $first_name, $subject, $message);
          
           
       }
		
		
    }
}
  
  function send_mail($to_mail, $name, $subject, $message)
	{			
       
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->IsHTML(true);
			$mail->SMTPDebug = 0;
			$mail->Host = 'tls://smtp.sendgrid.net:587'; 
			$mail->SMTPAuth = true;
			$mail->Username = "xproecommerce";
			$mail->Password = "seoparkmindz28";
			$mail->setFrom('ashtohash@gmail.com', 'Finfo');
        	$mail->addAddress($to_mail, $name);
			$mail->isHTML(true);
            $mail->Subject = $subject;
			$mail->Body = $message;
		//	$mail->AddAttachment("uploads/bills/".$billId.".pdf");
			
			if(!$mail->send()) 
			{
				echo "Mailer Error: " . $mail->ErrorInfo;
			} 
			else 
			{
				echo "Message has been sent successfully";
			}
    }











?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/phpmailer_vendor/autoload.php';

function smtpEmailConfig($to, $cc, $send_from, $Bcc, $sender_name, $subject, $body, $attachment = null)
{
    $AWS_SMTP_UN = 'app.journeygenie@gmail.com';
    $AWS_SMTP_PWD = getenv('GMAIL_APP_PASSWORD') ?: 'nzptmtdjfzxxbbnw';
    $SMTP_HOST = 'smtp.gmail.com';
    $SMTP_PORT = 587;

	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP(true);
		$mail->SetFrom($send_from, $sender_name);
		$mail->Username = $AWS_SMTP_UN; 
		$mail->Password = $AWS_SMTP_PWD; 
		$mail->Host = $SMTP_HOST; 
		$mail->Port = $SMTP_PORT; 
		$mail->SMTPAuth = true; 
		$mail->SMTPSecure = 'TLS'; 
		$mail->AddAddress($to);
		if ($Bcc) :
			$mail->AddBcc($Bcc);
		endif;
		if ($cc) :
			$mail->addCC($cc);
		endif;
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $body;
		if ($attachment) :
			$mail->addAttachment($attachment);
		endif;
		$send = $mail->Send();
		return true;
	} catch (Exception $e) {
        error_log("Email not sent. PHPMailer Error: {$mail->ErrorInfo}");
        return false;
	}
}

// Helper to send SMS via OnewaySMS
function sendOnewaySms($mobile_no, $message) {
    // Basic sanitization on mobile
    $mobile_no = preg_replace('/[^0-9]/', '', $mobile_no);

    $apiusername = urlencode('APIZWPSQQ275W');
    $apipassword = urlencode('APIZWPSQQ275WDLW7Y');
    $senderid = urlencode('RUPINIS');
    $msg = urlencode($message);

    $smsGatewayUrl = "http://gateway.onewaysms.sg:10002/api.aspx";
    $api_params = "?apiusername={$apiusername}&apipassword={$apipassword}&mobileno={$mobile_no}&senderid={$senderid}&languagetype=1&message={$msg}";
    $url = $smsGatewayUrl . $api_params;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    
    if (!$output) {
        $output = @file_get_contents($url);
    }

    return $output;
}
?>

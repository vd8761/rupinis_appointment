<?php
require_once '../includes/env.php';
require_once 'smtp_functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$country_code = htmlspecialchars($_POST['country_code'] ?? '');
$country_name = htmlspecialchars($_POST['country_name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');

if ($country_name) {
    $full_phone = trim("$country_name $country_code $phone");
} else {
    $full_phone = trim("$country_code $phone");
}

$service = htmlspecialchars($_POST['service'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$full_phone = $country_code . ' ' . $phone;

$subject = "New Contact Us Enquiry from $name";

$admin_email = getenv('ENQUIRY_EMAIL_SENT_TO') ?: 'rupinisit@gmail.com'; // From user specs or earlier context
$sender_email = getenv('SEND_FROM_EMAIL') ?: 'app.journeygenie@gmail.com';
$sender_name = getenv('SEND_FROM_NAME') ?: 'Rupinis';

// Construct HTML Body
$html_body = "
<h2>New Contact Form Submission</h2>
<p><strong>Name:</strong> $name</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Phone:</strong> $full_phone</p>
<p><strong>Interested Service:</strong> $service</p>
<p><strong>Message:</strong></p>
<blockquote style='border-left: 4px solid #eee; padding-left: 10px; margin-left: 0;'>
    " . nl2br($message) . "
</blockquote>
";

// Send email to admin
$sent_admin = smtpEmailConfig($admin_email, '', $sender_email, '', $name, $subject, $html_body);

$base_url = rtrim(getenv('BASE_URL') ?: 'https://rupinis.com/appointments', '/');
$logo_url = $base_url . '/assets/images/logo-preview.png';

$user_subject = "Thank you for contacting Rupini's!";
$user_body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f4f4f4">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #541F5C; padding: 30px;">
                            <img src="' . $logo_url . '" alt="Rupinis Logo" width="150" style="display: block; border: 0;">
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; color: #333333; line-height: 1.6;">
                            <h2 style="margin: 0 0 20px 0; color: #541F5C; text-align: center;">THANK YOU!</h2>
                            <p style="margin: 0 0 20px 0; font-size: 15px;">Dear ' . trim($name) . ',</p>
                            <p style="margin: 0 0 20px 0; font-size: 15px;">Thank you for reaching out to us. We have successfully received your enquiry regarding <strong>' . $service . '</strong>.</p>
                            <p style="margin: 0 0 20px 0; font-size: 15px;">Our team is currently reviewing your message and will get back to you as soon as possible.</p>
                            <p style="margin: 0 0 20px 0; font-size: 15px;">For urgent matters, please feel free to call us directly at <strong>+65 6291 6789</strong>.</p>
                            <br>
                            <p style="margin: 0; color: #541F5C; font-weight: bold; font-size: 15px;">Warm regards,<br>The Rupini\'s Team</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #541F5C; padding: 20px; color: #ffffff; font-size: 13px;">
                            <p style="margin: 0 0 10px 0; font-weight: bold; letter-spacing: 1px;">RUPINI\'S</p>
                            <p style="margin: 0 0 10px 0;">
                                <a href="https://www.rupinis.com" target="_blank" style="color: #ffffff; text-decoration: none; margin: 0 10px;">Website</a> | 
                                <a href="https://rupinis.com/contact/" target="_blank" style="color: #ffffff; text-decoration: none; margin: 0 10px;">Contact Us</a>
                            </p>
                            <p style="margin: 0; opacity: 0.7; font-size: 12px;">&copy; ' . date("Y") . ' Rupini\'s. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
';

$sent_user = smtpEmailConfig($email, '', $sender_email, '', $sender_name, $user_subject, $user_body);

if ($sent_admin) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again later.']);
}
?>

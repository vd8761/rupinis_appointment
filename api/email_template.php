<?php
function getAppointmentConfirmationEmailTemplate($booking_name, $booking_date, $booking_starttime, $booking_endtime, $booking_branch, $booking_beautician, $services_formatted, $booking_refno, $price, $payment_mode) {
    
    $date_formatted = date('M j, Y', strtotime($booking_date));
    $start_formatted = date('h:i A', strtotime($booking_starttime));
    $end_formatted = date('h:i A', strtotime($booking_endtime));
    
    $payment_status_text = (strpos(strtolower($payment_mode), 'hitpay') !== false) ? 'Paid' : 'Pending (Pay at Salon)';

    $base_url = getenv('BASE_URL') ?: 'https://rupinis.com/appointments';
    $logo_url = rtrim($base_url, '/') . '/assets/images/logo-preview.png';

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Appointment Confirmation</title>
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
                                <h2 style="margin: 0 0 0px 0; color: #541F5C; text-align: center;">THANK YOU !</h2>
                                <h3 style="margin: 0 0 20px 0; color: #541F5C; text-align: center;">For making an appointment with Rupini\'s</h3>
                                <p style="margin: 0 0 20px 0; font-size: 15px;">Hello ' . trim($booking_name) . ',</p>
                                <p style="margin: 0 0 20px 0; font-size: 15px;">We are delighted to confirm your appointment.</p>
                                <p style="margin: 0 0 20px 0; font-size: 15px;">Below are your appointment details for your reference:</p>
                                
                                <table width="100%" cellspacing="0" cellpadding="10" border="0" style="background-color: #f9f9f9; border-radius: 5px; margin-bottom: 25px;">
                                    <tr>
                                        <td style="font-weight: bold; width: 40%; color: #541F5C; border-bottom: 1px solid #eee;" colspan="2"><p style="margin: 0; font-size: 15px;">Appointment Details</p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; width: 40%;"><p style="margin: 0; font-size: 15px;">Date & Time:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . $date_formatted . '<br><span style="font-size: 0.9em; color: #666;">' . $start_formatted . ' - ' . $end_formatted . '</span></p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; width: 40%;"><p style="margin: 0; font-size: 15px;">Branch:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . trim(ucwords(strtolower((string) ($booking_branch)))) . '</p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; width: 40%;"><p style="margin: 0; font-size: 15px;">Beautician:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . trim(ucwords(strtolower((string) ($booking_beautician)))) . '</p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; vertical-align: top; width: 40%;"><p style="margin: 0; font-size: 15px;">Services:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . trim(ucwords(strtolower((string) ($services_formatted)))) . '</p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; width: 40%; color: #555; border-bottom: 1px solid #eee;"><p style="margin: 0; font-size: 15px;">Transaction Ref:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . $booking_refno . '</p></td>
                                    </tr>
                                </table>

                                <table width="100%" cellspacing="0" cellpadding="10" border="0" style="background-color: #f9f9f9; border-radius: 5px; margin-bottom: 25px;">
                                    <tr>
                                        <td style="font-weight: bold; width: 40%;  color: #541F5C; border-bottom: 1px solid #eee;" colspan="2"><p style="margin: 0; font-size: 15px;">Payment Details</p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; width: 40%;"><p style="margin: 0; font-size: 15px;">Total Amount:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">SGD ' . number_format($price, 2) . '</p></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; width: 40%;"><p style="margin: 0; font-size: 15px;">Payment Method:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . $payment_mode . '</p></td> 
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #555; border-bottom: 1px solid #eee; width: 40%;"><p style="margin: 0; font-size: 15px;">Payment Status:</p></td>
                                        <td style="color: #333; border-bottom: 1px solid #eee; width: 60%;"><p style="margin: 0; font-size: 15px;">' . $payment_status_text . '</p></td>
                                    </tr>
                                </table>

                                <p style="margin: 0 0 10px 0; font-size: 15px;">We look forward to welcoming you and providing you with an exceptional experience.</p>
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
    </html>';

    return $html;
}
?>

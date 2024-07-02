<?php

class Notification
{

    static function sendRegisterPaymentNotificationEmail(EventPayment $eventPayment, $addSubjectName = false)
    {
        global $wpdb;
        $CHART_BASE_URL = QR_CODE_BASE_URL;
        $to = array($eventPayment->ticket_email, $eventPayment->email_address, "soporte@worldcrowns.com");
        $subject = "Order {$eventPayment->invoice_id} for {$eventPayment->description}";
        if ($addSubjectName) {
            $subject = $subject . ' ' . $eventPayment->payee_name;
        }

        $queryString = "?t={$eventPayment->event_ticket}&e={$eventPayment->ticket_email}";
        $courtesy = $eventPayment->apply_for_free ? "<h1>ACCESO DE CORTESIA</h1>" : "";
        // $id = $eventPayment->id;
        ob_start();
        echo '<!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <title>Order Detail</title>
        </head>

        <body bgcolor="#f1f1f1" style="font-family: Helvetica,Arial,sans-serif;font-size: 13px;">
            <center>
                <table width="600px">
                    <tbody>
                        <tr>
                            <td>
                                <div style="margin:20px; background-color:#fff; color: #333; max-width: 800px; margin: 0 auto; background: white; padding: 24px 14px; border: solid 1px #e2e2e2;">
                                    <table style="width: 800px;text-align: justify;line-height: 18px;border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <td colspan="12" align="center"><img src="https://worldcrowns.com/wp-content/uploads/2020/07/wc512x512.png" width="100" /></td>
                                            </tr>
                                        </tbody><!-- HEADER -->
                                        <tbody>
                                            <tr>
                                                <td colspan="8" style="padding: 10px;font-weight: 500;line-height: 2;font-weight: bolder;">ORDER DETAILS</td>
                                                <td colspan="4" style="padding: 10px; text-align: right;font-weight: 500;color: gray;line-height: 2;">
                                                    <span style="
                                                            background: #6fc737;
                                                            padding: 8px 20px;
                                                            border-radius: 8px;
                                                            color: white;
                                                        ">' . $eventPayment->status . '</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!-- CUSTOMER INFO -->
                                        <tbody>
                                            <tr>
                                                <td colspan="6" style="padding: 10px;font-weight: 500;line-height: 2;font-weight: bolder;">Billing Details</td>
                                                <td colspan="6" style="padding: 10px;font-weight: 500;line-height: 2;font-weight: bolder;">Payment Details</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Name</td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->payee_name . '</td>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;">
                                                    <!-- Credit Card --> Auth id
                                                </td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->payments_id . '</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Email</td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->ticket_email . '</td>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Date of purchase</td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->update_time . '</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="padding: 3px 10px; font-weight: 700;">Address</td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;width: 25%;">' . $eventPayment->shipping_address_address_line_1 . '</td>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Invoice No.</td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->invoice_id . '</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;"></td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right; ">' . $eventPayment->shipping_address_admin_area_1 . ', ' . $eventPayment->shipping_address_country_code . '</td>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;">IBO.</td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->ibo . '</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style=" padding: 3px 10px;font-weight: 700;"></td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;">' . $eventPayment->shipping_address_postal_code . '</td>
                                                <td colspan="3" style="padding: 3px 10px;font-weight: 700;"></td>
                                                <td colspan="3" style="padding: 3px 10px;text-align: right;"></td>
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="12"> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="12" style="
                                                                    text-align: center;
                                                                    background: #ebebeb;
                                                                ">
                                                    ' . $courtesy . '
                                                    <a target="_blank" href="' . esc_url(get_permalink($eventPayment->post_id)) . '' . $queryString . '" style="
                                                                display: inline-block;
                                                                font-size: 1.4rem;
                                                                text-decoration: none;
                                                                padding: 10px 30px;
                                                                background: #6fc737;
                                                                color: white;
                                                                border-radius: 24px;
                                                                margin: 10px 0;
                                                            ">CLICK HERE</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="12" style="padding: 40px 10px 0 10px;font-weight: 500;">Order Items</td>
                                            </tr>
                                            <tr>
                                               <td style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;">Code</td>
                                                <td colspan="6" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;">Item</td>
                                                <td colspan="2" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">Price</td>
                                                <td colspan="1" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">Qty.</td>
                                                <td colspan="2" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">
                                                    Total</td>
                                            </tr>
                                            <tr>
                                                <td colspan="12">
                                                    <hr />
                                                </td>
                                            </tr>
                                            <tr style="vertical-align: top;">
                                               <td><img src="' . $CHART_BASE_URL . '?chs=150x150&cht=qr&chl=' . urlencode($eventPayment->event_ticket) . '" width="150" height="150"></td>
                                                <td colspan="6" style=" padding: 20px 10px 0 10px;">
                                                    ' . $eventPayment->description . '
                                                    <br />
                                                    <span style="
                                                                color: white;
                                                                font-size: .9rem;
                                                                padding: 4px 16px;
                                                                background: green;
                                                                border-radius: 21px;
                                                                margin: 6px;
                                                                display: inline-block;
                                                            ">' . $eventPayment->event_ticket . '</span>
                                                    <br />
                                                </td>
                                                <td colspan="2" style="padding: 20px 10px 0 10px;text-align: right;font-weight: 500;">' . $eventPayment->amoun_currency_value . '</td>
                                                <td colspan="1" style="padding: 20px 10px 0 10px;text-align: right;font-weight: 500;">1</td>
                                                <td colspan="2" style="padding: 20px 10px 0 10px;text-align: right;font-weight: 500;">' . $eventPayment->amoun_currency_value . '</td>
                                            </tr>
                                            <tr>
                                                <td colspan="12">
                                                    <b style="
                                                            margin-top: 10px;
                                                            display: block;
                                                        ">NOTE: DO NOT SHARE THIS LINK WITH ANYONE. This is a private LINK can only be used by one person at a time.</b>
                                                </td>
                                            </tr>
                                               <tr>
                                                <td colspan="12">
                                                    <hr />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;">Total</td>
                                                <td colspan="6" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">' . $eventPayment->amoun_currency_code . '$ ' . $eventPayment->amoun_currency_value . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <p style="text-align:center;font-size:12px;"><a target="_blank" href="/" style="text-decoration: none !important; color: #6fc737;font-size:12px;">World Crowns,
                                        Ave. Tiradentes, Plaza Naco. Local Worldcrowns. (809-549-6164) <br /> Copyright © Todos
                                        los derechos reservados.</a></p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </center>
        </body>

        </html>';
        $message = ob_get_clean();
        $headers = array("From: info@worldcrowns.com");
        $attachments = array();
        $status = false;

        try {
            $status = wp_mail($to, $subject, $message, $headers, $attachments);
        } catch (Exception $e) {
        }

        $wpdb->insert("{$wpdb->prefix}payment_notifications", array(
            "payment_id" => $eventPayment->payments_id,
            "to" => join(",", $to),
            "subject" => $subject,
            "message" => $message,
            "status" => $status
        ), $format = null);

        return $message;
    }

    static function resendNotificationFromEventPayment($event_payment_id,  $aditional_mail = "")
    {
        global $wpdb;
        $status = false;
        try {
            $payment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}event_payment WHERE id = '{$event_payment_id}' LIMIT 1");
            $notification = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}payment_notifications WHERE payment_id = '{$payment->payments_id}' LIMIT 1");
            $headers = array("From: info@worldcrowns.com");
            $attachments = array();
            if ($aditional_mail) {
                $notification->to = $notification->to . "," . $aditional_mail;
            }
            $status = wp_mail($notification->to, $notification->subject, $notification->message, $headers, $attachments);
        } catch (Exception $e) {
        };
        return $status;
    }

    static function resendNotificationFromID($notificationID, $aditional_mail = "")
    {
        global $wpdb;
        $status = false;
        try {
            $notification = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}payment_notifications WHERE id = {$notificationID} ORDER BY id DESC LIMIT 1");
            $headers = array("From: info@worldcrowns.com");
            $attachments = array();
            if ($aditional_mail) {
                $notification->to = $notification->to . "," . $aditional_mail;
            }
            $status = wp_mail($notification->to, $notification->subject, $notification->message, $headers, $attachments);
        } catch (Exception $e) {
        };
        return $status;
    }
}

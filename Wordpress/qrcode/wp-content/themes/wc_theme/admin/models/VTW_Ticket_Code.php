<?php
require_once( TEMPLATEPATH . '/admin/EventPayment.php' );

class TicketCode{

    public $event_payment_id = "";
    public $code = "";
    public $requested_by = "";
    public $requested_by_id = 0;
    public $status;
    public $created_at;

    public static function get_name(){
        global $wpdb;
        return "{$wpdb->prefix}event_ticket_code";
    }

    public static function insertTicket(TicketCode $tCode){
        global $wpdb;
        // $toInsert = (array) $tCode;
        $wpdb->insert(TicketCode::get_name(), (array) $tCode, null);
    }

    public static function insertTicketCodeFromPayment($paymentId, EventPayment $eventPayment){
        $tCode = new TicketCode();
        $tCode->event_payment_id = $paymentId;
        $tCode->code = $eventPayment->event_ticket;
        $tCode->requested_by = esc_sql($eventPayment->payee_name);
        $tCode->requested_by_id = get_current_user_id();
        $tCode->status = 1;

        $date = new DateTime();
        $tCode->created_at = $date->format('Y-m-d H:i:s');

        return TicketCode::insertTicket($tCode);

    }

    public static function getStatusLabel(int $status){
        $label = "";
        if($status !== null){
            switch ($status) {
                case 0:
                    $label = "USED";
                    break;
                case 1:
                    $label = "ACTIVE";
                    break;
                case 3:
                    $label = "CANCELED";
                    break;
                default:
                    $label =  "UNKNOWN";
                    break;
            }
        }
        return $label;
    }
    
    public static function isStatus(int $statusId, string $compareStatus){
        $stat = strtolower(TicketCode::getStatusLabel($statusId));
        $comp = strtolower($compareStatus);
        return $stat === $comp;
    }

    public static function getTable(){
        global $wpdb;
        return "{$wpdb->prefix}event_ticket_code";
    }

    public static function markAdRedeemed(int $id){
        global $wpdb;
        return $wpdb->update(
            "{$wpdb->prefix}event_ticket_code",
            array("status" => 0), // USED
            array("event_payment_id" => $id)
        );
    }

    public static function markAdRedeemeFisical(int $id){
        global $wpdb;
        return $wpdb->update(
            "{$wpdb->prefix}event_ticket_code",
            array("status" => 0), // USED
            array("event_payment_id" => $id)
        );
    }

	public static function markAsCanceled(Int $id){
        global $wpdb;
        $status = $wpdb->update("{$wpdb->prefix}event_ticket_code", array("status" => 3 ), array("id" => $id));
        if($status){
            $ticket = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}event_ticket_code WHERE id = {$id}");
            $status = $wpdb->update("{$wpdb->prefix}event_payment", array("status" => "ANULADO" ), array("id" => $ticket->event_payment_id));
        }
        return $status;
    }

    public static function getTicketsFromPaymentID(Int $paymentId){
        global $wpdb;
        $__paymentId = esc_sql($paymentId);
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}event_ticket_code WHERE event_payment_id = '{$__paymentId}'");
    }

    public static function getTicketCodeFromPaymentID(Int $paymentId){
        global $wpdb;
        $__paymentId = esc_sql($paymentId);
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}event_ticket_code WHERE event_payment_id = '{$__paymentId}' AND STATUS = 1 ORDER BY ID DESC LIMIT 1");
    }

    public static function updateLastTicketCodeIP($code, $paymentID, $IP){
        global $wpdb;
        $code = esc_sql($code);
        $IP = esc_sql($IP);
        $paymentID = esc_sql($paymentID);

        return $wpdb->update(
            "{$wpdb->prefix}event_ticket_code",
            array("watchedByIP" => $IP ),
            array(
                "code" => $code,
                "event_payment_id" => $paymentID
            )
        );
    }



    public static function qtyTickerSold($postID){
        global $wpdb;
        $__postID = esc_sql($postID);
        return $wpdb->get_row("SELECT count(id) as totalSold FROM {$wpdb->prefix}event_payment WHERE post_id = {$__postID}");
    }

    public static function getLastEventTicketCode($code, $email, $eventId){
        global $wpdb;
        $result = $wpdb->get_results("SELECT EP.*, code AS last_event_ticket_code
            FROM {$wpdb->prefix}event_ticket_code AS ETC
            LEFT JOIN {$wpdb->prefix}event_payment AS EP ON ETC.event_payment_id = EP.ID
            WHERE ETC.code = '{$code}'
                AND  EP.ticket_email = '{$email}'
                AND EP.post_id = '{$eventId}'
            AND ETC.status = 1
            ORDER BY ID DESC
            LIMIT 1
        ");
        return $result[0];
    }
    
    public static function getLastEventTicketCodeAny($code, $email, $eventId){
        global $wpdb;
        $result = $wpdb->get_results("SELECT EP.*, code AS last_event_ticket_code
            FROM {$wpdb->prefix}event_ticket_code AS ETC
            LEFT JOIN {$wpdb->prefix}event_payment AS EP ON ETC.event_payment_id = EP.ID
            WHERE ETC.code = '{$code}'
                AND  EP.ticket_email = '{$email}'
                AND EP.post_id = '{$eventId}'
            AND ETC.status IN (0,1) 
            ORDER BY ID DESC
            LIMIT 1
        ");
        return $result[0];
    }
    
     public static function getTicketCodeByCode($__code, $__eventId){
        global $wpdb;
        if(empty($__eventId)){
            $query = "SELECT * FROM {$wpdb->prefix}event_ticket_code WHERE code = '".esc_sql($__code)."' AND STATUS = 1 ORDER BY ID DESC LIMIT 1";
        }else{
            $query = "SELECT 
                ETC.*
            FROM
                {$wpdb->prefix}event_ticket_code AS ETC
            INNER JOIN
                {$wpdb->prefix}event_payment AS EP ON EP.id = ETC.event_payment_id
            WHERE
                EP.post_id = '".esc_sql($__eventId)."'
                    AND code = '".esc_sql($__code)."'
                    AND ETC.STATUS = 1
            ORDER BY ID DESC
            LIMIT 1";
        }
        return $wpdb->get_row($query);
    }

}

<?php
require_once( get_template_directory() . '/admin/EventPayment.php' );

class EventPaymentCatch{

	public $source = "";
	public $payload = "";
	public $at = "";
	public $event_payment_id = 0;
	public $hash = "";
	public $log = "";

    public static function table_name(){
        global $wpdb;
        return "{$wpdb->prefix}event_payment_catch";
    }

    public static function getAll(){
        global $wpdb;
        $table_name = EventPaymentCatch::table_name();
        return $wpdb->get_results("SELECT * FROM {$table_name}");
    }

    public static function getPending(){
        global $wpdb;
        $table_name = EventPaymentCatch::table_name();
        return $wpdb->get_results("SELECT * FROM {$table_name} WHERE event_payment_id IS NULL ORDER BY id DESC");
    }

    public static function countPetPending(){
        global $wpdb;
        $table_name = EventPaymentCatch::table_name();
        return $wpdb->get_row("SELECT count(ID) as totalPendingCatch FROM {$table_name} WHERE event_payment_id IS NULL AND log != 'PAYMENT_REF_EXIST'");
    }

    public static function getById($id){
        global $wpdb;
        $table_name = EventPaymentCatch::table_name();
        return $wpdb->get_row("SELECT * FROM {$table_name} WHERE id = {$id}");
    }
    
    public static function insert($source, EventPayment $eventPayment){
        global $wpdb;
        $toInsert = array(
            "eventID" => $eventPayment->post_id,
            "source" => $source,
            "payload" => serialize($eventPayment),
            // "at" => null,
            "hash" => md5(serialize($eventPayment)),
            "event_payment_id" => null
        );
        $wpdb->insert(EventPaymentCatch::table_name(), $toInsert, null);
        return $wpdb->insert_id;
    }

    public static function insertFromPayPal(EventPayment $eventPayment, $source = "PAYPAL"){
        global $wpdb;
        $toInsert = array(
            "eventID" => $eventPayment->post_id,
            "source" => $source,
            "payload" => serialize($eventPayment),
            "hash" => md5(serialize($eventPayment)),
            // "at" => current_time( 'timestamp' ),
            "event_payment_id" => null
        );
        $wpdb->insert(EventPaymentCatch::table_name(), $toInsert, null);
        return $wpdb->insert_id;
    }

    public static function addPaymentId($id, $payment_id){
        global $wpdb;
        return $wpdb->update(
            EventPaymentCatch::table_name(), 
            array("event_payment_id" => $payment_id), // USED
            array("id" => $id) 
        );
    }

    public static function addLog($id, $log){
        global $wpdb;
        return $wpdb->update(
            EventPaymentCatch::table_name(), 
            array("log" => $log), // USED
            array("id" => $id) 
        );
    }
}
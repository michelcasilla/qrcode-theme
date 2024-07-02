<?php


class VTW_Event_Payment_Zync
{

	public $id = null;
	public $source = null;
	public $payload = null;
	public $payment_id = null;
	public $status = null;
	public $response = null;
	public $at = null;


	public static function table_name()
	{
		global $wpdb;
		return "{$wpdb->prefix}event_payment_sync";
	}

	public static function getAll()
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Zync::table_name();
		return $wpdb->get_results("SELECT * FROM {$table_name}");
	}

	public static function add(VTW_Event_Payment_Zync $model)
	{
		global $wpdb;
		$toInsert = array(
			'source' => $model->source,
			'payload' => $model->payload,
			'payment_id' => $model->payment_id,
			'status' => $model->status,
			'response' => $model->response,
			'at' => $model->at
		);
		$wpdb->insert(VTW_Event_Payment_Zync::table_name(), $toInsert, null);
		return $wpdb->insert_id;
	}

	public static function getById(int $id)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Zync::table_name();
		$query = "SELECT * FROM {$table_name} WHERE id = {$id}";
		return $wpdb->get_results($query);
	}

	public static function getByPaymentId(int $paymentId)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Zync::table_name();
		$query = "SELECT * FROM {$table_name} WHERE payment_id = {$paymentId}";
		return $wpdb->get_results($query);
	}

	public static function getByStatud(int $status)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Zync::table_name();
		$query = "SELECT * FROM {$table_name} WHERE status = '{$status}'";
		return $wpdb->get_results($query);
	}

	public static function getBySource(int $source)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Zync::table_name();
		$query = "SELECT * FROM {$table_name} WHERE payment_id LIKE '%$source%'";
		return $wpdb->get_results($query);
	}
}

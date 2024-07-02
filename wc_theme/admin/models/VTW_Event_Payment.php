<?php

class VTW_Event_Payment
{

	public static function table_name()
	{
		global $wpdb;
		return "{$wpdb->prefix}event_payment";
	}

	public static function getById(int $id)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment::table_name();
		$query = "SELECT * FROM {$table_name} where id = {$id}";
		return $wpdb->get_row($query, "ARRAY_A");
	}

	public static function getByIbo(int $ibo)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment::table_name();
		$query = "SELECT * FROM {$table_name} where ibo = {$ibo}";
		return $wpdb->get_row($query, "ARRAY_A");
	}

	public static function getFreePaymentsByIbo($ibo, $eventId)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment::table_name();
		$query = $wpdb->prepare("SELECT COALESCE(SUM(quantity), 0) as total_tickets, COALESCE(COUNT(id), 0) as total_payment  FROM $table_name where ibo = '%s' AND apply_for_free = 1 AND post_id = %s", array($ibo, $eventId));
		return $wpdb->get_row($query);
	}
}

<?php

class VTW_Event_Payment_Reports
{

	public static function table_name()
	{
		global $wpdb;
		return "{$wpdb->prefix}event_payment";
	}

	public static function getTotal(int $eventID)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Reports::table_name();
		$query = "
			SELECT COUNT(post_id) as total
			FROM (SELECT *
				FROM {$table_name}
				WHERE post_id = {$eventID}
				AND status IN('COMPLETED', 'ADMIN_COMPLETED')) AS T;
		";
		return $wpdb->get_row($query, "OBJECT");
	}

	public static function getTotalTrial(int $eventID)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Reports::table_name();
		$query = "
			SELECT COUNT(post_id) as total
			FROM (SELECT *
				FROM {$table_name}
				WHERE post_id = {$eventID}
				AND apply_for_free = 1
				AND status IN('COMPLETED', 'ADMIN_COMPLETED')) AS T;
		";
		return $wpdb->get_row($query, "OBJECT");
	}

	public static function getGroupedReport(int $eventID)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Reports::table_name();
		$query = "
			SELECT COUNT(post_id) as Qty,
				sponsor,
				apply_for_free,
				post_id AS event_id,
				SUM(payments_amount) as earned_by_sponsor,
				payments_curency
			FROM (SELECT *
				FROM {$table_name}
				WHERE post_id = {$eventID}
				AND status IN('COMPLETED', 'ADMIN_COMPLETED')) AS T
			GROUP BY sponsor, payments_curency
			ORDER BY sponsor;
		";
		return $wpdb->get_results($query, "ARRAY_A");
	}

	public static function getDetailedReport(int $eventID)
	{
		global $wpdb;
		$table_name = VTW_Event_Payment_Reports::table_name();
		$tcodeTable = "{$wpdb->prefix}event_ticket_code";
		$query = "
			SELECT 1 as Qty,
				sponsor,
				apply_for_free,
				post_id AS event_id,
				payments_amount as earned_by_sponsor,
				payments_curency,
				payee_name,
				id,
				ibo,
				(SELECT status from {$tcodeTable} WHERE {$tcodeTable}.event_payment_id = T.id ORDER BY {$tcodeTable}.id DESC LIMIT 1) as ticket_status
			FROM {$table_name} AS T
			WHERE post_id = {$eventID}
				AND status IN('COMPLETED', 'ADMIN_COMPLETED')
			ORDER BY sponsor;	
		";

		return $wpdb->get_results($query, "ARRAY_A");
	}
}

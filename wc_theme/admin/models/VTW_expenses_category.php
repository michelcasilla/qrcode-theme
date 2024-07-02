<?php

class VTW_expenses_category {
    
    public static function create_category(string $name, int $status, string $shortCode) {
        global $wpdb;

        $table_name = VTW_expenses_category::table_name();

        $wpdb->insert(
            $table_name,
            array(
                'name'   => $name,
                'status'  => $status,
                'shortCode' => $shortCode
            )
        );
        
        return $wpdb->insert_id;
    }
    
    public static function add_expense(int $eventId, int $categoryId, int $amount) {
        global $wpdb;

        $table_name = "{$wpdb->prefix}expenses";

        $wpdb->insert(
            $table_name,
            array(
                'eventId'   => $eventId,
                'categoryId'  => $categoryId,
                'amount' => $amount
            )
        );
        
        return $wpdb->insert_id;
    }
    
    public static function update_expense(int $eventId, int $categoryId, int $amount) {
        global $wpdb;

        $table_name = "{$wpdb->prefix}expenses";
       
        $data = array(
            'amount' => $amount
        );

        $where = array(
            'eventId' => $eventId,
            'categoryId' => $categoryId
        );
        $wpdb->update($table_name, $data, $where);
        return $wpdb->insert_id;
    }

    public static function get_active_categorys() {
        return VTW_expenses_category::get_categorys_by_status(1);
    }
    
    public static function all() {
        global $wpdb;
		$table_name = VTW_expenses_category::table_name();
        return $wpdb->get_results("SELECT * FROM {$table_name}");
    }
    
    public static function get_expense_by_event_and_category($eventId, $categoryID) {
        global $wpdb;
		$table_name = "{$wpdb->prefix}expenses";
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE eventId = %d AND categoryId = %d", $eventId, $categoryID));
    }

	public static function get_event_category(int $eventID) {
        global $wpdb;
		$table_name = VTW_expenses_category::table_name();
		$expenses_table_name = "{$wpdb->prefix}expenses";
		$query = $wpdb->prepare("SELECT EC.*, E.amount FROM $table_name as EC LEFT JOIN {$expenses_table_name} as E ON E.eventid = %d and E.categoryid = EC.id", $eventID);
        return $wpdb->get_results($query);
    }
    
	public static function get_categorys_by_status(int $status = 1) {
        global $wpdb;
        $table_name = VTW_expenses_category::table_name();
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE status = %d",  $status));
    }
    
    public static function get_category(int $id) {
        global $wpdb;

        $table_name = VTW_expenses_category::table_name();

        $category = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $id
            )
        );
        
        return $category;
    }

    public static function update_category(int $id, string $name, int $status, string $shortCode) {
        global $wpdb;

        $table_name = VTW_expenses_category::table_name();

        $wpdb->update(
            $table_name,
            array(
                'name'   => $name,
                'status'  => $status,
                'shortCode' => $shortCode
            ),
            array('id' => $id)
        );
    }

    public static function delete_category($id) {
        global $wpdb;

        $table_name = VTW_expenses_category::table_name();

        $wpdb->delete($table_name, array('id' => $id));
    }

    public static function update_category_status($id, $status) {
        global $wpdb;

        $table_name = VTW_expenses_category::table_name();
        
        return $wpdb->update(
            $table_name,
            array('status' => $status),
            array('id' => $id)
        );
    }
    
	public static function table_name() {
        global $wpdb;

        return "{$wpdb->prefix}expenses_category";
    }
}

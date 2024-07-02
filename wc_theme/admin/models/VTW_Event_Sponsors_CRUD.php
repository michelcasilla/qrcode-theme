<?php

class VTW_Event_Sponsors_CRUD {
    
    public static function create_sponsor(string $name, string $level, string $status) {
        global $wpdb;

        $table_name = VTW_Event_Sponsors_CRUD::table_name();

        $wpdb->insert(
            $table_name,
            array(
                'name'   => $name,
                'level'  => $level,
                'status' => $status
            )
        );
        
        return $wpdb->insert_id;
    }

    public static function get_active_sponsors() {
        return VTW_Event_Sponsors_CRUD::get_sponsors_by_status(1);
    }
    
    public static function get_sponsors_by_status(int $status = 1) {
        global $wpdb;
        $table_name = VTW_Event_Sponsors_CRUD::table_name();
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE status = %d",  $status));
    }
    
    public static function get_sponsor(int $id) {
        global $wpdb;

        $table_name = VTW_Event_Sponsors_CRUD::table_name();

        $sponsor = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $id
            )
        );
        
        return $sponsor;
    }

    public static function update_sponsor(int $id, string $name, string $level, int $status) {
        global $wpdb;

        $table_name = VTW_Event_Sponsors_CRUD::table_name();

        $wpdb->update(
            $table_name,
            array(
                'name'   => $name,
                'level'  => $level,
                'status' => $status
            ),
            array('id' => $id)
        );
    }

    public static function delete_sponsor($id) {
        global $wpdb;

        $table_name = VTW_Event_Sponsors_CRUD::table_name();

        $wpdb->delete($table_name, array('id' => $id));
    }

    public static function update_sponsor_status($id, $status) {
        global $wpdb;

        $table_name = VTW_Event_Sponsors_CRUD::table_name();
        
        return $wpdb->update(
            $table_name,
            array('status' => $status),
            array('id' => $id)
        );
    }
    
	public static function table_name() {
        global $wpdb;

        return "{$wpdb->prefix}event_sponsors";
    }
}

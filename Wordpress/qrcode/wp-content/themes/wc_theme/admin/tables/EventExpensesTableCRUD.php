<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
require_once( TEMPLATEPATH . '/admin/models/VTW_expenses_category.php' );

class EventExpensesTableCRUD extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'category',
            'plural'   => 'categories',
            'ajax'     => false
        ));
    }

    public function prepare_items() {
        global $wpdb;

		$this->process_bulk_action();

		$this->_column_headers = [
			$this->get_columns(),
			[], // hidden columns
			$this->get_sortable_columns(),
			$this->get_primary_column_name(),
		];
        $table_name = VTW_expenses_category::table_name();
        $query = "SELECT * FROM {$table_name}";
        // Handle the sort order.
        if(isset($_REQUEST['s'])){
            $search = $_REQUEST['s'];
            $query .= " WHERE name LIKE '%".$search."%'";
        }

        $orderby = !empty($_GET['orderby']) ? $_GET['orderby'] : 'ASC';
        $order   = !empty($_GET['order']) ? $_GET['order'] : '';
        if (!empty($orderby) & !empty($order)) {
            $query .= ' ORDER BY ' . $orderby . ' ' . $order;
        }

        $this->items = $wpdb->get_results($query);
    }

    public function get_columns() {
        return array(
			'cb'      => '<input type="checkbox" />',
            'id'     => 'ID',
            'name'   => 'Name',
            'shortCode'  => 'Short Code',
            'status' => 'Status',
            'edit' => '',
        );
    }

    public function get_sortable_columns() {
        return array(
            'id'     => array('id', true),
            'name'   => array('name', true),
            'shortCode'  => array('shortCode', true),
            'status' => array('status', true),
            'edit' => array('edit', false),
        );
    }

	public function get_bulk_actions() {
        return array(
            'bulk_update_activate' => 'Activate',
            'bulk_update_deactivate' => 'Disable',
            'bulk_update_delete' => 'Delete',
        );
    }

	public function process_bulk_action() {
		$action = $this->current_action();
		if(!empty($action)){
            
            if ('delete' === $action){
                $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
                if($id){
                    $category = VTW_expenses_category::get_category($id);
                    VTW_expenses_category::delete_category($category->id);
                }
            }
            
            if ('update' === $action){
                $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
                if($id !== 0){
                    $currentCategory = VTW_expenses_category::get_category($id);
                    $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : $currentCategory->name;
                    $shortCode = isset($_REQUEST['shortCode']) ? $_REQUEST['shortCode'] : $currentCategory->shortCode;
                    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : $currentCategory->status;
                    VTW_expenses_category::update_category($id, $name, $status, $shortCode);
                }else{
                    $name = $_REQUEST['name'];
                    $status = $_REQUEST['status'];
                    $shortCode = $_REQUEST['shortCode'];
                    VTW_expenses_category::create_category($name, $status, $shortCode);
                }
            }
                
			if ('bulk_update_activate' === $action) {
                // Check if nonce field is set
                if (!isset($_REQUEST['_wpnonce'])) {
                    die('Nonce field not set.');
                }

                // Verify nonce value for security.
                if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'])) {
                    die('Invalid nonce value.');
                }
                
                $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
                if (empty($ids)){
                    return;
                }
                // Loop through the IDs and process the update for each.
				foreach ($ids as $id) {
					VTW_expenses_category::update_category_status($id, 1);
					// Call your update function here.
				}
			}
			
			if ('bulk_update_deactivate' === $action) {
				// Loop through the IDs and process the update for each.
                // Check if nonce field is set
                if (!isset($_REQUEST['_wpnonce'])) {
                    die('Nonce field not set.');
                }

                // Verify nonce value for security.
                if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'])) {
                    die('Invalid nonce value.');
                }
                
                $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
                if (empty($ids)){
                    return;
                }
				foreach ($ids as $id) {
					VTW_expenses_category::update_category_status($id, 0);
					// Call your update function here.
				}
			}
			
			if ('bulk_update_delete' === $action) {
				// Loop through the IDs and process the update for each.
                // Check if nonce field is set
                if (!isset($_REQUEST['_wpnonce'])) {
                    die('Nonce field not set.');
                }

                // Verify nonce value for security.
                if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'])) {
                    die('Invalid nonce value.');
                }
                
                $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
                if (empty($ids)){
                    return;
                }
				foreach ($ids as $id) {
					VTW_expenses_category::delete_category($id);
					// Call your update function here.
				}
			}
		}
    }

    function column_edit( $item ) {
        return sprintf(
            '<a onclick="openEdiCategoryThickBox(%s)" class="button-secondary">Edit</a> &nbsp;
            <a onclick="openDeleteCategoryThickBox(%s)" class="button-secondary">Delete</a> &nbsp;', 
            htmlspecialchars(json_encode($item, JSON_NUMERIC_CHECK), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(json_encode($item, JSON_NUMERIC_CHECK), ENT_QUOTES, 'UTF-8')
		);
	}
    
    function column_cb( $item ) {
		return sprintf(
		'<input type="checkbox" name="id[]" value="%s" />', $item->id
		);
	}
    
	public function getStatusLabel(int $status){
        $label = "";
        switch ($status) {
            case 0:
                $label = "INACTIVE";
                break;
            case 1:
                $label = "ACTIVE";
                break;
            case 3:
                $label = "DELETED";
                break;
            default:
                $label =  "UNKNOWN";
                break;
        }
        return $label;
    }
	
	public function column_status($item){
        $status = $this->getStatusLabel($item->status);
		return '<span class="label-'.$status.'">'.$status.'</span>';
	}

	public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'name':
            case 'shortCode':
			case 'status':
				return $item->$column_name; // '<span class="status-'.$item->$column_name.'">'.$item->$column_name.'</span>';
            default:
                return print_r($item, true);
        }
    }
}

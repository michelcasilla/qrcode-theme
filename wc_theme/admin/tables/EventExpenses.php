<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
require_once( TEMPLATEPATH . '/admin/models/VTW_expenses_category.php' );

class EventExpensesTable extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'expense',
            'plural'   => 'expenses',
            'ajax'     => false
        ));
    }

    public function get_total_earned(int $eventId){
        global $wpdb;
        $payment_table_name = "{$wpdb->prefix}event_payment";
        $query = $wpdb->prepare("SELECT SUM(payments_amount) as total FROM {$payment_table_name} WHERE post_id = %d", $eventId);
        $result = $wpdb->get_row($query);
        return $result->total;
    }
    
    public function get_total_expenses(int $eventId){
        global $wpdb;
        $expenses_table_name = "{$wpdb->prefix}expenses";
        $query = $wpdb->prepare("SELECT SUM(amount) as total FROM {$expenses_table_name} WHERE eventId = %d", $eventId);
        $result = $wpdb->get_row($query);
        return $result->total;
    }

    public function prepare_items() {

		$this->process_bulk_action();

		$this->_column_headers = [
			$this->get_columns(),
			[],
			$this->get_sortable_columns(),
			$this->get_primary_column_name(),
		];
        $eventID = 0;

        if(isset($_REQUEST['eventID']) && !empty($_REQUEST['eventID'])){
            $eventID = $_REQUEST['eventID'];
        }

        // $table_name = VTW_expenses_category::table_name();
        // $query = "SELECT * FROM {$table_name}";
        // // Handle the sort order.
        // if(isset($_REQUEST['s'])){
        //     $search = $_REQUEST['s'];
        //     $query .= " WHERE name LIKE '%".$search."%'";
        // }

        // $orderby = !empty($_GET['orderby']) ? $_GET['orderby'] : 'ASC';
        // $order   = !empty($_GET['order']) ? $_GET['order'] : '';
        // if (!empty($orderby) & !empty($order)) {
        //     $query .= ' ORDER BY ' . $orderby . ' ' . $order;
        // }

        $this->items = VTW_expenses_category::get_event_category($eventID);
    }

    public function process_bulk_action(){
        if ( 'update_event_expenses' === $this->current_action() ) {
            $eventId = $_REQUEST['eventID'];
            $categories = $_REQUEST['category'];
            foreach ($categories as $category => $amount) {
                $match = VTW_expenses_category::get_expense_by_event_and_category($eventId, $category);
                if(!$match){
                    VTW_expenses_category::add_expense($eventId, $category, $amount);
                }else{
                    VTW_expenses_category::update_expense($eventId, $category, $amount);
                }
            }
        }
    }

    public function get_columns() {
        return array(
			// 'cb'      => '<input type="checkbox" />',
            'id'     => 'ID',
            'name'   => 'Name',
            'status'  => 'Status',
            'shortCode' => 'Short Code',
            'amount' => 'Amount'
        );
    }

    public function get_sortable_columns() {
        return array(
            'id'     => array('id', false),
            'name'   => array('name', false),
            'status' => array('status', false),
            'shortCode' => array('shortCode', false),
            'amount' => array('amount', false)
        );
    }

    function column_amount( $item ) {
        return sprintf(
            '<input name="category[%d]" type="number" placeholder="amount" value="%d">',
            $item->id,
            $item->amount
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
			case 'amount':
				return $item->$column_name;
            default:
                return print_r($item, true);
        }
    }
}

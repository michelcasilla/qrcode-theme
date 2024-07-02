<?php
require_once(TEMPLATEPATH . '/admin/models/VTW_Ticket_Code.php');
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class EventPayment_List extends WP_List_Table
{

    /** Class constructor */
    public function __construct()
    {

        parent::__construct([
            'singular' => __('Event Payment', 'sp'), //singular name of the listed records
            'plural'   => __('Events Payments', 'sp'), //plural name of the listed records
            'ajax'     => true //should this table support ajax?

        ]);
    }

    /**
     * Retrieve event_payment’s data from the database
     *
     * @param int $per_page
     * @param int $page_number
     * @param int $created_at
     * @param int $end_at
     *
     * @return mixed
     */
    public static function get_event_payment($per_page = 5, $page_number = 1, $created_at = "", $end_at = "")
    {

        global $wpdb;
        $table = "{$wpdb->prefix}event_payment";
        $postMetaTable = "{$wpdb->prefix}postmeta";
        $sql = "
                SELECT {$table}.*,
                (SELECT status from {$wpdb->prefix}event_ticket_code WHERE {$wpdb->prefix}event_ticket_code.event_payment_id = {$table}.id ORDER BY {$wpdb->prefix}event_ticket_code.id DESC LIMIT 1) as ticketStatus,
                (SELECT meta_value FROM {$postMetaTable} where {$postMetaTable}.meta_key = 'isfisical' and {$postMetaTable}.post_id = {$table}.post_id LIMIT 1) as isPhysical
                FROM {$table}";
        // die($sql);
        $where =  false;

        if (!empty($_REQUEST['created_at'])) {
            $where = true;
            $created_at = esc_sql($_REQUEST['created_at']);
            $sql .= " WHERE created_at >= '{$created_at} 00:00:00'";
        }

        if (!empty($_REQUEST['end_at'])) {
            $end_at = esc_sql($_REQUEST['end_at']);
            $sql .= (!$where ? " WHERE " : " AND ") . "created_at <= '{$end_at} 23:59:59'";
            $where = true;
        }

        if (!empty($_REQUEST['eventID'])) {
            $eventID = esc_sql($_REQUEST['eventID']);
            $sql .= (!$where ? " WHERE " : " AND ") . "post_id = '{$eventID}'";
            $where = true;
        }

        if (!empty($_REQUEST['s'])) {
            $page_number = 1;
            $search = trim(esc_sql($_REQUEST['s']));
            $sql .= (!$where ? " WHERE " : " AND ") . "invoice_id = '{$search}' 
                    OR ticket_email LIKE  '%{$search}%' 
                    OR description LIKE '%{$search}%'
                    OR payee_name LIKE  '%{$search}%'
                    OR sponsor LIKE  '%{$search}%'
                    OR ibo LIKE  '%{$search}%'
                    OR payments_id LIKE  '%{$search}%'
                    OR post_id LIKE  '%{$search}%'
                    OR status LIKE  '%{$search}%'
                    OR id LIKE  '%{$search}%'
                ";
            $where = true;
        }


        if (isset($_REQUEST["dateFilter"])) {
            $page_number = 1;
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY id DESC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * Delete a event_payment record.
     *
     * @param int $id event_payment ID
     */
    public static function delete_event_payment($id)
    {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}event_payment",
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}event_payment";

        return $wpdb->get_var($sql);
    }

    /** Text displayed when no event_payment data is available */
    public function no_items()
    {
        _e('No payment yet', 'sp');
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_name($item)
    {

        // create a nonce
        $delete_nonce = wp_create_nonce('sp_delete_event_payment');

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = [
            'delete' => sprintf('<a href="?page=%s&action=%s&event_payment=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), $delete_nonce)
        ];

        return $title . $this->row_actions($actions);
    }

    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {

        switch ($column_name) {
            case 'id':
                // return '<a href="'.site_url().'/wp-admin/admin.php?page=event_payment&action=event_payment_detail&id='.$item['id'].'" class="thickbox event--show-detail-modal">'.$item[ $column_name ].'</a>';
            case 'ibo':
            case 'invoice_id':
            case 'ticket_email':
            case 'payee_name':
            case 'sponsor':
                // case 'event_ticket':
            case 'payments_id':
            case 'post_id':
            case 'created_at':
                return $item[$column_name];
            case 'description':
                return "<b>{$item[$column_name]}</b>";
            case 'payments_amount':
                return "$" . sprintf('%0.2f', $item[$column_name]);
            case 'status':
                return '<span class="status-' . $item[$column_name] . '">' . $item[$column_name] . '</span>';
            case 'ticketStatus':
                $stat = isset($item[$column_name]) ? TicketCode::getStatusLabel($item[$column_name]) : "--";
                $tpl =  '<span class="status-' . $stat . ' small-shadow">' . $stat . '</span>';
                return $tpl;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            $item['id']
        );
    }

    function column_tkt($item)
    {
        return sprintf(
            '<a href="/wp-json/wctvApi/v1/tktList?event_payment_id=%s&width=750" class="thickbox button-primary" >Tickets</a>',
            $item['id']
        );
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'tkt'      => '',
            'id'    => __('ID', 'sp'),
            'ibo'    => __('IBO', 'sp'),
            'description' => __('Event Title', 'sp'),
            'post_id'    => __('EventID', 'sp'),
            'created_at'    => __('Fecha', 'sp'),
            'payee_name'    => __('Name', 'sp'),
            'ticket_email'    => __('Email', 'sp'),
            'sponsor'    => __('Sponsor', 'sp'),
            'payments_amount'    => __('Amount', 'sp'),
            // 'event_ticket'    => __( 'Ticket', 'sp' ),
            'invoice_id'    => __('Invoice ID', 'sp'),
            'payments_id'    => __('Payment Ref', 'sp'),
            'status'    => __('Status', 'sp'),
            'ticketStatus'    => __('TK Status', 'sp'),
        ];

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'id' => array('id', true),
            'ibo' => array('ibo', true),
            'post_id'    => array("post_id", true),
            'invoice_id' => array('invoice_id', true),
            'payments_id' => array('payments_id', true),
            'payee_name' => array('payee_name', true),
            'ticket_email' => array('ticket_email', false),
            'description' => array('description', false),
            'sponsor' => array('sponsor', true),
            'payments_amount' => array('payments_amount', true),
            // 'event_ticket' => array( 'payments_amount', false ),
            'created_at' => array('created_at', true),
            'status' => array('status', true),
            'ticketStatus' => array('ticketStatus', true)
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            // 'bulk-delete' => 'Delete'
        ];

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {

        // $this->_column_headers = $this->get_column_info();
        $this->_column_headers = [
            $this->get_columns(),
            [], // hidden columns
            $this->get_sortable_columns(),
            $this->get_primary_column_name(),
        ];


        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page('event_payment_per_page', 500);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);


        $this->items = self::get_event_payment($per_page, $current_page);
    }

    public function process_bulk_action()
    {

        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'sp_delete_event_payment')) {
                die('Go get a life script kiddies');
            } else {
                self::delete_event_payment(absint($_GET['event_payment']));

                wp_redirect(esc_url(add_query_arg()));
                exit;
            }
        }

        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {

            $delete_ids = esc_sql($_POST['bulk-delete']);

            // loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                self::delete_event_payment($id);
            }

            wp_redirect(esc_url(add_query_arg()));
            exit;
        }
    }


    function custom_filter_views()
    {
        global $wpdb;

        $events = $wpdb->get_results("SELECT description, post_id 
                        FROM {$wpdb->prefix}event_payment 
                        GROUP BY description, post_id 
                        ORDER BY description ASC");

?>
        <div wp-table-filter class="tablenav-pages">
            <form method="GET" action="/wp-json/wctvApi/v1/csv" target="_blank">
                <label>
                    <b><?= __("Evento") ?></b>
                    <select name="eventID" id="eventID">
                        <option>--select event--</option>
                        <?php
                        foreach ($events as $event) {
                            $visible = get_post_meta($event->post_id, 'hide_from_report', true);
                        ?>
                            <?php if ($visible == "Visible") : ?>
                                <option value="<?= $event->post_id ?>"><?= $event->description ?></option>
                            <?php endif; ?>
                        <?php } ?>
                    </select>
                </label>
                &nbsp;
                <label>
                    <span>&nbsp;</span>
                    <button type="submit" name="dateFilter" class="button-secondary">EXPORT</button>
                </label>
                &nbsp;
                <label>
                    <span>&nbsp;</span>
                    <button type="button" name="registerPayment" onclick="registerPaymentModal()" class="button-primary">REGISTRAR PAGO</button>
                </label>
            </form>
            <script type="text/javascript">
                function home_url(url) {
                    return `<?= home_url() ?>${url}`;
                }

                function registerPaymentModal() {
                    let eventID = parseInt(jQuery('#eventID').val());
                    let userID = <?= get_current_user_id() ?>;
                    if (!userID) {
                        alert("Usuario no indicado");
                        return false;
                    }
                    if (!eventID) {
                        alert("Debes seleccionar un evento");
                        return false;
                    }
                    let url = home_url("/wp-json/wctvApi/v1/createNewFromAdmin?id=0&eventID=") + eventID + "&userID=" + userID;
                    tb_show("Create", url + "&height=800&width=1200", "");
                }
            </script>
        </div>
        <?php

    }

    function extra_tablenav($which)
    {
        if ($which == "top") {
        ?>
            <div class="alignleft actions bulkactions">

            </div>
<?php
        }
        if ($which == "bottom") {
            //The code that goes after the table is there

        }
    }

    static function getPaymentByPaymentRef(String $paymentRef)
    {
        global $wpdb;
        $paymentRef = esc_sql($paymentRef);
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}event_payment WHERE payments_id LIKE ('%{$paymentRef}%') LIMIT 1");
    }
}

<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
require_once(TEMPLATEPATH . "/admin/models/VTW_Event_Payment_Reports.php");
require_once(TEMPLATEPATH . "/admin/models/VTW_Ticket_Code.php");

class EventReportsTable extends WP_List_Table
{

    public $_report_type = 'grouped';
    public $total;
    public $totalTrial;

    public function __construct()
    {
        $this->items = [];
        parent::__construct(array(
            'singular' => 'report',
            'plural'   => 'reports',
            'ajax'     => false
        ));
    }

    public function set_type($type)
    {
        $this->_report_type = $type;
    }

    public function get_type()
    {
        return $this->_report_type;
    }

    public function get_total()
    {
        return $this->total;
    }

    public function get_total_trial()
    {
        return $this->totalTrial;
    }

    public function prepare_items()
    {

        if (isset($_REQUEST['eventID']) && !empty($_REQUEST['eventID'])) {

            $this->_column_headers = [
                $this->get_columns(),
                [], // hidden columns
                $this->get_sortable_columns(),
                $this->get_primary_column_name(),
            ];

            $eventID = (int)$_REQUEST['eventID'];
            $this->total = VTW_Event_Payment_Reports::getTotal($eventID);
            $this->totalTrial = VTW_Event_Payment_Reports::getTotalTrial($eventID);

            if ("grouped" == $this->get_type()) {
                $this->items = VTW_Event_Payment_Reports::getGroupedReport($eventID);
                if ('export_grouped_reports' == $this->current_action()) {
                    $this->to_csv($eventID);
                }
            }

            if ("detailed" == $this->get_type()) {
                $this->items = VTW_Event_Payment_Reports::getDetailedReport($eventID);
                if ('export_grouped_reports' == $this->current_action()) {
                    $this->to_csv($eventID);
                }
            }
        }
    }

    public function prepare_items_grouped()
    {
        $this->set_type('grouped');
        $this->prepare_items();
    }

    public function prepare_items_detailed()
    {
        $this->set_type('detailed');
        $this->prepare_items();
    }

    public function column_ticket_status($item)
    {
        return TicketCode::getStatusLabel($item['ticket_status']); // '--';
    }

    public function get_columns()
    {

        if ($this->get_type() == 'detailed') {
            $columns = array(
                'Qty'     => 'Qty',
                'ibo'     => 'IBO',
                'id'     => 'Payment Id',
                'event_id'  => 'EventID',
                'sponsor'   => 'Sponsor',
                'earned_by_sponsor' => 'Earned by Sponsor',
                'apply_for_free'    => 'Apply For Trial',
                'payments_curency' => 'Payments Currency',
                'payee_name' => 'Buyer',
                'ticket_status' => 'Status',
            );
        } else {
            $columns = array(
                'Qty'     => 'Qty',
                'event_id'  => 'EventID',
                'sponsor'   => 'Sponsor',
                'earned_by_sponsor' => 'Earned by Sponsor',
                'apply_for_free'    => 'Quantity Trial',
                'payments_curency' => 'Payments Currency',
            );
        }
        return $columns;
    }

    public function get_sortable_columns()
    {
        $columns = array(
            // 'Qty'     => array('Qty', false),
            // 'sponsor'   => array('sponsor', false),
            // 'event_id'  => array('event_id', false),
            // 'earned_by_sponsor' => array('earned_by_sponsor', false),
            // 'payments_curency' => array('payments_curency', false),
        );

        return $columns;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {

            case 'apply_for_free':
                return $item[$column_name] > 0 ? "YES" : "NO";
                break;
            default:
                return $item[$column_name]; //Show the whole array for troubleshooting purposes
        }
        return $item[$column_name];
    }

    public function to_csv(int $eventID)
    {
        $fileName_1 = $eventID . '_report_by_sponsor.csv';
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName_1}");
        header("Expires: 0");
        header("Pragma: public");
        $fh1 = @fopen('php://output', 'w');
        $headerDisplayed1 = false;
        $totalTickets = 0;
        foreach ($this->items as $data1) {
            // Add a header row if it hasn't been added yet
            if (!$headerDisplayed1) {
                // Use the keys from $data as the titles
                fputcsv($fh1, array_keys($data1));
                $headerDisplayed1 = true;
            }

            // Put the data into the stream
            fputcsv($fh1, $data1);

            $totalTickets = $totalTickets + $data1["Qty"];
        }
        fputcsv($fh1, array($totalTickets, "", "", "", ""));
        // Close the file
        fclose($fh1);
        // Make sure nothing else is sent, our file is done
        exit;
    }

    public function display()
    {
        //  parent::display();
        $singular = $this->_args['singular'];

        $this->display_tablenav('top');
        $this->screen->render_screen_reader_content('heading_list');
?>
        <table class="wp-list-table <?php echo implode(' ', $this->get_table_classes()); ?>">
            <thead>
                <?php if ($this->get_total()) : ?>
                    <tr>
                        <td colspan="<?= $this->get_column_count() ?>">
                            <b><?= $this->get_total()->total ?></b> Total
                            <?php if ($this->get_total_trial()) : ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                                <b><?= $this->get_total_trial()->total ?></b> Trial
                            <?php endif; ?>
                            <?php if ($this->get_total_trial()) : ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                                <b><?= $this->get_total()->total - $this->get_total_trial()->total ?></b> Pagadas
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <?php $this->print_column_headers(); ?>
                </tr>
            </thead>

            <tbody id="the-list" <?php
                                    if ($singular) {
                                        echo " data-wp-lists='list:$singular'";
                                    }
                                    ?>>
                <?php $this->display_rows_or_placeholder(); ?>
            </tbody>

            <tfoot>
                <tr>
                    <?php $this->print_column_headers(false); ?>
                </tr>
            </tfoot>
            <!-- <tfoot>
                <tr>
                    <td colspan="<?= $this->get_column_count() ?>">Extra Footer Content</td>
                </tr>
            </tfoot> -->
        </table>
<?php
        $this->display_tablenav('bottom');
    }
}

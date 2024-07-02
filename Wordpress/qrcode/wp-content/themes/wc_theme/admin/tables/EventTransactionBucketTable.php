<?php
// Include the necessary WordPress files
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
require_once(ABSPATH . 'wp-admin/includes/template.php');

class EventTransactionBucketTable extends WP_List_Table
{
    // Define the constructor
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'event_transaction',
            'plural' => 'event_transactions',
            'ajax' => true
        ));
    }

    // Prepare the table items
    public function prepare_items()
    {
        global $wpdb;

        $this->_column_headers = [
            $this->get_columns(),
            [], // hidden columns
            $this->get_sortable_columns(),
            $this->get_primary_column_name(),
        ];

        $per_page     = $this->get_items_per_page('event_payment_per_page', 99999);
        $page_number = $this->get_pagenum();


        // $table_name = $wpdb->prefix . 'event_transaction_bucket';
        $per_page = 99999;
        $current_page = $this->get_pagenum();

        // Get the total number of items
        $sql = "{$wpdb->prefix}event_transaction_bucket";
        $where =  false;

        if (!empty($_REQUEST['createAt'])) {
            $where = true;
            $created_at = esc_sql($_REQUEST['createAt']);
            $sql .= " WHERE createAt >= '{$created_at} 00:00:00'";
        }

        if (!empty($_REQUEST['updateAt'])) {
            $end_at = esc_sql($_REQUEST['updateAt']);
            $sql .= (!$where ? " WHERE " : " AND ") . "updateAt <= '{$end_at} 23:59:59'";
            $where = true;
        }

        if (!empty($_REQUEST['id'])) {
            $eventID = esc_sql($_REQUEST['id']);
            $sql .= (!$where ? " WHERE " : " AND ") . "id = '{$eventID}'";
            $where = true;
        }

        if (!empty($_REQUEST['status']) && strtolower($_REQUEST['status']) !== 'all') {
            $eventID = esc_sql($_REQUEST['status']);
            $sql .= (!$where ? " WHERE " : " AND ") . "status = '{$eventID}'";
            $where = true;
        }

        if (!empty($_REQUEST['s'])) {
            $page_number = 1;
            $search = trim(esc_sql($_REQUEST['s']));
            $sql .= (!$where ? " WHERE " : " AND ") . "paymentId LIKE '%{$search}%' 
                OR processor LIKE  '%{$search}%' 
                OR invoiceId LIKE '%{$search}%'
            ";
            $where = true;
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY id DESC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$sql}");

        // Calculate pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));

        // Get the data for the current page
        $offset = ($current_page - 1) * $per_page;

        $this->items = $wpdb->get_results("SELECT * FROM {$sql}", 'ARRAY_A');
    }

    // Define the columns
    public function get_columns()
    {
        return array(
            'id' => 'ID',
            'processor' => 'Processor',
            // 'data' => 'Data',
            'invoiceId' => 'Invoice ID',
            'paymentId' => 'Payment ID',
            // 'createAt' => 'Created At',
            // 'updateAt' => 'Updated At',
            'status' => 'Status',
            'actions' => 'Actions',
            // 'paymentData' => 'Payment Data'
        );
    }

    public function get_sortable_columns()
    {
        return array(
            'id' => array('id', true),
            'processor' => array('processor', true),
            'invoiceId' => array('invoiceId', false),
            'paymentId' => array('paymentId', false),
            'createAt' => array('createAt', false),
            'updateAt' => array('updateAt', false),
            'status' => array('status', true),
            'actions' => array('actions', false),
        );
    }

    // Render the column values
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'processor':
            case 'data':
            case 'paymentId':
            case 'createAt':
            case 'updateAt':
            case 'paymentData':
                return $item[$column_name];
                break;
            case 'status':
                return '<span class="status-' . $item[$column_name] . '" data-status="' . $item[$column_name] . '">' . $item[$column_name] . '</span>';
                break;
            case 'invoiceId':
                $bucketData = unserialize($item['data']);
                $approveUrl = $bucketData->approveUrl;
                return '<span data-invoiceId="' . $item[$column_name] . '" data-approve-url="' . $approveUrl . '">' . $item[$column_name] . '</span>';
                break;
            case 'actions':
                return "<a class='hide thickbox button-primary'>Crear</a>";
                break;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
                break;
        }
    }


    // Render the ID column as a link
    public function column_id($item)
    {
        $edit_url = admin_url('admin.php?page=event-transaction-edit&id=' . $item["id"]);
        return '<a href="' . esc_url($edit_url) . '">' . $item["id"] . '</a>';
    }

    // Define any additional actions
    public function column_action($item)
    {
        // Add any custom actions you want to perform
        return '';
    }

    public function search_box($text, $input_id)
    {
        $statusList = array("all", 'pending', 'complete');
        $status = "all";

        if (isset($_REQUEST['status'])) {
            $stat = esc_sql($_REQUEST['status']);
            if (in_array($stat, $statusList)) {
                $status = $stat;
            }
        }

        if (empty($_REQUEST['s']) && !$this->has_items()) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if (!empty($_REQUEST['orderby'])) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
        }
        if (!empty($_REQUEST['order'])) {
            echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
        }
        if (!empty($_REQUEST['post_mime_type'])) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr($_REQUEST['post_mime_type']) . '" />';
        }
        if (!empty($_REQUEST['detached'])) {
            echo '<input type="hidden" name="detached" value="' . esc_attr($_REQUEST['detached']) . '" />';
        }
?>
        <div class="custom-search-box">
            <div class="right">
                <input type="file" id="csv-file-input">
                <select name="status" id="status-dropdown" value="<?= $status ?>" onchange="filter()">
                    <?php foreach ($statusList as $_status) : ?>
                        <option value="<?= $_status ?>" <?= ($_status === $status ? "selected" : "") ?>><?= strtoupper($_status) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="right">
                <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo $text; ?>:</label>
                <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s" value="<?php _admin_search_query(); ?>" />
            </div>
            <div class="right">
                <?php submit_button($text, '', '', false, array('id' => 'search-submit')); ?>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                function handleConciliationUpload(visibleColumns = ['Fecha', 'Respuesta', 'Error', 'No. de Afiliado', 'No. de Autorizacion', 'Referencia', 'Monto']) {
                    // Get the input element and add an event listener for when a file is selected
                    const fileInput = document.getElementById('csv-file-input');
                    fileInput.addEventListener('change', handleFileSelect);

                    // Function to handle file selection
                    function handleFileSelect(event) {
                        // Get the selected file
                        const file = event.target.files[0];

                        // Create a new FileReader instance
                        const reader = new FileReader();

                        // Add an event listener for when the FileReader has finished loading the file
                        reader.addEventListener('load', (event) => handleFileLoad(event, visibleColumns));

                        // Read the selected file as text
                        reader.readAsText(file);
                    }

                    // Function to handle file loading
                    function handleFileLoad(event, visibleColumns) {
                        const table = document.createElement('table');
                        const preview = document.getElementById('csv-preview');
                        preview.innerHTML = '';
                        const noBucketForTransaction = [];
                        let haveRecords = false;
                        let haveError = false;
                        try {
                            $('.conciliation-wrapper').show();
                            // Get the contents of the file
                            const contents = event.target.result;

                            // Split the contents into lines
                            const lines = contents.split('\n');

                            // Create a table element to display the CSV data
                            table.classList.add('wp-list-table')
                            table.classList.add('widefat')
                            table.classList.add('fixed')
                            table.classList.add('striped')

                            // Create a header row based on the first line of the CSV
                            const headerLine = lines[0];
                            let headers = headerLine.split('",').map(header => header.replace(/^"|"$/g, ''));
                            console.log(headers);
                            headers.push("Bucket status");
                            headers.push("Action");
                            const headerRow = document.createElement('tr');
                            headerRow.classList.add('csv-header-row');

                            if (!visibleColumns || !visibleColumns.length) {
                                visibleColumns = headers;
                            }
                            visibleColumns.push('Bucket status');
                            visibleColumns.push('Action');
                            for (let i = 0; i < headers.length; i++) {
                                const header = headers[i];

                                // Only create header cell if column is in list of visible columns
                                // const trimmedHeader = header.replace(/^"|"$/g, '');
                                if (visibleColumns.includes(header)) {
                                    const cell = document.createElement('th');
                                    cell.classList.add('csv-header-cell');
                                    cell.textContent = header;
                                    headerRow.appendChild(cell);
                                }
                            }
                            const thead = document.createElement('thead');
                            thead.appendChild(headerRow);
                            table.appendChild(thead);
                            const tbody = document.createElement('tbody');
                            // Loop through each line of the CSV data
                            for (let i = 1; i < lines.length; i++) {
                                const line = lines[i];

                                // Split the line into fields
                                const fields = line.split('",');
                                const ref = fields[headers.indexOf('Referencia')].replace(/^"|"$/g, '');
                                const date = fields[headers.indexOf('Fecha')].replace(/^"|"$/g, '');
                                const affNo = fields[headers.indexOf('No. de Afiliado')].replace(/^"|"$/g, '');
                                const authNo = fields[headers.indexOf('No. de Autorizacion')].replace(/^"|"$/g, '');
                                const transactionStatus = fields[headers.indexOf('Respuesta')].replace(/^"|"$/g, '');
                                const isTransactionSuccess = transactionStatus == "00"; // azul success code
                                const match = $(`[data-invoiceid="${ref}"]`)?.parents('tr');
                                if (!match.length) {
                                    noBucketForTransaction.push({
                                        ref
                                    })
                                    continue;
                                }
                                const bucketStatus = match.find('[data-status]').data('status') || '';
                                const approveUrl = match.find('[data-approve-url]').data('approveUrl') || '';
                                const isCompleted = bucketStatus.toLowerCase() == 'complete';
                                if (isTransactionSuccess && !isCompleted) {
                                    haveRecords = true;
                                    const verified = `<span class="status-ANULADO">${bucketStatus}</span>`;
                                    const rnn = `${date}${affNo}${authNo}`.replace(/-/gi, '')
                                    const segment = `&approved=true&bucketRef=${ref}&payment_intent_client_secret=${ref}&payment_intent=${authNo}&payment_RNN=${rnn}`
                                    const registerUrl = `${approveUrl}${segment}`
                                    const action = `<a href="${registerUrl}" target="_blank" class='button-secondary'>Registrar</button>`;
                                    fields.push(verified);
                                    fields.push(action);

                                    // Create a new row in the table for this line of data
                                    const row = document.createElement('tr');
                                    row.setAttribute('data-ref', ref);
                                    // Loop through each field in the line of data and create a new cell in the row
                                    for (let j = 0; j < fields.length; j++) {
                                        const field = fields[j];
                                        const header = headers[j];

                                        // Only create cell if column is in list of visible columns
                                        const trimmedField = field.replace(/^"|"$/g, '');
                                        // const trimmedHeader = header?.replace(/^"|"$/g, '');
                                        if (visibleColumns.includes(header)) {
                                            const cell = document.createElement('td');
                                            cell.innerHTML = trimmedField;
                                            row.appendChild(cell);
                                        }
                                    }

                                    // Add the row to the table body
                                    tbody.appendChild(row);
                                }
                            }
                            table.appendChild(tbody);
                        } catch (e) {
                            console.error(e);
                            haveError = true;
                            let msg = '<h3>No se encontraron transacciones para registrar</h3>';
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('no-transaction');
                            wrapper.classList.add('have-error');
                            wrapper.innerHTML = `<h2>Se ha encontrado error parseando el archivo cargado</h2>
                                         <span>${e}</span><br/>.
                                         <b>Verifíque el archivo cargado sea un reporte de Azul</b>
                                        `;
                            preview.appendChild(wrapper);
                        } finally {
                            if (haveError) {
                                return;
                            }
                            // Clear any previous preview data and add the new table to the preview element
                            if (noBucketForTransaction.length) {
                                let msg = noBucketForTransaction.map(x => `<li>No se encontró bucket para <b>${x.ref}</b> en este report</li>`);
                                const ul = document.createElement('ul');
                                ul.classList.add('no-bucket-list');
                                ul.innerHTML = msg.join('\n');
                                preview.appendChild(ul);
                            }

                            if (!haveRecords) {
                                let msg = '<h3>No se encontraron transacciones para registrar</h3>';
                                const wrapper = document.createElement('div');
                                wrapper.classList.add('no-transaction');
                                wrapper.innerHTML = msg;
                                preview.appendChild(wrapper);
                            }

                            if (haveRecords) {
                                preview.appendChild(table);
                            }
                        }

                    }
                }


                handleConciliationUpload();
                // la conciliacion debe verificar 
                // * que existe el invoice id 
                // * el monto se igual
                // * si el estado es 200 debe ser igual

            });

            function home_url(url) {
                return `<?= home_url() ?>${url}`;
            }

            function openPayment(event) {
                const ref = jQuery(event).parents('tr').data().ref
                let url = home_url(`/wp-json/wctvApi/v1/pendingTransactionBucketNotificationsList?height=600&width=1200&invoiceId=${ref}`);
                tb_show("Notificaciones de transacciones pendientes", url, "");
            }
        </script>
<?php
    }
}

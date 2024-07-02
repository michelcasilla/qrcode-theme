<?php
// Usage example:
function search_backup_logs($dir, $search_string)
{
	$results = array();
	$files = scandir($dir);

	foreach ($files as $file) {
		if ($file === '.' || $file === '..') continue;
		$path = $dir . '/' . $file;
		if (is_dir($path)) {
			$results = array_merge($results, search_backup_logs($path, $search_string));
		} else if (strpos($path, '/backupLogs/') !== false) {
			$handle = fopen($path, "r");
			$line_number = 0;
			while (($line = fgets($handle)) !== false) {
				$line_number++;
				if (strpos($line, $search_string) !== false) {
					$results[] = array(
						'file' => $path,
						'line' => $line_number
					);
				}
			}
			fclose($handle);
		}
	}
	return $results;
}


function pendingTransactionBucketNotificationsList()
{
	$statusList = array("all", 'pending', 'complete', 'rejected');
	$status = "all";
	if (isset($_REQUEST['status'])) {
		$stat = esc_sql($_REQUEST['status']);
		if (in_array($stat, $statusList)) {
			$status = $stat;
		}
	}
	$invoiceId = '';
	if (isset($_REQUEST['invoiceId'])) {
		$invoiceId = esc_sql($_REQUEST['invoiceId']);
	}
	$getPending = TransactionBucket::getByStatus($status);
	$total = TransactionBucket::countPending($status);
?>
	<style>
		.tkt-heading {
			display: flex;
			padding: 14px 0;
		}

		.tkt-heading>* {
			flex: 1;
		}

		.tkt-heading .right {
			align-items: flex-end;
			justify-content: flex-end;
			display: flex;
		}

		.tkt-heading h2 {
			margin: 0;
		}

		[class^="status-"] {
			background: #70db2a;
			color: #295d07;
			border-radius: 4px;
			display: inline-block;
			align-items: center;
			justify-content: center;
			padding: 1px 10px;
			font-size: 10px;
		}

		[class^="status-CANCELED"] {
			background: #FFC107;
			color: #795548;
		}

		[class^="status-USED"] {
			background: #444;
			color: white;
		}


		/* // COLLAPSIBLE TABLE */
		.collapsible-table {
			border-collapse: collapse;
			width: 100%;
		}

		.collapsible-table th,
		.collapsible-table td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}

		.collapsible {
			cursor: pointer;
		}

		.content {
			display: none;
		}

		.content-inner {
			padding: 8px;
		}

		.text-center {
			text-align: center;
		}
	</style>
	<div class="tkt-heading">
		<div class="left">
			<h2><?= $total ?> <?= strtoupper($status) ?> transactions</h2>
			<!-- <p>Para las siguientes notificaciones de pagos no se encontraron entradas en el sistema. 
			Este listado es obtenido al recibir la notificacion de pago de paypal en el front, Se almacena en una tabla de logs verticar y si este es registrado, se le agrega una referencia a la entrada de pago.</p> -->
		</div>
		<div class="right">
			<input type="text" id="search-input" placeholder="Search..." value="<?= $invoiceId ?>">
		</div>
	</div>
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable">
				<div id="csv-preview"></div>
			</div>
			<br><br>
			<div class="meta-box-sortables ui-sortable">
				<table class="wp-list-table widefat fixed striped eventspayments" id="my-table">
					<thead>
						<tr>
							<th style="width: 50px;">ID</th>
							<th>Processor</th>
							<th>InvoiceId</th>
							<th style="width: 300px;">Payment Id</th>
							<!-- <th>Create at</th> -->
							<th>Status</th>
							<th style="width: 20px;"></th>
						</tr>
					</thead>
					<tbody id="the-list">
						<?php foreach ($getPending as $pending) : ?>
							<?php $data = unserialize($pending->data); ?>
							<tr class="collapsible" data-invoiceId="<?= $pending->invoiceId ?>" data-status="<?= $pending->status ?>">
								<td><?= $pending->id ?></td>
								<td><?= $pending->processor ?></td>
								<td><?= $pending->invoiceId ?></td>
								<td><?= $pending->paymentId ?></td>
								<td><?= $pending->status ?></td>
								<td>
									<span class="dashicons dashicons-arrow-down-alt2" data-id="expand"></span>
									<span class="dashicons dashicons-arrow-up-alt2" data-id="collapse" style="display:none"></span>
									<!-- <a onclick="createFromPending(<?= $pending->id ?>, <?= $pending->invoiceId ?>)" class="button-primary">CREAR</a>
							<a onclick="cancelPendingPayment(<?= $pending->id ?>, <?= $pending->invoiceId ?>)" class="button-secondary">CANCEL</a> -->
								</td>
							</tr>
							<tr class="content">
								<td colspan="6">
									<div class="content-inner">
										<?php if ($data) : ?>
											<pre><?= print_r($data) ?></pre>
										<?php else : ?>
											<h1 class="text-center">No Data</h1>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<p>NOTA: puede tener una entrada de pago que no sea relevante para el sistema, el proposito de esta logs es poder identificar casos fallidos, luego de que paypal responde y son enviados a nuestro sistema.</p>
	<!--     </div> -->
	<script type="text/javascript">
		function home_url(url) {
			return `<?= home_url() ?>${url}`;
		}

		function createFromPending($id, $eventID) {
			let url = home_url("/wp-json/wctvApi/v1/createNewFromAdmin?id=") + $id + "&eventID=" + $eventID;
			tb_show("Create", url + "&height=800&width=1200", "");
		}

		function cancelPendingPayment($id, $eventID) {
			if (confirm("¿Seguro que deseas marcarlo cancelado?")) {
				let url = home_url("/wp-json/wctvApi/v1/cancelPendingPayment?id=") + $id + "&eventID=" + $eventID;
				tb_show("Create", url + "&height=800&width=1200", "");
			}
		}
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.collapsible').click(function() {
				const $content = $(this).next('.content').toggle();
				if ($content.is(':visible')) {
					$('[data-id="expand"]').hide();
					$('[data-id="collapse"]').show();
				} else {
					$('[data-id="expand"]').show();
					$('[data-id="collapse"]').hide();
				}
			});
		});
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#search-input').on('keyup', function() {
				const value = $(this).val().toLowerCase();
				$('#my-table tbody tr.content').hide();
				$('#my-table tbody tr.collapsible').each((index, element) => {
					let searchableStr = $(element).text();
					searchableStr += $(element).next().text();
					const match = searchableStr.toLowerCase().indexOf(value) > -1;
					if (match) {
						$(element).show();
					} else {
						$(element).hide();
					}
				})
			});

		});
	</script>
	<script type="text/javascript">
		jQuery('#refresh-thickbox').click(function() {
			openPendingBucketNotifications();
		});

		function openPendingBucketNotifications() {
			let url = home_url("/wp-json/wctvApi/v1/pendingTransactionBucketNotificationsList?height=600&width=1200&status");
			tb_show("Notificaciones de transacciones pendientes", url, "");
		}

		function filter() {
			jQuery(document).ready(function($) {
				$status = $('#status-dropdown').val();
				debugger
				let url = home_url(`/wp-json/wctvApi/v1/pendingTransactionBucketNotificationsList?height=600&width=1200&status=${$status}`);
				tb_show("Notificaciones de transacciones pendientes", url, "");
			});
		}
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

			function handleConciliationUpload(visibleColumns = ['Fecha', 'Respuesta', 'Error', 'No. de Autorizacion', 'Referencia', 'Monto']) {

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
					// Get the contents of the file
					const contents = event.target.result;

					// Split the contents into lines
					const lines = contents.split('\n');

					// Create a table element to display the CSV data
					const table = document.createElement('table');
					table.classList.add('wp-list-table')
					table.classList.add('widefat')
					table.classList.add('fixed')
					table.classList.add('striped')

					// Create a header row based on the first line of the CSV
					const headerLine = lines[0];
					let headers = headerLine.split('",').map(header => header.replace(/^"|"$/g, ''));
					headers.push("Verified");
					headers.push("Action");
					const headerRow = document.createElement('tr');
					headerRow.classList.add('csv-header-row');

					if (!visibleColumns || !visibleColumns.length) {
						visibleColumns = headers;
					}
					visibleColumns.push('Verified');
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
						const index = headers.indexOf('Referencia');
						const ref = fields[index];
						const match = $(`[data-invoiceId="${ref.replace(/^"|"$/g, '')}"]`).length;
						const verified = match ? '<span class="status-COMPLETED">verified</span>' : '<span class="status-ANULADO">Un-verified</span>';
						const action = match ? '' : '<button class="button-secondary">Registrar</button>';
						debugger
						fields.push(verified);
						fields.push(action);

						// Create a new row in the table for this line of data
						const row = document.createElement('tr');

						// Loop through each field in the line of data and create a new cell in the row
						for (let j = 0; j < fields.length; j++) {
							console.log(j);
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

					table.appendChild(tbody);

					// Clear any previous preview data and add the new table to the preview element
					const preview = document.getElementById('csv-preview');
					preview.innerHTML = '';
					preview.appendChild(table);
				}
			}


			handleConciliationUpload();
			// la conciliacion debe verificar 
			// * que existe el invoice id 
			// * el monto se igual
			// * si el estado es 200 debe ser igual
		});
	</script>
<?php
}

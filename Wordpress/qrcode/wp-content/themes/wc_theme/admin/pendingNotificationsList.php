<?php

function pendingNotifications()
{
	$getPending = EventPaymentCatch::getPending();
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
	</style>
	<div class="tkt-heading">
		<div class="left">
			<h2>Pendings</h2>
			<p>Para las siguientes notificaciones de pagos no se encontraron entradas en el sismeta.
				Este listado es obtenido al recibir la notificacion de pago de paypal en el front, Se almacena en un atabla de logs verticar y si este es registrado, se le agrega una referencia a la entrada de pago.</p>
		</div>
	</div>
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable">
				<table class="wp-list-table widefat fixed striped eventspayments">
					<thead>
						<tr>
							<th>ID</th>
							<th>EventID</th>
							<th>Source</th>
							<th>Email</th>
							<th>Invoice</th>
							<th>Create at</th>
							<!-- <th>PaymentID</th> -->
							<th>Hash</th>
							<th>Log</th>
							<th style="width: 150px;"></th>
						</tr>
					</thead>
					<tbody id="the-list">
						<?php foreach ($getPending as $pending) : ?>
							<?php $payment = unserialize($pending->payload); ?>
							<tr>
								<td><?= $pending->id ?></td>
								<td><?= $pending->eventID ?></td>
								<td><?= $pending->source ?></td>
								<td><?= $payment->ticket_email ?></td>
								<td><?= $payment->invoice_id ?></td>
								<td><?= $pending->at ?></td>
								<!-- <td><?= $pending->event_payment_id ?></td> -->
								<td><?= $pending->hash ?></td>
								<td><?= $pending->log ?></td>
								<td>
									<a onclick="createFromPending(<?= $pending->id ?>, <?= $pending->eventID ?>)" class="button-primary">CREAR</a>
									<a onclick="cancelPendingPayment(<?= $pending->id ?>, <?= $pending->eventID ?>)" class="button-secondary">CANCEL</a>
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
<?php
}

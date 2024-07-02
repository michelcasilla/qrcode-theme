<?php
require_once(TEMPLATEPATH . '/admin/models/VTW_Event_Payment.php');

function tktList()
{
	$event_payment_id = esc_sql($_REQUEST['event_payment_id']);
	$ticks = TicketCode::getTicketsFromPaymentID((int) $event_payment_id);
	// $payment = VTW_Event_Payment::getById($event_payment_id);
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
			<h2>Tickets for <?= $event_payment_id ?></h2>
		</div>
		<div class="right">
			<!-- <a href="/wp-json/wctvApi/v1/renewTkt?event_payment_id=<?= $event_payment_id ?>" class="button-primary" >RENEW</a> -->
		</div>
	</div>
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable">
				<table class="wp-list-table widefat fixed striped eventspayments">
					<thead>
						<tr>
							<!-- <th>ID</th> -->
							<!-- <th>PAYMENT ID</th> -->
							<th>Code</th>
							<th>Requested By</th>
							<!-- <th>CreatedAt</th> -->
							<th>Status</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody id="the-list">
						<?php foreach ($ticks as $tk) : ?>
							<tr>
								<!-- <td><?= $tk->id ?></td> -->
								<!-- <td><?= $tk->event_payment_id ?></td> -->
								<td><?= $tk->code ?></td>
								<td><?= $tk->requested_by ?></td>
								<!-- <td><?= $tk->created_at ?></td> -->
								<td><span class="status-<?= TicketCode::getStatusLabel($tk->status) ?>"><?= TicketCode::getStatusLabel($tk->status) ?></span></td>
								<td>
									<?php if ($tk->status) { ?>
										<a onclick="promptAndOpenThickBox()" class="button-secondary">
											<?= __("Resend", "sp") ?>
										</a>
									<?php } ?>
								</td>
								<td>
									<?php if ($tk->status == 1) { ?>
										<a onclick="deactiveTicket(<?= $tk->id ?>)" class="button-secondary">
											<?= __("Anulate", "sp") ?>
										</a>
									<?php } ?>
								</td>
								<td>
									<?php if ($tk->status == 1) { ?>
										<a onclick="markUsed(<?= $tk->id ?>)" class="button-secondary">
											<?= __("Deliver", "sp") ?>
										</a>
									<?php } ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!--     </div> -->
	<script type="text/javascript">
		function home_url(url) {
			return `<?= home_url() ?>${url}`;
		}

		function promptAndOpenThickBox() {
			let extra = prompt("Ingrese los correos adicionales separados por coma.");
			let url = home_url("/wp-json/wctvApi/v1/resendTkt?event_payment_id=<?= $event_payment_id ?>");
			if (extra.trim() != "") {
				url = url + "&aditional_mail=" + extra;
			}
			tb_show("Reenvio de notification", url + "&height=200&width=400", "");
		}

		function deactiveTicket($id) {
			let url = home_url("/wp-json/wctvApi/v1/tktCancel?id=") + $id;
			tb_show("Anulando ticket", url + "&height=200&width=400", "");
		}

		function markUsed($id) {
			let url = home_url("/wp-json/wctvApi/v1/markAsUsed?id=") + <?= $event_payment_id ?>;
			tb_show("Ticket usado", url + "&height=200&width=400", "");
		}
	</script>
<?php
}

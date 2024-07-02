<?php

function showEventPayments()
{

	add_filter('set-screen-option', 'set_screen', 10, 3);

	function set_screen($status, $option, $value)
	{
		return $value;
	}

	$eventPaymentInstance = new EventPayment_List();
	$totalPendingCatch = EventPaymentCatch::countPetPending();
	$totalPendingBucketTransactions = TransactionBucket::countPending();
	$submenu_link = admin_url('admin.php?page=buckets-page')
?>
	<style>
		[wp-table-filter] form {
			display: flex;
		}

		[wp-table-filter] label {
			display: flex;
			flex-direction: column;
		}

		[wp-table-filter] input,
		[wp-table-filter] select {
			max-width: 250px;
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

		[class^="status-CANCELED"],
		[class^="status-ANULADO"] {
			background: #FFC107 !important;
			color: #795548;
		}

		[class^="status-USED"] {
			background: #444;
			color: white;
		}

		.small-shadow {
			box-shadow: -2px 0px 3px 0px #0808528f;
		}
	</style>
	<div class="wrap">
		<h2>Events Payments</h2>
		<?php if ($totalPendingBucketTransactions > 0) { ?>
			<div class="notice notice-warning">
				<h1>Bucket de transacciones pendientes</h1>
				<!-- <h3>Tienes <?= $totalPendingBucketTransactions ?> transacciones en estado pendiente.</h3> -->
				<p><a href="<?= $submenu_link ?>" class="button-secondary">VERIFICAR TRANSACCIONES</a></p>
			</div>
		<?php } ?>
		<?php if ($totalPendingCatch->totalPendingCatch > 0) { ?>
			<div class="notice notice-error">
				<h1>Notificaciones de pago pendiente de procesar</h1>

				<h3>Tienes <?= $totalPendingCatch->totalPendingCatch ?> notificaciones de pagos en que no fueron registradas como entradas de pago.</h3>
				<p><a onclick="openPendingNotifications()" class="button-primary">VER NOTIFICACIONES</a></p>
			</div>
		<?php } ?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<div class="alignleft actions bulkactions">
							<?php $eventPaymentInstance->custom_filter_views(); ?>
						</div>
						<form method="post">
							<?php
							$eventPaymentInstance->prepare_items();
							$eventPaymentInstance->search_box('Search', 'search');
							$eventPaymentInstance->display();
							?>
						</form>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>
	<script type="text/javascript">
		function home_url(url) {
			return `<?= home_url() ?>${url}`;
		}

		function openPendingNotifications() {
			let url = home_url("/wp-json/wctvApi/v1/pendingNotifications?height=600&width=1200");
			tb_show("Notificaciones de pago pendientes", url, "");
		}
	</script>
	<script type="text/javascript">
		function openPendingBucketNotifications() {
			let url = home_url("/wp-json/wctvApi/v1/pendingTransactionBucketNotificationsList?height=600&width=1200");
			tb_show("Notificaciones de transacciones pendientes", url, "");
		}
	</script>
	<script type="text/javascript">
		function deliverPhysical(paymentId) {
			// const confirmed = confirm("Seguro que desea marcar como entregado");
			let url = home_url(`/wp-json/wctvApi/v1/tktList?event_payment_id=${paymentId}&width=750`);
			tb_show("Reenvio de notification", url, "");
		}
	</script>
<?php
}

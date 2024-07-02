<?php
require_once(TEMPLATEPATH . "/admin/tables/EventReportsTable.php");

function detailed_reports()
{
	global $wpdb;
	$eventID = null;
	$events = $wpdb->get_results("SELECT description, post_id 
			FROM {$wpdb->prefix}event_payment 
			GROUP BY description, post_id 
			ORDER BY description ASC");

	if(isset($_REQUEST['eventID']) && !empty($_REQUEST['eventID'])){
		$eventID = (int)$_REQUEST['eventID'];
	}

	$EventReportTableInstance  = new EventReportsTable();
?>
	<div class="wrap">
		<h2>Event detailed report</h2>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<div class="inline-forms">
							<form method="POST">
								<label>
									<b><?= __("Evento") ?></b>
									<select name="eventID" onchange="hideExport(this)">
										<option value="">--select event--</option>
										<?php foreach ($events as $event) {
											$visible = get_post_meta($event->post_id, 'hide_from_report', true);
										?>
											<?php if ($visible == "Visible") : ?>
												<option value="<?= $event->post_id ?>" <?= (($eventID == $event->post_id) ? 'selected="true"' : '') ?>><?= $event->description ?></option>
											<?php endif; ?>
										<?php } ?>
									</select>
								</label>
								&nbsp;
								<label>
									<span>&nbsp;</span>
									<button type="submit" name="dateFilter" class="button-primary"><?=__("GENERAR", "sp")?></button>
								</label>
							</form>
							<?php if($eventID): ?>
							<form method="GET" action="/wp-json/wctvApi/v1/csv" name="export" target="_blank">
								<label>
									<span>&nbsp;</span>
									<input type="hidden" name="eventID" value="<?=$eventID?>">
									<input type="hidden" name="type" value="detailed">
									<button type="submit" name="dateFilter" class="button-secondary"><?=__("EXPORT", "sp")?></button>
								</label>
							</form>
							<?php endif; ?>
						</div>
						<form method="post">
							<?php
								$EventReportTableInstance->prepare_items_detailed();
								$EventReportTableInstance->display();
							?>
						</form>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>
	<script>
		function updateEventID(ctx){
			const id = ctx.value;
			const eventInput = document.querySelectorAll('input[name="eventID"]');
			if(eventInput.length){
				eventInput[0].value = id;
			}
		}
		// function hideExport(ctx){
		// 	const initialID = <?=$eventID?>;
		// 	const updatedId = Number(ctx.value);
		// 	const form = document.querySelectorAll('form[name="export"]')[0];
		// 	debugger
		// 	if(initialID !== updatedId){
		// 		form.classList.add('hide-form');
		// 	}else{
		// 		form.classList.remove('hide-form');
		// 	}
		// }
	</script>

<?php
}

<?php

function visiblePostAndEvents(){
	global $wpdb;
	$events = $wpdb->get_results("SELECT description, post_id 
		FROM {$wpdb->prefix}event_payment 
		GROUP BY post_id, description 
		ORDER BY post_id DESC");
	
		if (isset($_POST['saveEventsConfigurations'])) {
			// Process the submitted form data
			
			// Retrieve the selected checkboxes
			$selectedCheckboxes = isset($_POST['event']) ? $_POST['event'] : array();
			
			// Process the selected checkboxes
			foreach ($events as $event) {
				// Do something with the selected checkboxes
				$wasSelected = in_array($event->post_id, $selectedCheckboxes);
				$state = $wasSelected ? "Visible" : 'Hidden';
				update_post_meta($event->post_id, 'hide_from_report', $state);
			}
			// Display a success message or perform other actions
			echo '<div id="success-message" class="notice notice-success is-dismissible"><h3>Success!</h3></div>';
		}
  ?>
	<div class="wrap">
		<form 
			method="POST" 
			action="<?=esc_url($_SERVER['REQUEST_URI'])?>"
			target="_self">
			<div class="row">
				<div class="left">
					<h3>Visible events</h3>
				</div>
				<div class="right">
					<!-- <label for="search">
						<input type="search" name="search" placeholder="Event id">
					</label> -->
					<label>
						<button type="submit" name="saveEventsConfigurations" class="button-secondary">SAVE</button>
					</label>
				</div>
			</div>
			<table id="visible-events" class="wp-list-table widefat striped">
				<thead>
					<tr>
						<th><input type="checkbox" name="select_all"></th>
						<th>Id</th>
						<th>Event Name</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach($events as $event){
							$visible = get_post_meta($event->post_id, 'hide_from_report', true);
							$state = !empty($visible) ? $visible : "Hidden";
						?>
						<tr data-event_id="<?=$event->post_id?>">
							<td><input type="checkbox" name="event[]" value="<?=$event->post_id?>" <?=$visible == "Visible" ? 'checked="true"': ''?>></td>
							<td><?=$event->post_id?></td>
							<td><?=$event->description?></td>
							<td><label class="label-<?=$state?>"><?=$state?></label></td>
						</tr>
					<?php } ?>
					<!-- Add more table rows here -->
				</tbody>
			</table>
		</form>
	</div>

<?php

}
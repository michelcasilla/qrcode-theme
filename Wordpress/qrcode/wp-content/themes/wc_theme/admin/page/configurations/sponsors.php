<?php
require_once( TEMPLATEPATH . '/admin/tables/EventSponsorsTable.php' );

function sponsors(){
	$eventSponsorTableInstance = new EventSponsorsTable();
?>
<div class="wrap">
	<h2>Sponsors <button class="button-primary button-create" onclick="openEditSponsorThickBox({name:''})">NEW</button></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
							$eventSponsorTableInstance->prepare_items();
							$eventSponsorTableInstance->search_box('Search', 'search');
							$eventSponsorTableInstance->display(); 
						?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>  
</div>

<script>
	function openEditSponsorThickBox(sponsor) {
		const content = `<form method="POST" name="edit_sponsor">
		<br/>
			<label>
				<b>Name</b>
				<input name="name" value="${sponsor.name}" required>
			</label>
			<label>
				<b>Level</b>
				<select name="level" required>
					<option value="Platinum" ${sponsor.level == 'Platinum' ? 'selected="true"' : '' }>Platinum</option>
					<option value="Emeralds" ${sponsor.level == 'Emeralds' ? 'selected="true"' : '' }>Emeralds</option>
					<option value="Diamonds" ${sponsor.level == 'Diamonds' ? 'selected="true"' : '' }>Diamonds</option>
				</select>
			</label>
			<label>
				<b>Status</b>
				<select name="status" value="${sponsor.status}" required>
					<option value="1" ${sponsor.status == 1 ? 'selected="true"' : '' }>Enable</option>
					<option value="0" ${sponsor.status == 0 ? 'selected="true"' : '' }>Disabled</option>
				</select>
			</label>
			</br>
			<label>
				<input type="hidden" name="id" value="${sponsor.id}">
				<button class="button-primary" name="action" type="submit" value="update_sponsor">SAVE</button>
			</label>
		</form>`;
		tb_show("Edit Sponsor", "#TB_inline?width=300&height=295&inlineId=edit_sponsor");
		document.getElementById("TB_ajaxContent").innerHTML = content;
	}

	function openDeleteSponsorThickBox(sponsor) {
		const content = `<form method="POST" name="delete_sponsor">
		<br/>
			<h3>Are you sure you want to delete ${sponsor.name}</h3>
			<label>
				<input type="hidden" name="id" value="${sponsor.id}">
				<button class="button-primary" name="action" type="submit" value="delete_sponsor">DELETE</button>
			</label>
		</form>`;
		tb_show("Delete Sponsor", "#TB_inline?width=300&height=150&inlineId=delete_sponsor");
		document.getElementById("TB_ajaxContent").innerHTML = content;
	}

</script>
<?php

}
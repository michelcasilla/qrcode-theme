<?php
require_once( TEMPLATEPATH . '/admin/tables/EventExpensesTableCRUD.php' );

function categories(){
	$EventExpensesTableCRUDInstance = new EventExpensesTableCRUD();
?>
<div class="wrap">
	<h2>Categories <button class="button-primary button-create" onclick="openEdiCategoryThickBox({name:'', shortCode:''})">NEW</button></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
							$EventExpensesTableCRUDInstance->prepare_items();
							$EventExpensesTableCRUDInstance->search_box('Search', 'search');
							$EventExpensesTableCRUDInstance->display(); 
						?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>  
</div>

<script>
	function openEdiCategoryThickBox(category) {
		const content = `<form method="POST" name="edit_category">
		<br/>
			<label>
				<b>Name</b>
				<input name="name" value="${category.name}" required>
			</label>
			<label>
				<b>Short Code</b>
				<input name="shortCode" value="${category.shortCode}" required>
			</label>
			<label>
				<b>Status</b>
				<select name="status" value="${category.status}" required>
					<option value="1" ${category.status == 1 ? 'selected="true"' : '' }>Enable</option>
					<option value="0" ${category.status == 0 ? 'selected="true"' : '' }>Disabled</option>
				</select>
			</label>
			</br>
			<label>
				<input type="hidden" name="id" value="${category.id}">
				<button class="button-primary" name="action" type="submit" value="update">SAVE</button>
			</label>
		</form>`;
		tb_show("Edit Category", "#TB_inline?width=300&height=295&inlineId=edit_category");
		document.getElementById("TB_ajaxContent").innerHTML = content;
	}

	function openDeleteCategoryThickBox(category) {
		const content = `<form method="POST" name="delete">
		<br/>
			<h3>Are you sure you want to delete ${category.name}</h3>
			<label>
				<input type="hidden" name="id" value="${category.id}">
				<button class="button-primary" name="action" type="submit" value="delete">DELETE</button>
			</label>
		</form>`;
		tb_show("Delete Category", "#TB_inline?width=300&height=150&inlineId=delete");
		document.getElementById("TB_ajaxContent").innerHTML = content;
	}

</script>
<?php

}
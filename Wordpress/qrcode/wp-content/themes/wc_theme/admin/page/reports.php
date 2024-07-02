<?php
require_once( TEMPLATEPATH . '/admin/page/reports/detailed_reports.php' );
require_once( TEMPLATEPATH . '/admin/page/reports/grouped_reports.php' );

function reports_page() {
	$eventID = null;
	if(isset($_REQUEST['eventID']) && !empty($_REQUEST['eventID'])){
		$eventID = (int)$_REQUEST['eventID'];
	}
    ?>
	<style>
		.inline-forms {
			display: flex;
		}
		.hide-form{
			display: none;
		}
		/* .wctv-configurations .row {
			display: flex;
			flex-direction: row;
		}

		.wctv-configurations .row .left {
			flex: 1;
		}
		.wctv-configurations .nav-tab{
			cursor: pointer;
		}
		
		.wctv-configurations #success-message{
			min-height: 38px;
		}

		[class^="label-"] {
			background: #70db2a;
			color: #295d07;
			border-radius: 4px;
			display: inline-block;
			align-items: center;
			justify-content: center;
			padding: 1px 10px;
			font-size: 10px;
		}

		[class^="label-Hidden"] {
			background: #ccc !important;
			color: #fff !important;
		}
		[class^="label-INACTIVE"] {
			background: #ccc !important;
			color: #fff !important;
		}

		[name="edit_sponsor"] {
			display: flex;
			flex-direction: column;
		}

		[name="edit_sponsor"] label {
			display: flex;
			flex-direction: column;
			margin-bottom: 20px;
		}
		td.edit.column-edit a {
			visibility: hidden;
		}

		tr:hover td.edit.column-edit a {
			visibility: visible;
		}

		[name="delete_sponsor"] button {
			background-color: red !important;
			border-color: red !important;
		}

		[name="edit_sponsor"] button,
		.button-create {
			background-color: #4CAF50 !important;
			border-color: #009688 !important;
		} */
	</style>
    <div class="wrap wctv-configurations">
        <h1>Reports</h1>
        
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" data-tab="tab1">Event Grouped</a>
            <a class="nav-tab" data-tab="tab2">Event Detailed</a>
        </h2>
        
        <div id="tab1" class="tab-content">
			<?php grouped_reports(); ?>
        </div>
        
        <div id="tab2" class="tab-content" style="display:none;">
			<?php detailed_reports(); ?>
        </div>
        
    </div>
    
    <script>
    jQuery(document).ready(function($) {
		const selectedTab = localStorage.getItem('report_selected_tab');
		$('.nav-tab').click(function() {
			var tab = $(this).data('tab');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').hide();
            $('#' + tab).show();
			localStorage.setItem('report_selected_tab', tab);
        });

		if(selectedTab){
			$(`[data-tab="${selectedTab}"]`).click();
		}
    });
    </script>
	<script>
		function hideExport(ctx){
			const initialID = Number('<?=$eventID?>' || 0);
			const updatedId = Number(ctx.value);
			const forms = document.querySelectorAll('form[name="export"]');
			forms.forEach(form => {
				if(initialID !== updatedId){
					form.classList.add('hide-form');
				}else{
					form.classList.remove('hide-form');
				}
			})
		}
	</script>
    <?php
}
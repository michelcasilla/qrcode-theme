<?php
require_once('configurations/visiblePostAndEvents.php');
require_once('configurations/sponsors.php');
require_once('configurations/categories.php');

function configurations() {
    ?>
	<style>
		.wctv-configurations .row {
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

		/* [class^="label-active"] {
			background: #FFC107 !important;
			color: #795548 !important;
		} */
		[class^="label-Hidden"] {
			background: #ccc !important;
			color: #fff !important;
		}
		[class^="label-INACTIVE"] {
			background: #ccc !important;
			color: #fff !important;
		}

		[name="edit_sponsor"], [name^="edit_"] {
			display: flex;
			flex-direction: column;
		}

		[name="edit_sponsor"] label, [name^="edit_"] label {
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

		[name="delete_sponsor"] button,
		[name="^delete_"] button {
			background-color: red !important;
			border-color: red !important;
		}

		[name="edit_sponsor"] button,
		[name^="edit_"] button,
		.button-create {
			background-color: #4CAF50 !important;
			border-color: #009688 !important;
		}
	</style>
    <div class="wrap wctv-configurations">
        <h1>Configurations</h1>
        
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" data-tab="tab1"><?=__("Visible Events","sp")?></a>
            <a class="nav-tab" data-tab="tab2"><?=__("Sponsors","sp")?></a>
            <a class="nav-tab" data-tab="tab3"><?=__("Categories","sp")?></a>
        </h2>
        
        <div id="tab1" class="tab-content">
            <!-- Content for Tab 1 -->
			<?php visiblePostAndEvents(); ?>
        </div>
        
        <div id="tab2" class="tab-content" style="display:none;">
            <!-- Content for Tab 2 -->
			<?php sponsors(); ?>
        </div>
        
        <div id="tab3" class="tab-content" style="display:none;">
			<?php categories(); ?>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
		const selectedTab = localStorage.getItem('config_selected_tab');
		$('.nav-tab').click(function() {
			var tab = $(this).data('tab');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').hide();
            $('#' + tab).show();
			localStorage.setItem('config_selected_tab', tab);
        });

		if(selectedTab){
			$(`[data-tab="${selectedTab}"]`).click();
		}
    });
    </script>

	<script>
		jQuery(document).ready(function($) {
			$('#visible-events input[name="select_all"]').change(function() {
				var isChecked = $(this).is(':checked');
				$('#visible-events').find(':checkbox').prop('checked', isChecked);
			});
		});
    </script>

	<script>
		jQuery(document).ready(function($) {
			// Hide success message after 5 milliseconds
			setTimeout(function() {
				$('#success-message').fadeOut();
			}, 5000);
		});
    </script>
    <?php
}
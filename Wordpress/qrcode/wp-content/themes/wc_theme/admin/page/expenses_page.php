<?php
require_once( TEMPLATEPATH . '/admin/page/reports/grouped_reports.php' );
require_once( TEMPLATEPATH . '/admin/models/VTW_expenses_category.php' );
require_once( TEMPLATEPATH . '/admin/tables/EventExpenses.php' );

function expenses_page() {
	$eventID = null;
	$new_category_added_msg = '';
	$earned = 0;
	$expenses = 0;
	$balance = 0;
	
	$event_expenses_table_instance = new EventExpensesTable();
	$event_expenses_table_instance->prepare_items();
	
	if(isset($_REQUEST['eventID']) && !empty($_REQUEST['eventID'])){
		$eventID = (int)$_REQUEST['eventID'];
		$earned = $event_expenses_table_instance->get_total_earned($eventID);
		$expenses = $event_expenses_table_instance->get_total_expenses($eventID);
		$balance = ((int)$earned - (int)$expenses);
	}
	
	if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){

		if($_REQUEST['action'] == "add_category"){
			$name = $_REQUEST['name'];
			$shortCode = $_REQUEST['shortCode'];
			$status = 1;
			$new_category_added_msg = VTW_expenses_category::create_category($name, $status, $shortCode);
		}

	}
	$hideExportButton = true;
	
    ?>
	<style>
		.inline-forms {
			display: flex;
		}
		.hide-form{
			display: none;
		}
		.wctv-expenses {
			padding-bottom: 56px;
		}
		.wctv-expenses-row{
			display: flex;
			flex-direction: row;
		}
		.left {
			max-width: 48vw;
		}

		.left table {
			max-width: 50vw;
		}
		h2{
			font-size: 23px;
			font-weight: 400;
			margin: 0;
			padding: 9px 0 4px;
			line-height: 1.3;
		}

		#poststuff {
			max-width: 100% !important;
			min-width: 100%;
		}
		.inline-crud-form {
			padding-top: 20px;
		}
		.wctv-expenses-row > * {
			flex: 1;
		}
		[data-wp-lists="list:expense"] input {
			max-width: 100%;
		}

		.expenses-balance-footer {
			position: fixed;
			bottom: 0;
			background: white;
			z-index: 2;
			width: 100%;
			margin-left: -20px;
			box-shadow: -1px -3px 10px 5px rgb(8 15 55 / 37%);
			display: flex;
    		flex-direction: row;
		}
		.expenses-balance-footer > * {
			flex: 1;
			flex-direction: row;
			line-height: 180%;
		}

		.expenses-balance-footer h1 {
			margin: 0;
		}
		.expenses-balance-footer > * {
			padding: 20px;
			border-left: dashed 1px #ebebeb;
		}
		.left.balance {
			background: #98dd9b;
		}
		.left.balance.balance-negative {
			background: #fba1a1;
		}
		.button-print {
			margin-top: 10px !important;
			background: transparent !important;
			border-color: green !important;
			color: green !important;
		}
		@media print {
			/* Hide admin header and footer */
			#wpadminbar,
			.notice,
			.wrap > h1:first-child,
			#screen-meta,
			#wpfooter {
				display: none;
			}


			/* Hide left menu */
			.hide-for-print,
			#adminmenumain,
			#adminmenuback,
			#adminmenuwrap,
			#adminmenu {
				display: none !important;
			}

			/* Adjust page layout */
			#wpcontent,
			#wpbody-content {
				margin: 0;
				padding: 0;
				width: 100%;
			}

			/* Adjust specific elements */
			.wrap {
				margin: 0;
			}

			*{
				position:relative !important;
			}
			.wctv-expenses-row{
				display: block;
			}
			.wctv-expenses-row table{
				width: 100vw;
				max-width: 100vw;
			}
			.left, .right{
				max-width: 100vw
			}
			.expenses-balance-footer {
				box-shadow: none;
				width: 100vw;
				margin: 0 !important;
			}
			input {
				padding: 0 !important;
				border: none !important;
				background: transparent !important;
				height: 20px;
			}

			td {
				padding: 0 !important;
			}
		}

	</style>
    <div class="wrap wctv-expenses">
		<div class="wctv-expenses-row">
			<div class="left">
				<?php grouped_reports($hideExportButton); ?>
			</div>
			<?php if($eventID): ?>
				<div class="right">
					<h2>Event expenses</h2>
					<div class="inline-crud-form">
						<form action="" method="POST" class="hide-for-print">
							<input type="hidden" name="eventID" value="<?=$eventID?>">
							<label>
								<?=__("Nombre", "sp")?>
								<input type="text" name="name" placeholder="name">
							</label>
							<label>
								<?=__("Short Code", "sp")?>
								<input type="text" name="shortCode" placeholder="Short Code">
							</label>
							<label>
								&nbsp;
								<button class="button-primary" name="action" value="add_category"><?=__("+ ADD", "sp")?></button>
							</label>
						</form>
						<?php if($new_category_added_msg): ?>
							<label class="label"><?=$new_category_added_msg?></label>
						<?php endif; ?>
						<div class="category-list">
						<form method="post">
							<input type="hidden" name="eventID" value="<?=$eventID?>">
							<?php
								$event_expenses_table_instance->display();
							?>
							<button class="button-primary hide-for-print" name="action" value="update_event_expenses"><?=__("ACTUALIZAR", 'sp')?></button>
						</form>
						</div>
					</div>
				</div>
			<?php else: ?>
				<div class="no-event-selected-wrapper">
					<h3><?=__("Seleccionar un evento", 'sp')?></h3>
				</div>
			<?php endif; ?>
		</div>
    </div>
	<?php if($eventID): ?>
		<div class="expenses-balance-footer">
			<div class="left earned">
				<span><?=__("Income", "sp")?></span>
				<h1><?=number_format($earned, 2, '.', ',')?></h1>
			</div>
			<div class="right expenses">
				<span><?=__("Expenses","sp")?></span>
				<h1><?=number_format($expenses, 2, '.', ',')?></h1>
			</div>
			<div class="left balance <?=$balance < 1 ? "balance-negative" : 'balance-positive'?>">
				<span><?=__("Balance","sp")?></span>
				<h1><?=number_format($balance, 2, '.', ',')?></h1>
				<button class="button-secondary hide-for-print button-print" onclick="window.print()"><?=__("IMPRIMIR", 'sp')?></button>
			</div>
		</div>
	<?php endif; ?>
    <?php
}
<?php
require_once(TEMPLATEPATH."/admin/tables/EventTransactionBucketTable.php");

function bucketTransactions () {

add_filter( 'set-screen-option', 'set_screen', 10, 3 );

function set_screen( $status, $option, $value ) {
	return $value;
}

// $eventPaymentInstance = new EventPayment_List();
$event_transaction_table = new EventTransactionBucketTable();
?>
<style>
	.hide{
		display: none !important;
	}
	.custom-search-box {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: flex-end;
	}
	.filter-holder {
		display: flex;
		flex-direction: row;
	}

	.filter-holder .left {flex: 1;}

	[class^="status-"]{
		background: #70db2a;
		color: #295d07;
		border-radius: 4px;
		display: inline-block;
		align-items: center;
		justify-content: center;
		padding: 1px 10px;
		font-size: 10px;
	}
	[class^="status-ANULADO"] {
		background: #FFC107 !important;
    	color: #795548 !important;
	}

	span.status-PENDING {
		background: #999;
		color: white;
	}

	.conciliation-wrapper{
		padding: 20px;
		background: #fffaed;
		border-left: solid 3px orange;
		display: none;
	}
	
	.conciliation-wrapper table{
		background: white;
    	box-shadow: 0px 0px 6px 4px #5f441d36;
	}
	.no-transaction {
		padding: 20px;
		text-align: center;
		justify-content: center;
		background: #e8e2d4;
	}
	.no-bucket-list{
		list-style: inside;
	}
	.have-error * {
		color: red;
		margin: 0 !important;
    	padding: 0 !important;
	}
</style>

<div class="wrap">
	<div id="poststuff">
		<div class="filter-holder">
			<div class="left">
				<h1>Bucket transactions</h1>
			</div>
			<div class="right">
				<form method="post">
					<?php
						$event_transaction_table->prepare_items();
						$event_transaction_table->search_box('Search', 'search');
					?>
				</form>
				
			</div>
		</div>
		<div class="conciliation-wrapper meta-box-sortables ui-sortable">
			<div id="csv-preview"></div>
		</div>
		<div id="post-body" class="metabox-holder columns-1">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php $event_transaction_table->display(); ?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>  
</div>
<?php //pendingTransactionBucketNotificationsList(); ?>
<?php } ?>
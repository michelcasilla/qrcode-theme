<?php

include_once("EventPayment.php");
include_once("tables/EventPaymentListTable.php");
include_once("notification.class.php");
include_once("models/VTW_Ticket_Code.php");
include_once("models/VTW_Event_Payment_Catch.php");
include_once("pendingNotificationsList.php");
include_once("pendingTransactionBucketNotificationsList.php");
include_once("tktList.php");
include_once("createNewFromAdmin.php");
include_once("registerPaymentAdmin.php");
include_once("StripePaymentGateWay.php");
include_once("azul/createPayment.php");
include_once(TEMPLATEPATH . "/admin/models/VTW_Event_Payment_Reports.php");
include_once(TEMPLATEPATH . "/admin/AmwayGateWay.php");
include_once(TEMPLATEPATH . "/admin/WcAppApiConnect.php");

add_action('rest_api_init', function () {

	register_rest_route('wctvApi/v1', 'registerPayment', array(
		'methods'  => 'POST',
		'callback' => 'registerPayment',
		'args' => array(
			"action" => array(),
			"post_id" => array(),
			"invoice_id" => array(),
			"description" => array(),
			"create_time" => array(),
			"update_time" => array(),
			"status" => array(),
			"amoun_currency_code" => array(),
			"amoun_currency_value" => array(),
			"email_address" => array(),
			"merchant_id" => array(),
			"payee_email_address" => array(),
			"payee_merchant_id" => array(),
			"payments_amount" => array(),
			"payments_curency" => array(),
			"payments_id" => array(),
			"payment_status" => array(),
			"payment_update_time" => array(),
			"shipping_address_address_line_1" => array(),
			"shipping_address_admin_area_1" => array(),
			"shipping_address_admin_area_2" => array(),
			"shipping_address_country_code" => array(),
			"shipping_address_postal_code" => array(),
			"shipping_name_full_name" => array(),
			"payment_contract" => array(),
			"payment_contract_hash" => array(),
			"created_at" => array(),
			"invoiceID" => array(),
			"sponsor" => array(),
			"paypal_order_id" => array(),
			"ticket_email" => array(),
			"payee_name" => array(),
			"ticketNames" => array(),
			"eventPrice" => array(),
			"eventQty" => array(),
			"ibo" => array(),
		)
	));

	register_rest_route('wctvApi/v1', 'registerPaymentAdmin', array(
		'methods'  => 'POST',
		'callback' => 'registerPaymentAdmin',
		'args' => array(
			'email' => array('require' => true),
			'sponsor' => array('require' => true),
			'eventId' => array('require' => true),
			'paymentref' => array('require' => true),
			'catchID' => array(),
			'paypload' => array(),
			'currency' => array(),
			'invoiceID' => array('require' => true),
			'payee_name' => array('require' => true),
			'ibo' => array('require' => true),
			'apply_for_free' => array('require' => false),
		)
	));

	register_rest_route('wctvApi/v1', 'validateTicket', array(
		'methods'  => 'POST',
		'callback' => 'validateTicket',
		'args' => array(
			'code' => array('require' => true),
			'email' => array('require' => true),
			'eventId' => array()
		)
	));

	register_rest_route('wctvApi/v1', 'csv', array(
		'methods'  => 'GET',
		'callback' => 'csvD',
		'args' => array(
			'code' => array('require' => true),
			'type' => array('require' => false),
			'email' => array('require' => true),
		)
	));

	register_rest_route('wctvApi/v1', 'tktList', array(
		'methods'  => 'GET',
		'callback' => 'tktList',
		'args' => array(
			'event_payment_id' => array('require' => true)
		)
	));

	register_rest_route('wctvApi/v1', 'tktCancel', array(
		'methods'  => 'GET',
		'callback' => 'tktCancel',
		'args' => array(
			'id' => array('require' => true)
		)
	));

	register_rest_route('wctvApi/v1', 'markAsUsed', array(
		'methods'  => 'GET',
		'callback' => 'markAsUsed',
		'args' => array(
			'id' => array('require' => true)
		)
	));

	register_rest_route('wctvApi/v1', 'resendTkt', array(
		'methods'  => 'GET',
		'callback' => 'resendTkt',
		'args' => array(
			'event_payment_id' => array(),
			'notification_id' => array(),
			'aditional_mail' => array()
		)
	));

	register_rest_route('wctvApi/v1', 'pendingNotifications', array(
		'methods'  => 'GET',
		'callback' => 'pendingNotifications'
	));

	register_rest_route('wctvApi/v1', 'pendingTransactionBucketNotificationsList', array(
		'methods'  => 'GET',
		'callback' => 'pendingTransactionBucketNotificationsList',
		'args' => array(
			'status' => array(),
		)
	));

	register_rest_route('wctvApi/v1', 'createNewFromAdmin', array(
		'methods'  => 'GET',
		'callback' => 'createNewFromAdmin',
		'args' => array(
			'id' => array('require' => true),
			'eventID' => array('required' => true),
			'userID' => array(),
		)
	));

	register_rest_route('wctvApi/v1', 'cancelPendingPayment', array(
		'methods'  => 'GET',
		'callback' => 'cancelPendingPayment',
		'args' => array(
			'id' => array('require' => true),
			'eventID' => array('required' => true),
			'userID' => array()
		)
	));

	register_rest_route('wctvApi/v1', 'watchFrom', array(
		'methods'  => 'GET',
		'callback' => 'watchFrom',
		'args' => array(
			'tickedCodeID' => array('require' => true),
			'tickedCode' => array('require' => true),
		)
	));

	register_rest_route('wctvApi/v1', 'isSoldOut', array(
		'methods'  => 'GET',
		'callback' => 'isSoldOut',
		'args' => array(
			'eventID' => array('require' => true)
		)
	));

	register_rest_route('wctvApi/v1', 'redemeTicket', array(
		'methods'  => 'POST',
		'callback' => 'redemeTicket',
		'args' => array(
			'ticketCode' => array('require' => true),
			'eventId' => array('require' => false)
		)
	));

	register_rest_route('wctvApi/v1', 'verifyIBO', array(
		'methods'  => 'POST',
		'callback' => 'verifyIBO',
		'args' => array(
			'ibo' => array('require' => true),
			'eventId' => array('require' => false)
		)
	));

	register_rest_route('wctvApi/v1/stripe', 'createPaymentID', array(
		'methods'  => 'POST',
		'callback' => 'stripeCreatePaymentID',
		'args' => array(
			'amount' => array('require' => true),
			'currency' => array('require' => false),
			'payment_method_types' => array('require' => false),
			'eventId' => array('require' => true),
			'statementDescriptor' => array('require' => true),
			'invoiceId' => array('require' => true),
			'ibo' => array('require' => true),
		)
	));

	register_rest_route('wctvApi/v1/stripe', 'retrivePaymentIntentInfos', array(
		'methods'  => 'POST',
		'callback' => 'retrivePaymentIntentInfos',
		'args' => array(
			'paymentId' => array('require' => true)
		)
	));

	register_rest_route('wctvApi/v1/stripe', 'updatePaymentIntent', array(
		'methods'  => 'POST',
		'callback' => 'updatePaymentIntent',
		'args' => array(
			'paymentId' => array('require' => true),
			'amount' => array('require' => true),
			'currency' => array('require' => false),
			'customer' => array('require' => false),
			'statementDescriptor' => array('require' => false),
			'receipt_email' => array('require' => false),
			'setup_future_usage' => array('require' => false),
			'extraFields' => array('require' => true),
			'invoiceId' => array('require' => true),
			'eventId' => array('require' => true),
			'ibo' => array('require' => true),
		)
	));

	register_rest_route('wctvApi/v1/azul', 'create_payment', get_process());
	register_rest_route('wctvApi/v1/azul', 'process_response', process_response());
	register_rest_route('wctvApi/v1/azul', 'retrive_payment_intent_infos', retrivePaymentInfo());
});


function cancelPendingPayment(WP_REST_Request $request)
{
	$response = null;
	try {

		$id     = esc_sql($_REQUEST['id']);

		$catch = EventPaymentCatch::getById($id);
		if ($catch) {
			$catch = EventPaymentCatch::addPaymentId($id, 0);
		}
?>
		<script type="text/javascript">
			function home_url(url) {
				return `<?= home_url() ?>${url}`;
			}
			(function() {
				let url = home_url("/wp-json/wctvApi/v1/pendingNotifications?height=600&width=1200");
				tb_show("Notificaciones de pago pendientes", url, "");
			})()
		</script>
	<?php

	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNKNOWN", "errorInfo" => $e));
		$response->set_status(500);
		return $response;
	}
}

function updatePaymentIntent(WP_REST_Request $request)
{
	$response = null;
	try {

		$paymentId = esc_sql($request->get_param('paymentId'));
		$amount = esc_sql($request->get_param('amount'));
		$currency = $request->get_param('currency') ? esc_sql($request->get_param('currency')) : null;
		$method = $request->get_param('payment_method_types') ? esc_sql($request->get_param('payment_method_types')) : null;
		$eventId = esc_sql($request->get_param('eventId'));
		$invoiceId = esc_sql($request->get_param('invoiceId'));
		$statementDescriptor = esc_sql($request->get_param('statementDescriptor'));
		$email = esc_sql($request->get_param('receipt_email'));
		$extraFields = esc_sql($request->get_param('extraFields'));
		$ibo = esc_sql($request->get_param('ibo'));

		$stripePayment = StripePaymentGateWay::getInstance();
		$stripeResponse = $stripePayment->updatePaymentIntent(
			$paymentId,
			$amount,
			$eventId,
			$invoiceId,
			$statementDescriptor,
			$currency,
			$method,
			$email,
			$ibo,
			$extraFields
		);

		if (isset($stripeResponse->error)) {
			$response = new WP_REST_Response(array("status" => "API_ERROR", "errorInfo" => $stripeResponse->error));
			$response->set_status(500);
		} else {
			$response = new WP_REST_Response(array("status" => true, "data" => $stripeResponse->data));
			$response->set_status(200);
		}
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNKNOWN", "errorInfo" => $e));
		$response->set_status(500);
	}

	return $response;
}

function retrivePaymentIntentInfos(WP_REST_Request $request)
{
	$response = null;
	try {

		$paymentId = esc_sql($request->get_param('paymentId'));

		$stripePayment = StripePaymentGateWay::getInstance();
		$stripeResponse = $stripePayment->retrivePaymentIntentInfos($paymentId);

		if (isset($stripeResponse->error)) {
			$response = new WP_REST_Response(array("status" => "API_ERROR", "errorInfo" => $stripeResponse->error));
			$response->set_status(500);
		} else {
			$response = new WP_REST_Response(array("status" => true, "data" => $stripeResponse->data));
			$response->set_status(200);
		}
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNKNOWN", "errorInfo" => $e));
		$response->set_status(500);
	}

	return $response;
}

function stripeCreatePaymentID(WP_REST_Request $request)
{
	$response = null;
	try {
		$amount = esc_sql($request->get_param('amount'));
		$currency = $request->get_param('currency') ? esc_sql($request->get_param('currency')) : null;
		$method = $request->get_param('payment_method_types') ? esc_sql($request->get_param('payment_method_types')) : null;
		$eventId = esc_sql($request->get_param('eventId'));
		$invoiceId = esc_sql($request->get_param('invoiceId'));
		$ibo = esc_sql($request->get_param('ibo'));
		$statementDescriptor = esc_sql($request->get_param('statementDescriptor'));

		$stripePayment = StripePaymentGateWay::getInstance();
		$stripeResponse = $stripePayment->paymentIntentCreate($amount, $eventId, $invoiceId, $statementDescriptor, $currency, $ibo, $method);

		if (isset($stripeResponse->error)) {
			$response = new WP_REST_Response(array("status" => "API_ERROR", "errorInfo" => $stripeResponse->error));
			$response->set_status(500);
		} else {
			$response = new WP_REST_Response(array("status" => true, "data" => $stripeResponse->data));
			$response->set_status(200);
		}
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNKNOWN", "errorInfo" => $e));
		$response->set_status(500);
	}

	return $response;
}

function resendTkt()
{
	// global $wpdb;
	$error = "";
	$status = false;
	try {
		$event_payment_id = esc_sql($_REQUEST['event_payment_id']);
		$notification_id = esc_sql($_REQUEST['notification_id']);
		$aditional_mail = esc_sql($_REQUEST['aditional_mail']);
		if ($event_payment_id) {
			Notification::resendNotificationFromEventPayment($event_payment_id, $aditional_mail);
		}
		if ($notification_id) {
			Notification::resendNotificationFromID($notification_id, $aditional_mail);
		}
		$response = new WP_REST_Response(array("status" => true));
		$status = true;
	} catch (Exception $e) {
		$status = false;
		$error = $e;
	}
	if ($status) {

	?>
		<h1>Mensaje enviado correctamente</h1>
	<?php } else { ?>
		<h1>Error enviando mensaje</h1>
		<p><?php echo $error; ?></p>
<?php
	}
}

function tktCancel()
{
	$status = false;
	if (custom_is_admin()) {
		if (isset($_REQUEST['id'])) {
			$id = esc_sql($_REQUEST['id']);
			$status = TicketCode::markAsCanceled((int) $id);
		}
	}
	if (!$status) {
		echo "<h2>No se pudo anular el ticket</h2>";
	} else {
		echo "<h2>Ticket anulado satisfactoriamente</h2>";
	}
}

function markAsUsed()
{
	$status = false;
	if (custom_is_admin()) {
		if (isset($_REQUEST['id'])) {
			$id = esc_sql($_REQUEST['id']);
			$status = TicketCode::markAdRedeemed((int) $id);
		}
	}
	if (!$status) {
		echo "<h2>No se pudo marcar como redimído / usado</h2>";
	} else {
		echo "<h2>Ticket marcado como usado / redimído satisfactoriamente</h2>";
	}
}


function csvD()
{

	global $wpdb;

	if (custom_is_admin() == false) {
		die("ADMIN_REQUIRED");
	}


	if (!isset($_REQUEST['eventID']) || (int)$_REQUEST['eventID'] < 1) {
		die("MOST_SELECT_A_VALID_EVENT_ID");
	}

	$eventID = (int)$_REQUEST['eventID'];

	$type = 'grouped';

	if (isset($_REQUEST['type'])) {
		$type = esc_sql($_REQUEST['type']);
	}

	if ($type == 'detailed') {
		$data = VTW_Event_Payment_Reports::getDetailedReport($eventID);
		$fileName_1 = $eventID . '_detailed_report.csv';
	} else {
		$data = VTW_Event_Payment_Reports::getGroupedReport($eventID);
		$fileName_1 = $eventID . '_report_by_sponsor.csv';
	}

	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-Description: File Transfer');
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename={$fileName_1}");
	header("Expires: 0");
	header("Pragma: public");
	$fh1 = @fopen('php://output', 'w');
	$headerDisplayed1 = false;
	$totalTickets = 0;
	foreach ($data as $data1) {
		// Add a header row if it hasn't been added yet
		if (!$headerDisplayed1) {
			// Use the keys from $data as the titles
			fputcsv($fh1, array_keys($data1));
			$headerDisplayed1 = true;
		}

		// Put the data into the stream
		fputcsv($fh1, $data1);

		$totalTickets = $totalTickets + $data1["Qty"];
	}
	fputcsv($fh1, array($totalTickets, "", "", "", ""));
	// Close the file
	fclose($fh1);
	// Make sure nothing else is sent, our file is done
	exit;
	//   }

}

function validateTicket(WP_REST_Request $request)
{
	global $wpdb;
	// global $post;


	$response = new WP_REST_Response(array(
		"status" => 0,
		"msg" => "NO_TK_AVAILABLE"
	));
	$response->set_status(500);

	$code = esc_sql($request->get_param("code"));
	$email = esc_sql($request->get_param("email"));
	$eventId = esc_sql($request->get_param("eventId"));
	$validateDate = true;

	if ($code && $email && $eventId) {

		$result = TicketCode::getLastEventTicketCode($code, $email, $eventId);
		if ($result) {
			// if ticket yet available
			$endDate = DateTime::createFromFormat("m/d/Y g:i a", get_field("end_date", $eventId));
			$now = current_datetime();
			//print_r($endDate);
			//print_r($now);
			//die;
			if ($validateDate && ($now > $endDate)) {
				$response = new WP_REST_Response(array("status" => "EXPIRED"));
				$response->set_status(500);
			} else {
				$response = new WP_REST_Response(array(
					"ticket" => $result,
					"source" => get_field("event_embed_source", $eventId)
				));
				$response->set_status(200);

				try {

					TicketCode::updateLastTicketCodeIP($result->last_event_ticket_code, $result->id, get_the_user_ip());
				} catch (Exception $e) {
				}
			}

			$startDate = DateTime::createFromFormat("d/m/Y g:i a", get_field("event_date", $eventId));
			if ($now > $startDate || $now > $endDate) {
				// TicketCode::markAdRedeemed($result->id);
			}
		}
	}

	return $response;
}

function watchFrom(WP_REST_Request $request)
{
	global $wpdb;
	$ticketCodeID = esc_sql($request->get_param("tickedCodeID"));

	$ticketCode = esc_sql($request->get_param("tickedCode"));
	$currentIP = get_the_user_ip();

	$ticket = TicketCode::getTicketCodeByCode($ticketCode, $ticketCodeID);
	if (!$ticket) {
		$response = new WP_REST_Response(array("status" => true, "code" => "NOT_MATCH"));
		$response->set_status(200);
	} elseif ($ticket->watchedByIP == "") {
		$response = new WP_REST_Response(array("status" => true, "code" => "VALID"));
		$response->set_status(200);
	} elseif ($ticket->watchedByIP == $currentIP) {
		$response = new WP_REST_Response(array("status" => true, "code" => "VALID"));
		$response->set_status(200);
	} else {
		$response = new WP_REST_Response(array("status" => true, "code" => "MISS_MATCH_IP"));
		$response->set_status(500);
	}
	return $response;
}


function isSoldOut(WP_REST_Request $request)
{
	global $wpdb;
	try {
		$eventID = esc_sql($request->get_param("eventID"));
		$fisicalmaxquantity = get_field("fisicalmaxquantity", $eventID);
		$isfisical = get_field("isfisical", $eventID);
		$qtyTickerSold = TicketCode::qtyTickerSold($eventID)->totalSold;

		if ($isfisical && $qtyTickerSold >= $fisicalmaxquantity) {
			$response = new WP_REST_Response(array("status" => true, "code" => "SOLD_SOUT", "limit" => $fisicalmaxquantity));
		} else {
			$response = new WP_REST_Response(array("status" => false, "code" => "SOLD_SOUT", "limit" => $fisicalmaxquantity));
		}
		$response->set_status(200);
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => false, "code" => $e->getMessage()));
		$response->set_status(500);
	}

	return $response;
}

function verifyIBO(WP_REST_Request $request)
{
	$ibo = esc_sql($request->get_param("ibo"));
	$eventId = esc_sql($request->get_param("eventId"));
	$gate = new AmwayGateWay();
	$resp = $gate->verifyIBO($ibo, $eventId);
	$response = new WP_REST_Response($resp);
	return $response;
}

function redemeTicket(WP_REST_Request $request)
{
	try {
		$ticketCode = esc_sql($request->get_param("ticketCode"));
		$eventId = esc_sql($request->get_param("eventId"));
		$ticket = TicketCode::getTicketCodeByCode($ticketCode, $eventId);
		if (!$ticket) {
			throw new Exception("Ticket not found");
		}
		if ($ticket->status && $ticket->status == 1) {
			$status = TicketCode::markAdRedeemeFisical($ticket->event_payment_id);
			if ($status == 1) {
				$response = new WP_REST_Response(array("status" => true, "code" => "REDEMED", "msg" => 'Ticket Redimido Correctamente'));
				$response->set_status(200);
			} else {
				$response = new WP_REST_Response(array("status" => false, "code" => "UNABLE_REDEM", "msg" => 'Ocurrió un error marcando el tieckt como usado'));
				$response->set_status(500);
			}
		} else if ($ticket->status == 0) {
			$response = new WP_REST_Response(array("status" => false, "code" => "USED", "msg" => 'Este ticket ya ha sido canjeado o no fue encontrado'));
			$response->set_status(500);
		} else if ($ticket->status == 3) {
			$response = new WP_REST_Response(array("status" => false, "code" => "CANCELED", "msg" => 'Este ticket ha sido cancelado'));
			$response->set_status(500);
		}
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => false,  "code" => "NOT_FOUND", "msg" => 'Ticket no encontrado'));
		$response->set_status(500);
	}

	return $response;
}

function registerPayment(WP_REST_Request $request)
{
	global $wpdb;

	$eventPayment = new EventPayment();
	$eventPayment->invoice_id 						= esc_sql($request->get_param("invoice_id"));
	$eventPayment->description 						= esc_sql($request->get_param("description"));
	$eventPayment->create_time 						= esc_sql($request->get_param("create_time"));
	$eventPayment->update_time 						= esc_sql(date('Y-m-d', strtotime(str_replace('/', '-', (string) $request->get_param("update_time")))));
	$eventPayment->status 							= esc_sql($request->get_param("status"));
	$eventPayment->amoun_currency_code 				= esc_sql($request->get_param("amoun_currency_code"));
	$eventPayment->amoun_currency_value 			= esc_sql($request->get_param("amoun_currency_value"));
	$eventPayment->email_address 					= esc_sql($request->get_param("email_address"));
	$eventPayment->merchant_id 						= esc_sql($request->get_param("merchant_id"));
	$eventPayment->payee_email_address 				= esc_sql($request->get_param("payee_email_address"));
	$eventPayment->payee_merchant_id 				= esc_sql($request->get_param("payee_merchant_id"));
	$eventPayment->payments_amount 					= esc_sql($request->get_param("payments_amount"));
	$eventPayment->payments_curency 				= esc_sql($request->get_param("payments_curency"));
	$eventPayment->payments_id 						= esc_sql($request->get_param("payments_id"));
	$eventPayment->payments_invoice_id 				= esc_sql($request->get_param("payments_invoice_id"));
	$eventPayment->payment_status 					= esc_sql($request->get_param("payment_status"));
	$eventPayment->payment_update_time 				= esc_sql(date('Y-m-d', strtotime(str_replace('/', '-', (string) $request->get_param("payment_update_time")))));
	$eventPayment->shipping_address_address_line_1 	= esc_sql($request->get_param("shipping_address_address_line_1"));
	$eventPayment->shipping_address_admin_area_1 	= esc_sql($request->get_param("shipping_address_admin_area_1"));
	$eventPayment->shipping_address_admin_area_2 	= esc_sql($request->get_param("shipping_address_admin_area_2"));
	$eventPayment->shipping_address_country_code 	= esc_sql($request->get_param("shipping_address_country_code"));
	$eventPayment->shipping_address_postal_code 	= esc_sql($request->get_param("shipping_address_postal_code"));
	$eventPayment->shipping_name_full_name 			= esc_sql($request->get_param("shipping_name_full_name"));
	$eventPayment->payment_contract 				= esc_sql($request->get_param("payment_contract"));
	$eventPayment->payment_contract_hash 			= md5($request->get_param("payment_contract_hash"));
	$eventPayment->created_at 						= current_time('mysql'); //esc_sql($request->get_param("created_at"));
	$eventPayment->sponsor 							= esc_sql($request->get_param("sponsor"));
	$eventPayment->paypal_order_id 					= esc_sql($request->get_param("paypal_order_id"));
	$eventPayment->post_id 							= esc_sql($request->get_param("post_id"));
	$eventPayment->post_snap 						= serialize(get_post($eventPayment->post_id));
	$eventPayment->ticket_email 					= esc_sql($request->get_param("ticket_email"));
	$eventPayment->payee_name 						= esc_sql($request->get_param("payee_name"));

	$eventPayment->ticketNames 						= esc_sql($request->get_param("ticketNames"));
	$eventPayment->eventPrice 						= esc_sql($request->get_param("eventPrice"));
	$eventPayment->eventQty 						= esc_sql($request->get_param("eventQty"));
	$eventPayment->quantity 						= $eventPayment->eventQty;
	$action 										= esc_sql($request->get_param("action"));
	$eventPayment->ibo 								= esc_sql($request->get_param("ibo"));
	$apply_for_free 								= esc_sql($request->get_param("apply_for_free")) || false;
	$eventPayment->apply_for_free 					= $apply_for_free ? 1 : 0;


	// catch payment
	$source = "PAYPAL";

	if ($eventPayment->apply_for_free) {
		$eventApplyForFree = get_field('apply_for_trial', $eventPayment->post_id);
		// Is the event does not apply for free but the user is trying to apply for free
		if ($eventApplyForFree != true) {
			$response = new WP_REST_Response(array("status" => "EVENT_NOT_APPLY_FOR_FREE"));
			$response->set_status(500);
			return $response;
		}

		$totalInEvent = VTW_Event_Payment::getFreePaymentsByIbo($eventPayment->ibo, $eventPayment->post_id);
		$freePayments = FREE_PAYMENT_PER_EVENT - $totalInEvent->total_tickets;

		if ($eventPayment->eventQty > $freePayments) {
			$response = new WP_REST_Response(array("status" => "TOO_MANY_FREE_PAYMENTS"));
			$response->set_status(500);
			return $response;
		}
	}

	try {
		if ($action == 'register_stripe_payment') {
			$source = "STRIPE";
		}

		if ($action == 'register_azul_payment') {
			$source = "AZUL";
			$status = AzulPaymentGateWay::verifyBucketComplete($eventPayment->payments_id);
			// verify azul payment in bucket
			if (!$status) {
				throw new Exception('NO_PAYMENT_INFO');
			}
		}

		$paymentCatch = EventPaymentCatch::insertFromPayPal($eventPayment, $source);
		$paymentRefResult = EventPayment_List::getPaymentByPaymentRef($eventPayment->payments_id);

		if (count($paymentRefResult) == 0) {
			if ($eventPayment->eventQty > 1) {
				$response = registerMultyTicketPayment($eventPayment, $paymentCatch);
			} else {
				$response = registerSingleTicketPayment($eventPayment, $paymentCatch);
			}
		} else {
			EventPaymentCatch::addLog($paymentCatch, "PAYMENT_REF_EXIST");
			$response = new WP_REST_Response(array("status" => "PAYMENT_REF_EXIST"));
			$response->set_status(500);
		}
	} catch (Exception $e) {
		$msg = $e->getMessage();
		EventPaymentCatch::addLog($paymentCatch, $msg);
		$response = new WP_REST_Response(array("status" => $msg));
		$response->set_status(500);
	}


	return $response;
}

function registerMultyTicketPayment($eventPayment, $paymentCatch)
{
	global $wpdb;
	$response = new StdClass();
	$response->done = array();
	$response->fail = array();

	try {
		for ($i = 0; $i <= $eventPayment->eventQty - 1; $i++) {
			$newEventPayment = clone ($eventPayment);
			$newEventPayment->payments_id = $newEventPayment->payments_id . '_' . ($i + 1);
			$newEventPayment->amoun_currency_value = $newEventPayment->eventPrice;
			$newEventPayment->payments_amount = $newEventPayment->eventPrice;
			$newEventPayment->payee_name = $newEventPayment->ticketNames[$i]['name'];

			// This properties area not stored into de database;
			unset($newEventPayment->ticketNames);
			unset($newEventPayment->eventPrice);
			unset($newEventPayment->eventQty);

			$status = $wpdb->insert("{$wpdb->prefix}event_payment", (array) $newEventPayment, null);
			if ($status) {
				$useSubjectName = true;
				$id = $wpdb->insert_id;
				$newEventPayment->event_ticket = getTicketNumber($newEventPayment);
				Notification::sendRegisterPaymentNotificationEmail($newEventPayment, $useSubjectName);
				$tCodeID = TicketCode::insertTicketCodeFromPayment($id, $newEventPayment);
				EventPaymentCatch::addPaymentId($paymentCatch, $id);
				$response->done[] = array(
					"status" => $status,
					"id" => $id,
					"msg" => $newEventPayment,
					"tCodeId" => $tCodeID
				);
				WCAppApiConnect::logFromEventPayment($eventPayment);
			} else {
				EventPaymentCatch::addLog($paymentCatch, $wpdb->last_error);
				$response->fail[] = array("status" => "UNABLE_TO_SAVE_PAYMENT", "data" => $wpdb->last_error);
			}
		}

		if (count($response->fail) > 0) {
			$response = new WP_REST_Response(array("status" => "UNABLE_TO_SAVE_PAYMENT", "detail" => $response->fail));
			$response->set_status(500);
		} else {
			$response = new WP_REST_Response($response->done);
			$response->set_status(200);
		}
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNABLE_TO_SAVE_PAYMENT", "detail" => $e));
		$response->set_status(500);
	}
	return $response;
}

function registerSingleTicketPayment($eventPayment, $paymentCatch)
{
	global $wpdb;
	// This properties area not stored into de database;
	unset($eventPayment->ticketNames);
	unset($eventPayment->eventPrice);
	unset($eventPayment->eventQty);
	$status = $wpdb->insert("{$wpdb->prefix}event_payment", (array) $eventPayment, null);

	if ($status) {
		$id = $wpdb->insert_id;
		$useSubjectName = true;
		$eventPayment->event_ticket = getTicketNumber($eventPayment);
		Notification::sendRegisterPaymentNotificationEmail($eventPayment, $useSubjectName);
		$tCodeID = TicketCode::insertTicketCodeFromPayment($id, $eventPayment);
		EventPaymentCatch::addPaymentId($paymentCatch, $id);

		$response = new WP_REST_Response(array(array(
			"status" => $status,
			"id" => $id,
			"msg" => $eventPayment,
			"tCodeId" => $tCodeID
		)));
		$response->set_status($status ? 200 : 500);
		WCAppApiConnect::logFromEventPayment($eventPayment);
	} else {
		EventPaymentCatch::addLog($paymentCatch, $wpdb->last_error);
		$response = new WP_REST_Response(array("status" => "UNABLE_TO_SAVE_PAYMENT"));
		$response->set_status(500);
	}
	return $response;
}


#function getTicketNumber($eventPayment){
#	return strtoupper(uniqid($eventPayment->paypal_order_id));
#}

function getTicketNumber(
	$eventPayment,
	int $length = 6,
	string $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'
	// string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
	if ($length < 1) {
		throw new \RangeException("Length must be a positive integer");
	}
	$pieces = [];
	$max = mb_strlen($keyspace, '8bit') - 1;
	for ($i = 0; $i < $length; ++$i) {
		$pieces[] = $keyspace[random_int(0, $max)];
	}
	return implode('', $pieces);
}

function custom_is_admin()
{
	$status = false;
	$allowed_admin_pages = array(
		get_admin_url(null, 'admin.php?page=paymentlist'),
		get_admin_url(null, 'admin.php?page=reports-page')
	);

	$ref = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "DIRECT_ACCESS_NOT_ALLOWED";
	if (array_search($ref, $allowed_admin_pages) !== false) {
		$status = true;
	}

	return $status;
}

function get_the_user_ip()
{

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

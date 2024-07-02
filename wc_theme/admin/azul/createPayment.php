<?php
include_once("azulPaymentGateWay.php");
include_once("azulPayment.php");



function get_process()
{
	return array(
		'methods'  => 'POST',
		'callback' => 'create_form',
		'permission_callback' => '__return_true',
		'args' => array(
			'eventId' => array('require' => true),
			'eventQty' => array('require' => true),
			'confEmail' => array('require' => true),
			'refundPolicy' => array('require' => true),
			'emails' => array('require' => false),
			'amount' => array('require' => true),
			'currency' => array('require' => false),
			'invoiceId' => array('require' => true),
			'selectSponsor' => array('require' => true),
			'approveUrl' => array('require' => true),
			'cancelUrl' => array('require' => true),
			'declineUrl' => array('require' => true),
		)
	);
}

function process_response()
{
	return array(
		'methods'  => 'GET',
		'callback' => 'processPayment',
		'permission_callback' => '__return_true',
		'args' => array(
			'OrderNumber' => array('require' => true),
			'Amount' => array('require' => true),
			'AuthorizationCode' => array('require' => true),
			'DateTime' => array('require' => true),
			'ResponseCode' => array('require' => true),
			'IsoCode' => array('require' => true),
			'ResponseMessage' => array('require' => true),
			'ErrorDescription' => array('require' => true),
			'RRN' => array('require' => true)
		)
	);
}

function retrivePaymentInfo()
{
	return array(
		'methods'  => 'POST',
		'callback' => 'azullRetrivePaymentIntentInfos',
		'permission_callback' => '__return_true',
		'args' => array(
			'paymentId' => array('require' => true)
		)
	);
}


function create_form(WP_REST_Request $request)
{
	$response = null;
	try {
		$data = new AzulPayment();
		$data->price = intval(esc_sql($request->get_param('amount')));
		$data->currency = $request->get_param('currency') ? esc_sql($request->get_param('currency')) : null;
		$data->eventId = intval(esc_sql($request->get_param('eventId')));
		$data->invoiceId = esc_sql($request->get_param('invoiceId'));
		$data->refundPolicy = intval(esc_sql($request->get_param('refundPolicy')));
		$data->email = esc_sql($request->get_param('confEmail'));
		$data->emails = esc_sql($request->get_param('emails'));
		$data->extraFields = esc_sql($request->get_param('extraFields'));
		$data->qtyTickets = intval(esc_sql($request->get_param('eventQty')));
		$data->selectSponsor = esc_sql($request->get_param('selectSponsor'));
		$data->approveUrl = sanitize_text_field($request->get_param('approveUrl'));
		$data->cancelUrl = sanitize_text_field($request->get_param('cancelUrl'));
		$data->declineUrl = sanitize_text_field($request->get_param('declineUrl'));
		$data->productImageUrl = sanitize_text_field(get_field('azul_product_image', $data->eventId));

		$data->amount = ($data->qtyTickets * $data->price);

		$azulResponse = new StdClass();
		$azulGate = new AzulPaymentGateWay(AZUL_MERCHANT_ID, AZUL_MERCHANT_NAME, AZUL_MERCHANT_TYPE, AZUL_PRIVATE_KEY, AZUL_MODE);
		$match = TransactionBucket::findByInvoiceId($data->invoiceId);
		if ($match) {
			throw new Exception("INVOICE_ID_ALREADY_EXIST");
		}
		$bucketId = $azulGate->processPayment($data);
		$bucket = TransactionBucket::findById($bucketId);
		$form = $azulGate->form($bucket);


		$azulResponse->data = $form;

		if (isset($azulResponse->error)) {
			$response = new WP_REST_Response(array("status" => "API_ERROR", "errorInfo" => $azulResponse->error));
			$response->set_status(500);
		} else {
			$response = new WP_REST_Response(array("status" => true, "data" => $azulResponse->data));
			$response->set_status(200);
		}
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNKNOWN", "errorInfo" => $e->getMessage()));
		$response->set_status(500);
	}

	return $response;
}

function processPayment(WP_REST_Request $request)
{

	try {
		$payment = new AzulPaymentResponse();
		$payment->OrderNumber = esc_sql($request->get_param('OrderNumber'));
		$payment->Amount = esc_sql($request->get_param('Amount'));
		$payment->Itbis = esc_sql($request->get_param('Itbis'));
		$payment->AuthorizationCode = esc_sql($request->get_param('AuthorizationCode'));
		$payment->DateTime = esc_sql($request->get_param('DateTime'));
		$payment->ResponseCode = esc_sql($request->get_param('ResponseCode'));
		$payment->IsoCode = esc_sql($request->get_param('IsoCode'));
		$payment->ResponseMessage = esc_sql($request->get_param('ResponseMessage'));
		$payment->ErrorDescription = esc_sql($request->get_param('ErrorDescription'));
		$payment->RRN = esc_sql($request->get_param('RRN'));
		$payment->AuthHash = esc_sql($request->get_param('AuthHash'));
		$payment->CustomOrderId = esc_sql($request->get_param('CustomOrderId'));
		$payment->CardNumber = esc_sql($request->get_param('CardNumber'));
		$payment->DataVaultToken = esc_sql($request->get_param('DataVaultToken'));
		$payment->DataVaultExpiration = esc_sql($request->get_param('DataVaultExpiration'));
		$payment->DataVaultBrand = esc_sql($request->get_param('DataVaultBrand'));
		$payment->AzulOrderId = esc_sql($request->get_param('AzulOrderId'));

		// Get bucket
		$bucket = TransactionBucket::findByInvoiceId($payment->OrderNumber);

		// TEMPORAL
		try {
			$bucketEncoded = json_encode($bucket);
			log_error("AZUL_processPayment_BUCKERINFO {$bucketEncoded}");
		} catch (\Throwable $th) {
		}
		// END TEMPORAL
		if (!$bucket) {
			// retry
			sleep(2);
			$bucket = TransactionBucket::findByInvoiceId($payment->OrderNumber);
			if (!$bucket) {
				throw new Exception('BUCKET_NOT_FOUND');
			}
		}

		if (!$bucket->data) {
			throw new Exception('BUCKET_EMPTY');
		}

		if ($bucket->status !== 'PENDING') {
			throw new Exception('BUCKET_INVALID_STATUS');
		}


		// Get bucket data object
		$bucketData = unserialize($bucket->data);

		try {
			$azulGate = new AzulPaymentGateWay(AZUL_MERCHANT_ID, AZUL_MERCHANT_NAME, AZUL_MERCHANT_TYPE, AZUL_PRIVATE_KEY, AZUL_MODE);
			$azulPayment = $azulGate->verifyPayment($payment);
			if ($azulPayment->hasError) {
				throw new Exception($azulPayment->error);
			}


			// Save payment info
			TransactionBucket::addPaymentData($bucket->id, $payment);
			// Get amount
			$amount = $azulGate->getAmount($bucketData->amount);

			// Verify expected amount
			if ($amount !== $payment->Amount) {
				TransactionBucket::setRejected($bucket->id);
				throw new Exception('MISMATCH_AMOUNT');
			}
			if ($payment->IsoCode !== '00') {
				TransactionBucket::setRejected($bucket->id);
				throw new Exception("TRANSACTION_NOT_APPROVED");
			}

			// Add payment ID
			TransactionBucket::addPaymentID($bucket->id, $payment->AzulOrderId);
			// Update bucket status
			TransactionBucket::setComplete($bucket->id);

			// Redirect with approval status
			$url = add_query_arg('approved', 'true', $bucketData->approveUrl);
			$url = add_query_arg('bucketRef', $bucketData->invoiceId, $url);
			$url = add_query_arg('payment_intent_client_secret', $bucketData->invoiceId, $url);
			$url = add_query_arg('payment_intent', $payment->AzulOrderId, $url);
			$url = add_query_arg('payment_RNN', $payment->RRN, $url);
			wp_redirect($url);
			exit();
		} catch (Exception $e) {
			$url = add_query_arg('rejected', 'true', $bucketData->declineUrl);
			$url = add_query_arg('error', $e, $url);
			wp_redirect($bucketData->declineUrl);
		}
	} catch (Exception $e) {
		echo ("AZUL_processPayment: Hemos recibido su pago, pero hubo un error enviado el correo, favor solicitar reenvio mediante ayuda@worldcrowns.com o al telefono de soporte.");
		log_full_url("AZUL_processPayment:");
		error_log($e, 0);
		die;
	} finally {
		log_full_url("AZUL_processPayment:");
	}
}

function azullRetrivePaymentIntentInfos(WP_REST_Request $request)
{
	$response = null;
	try {
		$paymentId = esc_sql($request->get_param('paymentId'));
		$bucket = TransactionBucket::findByPaymentId($paymentId);
		$bucketData = unserialize($bucket->data);
		$bucketPaymentData = unserialize($bucket->paymentData);

		if (!$bucketData) {
			throw new Exception("INVALID_PAYMENT_REF");
		}
		$paymentContent = $bucketData;
		$paymentContent->id = $bucket->id;
		$paymentContent->status = $bucketPaymentData->IsoCode == '00' ? "succeeded" : $bucketPaymentData->ResponseMessage;
		$paymentContent->createAt = $bucket->createAt;

		$paymentContent->metadata = unserialize($bucket->data);
		$paymentContent->metadata->eventQty = $paymentContent->metadata->qtyTickets;
		$paymentContent->charges = array(
			"data" => $bucketPaymentData
		);
		$response = new WP_REST_Response(array("status" => true, "data" => $paymentContent));

		$response->set_status(200);
	} catch (Exception $e) {
		$response = new WP_REST_Response(array("status" => "UNKNOWN", "errorInfo" => $e->getMessage()));
		$response->set_status(500);
	}
	return $response;
}

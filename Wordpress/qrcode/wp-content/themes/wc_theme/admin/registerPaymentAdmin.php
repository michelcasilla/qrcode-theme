<?php
include_once(TEMPLATEPATH . "/admin/WcAppApiConnect.php");

function registerPaymentAdmin(WP_REST_Request $request) {
    global $wpdb;
    
    $postID = $request->get_param("eventId"); //get_post($eventPayment->post_id);
    $paymentCatch = $request->get_param("catchID"); //get_post($eventPayment->post_id);
    $currency = $request->get_param("currency"); //get_post($eventPayment->post_id);
    $post = get_post($postID);
    
    $eventPayment = new EventPayment();
    $eventPayment->invoice_id 						= esc_sql($request->get_param("invoice_id"));
    $eventPayment->description 						= get_field("event_title", $postID);
    $eventPayment->create_time 						= current_time( 'mysql' );
    $eventPayment->update_time 						= current_time( 'mysql' );
    $eventPayment->status 							= "ADMIN_COMPLETED";
    $eventPayment->amoun_currency_code 				= $currency;
    $eventPayment->amoun_currency_value 			= get_field('event_price', $postID);
    $eventPayment->email_address 					= esc_sql($request->get_param("email"));
    $eventPayment->merchant_id 						= "WCTVADMIN";
    $eventPayment->payee_email_address 				= esc_sql($request->get_param("email"));
    $eventPayment->payee_merchant_id 				= "WCTVADMIN";
    $eventPayment->payments_amount 					= get_field('event_price', $postID); // "0.00";
    $eventPayment->payments_curency 				= $currency;
    $eventPayment->payments_id 						= esc_sql($request->get_param("paymentref"));
    $eventPayment->payments_invoice_id 				= esc_sql($request->get_param("invoiceID"));
    $eventPayment->payment_status 					= "ADMIN_COMPLETED";
    $eventPayment->payment_update_time 				= current_time( 'mysql' );
    $eventPayment->shipping_address_address_line_1 	= "N/A";
    $eventPayment->shipping_address_admin_area_1 	= "N/A";
    $eventPayment->shipping_address_admin_area_2 	= "N/A";
    $eventPayment->shipping_address_country_code 	= "N/A";
    $eventPayment->shipping_address_postal_code 	= "N/A";
    $eventPayment->shipping_name_full_name 			= "N/A";
    $eventPayment->payment_contract 				= esc_sql($request->get_param("paypload"));
    $eventPayment->payment_contract_hash 			= md5($request->get_param("payment_contract_hash"));
    $eventPayment->created_at 						= current_time( 'mysql' );
    $eventPayment->sponsor 							= esc_sql($request->get_param("sponsor"));
    $eventPayment->paypal_order_id 					= esc_sql($request->get_param("paymentref"));
    $eventPayment->post_id 							= $postID;
    $eventPayment->post_snap 						= serialize(get_post($postID));
    $eventPayment->ticket_email 					= esc_sql($request->get_param("email"));
    $eventPayment->payee_name 						= esc_sql($request->get_param("payee_name"));
    $eventPayment->ibo 						        = esc_sql($request->get_param("ibo"));
    $apply_for_free                                 = esc_sql($request->get_param("apply_for_free")) || false;
    $eventPayment->apply_for_free                     = $apply_for_free ? 1 : 0;
    
    // catch payment
    $paymentRefResult = EventPayment_List::getPaymentByPaymentRef($eventPayment->payments_id);
	if(count($paymentRefResult) == 0){
        unset($eventPayment->ticketNames);
        unset($eventPayment->eventPrice);
        unset($eventPayment->eventQty);
        $status = $wpdb->insert("{$wpdb->prefix}event_payment", (array) $eventPayment, null);
        if($status){
            $id = $wpdb->insert_id;
            $eventPayment->event_ticket = getTicketNumber($eventPayment);
            Notification::sendRegisterPaymentNotificationEmail($eventPayment);
            $tCodeID = TicketCode::insertTicketCodeFromPayment($id, $eventPayment);
            if($paymentCatch){ EventPaymentCatch::addPaymentId($paymentCatch, $id); }

            $response = new WP_REST_Response(array(
                "status" => $status,
                "id" => $id,
                "msg" => $eventPayment,
                "tCodeId" => $tCodeID
            ));
            $response->set_status($status ? 200 : 500);
            WCAppApiConnect::logFromEventPayment($eventPayment);
        }else{
            EventPaymentCatch::addLog($paymentCatch, $wpdb->last_error);
            $response = new WP_REST_Response(array("status" => "UNABLE_TO_SAVE_PAYMENT" ));
            $response->set_status(500);
        }
    }else{
		if($paymentCatch){ EventPaymentCatch::addLog($paymentCatch, "PAYMENT_REF_EXIST"); }
		$response = new WP_REST_Response(array("status" => "PAYMENT_REF_EXIST" ));
		$response->set_status(500);
	}

  return $response;
}
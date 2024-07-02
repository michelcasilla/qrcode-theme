<?php

class RegisterApi
{
    public $registerApiParams = array(
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
        )
    );
}

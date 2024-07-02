<?php

include_once("EventPayment.php");
include_once("tables/EventPaymentListTable.php");
include_once("notification.class.php");
require_once('azul/transactionHold.db.php');
require_once("page/showEventPayments.php");
require_once("page/bucketTransactions.php");
require_once("page/configurations.php");
require_once("page/reports.php");
require_once("page/expenses_page.php");

// Register settings using the Settings API 
$isAdmin = current_user_can('administrator');
$isSuperUser = current_user_can('wc_super_user');
$isWcPaymentAdmin = current_user_can('wc_payments_admin');

$eventPaymentInstance;

add_action('admin_menu', 'news_admin_test');

function news_admin_test() {

    $parent_slug = 'paymentlist';

     $hook = add_menu_page( 
        __('Payments Group'), 
        __('Codes'), 
        'payment_list', 
        $parent_slug, 
        'showEventPayments'
    );

    add_submenu_page(
        $parent_slug,
        __('Payments'),
        __('Payments'),
        'payment_list',
        $parent_slug,
        'showEventPayments'
    );
   

    add_submenu_page(
        $parent_slug,
        __('Buckets'),
        __('Buckets'),
        'payment_list',
        'buckets-page',
        'bucketTransactions'
    );
   
    add_submenu_page(
        $parent_slug,
        __('Configurations'),
        __('Configurations'),
        'payment_list',
        'configuration-page',
        'configurations'
    );
    
    add_submenu_page(
        $parent_slug,
        __('Reports'),
        __('Reports'),
        'payment_list',
        'reports-page',
        'reports_page'
    );

    add_submenu_page(
        $parent_slug,
        __('Expenses'),
        __('Expenses'),
        'payment_list',
        'expenses-page',
        'expenses_page'
    );

    add_action( "load-$hook", "init_list_table" );
}


function init_list_table() {
    $eventPaymentInstance = new EventPayment_List();
}
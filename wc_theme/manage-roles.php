<?php

// Remove roles if they already exist to avoid duplication
remove_role('WC_redem');
remove_role('WC_Payments');
remove_role('WC_superuser');

// Add WC_redem role with capabilities
add_role('WC_redem', 'WC Redem Tickets', array(
    'read' => true,  // Needed to access the admin area
    'wc_redem_tickets' => true,
    'level_1' => true,
));

$role = get_role('WC_redem');
if ($role) {
    $role->add_cap('redem_page', true);
    $role->add_cap('wc_subscriptions', true);
}

// Add WC_Payments role with capabilities
add_role('WC_Payments', 'WC Payments', array(
    'read' => true,  // Needed to access the admin area
    'wc_payments_admin' => true,
));

$role = get_role('WC_Payments');
if ($role) {
    $role->add_cap('payment_list', true);
    $role->add_cap('wc_subscriptions', true);
}

// Add WC_superuser role with capabilities
add_role('WC_superuser', 'WC Super User', array(
    'read' => true,  // Needed to access the admin area
));

$role = get_role('WC_superuser');
if ($role) {
    $role->add_cap('payment_list', true);
    $role->add_cap('redem_page', true);
    $role->add_cap('wc_subscriptions', true);
}

// Add capabilities to the administrator role
$role = get_role('administrator');
if ($role) {
    $role->add_cap('payment_list', true);
    $role->add_cap('redem_page', true);
    $role->add_cap('wc_subscriptions', true);
}

<?php
function my_enqueue_scripts()
{
    // PHP variable to pass to JavaScript
    $version = '1.1.0'; // Change this to your version number
    $showScriptInFooter = false;
    $googleTagManager = GOOGLE_TAG_MANAGER;

    // externals styles
    wp_enqueue_style('google-fonts-comfortaa', 'https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('google-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), null);
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), '5.1.3', 'all');
    wp_enqueue_style('bootstrap-icons', '//cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css', array(), '1.8.1', 'all');
    wp_enqueue_style('custom-style', load_static_theme_file('/assets/css/style.css'), array(), $version, 'all');
    wp_enqueue_style('custom-queries', load_static_theme_file('/assets/css/queries.css'), array(), $version, 'all');


    // Enqueue external scripts
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array(), '5.1.3', $showScriptInFooter);
    wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, $showScriptInFooter);
    wp_enqueue_script('google-analytics', "https://www.googletagmanager.com/gtag/js?id=$googleTagManager", array(), null, true);

    // Enqueue JavaScript files
    wp_enqueue_script('moment', load_static_theme_file('/assets/js/moment.js'), array(), $version, $showScriptInFooter);
    wp_enqueue_script('encode-decode-url-params', load_static_theme_file('/assets/js/encodDecodeUrlParams.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('azul-payment-gate', load_static_theme_file('/assets/js/azulPaymentGate.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('single-content-payment', load_static_theme_file('/assets/js/single-content-payment.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('single-content-info', load_static_theme_file('/assets/js/single-content-info.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('draw-voucher', load_static_theme_file('/assets/js/draw-voucher.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('stripe-payment', load_static_theme_file('/assets/js/StripePayment.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('paypal-payment-gate', load_static_theme_file('/assets/js/paypal-payment-gate.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('loading-handler', load_static_theme_file('/assets/js/loadingHandler.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('check-sold-out', load_static_theme_file('/assets/js/check-sould-out.js'), array('moment'), $version, $showScriptInFooter);
    wp_enqueue_script('check-antyfraud', load_static_theme_file('/assets/js/check-antyfraud.js'), array('moment'), $version, $showScriptInFooter);

    // Localize PHP variables to a JavaScript object
    wp_localize_script('stripe-payment', 'wc_vars', array('STRIPE_PUBLISHABLE_KEY' => STRIPE_PUBLISHABLE_KEY));
}

add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

<?php

/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */
// include_once("admin.php");
include_once(TEMPLATEPATH . "/admin/EventPayment.php");
include_once(TEMPLATEPATH . "/admin/tables/EventPaymentListTable.php");

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_VERSION', '2.2.0');

if (!isset($content_width)) {
	$content_width = 800; // Pixels.
}

if (!function_exists('hello_elementor_setup')) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup()
	{
		$hook_result = apply_filters_deprecated('elementor_hello_theme_load_textdomain', [true], '2.0', 'hello_elementor_load_textdomain');
		if (apply_filters('hello_elementor_load_textdomain', $hook_result)) {
			load_theme_textdomain('hello-elementor', get_template_directory() . '/languages');
		}

		$hook_result = apply_filters_deprecated('elementor_hello_theme_register_menus', [true], '2.0', 'hello_elementor_register_menus');
		if (apply_filters('hello_elementor_register_menus', $hook_result)) {
			register_nav_menus(array('menu-1' => __('Primary', 'hello-elementor')));
		}

		$hook_result = apply_filters_deprecated('elementor_hello_theme_add_theme_support', [true], '2.0', 'hello_elementor_add_theme_support');
		if (apply_filters('hello_elementor_add_theme_support', $hook_result)) {
			add_theme_support('post-thumbnails');
			add_theme_support('automatic-feed-links');
			add_theme_support('title-tag');
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				)
			);

			/*
			 * Editor Style.
			 */
			add_editor_style('editor-style.css');

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated('elementor_hello_theme_add_woocommerce_support', [true], '2.0', 'hello_elementor_add_woocommerce_support');
			if (apply_filters('hello_elementor_add_woocommerce_support', $hook_result)) {
				// WooCommerce in general.
				add_theme_support('woocommerce');
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support('wc-product-gallery-zoom');
				// lightbox.
				add_theme_support('wc-product-gallery-lightbox');
				// swipe.
				add_theme_support('wc-product-gallery-slider');
			}
		}
	}
}
add_action('after_setup_theme', 'hello_elementor_setup');

if (!function_exists('hello_elementor_scripts_styles')) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles()
	{
		$enqueue_basic_style = apply_filters_deprecated('elementor_hello_theme_enqueue_style', [true], '2.0', 'hello_elementor_enqueue_style');
		$min_suffix          = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		if (apply_filters('hello_elementor_enqueue_style', $enqueue_basic_style)) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if (apply_filters('hello_elementor_enqueue_theme_style', true)) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action('wp_enqueue_scripts', 'hello_elementor_scripts_styles');

if (!function_exists('hello_elementor_register_elementor_locations')) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations($elementor_theme_manager)
	{
		$hook_result = apply_filters_deprecated('elementor_hello_theme_register_elementor_locations', [true], '2.0', 'hello_elementor_register_elementor_locations');
		if (apply_filters('hello_elementor_register_elementor_locations', $hook_result)) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action('elementor/theme/register_locations', 'hello_elementor_register_elementor_locations');

if (!function_exists('hello_elementor_content_width')) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width()
	{
		$GLOBALS['content_width'] = apply_filters('hello_elementor_content_width', 800);
	}
}
add_action('after_setup_theme', 'hello_elementor_content_width', 0);

if (is_admin()) {
	require get_template_directory() . '/includes/admin-functions.php';
}

if (!function_exists('hello_elementor_check_hide_title')) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title($val)
	{
		if (defined('ELEMENTOR_VERSION')) {
			$current_doc = \Elementor\Plugin::instance()->documents->get(get_the_ID());
			if ($current_doc && 'yes' === $current_doc->get_settings('hide_title')) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter('hello_elementor_page_title', 'hello_elementor_check_hide_title');

/**
 * Wrapper function to deal with backwards compatibility.
 */
if (!function_exists('hello_elementor_body_open')) {
	function hello_elementor_body_open()
	{
		if (function_exists('wp_body_open')) {
			wp_body_open();
		} else {
			do_action('wp_body_open');
		}
	}
}

if (!function_exists('full_url')) {
	function full_url()
	{
		global $wp;
		$queryString = '';
		if (isset($_SERVER['QUERY_STRING'])) {
			$queryString = $_SERVER['QUERY_STRING'];
		}
		return home_url($wp->request) . "?" . $queryString;
	}
}

if (!function_exists('query_url_content')) {
	function query_url_content()
	{
		$response =  new StdClass();
		if (isset($_GET['content'])) {
			$queryString = $_GET['content'];
			$decode = base64_decode($queryString);
			$response = json_decode($decode);
		}
		return $response;
	}
}

if (!function_exists('log_full_url')) {
	function log_full_url($subfix = '')
	{
		global $wp;
		$__url = full_url();
		error_log($subfix . $__url);
	}
}


/***
 *
 * CUSTOMS FUNCTIONS AND MENUS
 */


function htmlContentType()
{
	return "text/html";
}
add_filter('wp_mail_content_type', 'htmlContentType');


include_once("admin/payments.php");
include_once("admin/list.php");
include_once("admin/redeem.php");
include_once("admin/subscriptions.php");


// CUSTOMS

//add code to use ajax
function folder_contents()
{
	$folderPath = $_POST['path'];
	$files = array_diff(scandir($folderPath), array('.', '..'));
	print_r($files);
	return $files;

	die();
}

add_action('wp_ajax_folder_contents', 'folder_contents');

#add_shortcode('field', 'shortcode_field');
#
#function shortcode_field($atts){
#     extract(shortcode_atts(array(
#                  'post_id' => NULL,
#               ), $atts));
#  if(!isset($atts[0])) return;
#       $field = esc_attr($atts[0]);
#       global $post;
#       $post_id = (NULL === $post_id) ? $post->ID : $post_id;
#       return get_post_meta($post_id, $field, true);
#}
add_filter('acf/format_value/type=textarea', 'do_shortcode');

require_once('manage-roles.php');

if (!function_exists('load_static_theme_file')) {
	function load_static_theme_file($name = '')
	{
		if ($name) {
			return get_template_directory_uri() . "{$name}";
		}
	}
}

if (!function_exists('get_post_country_image')) {
	function get_post_country_image($country = '', $class = 'image--flag')
	{
		$image = '';
		if ($country == "DO") {
			$image = '/assets/images/flag-do.svg';
		} elseif ($country == "US") {
			$image = '/assets/images/flag-eng.svg';
		}
		$path = load_static_theme_file($image);
		echo "<img class='{$class}' src='{$path}'>";
	}
}


function custom_event_query_vars($qvars)
{
	$qvars[] = 'payment_step';
	$qvars[] = 'name';
	$qvars[] = 'email';
	$qvars[] = 'email_confirmation';
	$qvars[] = 'sponsor';
	$qvars[] = 'accept_terms_of_service';
	return $qvars;
}
add_filter('query_vars', 'custom_event_query_vars');

// $admin_role = get_role( 'administrator' );
// $default_caps = ( new WP_User )->get_role_caps();
// update_option( 'role_caps', array( 'administrator' => $default_caps ) );


if (!function_exists('show_buy_after_sold_out_msg')) {
	function show_buy_after_sold_out_msg()
	{
		$buyAfterSoldOut = get_field("enable_buy_after_soldout") ?? false;
		if ($buyAfterSoldOut) {
			get_template_part('template-parts/after_soldout_msg');
		}
	}
}

if (!function_exists('show_sold_out_veil')) {
	function show_sold_out_veil()
	{
		$buyAfterSoldOut = get_field("enable_buy_after_soldout") ?? false;
		if (false == $buyAfterSoldOut) {
			get_template_part('template-parts/soldout');
		}
	}
}


if (!function_exists('render_iframe_from_url')) {
	function render_iframe_from_url($url)
	{
		if (!empty($url)) {
			if (stripos($url, '<iframe') !== false) {
				echo $url;
			} else if (filter_var($url, FILTER_VALIDATE_URL)) {
				echo '<iframe class="map-frame" style="width: 100%;min-height: 300px !important;" src="' . $url . '"></iframe>';
			} else {
				echo '<address class="address-frame">' . $url . '</address>';
			}
		}
	}
}

if (!function_exists('get_event_currency')) {
	function get_event_currency($id)
	{
		$country = get_field('country', $id);
		$currency = 'RD$';
		if ($country !== 'DO') {
			$currency = 'USD';
		}
		return $currency;
	}
}


if (!function_exists('is_leadership_program')) {
	function is_leadership_program()
	{
		$post_id = get_the_ID();  // Get the ID of the current post
		$category_name = 'leadership-program';
		$state = false;
		if (has_category($category_name, $post_id)) {
			$state = true;
		}
		return $state;
	}
}

add_filter('wp_kses_allowed_html', 'acf_add_allowed_iframe_tag', 10, 2);
function acf_add_allowed_iframe_tag($tags, $context)
{
	if ($context === 'acf') {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
	}

	return $tags;
}

add_filter( 'acf/admin/prevent_escaped_html_notice', '__return_true' );


require get_template_directory() . '/enqueue-scripts.php';
<?php
/**
 * Hello Elementor admin functions.
 *
 * @package HelloElementor
 */

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @return void
 */
function hello_elementor_fail_load_admin_notice() {
	// Leave to Elementor Pro to manage this.
	if ( function_exists( 'elementor_pro_load_plugin' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	if ( 'true' === get_user_meta( get_current_user_id(), '_hello_elementor_install_notice', true ) ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	$installed_plugins = get_plugins();

	$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

	if ( $is_elementor_installed ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = __( 'Hello theme is a lightweight starter theme designed to work perfectly with Elementor Page Builder plugin.', 'hello-elementor' );

		$button_text = __( 'Activate Elementor', 'hello-elementor' );
		$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$message = __( 'Hello theme is a lightweight starter theme. We recommend you use it together with Elementor Page Builder plugin, they work perfectly together!', 'hello-elementor' );

		$button_text = __( 'Install Elementor', 'hello-elementor' );
		$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
	}

	?>
	<style>
		.notice.hello-elementor-notice {
			border-left-color: #9b0a46 !important;
			padding: 20px;
		}
		.rtl .notice.hello-elementor-notice {
			border-right-color: #9b0a46 !important;
		}
		.notice.hello-elementor-notice .hello-elementor-notice-inner {
			display: table;
			width: 100%;
		}
		.notice.hello-elementor-notice .hello-elementor-notice-inner .hello-elementor-notice-icon,
		.notice.hello-elementor-notice .hello-elementor-notice-inner .hello-elementor-notice-content,
		.notice.hello-elementor-notice .hello-elementor-notice-inner .hello-elementor-install-now {
			display: table-cell;
			vertical-align: middle;
		}
		.notice.hello-elementor-notice .hello-elementor-notice-icon {
			color: #9b0a46;
			font-size: 50px;
			width: 50px;
		}
		.notice.hello-elementor-notice .hello-elementor-notice-content {
			padding: 0 20px;
		}
		.notice.hello-elementor-notice p {
			padding: 0;
			margin: 0;
		}
		.notice.hello-elementor-notice h3 {
			margin: 0 0 5px;
		}
		.notice.hello-elementor-notice .hello-elementor-install-now {
			text-align: center;
		}
		.notice.hello-elementor-notice .hello-elementor-install-now .hello-elementor-install-button {
			padding: 5px 30px;
			height: auto;
			line-height: 20px;
			text-transform: capitalize;
		}
		.notice.hello-elementor-notice .hello-elementor-install-now .hello-elementor-install-button i {
			padding-right: 5px;
		}
		.rtl .notice.hello-elementor-notice .hello-elementor-install-now .hello-elementor-install-button i {
			padding-right: 0;
			padding-left: 5px;
		}
		.notice.hello-elementor-notice .hello-elementor-install-now .hello-elementor-install-button:active {
			transform: translateY(1px);
		}
		@media (max-width: 767px) {
			.notice.hello-elementor-notice {
				padding: 10px;
			}
			.notice.hello-elementor-notice .hello-elementor-notice-inner {
				display: block;
			}
			.notice.hello-elementor-notice .hello-elementor-notice-inner .hello-elementor-notice-content {
				display: block;
				padding: 0;
			}
			.notice.hello-elementor-notice .hello-elementor-notice-inner .hello-elementor-notice-icon,
			.notice.hello-elementor-notice .hello-elementor-notice-inner .hello-elementor-install-now {
				display: none;
			}
		}
	</style>
	<script>jQuery( function( $ ) {
			$( 'div.notice.hello-elementor-install-elementor' ).on( 'click', 'button.notice-dismiss', function( event ) {
				event.preventDefault();
				$.post( ajaxurl, {
					action: 'hello_elementor_set_admin_notice_viewed'
				} );
			} );
		} );</script>
	<div class="notice updated is-dismissible hello-elementor-notice hello-elementor-install-elementor">
		<div class="hello-elementor-notice-inner">
			<div class="hello-elementor-notice-icon">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/elementor-logo.png' ); ?>" alt="Elementor Logo" />
			</div>

			<div class="hello-elementor-notice-content">
				<h3><?php esc_html_e( 'Thanks for installing Hello Theme!', 'hello-elementor' ); ?></h3>
				<p>
					<p><?php echo esc_html( $message ); ?></p>
					<a href="https://go.elementor.com/hello-theme-learn/" target="_blank"><?php esc_html_e( 'Learn more about Elementor', 'hello-elementor' ); ?></a>
				</p>
			</div>

			<div class="hello-elementor-install-now">
				<a class="button button-primary hello-elementor-install-button" href="<?php echo esc_attr( $button_link ); ?>"><i class="dashicons dashicons-download"></i><?php echo esc_html( $button_text ); ?></a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Set Admin Notice Viewed.
 *
 * @return void
 */
function ajax_hello_elementor_set_admin_notice_viewed() {
	update_user_meta( get_current_user_id(), '_hello_elementor_install_notice', 'true' );
	die;
}

add_action( 'wp_ajax_hello_elementor_set_admin_notice_viewed', 'ajax_hello_elementor_set_admin_notice_viewed' );

if ( ! did_action( 'elementor/loaded' ) ) {
	add_action( 'admin_notices', 'hello_elementor_fail_load_admin_notice' );
}



/*
add_action('rest_api_init', function () {

	register_rest_route( 'wctvApi/v1', 'csv',array(
		'methods'  => 'GET',
		'callback' => 'csvD',
		'args' => array(
			'code' => array('require'=>true),
			'email' => array('require'=>true),
		)
	));

	register_rest_route( 'wctvApi/v1', 'tktList',array(
		'methods'  => 'GET',
		'callback' => 'tktList',
		'args' => array(
			'event_payment_id' => array('require'=>true)
		)
	));

  });

  function tktList(){
	$event_payment_id = esc_sql($_REQUEST['event_payment_id']);
	$ticks = TicketCode::getTicketsFromPaymentID((int) $event_payment_id);
	?>
	<h2>Payment for ticket <?=$event_payment_id?> <?=wp_get_current_user()?></h2>
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
				<table class="wp-list-table widefat fixed striped eventspayments">
				<thead>
					<tr>
						<th>ID</th>
						<th>PAYMENT ID</th>
						<th>Code</th>
						<th>Requested By</th>
						<th>CreatedAt</th>
						<th>Status</th>
					</tr>
				</thead>
					<tbody id="the-list">
					<?php foreach($ticks as $tk):?>
						<tr>
							<td><?=$tk->id?></td>
							<td><?=$tk->event_payment_id?></td>
							<td><?=$tk->code?></td>
							<td><?=$tk->requested_by?></td>
							<td><?=$tk->created_at?></td>
							<td><span style="
								background: #70db2a;
								color: #295d07;
								border-radius: 4px;
								display: inline-block;
								align-items: center;
								justify-content: center;
								padding: 1px 10px;
								font-size: 10px;
							"><?=$tk->status ? "ACTIVE" : "INACTIVE"?></span></td>
						</tr>
					<?php endforeach; ?>
					</tbody>	
				</table>
				</div>
			</div>
		</div>
    </div>
	<?php
  }

  function csvD(){
	
	global $wpdb;

	$eventID = esc_sql($_REQUEST['eventID']);
	$created_at = esc_sql($_REQUEST['created_at']);
	$end_at = esc_sql($_REQUEST['end_at']);
    $data = $wpdb->get_results("
		SELECT COUNT(post_id) as Qty, sponsor, post_id AS event_id, SUM(payments_amount) as earned_by_sponsor, payments_curency, status
		FROM {$wpdb->prefix}event_payment
		WHERE post_id = {$eventID}
		AND status = \"COMPLETED\"
		GROUP BY sponsor, payments_curency
		ORDER BY sponsor;
	","ARRAY_A");
	
	// AND created_at >= \"{$created_at} 00:00:00\"
    //AND created_at <= \"{$end_at} 23:59:59\"
    // if(is_admin()){
        //   $data = array(
        //       array('name' => 'A', 'mail' => 'a@gmail.com', 'age' => 43),
        //       array('name' => 'C', 'mail' => 'c@gmail.com', 'age' => 24),
        //       array('name' => 'B', 'mail' => 'b@gmail.com', 'age' => 35),
        //       array('name' => 'G', 'mail' => 'f@gmail.com', 'age' => 22),
        //       array('name' => 'F', 'mail' => 'd@gmail.com', 'age' => 52),
        //       array('name' => 'D', 'mail' => 'g@gmail.com', 'age' => 32),
        //       array('name' => 'E', 'mail' => 'e@gmail.com', 'age' => 34),
        //       array('name' => 'K', 'mail' => 'j@gmail.com', 'age' => 18),
        //       array('name' => 'L', 'mail' => 'h@gmail.com', 'age' => 25),
        //       array('name' => 'H', 'mail' => 'i@gmail.com', 'age' => 28),
        //       array('name' => 'J', 'mail' => 'j@gmail.com', 'age' => 53),
        //       array('name' => 'I', 'mail' => 'l@gmail.com', 'age' => 26),
        //   );
	  
		
	
      $fileName_1 = $eventID.'_report_by_sponsor.csv';
              header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
              header('Content-Description: File Transfer');
              header("Content-type: text/csv");
              header("Content-Disposition: attachment; filename={$fileName_1}");
              header("Expires: 0");
              header("Pragma: public");
              $fh1 = @fopen( 'php://output', 'w' );
              $headerDisplayed1 = false;
      
              foreach ( $data as $data1 ) {
                  // Add a header row if it hasn't been added yet
                  if ( !$headerDisplayed1 ) {
                      // Use the keys from $data as the titles
                      fputcsv($fh1, array_keys($data1));
                      $headerDisplayed1 = true;
                  }
      
                  // Put the data into the stream
                  fputcsv($fh1, $data1);
              }
          // Close the file
              fclose($fh1);
          // Make sure nothing else is sent, our file is done
              exit;
		//   }
		  
  }
*/
<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    get_template_part( 'template-parts/footer' );
}
?>

<?php wp_footer(); ?>
<script >
  !function(window, document) {

      window._atatusConfig = {
          apikey: '359c1d4aab344e8a9b3bc3507cc4d399',
          // Other options if needed
      };

      // Load AtatusJS asyc
      function _asyncAtatus(t){var e=document.createElement("script");e.type="text/javascript",e.async=!0,e.src="https://dmc1acwvwny3.cloudfront.net/atatus.js";var n=document.getElementsByTagName("script")[0];e.addEventListener&&e.addEventListener("load",(function(e){t(null,e)}),!1),n.parentNode.insertBefore(e,n)}

      _asyncAtatus(function() {
          // Any atatus related calls.
          if (window.atatus) {
              // window.atatus.setUser('unique_user_id', 'emailaddress@company.com', 'Full Name');
          }
      });

  }(window, document);
</script>
</body>
</html>

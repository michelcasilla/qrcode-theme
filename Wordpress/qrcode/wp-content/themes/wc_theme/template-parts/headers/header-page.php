<?php

/**
 * The template for displaying header.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$site_name = get_bloginfo('name');
$tagline   = get_bloginfo('description', 'display');
log_full_url("WC_THEME:");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script>
        function home_url(url) {
            return `<?= home_url() ?>${url}`;
        }
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', "<?= GOOGLE_TAG_MANAGER ?>");
    </script>
</head>

<body class="bg">
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid container-main">
                    <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                        <img class="navbar-brand--logo" src="<?= load_static_theme_file("/assets/images/logo.svg") ?>" alt="<?= $site_name ?>">
                    </a>
                    <div class="justify-content-end navbar-actions">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link nav-item--button" href="/program" style="padding: 0.425rem !important"><?= __("Programas", "sp") ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-item--button" href="/events" style="padding: 0.425rem !important"><?= __("Eventos", "sp") ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-item--button" href="/calendar" style="padding: 0.425rem !important"><?= __("Calendario", "sp") ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-item--button" href="/training" style="padding: 0.425rem !important"><?= __("Entrenamientos", "sp") ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-item--button" href="/wecare" style="padding: 0.425rem !important"><?= __("WeCare", "sp") ?></a>
                            </li>
                        </ul>
                        <?= do_shortcode('[language-switcher]') ?>
                        <!-- <a class="navbar-flag" href="#">
                            <img src="<?= load_static_theme_file("/assets/images/flag-eng.svg") ?>" alt="EN">
                            <span>EN</span>
                        </a> -->
                    </div>
                </div>
            </nav>
        </div>
    </header>
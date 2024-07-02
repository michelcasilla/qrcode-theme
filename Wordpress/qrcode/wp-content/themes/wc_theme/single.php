<?php
/**
 * /*
 * Template Name: Product Detail Template
 * Template Post Type: Post
 */

 get_template_part('template-parts/headers/header-page'); 
 ?>
<?php 
	$template = "template-parts/single-content";
	if(isset($_GET['payment_step'])){
		$step = $_GET['payment_step'];
		switch($step){
			case "INFO":
				$template = 'template-parts/single-content-info'; 
			break;
			case "PAY":
				$template = 'template-parts/single-content-pay'; 
			break;
			case "INVOICE":
				$template = 'template-parts/single-content-invoice'; 
			break;
			default:
			$template = 'template-parts/single-content'; 
			break;
		}
	}elseif(isset($_GET['t']) && isset($_GET['e'])){
		$template = 'template-parts/single-stream';
	}
?>
<?php get_template_part($template); ?>
<?php get_template_part('template-parts/footers/footer-page'); ?>
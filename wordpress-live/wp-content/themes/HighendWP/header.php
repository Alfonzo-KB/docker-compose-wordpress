<?php
/**
 * The header for our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * 
 * @package  Highend
 * @since    1.0.0
 */

?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">

<?php do_action( 'highend_before_page_wrapper' ); ?>

<div id="hb-wrap">

	<div id="main-wrapper" <?php highend_main_wrapper_class(); ?>>

		<?php do_action( 'highend_before_header' ); ?>
		<?php do_action( 'highend_header' ); ?>
		<?php do_action( 'highend_after_header' ); ?>

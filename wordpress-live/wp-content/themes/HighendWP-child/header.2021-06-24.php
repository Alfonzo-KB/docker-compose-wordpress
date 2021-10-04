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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<?php wp_head(); ?>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145663112-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-145663112-1');
  </script>
	<meta name="facebook-domain-verification" content="zj560qb68l3hwafcv223t8rf7uwz04" />
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '145004909363308');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=145004909363308&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->
</head>

<body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">

<?php do_action( 'highend_before_page_wrapper' ); ?>

<div id="hb-wrap">

	<div id="main-wrapper" <?php highend_main_wrapper_class(); ?>>

		<?php do_action( 'highend_before_header' ); ?>
		<?php do_action( 'highend_header' ); ?>
		<?php do_action( 'highend_after_header' ); ?>

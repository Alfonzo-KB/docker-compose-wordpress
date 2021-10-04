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
	<!-- Pinterest Pixel Code -->
	<script type="text/javascript">
	!function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(
	  Array.prototype.slice.call(arguments))};var
	  n=window.pintrk;n.queue=[],n.version="3.0";var
	  t=document.createElement("script");t.async=!0,t.src=e;var
	  r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
	pintrk('load', '2614247331577'​);
	pintrk('page');
	</script>
	<noscript>
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2614247331577&noscript=1" />
	</noscript>
	<script>
	pintrk('track', 'checkout', {
	 value: {{Enhanced Transaction Revenue}},
	 order_quantity: {{item.quantity}}
	});
	pintrk('track', 'AddToCart', {
	  value: {{Enhanced Transaction Revenue}},
	  order_quantity: {{item.quantity}},
	  currency: 'USD'
	});
	pintrk('track', 'pagevisit');
	pintrk('track', 'signup');
	pintrk('track', 'watchvideo', {
	  video_title: 'My Product Video 01'
	});
	pintrk('track', 'viewcategory');
	</script>
	<noscript>
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=123456789&event=checkout&ed[value]={{Enhanced Transaction Revenue}}&ed[order_quantity]={{item.quantity}}&noscript=1" />
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2614247331577&event=AddToCart&ed[value]={{Enhanced Transaction Revenue}}&ed[order_quantity]={{item.quantity}}&noscript=1" />
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2614247331577&event=pagevisit&noscript=1" />
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2614247331577&event=signup&noscript=1" />
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2614247331577&event=watchvideo&ed[video_title]=My+Product+Video+01&noscript=1" />
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2614247331577&event=viewcategory&noscript=1" />
	</noscript>
	<!-- End Pinterest Pixel Code -->
</head>

<body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">

<?php do_action( 'highend_before_page_wrapper' ); ?>

<div id="hb-wrap">

	<div id="main-wrapper" <?php highend_main_wrapper_class(); ?>>

		<?php do_action( 'highend_before_header' ); ?>
		<?php do_action( 'highend_header' ); ?>
		<?php do_action( 'highend_after_header' ); ?>

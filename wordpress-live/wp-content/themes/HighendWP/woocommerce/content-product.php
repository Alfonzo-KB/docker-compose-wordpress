<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

if ( is_cart() ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

if ( is_shop() || is_product_tag() || is_product_category() )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', highend_option('hb_woo_product_columns') );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
//$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
$classes[] = 'hb-woo-product';
$read_more = '';

if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

$sidebar_layout = vp_metabox('layout_settings.hb_page_layout_sidebar'); 
$sidebar_name = vp_metabox('layout_settings.hb_choose_sidebar');

if(isset($_REQUEST['layout']) && !empty($_REQUEST['layout'])) {
	$sidebar_layout = $_REQUEST['layout'];
} else {
	if ( is_single() ) { 
		$sidebar_layout = highend_option('hb_woo_sp_layout_sidebar');
		$sidebar_name = highend_option('hb_woo_sp_choose_sidebar');
	}
	else { 
		$sidebar_layout = highend_option('hb_woo_layout_sidebar');
		$sidebar_name = highend_option('hb_woo_choose_sidebar');
	}
}

$classes[] = 'hb-animate-element hb-top-to-bottom';
$classes[] = 'col-' . intval( 12 / $woocommerce_loop['columns'] );

apply_filters( 'highend_woocommerce_product_classes', $classes );
?>

<div <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">
	<div class="hb-woo-image-wrap">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );

			if ( has_post_thumbnail() ) {
				$image_id     = get_post_thumbnail_id();
				$shop_catalog = wc_get_image_size( 'shop_catalog' );
				
				$image = highend_resize( $image_id, $shop_catalog['width'], $shop_catalog['height'] );

				if ( $image ) {
					echo '<div class="woo-category-wrap"><img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '"></div>';
				}
			} else {
				echo '<img src="'. wc_placeholder_img_src() .'" alt="Placeholder" width="526" height="700" />';
			}

			$product_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );

			if ( ! empty( $product_gallery ) ) {
				
				$gallery = explode( ',', $product_gallery );

				if ( ! empty ( $gallery ) ) {

					$image_id        = $gallery[0];
					$shop_catalog    = wc_get_image_size( 'shop_catalog' );
					$image_src_hover = highend_resize( $image_id, $shop_catalog['width'], $shop_catalog['height'] );

					echo '<img src="' . $image_src_hover['url'] . '" alt="' . get_the_title() . '" class="product-hover-image" title="' . get_the_title() . '">';
				}
			}

			$postdate 		= get_the_time( 'Y-m-d' );			// Post date
			$postdatestamp 	= strtotime( $postdate );			// Timestamped post date
			$newness 		= 3; 	// Newness in days

			if ( $product->get_sale_price() ){
				// Sale will be added through action hook
			}
			else if ( hb_is_out_of_stock() ) {	
				echo '<span class="out-of-stock-badge">' . __( 'Sold out', 'hbthemes' ) . '</span>';
			} else if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) { // If the product was published within the newness time frame display the new badge
				echo '<span class="wc-new-badge">' . __( 'New', 'hbthemes' ) . '</span>';
			}
		?>
		<?php hb_get_star_rating(); ?>

		<?php echo '<span class="product-loading-icon"></span>'; ?>

		<?php
		$out_of_stock_class = ' add_to_cart_button';
		if ( hb_is_out_of_stock() ) {
			$out_of_stock_class = ' no-action';
		}
		if ( ! $product->is_in_stock() ) {
			$hb_add_to_cart = '<a href="'. apply_filters( 'out_of_stock_add_to_cart_url', get_permalink( $product->get_id() ) ).'" class="hb-buy-button'.$out_of_stock_class.'">'. apply_filters( 'out_of_stock_add_to_cart_text', __( 'READ MORE', 'hbthemes' ) ).'</a>';
			$out_of_stock_badge = '<span class="hb-out-stock">'.__( 'OUT OF STOCK', 'hbthemes' ).'</span>';
		}
		else { ?>

			<?php

			switch ( $product->get_type() ) {
			case "variable" :
				$link  = apply_filters( 'variable_add_to_cart_url', get_permalink( $product->get_id() ) );
				$label  = apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'hbthemes' ) );
				break;
			case "grouped" :
				$link  = apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->get_id() ) );
				$label  = apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'hbthemes' ) );
				break;
			case "external" :
				$link  = apply_filters( 'external_add_to_cart_url', get_permalink( $product->get_id() ) );
				$label  = apply_filters( 'external_add_to_cart_text', __( 'View Details', 'hbthemes' ) );
				break;
			default :
				$link  = apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
				$label  = apply_filters( 'add_to_cart_text', __( 'Add to Cart', 'hbthemes' ) );
				$read_more = '<a href="'. get_permalink( $product->get_id() ) .'" rel="nofollow" class="hb-more-details">' . __( 'View Details', 'hbthemes' ) . '</a>';
				break;
			}

			$hb_add_to_cart = '';
			do_action( 'woocommerce_after_shop_loop_item' );
		}
		?>

		<?php echo $hb_add_to_cart; ?>
		<?php echo $read_more; ?>
	</div>
	</a>

	<div class="hb-product-meta-wrapper clearfix">
	<div class="hb-product-meta">

		<div class="hb-woo-product-details">
			<a href="<?php the_permalink(); ?>"><?php do_action( 'woocommerce_shop_loop_item_title' ); ?></a>
			<?php
			$product_cat_terms = get_the_terms( $post->ID, 'product_cat' );
			$size = is_array( $product_cat_terms ) ? sizeof( $product_cat_terms ) : 0; ?>
			<div class="woo-cats"><?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="hb-woo-shop-cats">' . _n( '', '', $size, 'hbthemes' ) . ' ', '</span>' ); ?></div>
		</div>

		<?php
			if ( highend_option('hb_woo_enable_likes') ){
				echo hb_print_likes(get_the_ID());
			}
		?>

	</div>

	<?php
		/**
		 * woocommerce_after_shop_loop_item_title hook
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
	?>

</div>
</div>

<?php 
if ( !isset ( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = 2;
}

if ( $woocommerce_loop['loop'] % $woocommerce_loop['columns'] == 0 ) {
	echo '<div class="clear"></div>';
}
?>
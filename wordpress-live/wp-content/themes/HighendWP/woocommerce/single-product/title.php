<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

?>
<h3 itemprop="name" class="hb-heading">
	<span>
		<?php echo esc_html( get_the_title() ); ?>
	</span>
</h3>

<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
	<span class="product_meta sku_wrapper">
		<?php _e( 'SKU:', 'hbthemes' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? esc_html( $sku ) : __( 'n/a', 'hbthemes' ); ?></span>.
	</span>
<?php endif; ?>

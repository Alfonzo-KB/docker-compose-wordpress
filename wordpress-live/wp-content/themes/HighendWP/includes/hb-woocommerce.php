<?php
/**
* @package WordPress
* @subpackage Highend
*/

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/* REMOVE ACTIONS
================================================== */ 
if ( get_option( 'woocommerce_enable_lightbox' ) == 'yes' ){
    delete_option('woocommerce_enable_lightbox');
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

/* ADD ACTIONS
================================================== */ 
add_action( 'woocommerce_before_main_content', 'hb_woocommerce_output_content_wrapper', 10);
add_action( 'woocommerce_after_main_content', 'hb_woocommerce_output_content_wrapper_end', 10);
add_action( 'wp_footer', 'hb_woo_notifications' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

add_filter( 'woocommerce_cross_sells_columns', 'hb_change_cross_sells_columns' ); 
function hb_change_cross_sells_columns( $columns ) {
	return 2;
}

/* LOOP COUNT
================================================== */ 
add_filter('loop_shop_per_page', 'hb_loop_shop_per_page');
function hb_loop_shop_per_page(){
	global $data;

	$per_page = 12;
	$pc = 12;

	parse_str($_SERVER['QUERY_STRING'], $params);

	if( highend_option('hb_woo_count') ) {
		$per_page = highend_option('hb_woo_count');
	} else {
		$per_page = 12;
	}

	$pc = !empty($params['product_count']) ? $params['product_count'] : $per_page;

	return $pc;
}


/* RELATED PRODUCTS AND UPSELL PRODUCTS COUNT
================================================== */	
add_filter( 'woocommerce_output_related_products_args', 'hbthemes_related_products_args' );
function hbthemes_related_products_args( $args ) {
	$args['posts_per_page'] = 4; // 4 related products
	$args['columns'] = 4; // arranged in 4 columns
	return $args;
}


/* BEFORE MAIN CONTENT
================================================== */
function hb_woocommerce_output_content_wrapper() {
	global $post; 

	$sidebar_layout = vp_metabox('layout_settings.hb_page_layout_sidebar'); 
	$sidebar_name = vp_metabox('layout_settings.hb_choose_sidebar');

	if(isset($_REQUEST['layout']) && !empty($_REQUEST['layout'])) {
		$sidebar_layout = $_REQUEST['layout'];
		$sidebar_name = highend_option('hb_woo_choose_sidebar');
	} else {
		if ( is_product() ) { 
			$sidebar_layout = highend_option('hb_woo_sp_layout_sidebar');
			$sidebar_name = highend_option('hb_woo_sp_choose_sidebar');
		}
		else { 
			$sidebar_layout = highend_option('hb_woo_layout_sidebar');
			$sidebar_name = highend_option('hb_woo_choose_sidebar');
		}
	}

	?>
	
	<div id="main-content">
		<div class="container">
			<div class="row <?php echo $sidebar_layout; ?> main-row">
				<div id="page-<?php the_ID(); ?>" class="hb-woo-wrapper">

					<!-- BEGIN .hb-main-content -->
					<?php if ( $sidebar_layout != 'fullwidth' ) { ?>
					<div class="col-9 hb-equal-col-height hb-main-content">
					<?php } else { ?>
					<div class="col-12 hb-main-content">
					<?php } ?>
<?php }


/* AFTER MAIN CONTENT
================================================== */
function hb_woocommerce_output_content_wrapper_end() {
	global $post; 

	$sidebar_layout = vp_metabox('layout_settings.hb_page_layout_sidebar'); 
	$sidebar_name = vp_metabox('layout_settings.hb_choose_sidebar');

	if(isset($_REQUEST['layout']) && !empty($_REQUEST['layout'])) {
		$sidebar_layout = $_REQUEST['layout'];
		$sidebar_name = highend_option('hb_woo_choose_sidebar');
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

	?>
					</div>
					<?php if ( $sidebar_layout != 'fullwidth' ) { ?>
					<!-- BEGIN .hb-sidebar -->
					<div class="col-3  hb-equal-col-height hb-sidebar">
						<?php 
						if ( $sidebar_name && function_exists('dynamic_sidebar') )
							dynamic_sidebar($sidebar_name);
						?>
					</div>
					<!-- END .hb-sidebar -->
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php }



/* WOOCOMMERCE STYLES
================================================== */
function hb_woocommerce_styles(){

	if ( is_admin() || 'wp-login.php' == basename( $_SERVER['PHP_SELF'] ) ){
		return;
	}

	wp_enqueue_style( 
		'hb-woocommerce',
		get_template_directory_uri() . '/assets/css/woocommerce.css',
		false,
		false,
		'all'
	);
}
add_action( 'highend_enqueue_scripts', 'hb_woocommerce_styles' );



/* MISC FUNCTIONS
================================================== */
function woocommerce_template_loop_product_title() {
	echo '<h3 class="woocommerce-loop-product__title">' . get_the_title() . '</h3>';
}

function hb_is_out_of_stock() {
    global $post;
    $post_id = $post->ID;
    $stock_status = get_post_meta($post_id, '_stock_status',true);
    
    if ($stock_status == 'outofstock') {
    return true;
    } else {
    return false;
    }
}

function hb_get_star_rating(){
    global $woocommerce, $product;

    if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ){
    	return;
    } else {
    	$average = $product->get_average_rating();

    if ($average > 0){
	    echo '<div class="star-wrapper"><div class="star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'hbthemes' ).'</span></div></div>';
		}
	}
}

function hb_product_items_text($count) {
		
	$product_item_text = "";
	
	if ( $count > 1 ) {
    	$product_item_text = str_replace('%', number_format_i18n($count), __('% products', 'hbthemes'));
    } elseif ( $count == 0 ) {
    	$product_item_text = __('0 products', 'hbthemes');
    } else {
    	$product_item_text = __('1 product', 'hbthemes');
    }
    
    return $product_item_text;
    
}

function hb_addURLParameter($url, $paramName, $paramValue) {
	$url_data = parse_url($url);
	if(!isset($url_data["query"]))
		$url_data["query"]="";

	$params = array();
	parse_str($url_data['query'], $params);
	$params[$paramName] = $paramValue;
	$url_data['query'] = http_build_query($params);
	return hb_build_url($url_data);
}


function hb_build_url($url_data) {
	$url="";
	if(isset($url_data['host'])){
		$url .= $url_data['scheme'] . '://';
		if (isset($url_data['user'])) {
			$url .= $url_data['user'];
			if (isset($url_data['pass'])) {
				$url .= ':' . $url_data['pass'];
			}
			$url .= '@';
		}
	$url .= $url_data['host'];
	if (isset($url_data['port'])) {
		$url .= ':' . $url_data['port'];
	}
}
	if (isset($url_data['path'])) {
		$url .= $url_data['path'];
	}
	if (isset($url_data['query'])) {
		$url .= '?' . $url_data['query'];
	}
	if (isset($url_data['fragment'])) {
		$url .= '#' . $url_data['fragment'];
	}
	return $url;
}

if ( !function_exists('hb_woo_notifications') ) {
	function hb_woo_notifications(){
		if ( highend_option('hb_woo_notifications') ){
			global $woocommerce;
			if ( !isset($woocommerce) ) {
				return;
			}
			$checkout_url = wc_get_checkout_url();
			?><ul id="hb-woo-notif" data-text="<?php _e('added to cart.', 'hbthemes'); ?>" data-cart-text="<?php _e('Checkout', 'hbthemes'); ?>" data-cart-url="<?php echo $checkout_url; ?>"></ul><?php
		}
	}
}


/* CART DROPDOWN
================================================== */ 
if (!function_exists('hb_woo_cart')) {
    function hb_woo_cart() {
    
        $cart_output = "";
        
        // Check if WooCommerce is active
        if ( class_exists('Woocommerce') ) {
        
            global $woocommerce;
            
            $cart_total = $woocommerce->cart->get_cart_total();
            $cart_count = $woocommerce->cart->cart_contents_count;
            $cart_count_text = hb_product_items_text($cart_count);
            $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
            
            $cart_output .= '<div id="top-cart-widget" class="top-widget float-right">';

            $cart_output .= '<a href="'. wc_get_cart_url().'"><i class="hb-icon-cart"></i><span class="amount">'.$cart_total.'</span><i class="icon-angle-down"></i></a>';

            $cart_output .= '<div class="hb-dropdown-box cart-dropdown">';

            if ($cart_count == '0'){
                $cart_output .= '<div class="hb-cart-count empty">';
                $cart_output .= __('No products in the cart','hbthemes');
            } else {
                $cart_output .= '<div class="hb-cart-count">';
                $cart_output .= $cart_count_text . ' ' . __('in the cart.','hbthemes');
            }
            $cart_output .= '</div>'; 


            if ($cart_count != '0'){
                // PRINT EACH ITEM
                $cart_output .= '<div class="hb-cart-items">';
                
                foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) {
                
                    $bag_product = $cart_item['data']; 
                    $product_title = $bag_product->get_title();
                    $product_short_title = (strlen($product_title) > 25) ? substr($product_title, 0, 22) . '...' : $product_title;
                                                               
                    if ($bag_product->exists() && $cart_item['quantity']>0) {                                            
                        $cart_output .= '<div class="hb-item-product clearfix">';
                      	$cart_output .= '<figure class="item-figure"><a class="hb-item-product-img" href="'.get_permalink($cart_item['product_id']).'">'.$bag_product->get_image().'</a></figure>';                      
                        $cart_output .= '<div class="hb-item-product-details">';
                        $cart_output .= '<div class="hb-item-product-title"><a href="'.get_permalink($cart_item['product_id']).'">' . apply_filters('woocommerce_cart_widget_product_title', $product_short_title, $bag_product) . '</a></div>';
                        $cart_output .= '<div class="bag-product-price">'.$cart_item['quantity'].' x '.wc_price($bag_product->get_price()).'</div>';
                        $cart_output .= '</div>';
                        $cart_output .= apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __('Remove this item', 'hbthemes') ), $cart_item_key );
                        
                        $cart_output .= '</div>';
                	}
                }

                $cart_output .= '</div>';
        	}

            // CART BUTTONS
            $cart_output .= '<div class="hb-bag-buttons">';
                
            if ($cart_count != '0'){
            $cart_output .= '<a class="shop-button" href="'.esc_url( wc_get_cart_url() ).'">'. __('View shopping cart', 'hbthemes').'</a>';
            $cart_output .= '<a class="checkout-button" href="'. esc_url( wc_get_checkout_url() ).'">'.__('Proceed to checkout', 'hbthemes').'</a>';
        	} else {
        		$cart_output .= '<a class="checkout-button" href="'.esc_url( $shop_page_url ).'">'.__('Go to shop', 'hbthemes').'</a>';
        	}
                                
            $cart_output .= '</div>';


            $cart_output .= '</div>';

            $cart_output .= '</div>';
        
        }
        
        return $cart_output;
    }
}


/* AJAX RELOAD
================================================== */ 
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_cart_link');

function woocommerce_cart_link($fragments) {
    global $woocommerce;
    ob_start();

    $cart_total = $woocommerce->cart->get_cart_total();
	$cart_count = $woocommerce->cart->cart_contents_count;
	$cart_count_text = hb_product_items_text($cart_count);
	$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );?>
            
	<div id="top-cart-widget" class="top-widget float-right">
    <a href="<?php echo wc_get_cart_url(); ?>"><i class="hb-icon-cart"></i><span class="amount"><?php echo $cart_total; ?></span><i class="icon-angle-down"></i></a>

   <div class="hb-dropdown-box cart-dropdown">

	<?php if ($cart_count == '0'){ ?>
		<div class="hb-cart-count empty">
		<?php _e('No products in the cart','hbthemes');
	} else {?>
		<div class="hb-cart-count">
		<?php echo $cart_count_text . ' ' . __('in the cart.','hbthemes');
	} ?>
	</div>
	<?php if ($cart_count != '0'){ ?>
		<div class="hb-cart-items">
		<?php foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) {        
				$bag_product = $cart_item['data']; 
				$product_title = $bag_product->get_title();
				$product_short_title = (strlen($product_title) > 25) ? substr($product_title, 0, 22) . '...' : $product_title;
	                                                               
				if ($bag_product->exists() && $cart_item['quantity']>0) { ?>                                      
					<div class="hb-item-product clearfix">
					<figure class="item-figure"><a class="hb-item-product-img" href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo $bag_product->get_image(); ?></a></figure>
					<div class="hb-item-product-details">
					<div class="hb-item-product-title"><a href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo apply_filters('woocommerce_cart_widget_product_title', $product_short_title, $bag_product); ?></a></div>
					<div class="bag-product-price"><?php echo $cart_item['quantity'].' x '.wc_price($bag_product->get_price()); ?></div>
					</div>
					<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __('Remove this item', 'hbthemes') ), $cart_item_key ); ?>
	                        
					</div>
					<?php }
				} ?>

			</div>
        	<?php } ?>

           <div class="hb-bag-buttons">
                
            <?php if ($cart_count != '0'){ ?>
			<a class="shop-button" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php  _e('View shopping cart', 'hbthemes'); ?></a>
			<a class="checkout-button" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php _e('Proceed to checkout', 'hbthemes'); ?></a>
        	<?php } else {
        		echo '<a class="checkout-button" href="'.esc_url( $shop_page_url ).'">'.__('Go to shop', 'hbthemes').'</a>';
        	} ?>
                                
            </div>
		</div>

	</div>

    <?php
    $fragments['#top-cart-widget'] = ob_get_clean();
    return $fragments;
}

add_action( 'woocommerce_before_add_to_cart_form', 'highend_before_add_to_cart_form' );
function highend_before_add_to_cart_form() {
	echo '<div class="hb-separator"></div>';
}

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message' );
add_action( 'woocommerce_cart_is_empty', 'highend_empty_cart_message' );
function highend_empty_cart_message() {
	echo '<h4 class="hb-heading hb-center-heading cart-empty"><span>' . wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'hbthemes' ) ) ) . '</span></h4>';
}


add_action( 'woocommerce_before_shop_loop', 'highend_before_shop_loop_clear', 40 );
function highend_before_shop_loop_clear() {
	echo '<div class="clear"></div>';
}

add_action( 'woocommerce_product_review_comment_form_args', 'highend_review_form_args' );
function highend_review_form_args( $args ) {

	$args['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" tabindex="110" name="comment" cols="45" placeholder="'. __( 'Your review *', 'hbthemes' ) .'" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>';

	if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
		$args['comment_field'] .= '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'hbthemes' ) . '</label><select name="rating" id="rating" required>
			<option value="">' . esc_html__( 'Rate&hellip;', 'hbthemes' ) . '</option>
			<option value="5">' . esc_html__( 'Perfect', 'hbthemes' ) . '</option>
			<option value="4">' . esc_html__( 'Good', 'hbthemes' ) . '</option>
			<option value="3">' . esc_html__( 'Average', 'hbthemes' ) . '</option>
			<option value="2">' . esc_html__( 'Not that bad', 'hbthemes' ) . '</option>
			<option value="1">' . esc_html__( 'Very poor', 'hbthemes' ) . '</option>
		</select></div>';
	}

	

	return $args;
}

function highend_woocommerce_localize_vars( $localized ) {

	if ( ! is_null( WC()->cart ) ) {
		$localized['cart_url']   = wc_get_cart_url();
		$localized['cart_count'] = WC()->cart->get_cart_contents_count();
	}

	return $localized;
}
add_filter( 'highend_custom_js_localized', 'highend_woocommerce_localize_vars' );


/** Remove likes from duplicated products  */
function highend_woocommerce_duplicate_exclude_meta( $meta_keys ) {
	array_push( $meta_keys, '_likes' );
	return $meta_keys;
}
add_filter( 'woocommerce_duplicate_product_exclude_meta', 'highend_woocommerce_duplicate_exclude_meta' );

function highend_woocommerce_shop_id( $post_id ) {

	if ( function_exists( 'is_shop' ) && is_shop() && function_exists( 'wc_get_page_id' ) ){
		$post_id = wc_get_page_id( 'shop' );
	}

	return $post_id;
}
add_filter( 'highend_get_the_id', 'highend_woocommerce_shop_id' );

function highend_woocommerce_page_title( $title ) {

	$shop_page_id = wc_get_page_id( 'shop' );

	if ( vp_metabox( 'general_settings.hb_page_title_h1', null, $shop_page_id ) ) {
		return vp_metabox( 'general_settings.hb_page_title_h1', null, $shop_page_id );
	}

	return $title;
}
add_filter( 'woocommerce_page_title', 'highend_woocommerce_page_title' );

// Get the classes for the product cat div.
function highend_product_cat_class( $classes, $class, $category ) {

	$classes[] = 'hb-woo-product';

	$columns = wc_get_loop_prop( 'columns' );

	if ( is_product_tag() || is_product_category() ) {
		$columns = highend_option( 'hb_woo_product_columns' );
	}

	if ( empty( $columns ) ) {
		$columns = 4;
	}

	$columns = apply_filters( 'loop_shop_columns', $columns );

	$classes[] = 'col-' . ( 12 / $columns );

	return $classes;
}
add_filter( 'product_cat_class', 'highend_product_cat_class', 10, 3 );


/**
 * Show the subcategory title in the product loop.
 *
 * @param object $category Category object.
 */
function highend_template_loop_category_title( $category ) {
	?>
	<div class="woo-cat-details">
		<h6 class="special"><?php echo $category->name; ?></h6>
		
		<?php
		if ( $category->count > 0 ) {
			echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">' . $category->count . ' '. __("products", "hbthemes") . '</span>', $category );
		}
		?>

	</div>
	<?php
}
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title' );
add_action( 'woocommerce_shop_loop_subcategory_title', 'highend_template_loop_category_title' );

function highend_fix_woocommerce_rest_api_includes() {
    if ( function_exists( 'WC' ) && empty( WC()->cart ) ) {
        WC()->frontend_includes();
        wc_load_cart();
    }
}
add_action( 'rest_api_init', 'highend_fix_woocommerce_rest_api_includes' );

<?php
add_action('woocommerce_before_checkout_form', 'order_duplicate_check', 10);
add_action('woocommerce_before_cart', 'order_duplicate_check', 10);
function order_duplicate_check(){
	//warns user of duplicate items at checkout
	if ( ! is_user_logged_in() ) return;
	if (is_wholesale_customer() ) return;
	$current_user = wp_get_current_user();
	$cart = WC()->cart;
	$duped_product_list = array();
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
		$product = $cart_item['data'];
		$cart_product_id = $cart_item['product_id'];
		if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $cart_product_id ) ){
			array_push($duped_product_list, $product->name);
		}
	}
	if (count($duped_product_list) > 0 ){
		$single = false;
		if (count($duped_product_list) == 1){
			$single = true;
		}
		echo '<ul class="woocommerce-error" role="alert">';
		echo '<div class="duplicate-warning" style="color: #b81c23;">';
		echo '<b>WARNING:</b>  ';
		echo 'You\'ve purchased the following product';
		if (!$single){
			echo 's';
		}
		echo ' in the past.  If you purchase ';
		if (!$single){
			echo 'any of these items again, ';
		}else{
			echo 'this item again, ';
		}
		echo 'you may be ineligible for a refund.</div><br>';

		foreach( $duped_product_list as $duped_product ){
			echo '<li><b> - '.$duped_product.'</b></li>';
		}
		echo '</ul>';
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'user_logged_in_product_already_bought', 30 );
function user_logged_in_product_already_bought() {
	global $product;
	if ( ! is_user_logged_in() ) return;
	if (is_wholesale_customer() ) return;
	$current_user = wp_get_current_user();
	if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->get_id() ) ){
		echo '<div class="text-orange user-bought" style="text-align: center;">&#9733;Previously Purchased Item&#9733;</div>';
	}
}
add_action( 'woocommerce_before_add_to_cart_form', 'previous_purchase_storefront', 30 );
function previous_purchase_storefront(){
	global $product;
	if ( ! is_user_logged_in() ) return;
	if (is_wholesale_customer() ) return;
	$current_user = wp_get_current_user();
	if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->get_id() ) ){
		echo '<div class="text-orange user-bought" style="text-align: center;">';
		echo '&#9733; Previously Purchased Item &#9733;';
		echo '</div><br>';
	}
}
?>

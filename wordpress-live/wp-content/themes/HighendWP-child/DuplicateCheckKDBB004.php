<?php
add_filter('woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
add_action('woocommerce_before_checkout_form', 'kdbb004_duplicate_check', 10);
add_action('woocommerce_applied_coupon', 'kdbb004_duplicate_check', 10);

function rename_coupon_label( $err, $err_code=null, $something=null ){
	$err = str_ireplace("Coupon code applied successfully.","The coupon code you entered is not applicable to this order.",$err);
	return $err;
}

include_once("coupons.php");

function no_message_in_cart(){
	//hide the coupon messages on the cart page.
	$notices = WC()->session->get('wc_notices', array());
	unset( $notices );
	WC()->session->set('wc_notices', $notices);
}

function double_in_cart($quantities, $targeted_id){
	if ($quantities[$targeted_id] >= 2){
		return true;
	}
	return false;
}

function remove_1kdbb004(){
	if(in_array('1kdbb004', WC()->cart->get_applied_coupons())){
		WC()->cart->remove_coupon( '1kdbb004' );
	}
	if(!in_array('10kdbb004', WC()->cart->get_applied_coupons())){
		WC()->cart->apply_coupon( '10kdbb004' );
	}
}

function remove_10kdbb004(){
	if(in_array('10kdbb004', WC()->cart->get_applied_coupons())){
		WC()->cart->remove_coupon( '10kdbb004' );
	}
	if(!in_array('1kdbb004', WC()->cart->get_applied_coupons())){
		WC()->cart->apply_coupon( '1kdbb004' );
	}
}

function remove_both_kdbb004(){
	if(in_array('1kdbb004', WC()->cart->get_applied_coupons())){
		WC()->cart->remove_coupon( '1kdbb004' );
	}
	if(in_array('10kdbb004', WC()->cart->get_applied_coupons())){
		WC()->cart->remove_coupon( '10kdbb004' );
	}
}

function kdbb004_duplicate_check(){
	//checks if the customer has bought KDBB004 before
	$current_user = wp_get_current_user();
	$kdbb004_product_list = array();
	$cart = WC()->cart;
	$coupons = $cart->get_applied_coupons();
	$targeted_id = 92377;
	$coupon_code = "10kdbb004";
	$quantities = WC()->cart->get_cart_item_quantities();
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
		$product = $cart_item['data'];
		$cart_product_id = $cart_item['product_id'];
		if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $cart_product_id ) ){
			array_push($kdbb004_product_list, $product->name);
		}
	}
	foreach ($coupons as $coupon) {
		if (strpos($coupon, "10kdbb004", 0) !== 0) {
			array_push($get_applied_coupons, $coupon->name);
		}
	}
	if ( is_user_logged_in() ) {
		if(in_array('Bella Box Live Creatively', $kdbb004_product_list)){
			//Customer has bought a BB before
			remove_1kdbb004();
		}else if (isset($quantities[$targeted_id]) && double_in_cart($quantities, $targeted_id)) {
			//Customer hasn't bought a BB before, but has more than one in their cart
			remove_10kdbb004();
			WC()->cart->calculate_totals();
			no_message_in_cart();
		}else{
			//Customer hasn't bought a BB before, and doesn't have more than one in their cart
				remove_both_kdbb004();
				add_filter( 'woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
				WC()->cart->calculate_totals();
		}
	}else{
		//User is not logged in
		if (isset($quantities[$targeted_id]) && double_in_cart($quantities, $targeted_id)) {
			remove_10kdbb004();
			WC()->cart->calculate_totals();
		} else {
			remove_both_kdbb004();
			add_filter( 'woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
			WC()->cart->calculate_totals();
		}
	}
}
?>

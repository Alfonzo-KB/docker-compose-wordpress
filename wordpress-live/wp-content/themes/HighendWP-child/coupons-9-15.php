<?php
add_action('woocommerce_before_checkout_form', 'KDMR123_applied_check', 10);
add_action('woocommerce_applied_coupon', 'KDMR123_applied_check', 10);

function KDMR123_applied_check(){
	$quantities = WC()->cart->get_cart_item_quantities();
	$targeted_id = 73233;
	if($quantities[$targeted_id] >= 10){
		//apply coupon
		if(!in_array('10kdmr123', WC()->cart->get_applied_coupons())){
			WC()->cart->apply_coupon( '10KDMR123' );
			add_filter( 'woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
			WC()->cart->calculate_totals();
		}
	}else{
		//remove coupon
		if(in_array('10kdmr123', WC()->cart->get_applied_coupons())){
			WC()->cart->remove_coupon( '10KDMR123' );
			add_filter( 'woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
			WC()->cart->calculate_totals();
		}
	}
}

?>
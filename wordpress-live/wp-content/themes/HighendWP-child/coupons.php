<?php
add_action('woocommerce_before_checkout_form', 'coupon_checker', 11);
add_action('woocommerce_applied_coupon', 'coupon_checker', 11);

function coupon_checker(){
	$cart = WC()->cart;
	if( $cart->cart_contents_count > 9 ){
		$quantities = WC()->cart->get_cart_item_quantities();
		$targeted_ids = array(75004, 75003, 75002, 75001, 75000, 73233);
		$coupons = array("10KDKB192", "10KDKB190", "10KDKB193", "10KDKB191", "10KD578", "10KDMR123");
		for($i = 0; $i < count($targeted_ids); $i++){
			$targeted_id = $targeted_ids[$i];
			if(isset($quantities[$targeted_id])){
				if($quantities[$targeted_id] >= 10){
					//apply coupon
					if(!in_array($coupons[$i], WC()->cart->get_applied_coupons())){
						WC()->cart->apply_coupon( $coupons[$i] );
						WC()->cart->calculate_totals();
					}
				}else{
					//remove coupon
					if(in_array($coupons[$i], WC()->cart->get_applied_coupons())){
						WC()->cart->remove_coupon( $coupons[$i] );
						add_filter( 'woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
						WC()->cart->calculate_totals();
					}
				}
			}
		}
	}else{
		//remove coupons that require 10 or more products
		$coupons = array("10KDKB192", "10KDKB190", "10KDKB193", "10KDKB191", "10KD578", "10kdmr123");
		for($i = 0; $i < count($coupons); $i++){
			if(in_array($coupons[$i], WC()->cart->get_applied_coupons())){
				WC()->cart->remove_coupon( $coupons[$i] );
				add_filter( 'woocommerce_coupon_message', 'rename_coupon_label', 10, 3 );
				WC()->cart->calculate_totals();
			}
		}
	}
}

?>
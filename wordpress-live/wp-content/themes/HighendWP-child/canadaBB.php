<?php
	function get_customer_location(){
		$location = WC_Geolocation::geolocate_ip();
		return $location['country'];
	}

	function ca_bb_mix_notify(){
		wc_add_notice(__('Due to the flat rate shipping price for the Bella Box, it is not possible to add additional merchandise to this order.', 'woocommerce'), 'error');
	}

	add_action('woocommerce_add_to_cart_validation', 'check_if_bb_in_ca', 10, 3);
	function check_if_bb_in_ca($passed, $product_id, $quantity){
		$product = wc_get_product( $product_id );
		if(get_customer_location() === "CA"){
			if(strpos($product->get_categories(), "The Bella Box") !== false){
				//Product is a Bella Box
				global $cart;
				$items = WC()->cart->get_cart();
				foreach($items as $item => $values) { 
		            $_product =  wc_get_product( $values['data']->get_id()); 
		            if(strpos($_product->get_categories(), "The Bella Box") !== false){

		            }else{
		            	ca_bb_mix_notify();
		            	$passed = false;
		            }
		        } 
			}else{
				//Product is not Bella Box
				global $cart;
				$items = WC()->cart->get_cart();
				foreach($items as $item => $values) { 
		            $_product =  wc_get_product( $values['data']->get_id()); 
		            if(strpos($_product->get_categories(), "The Bella Box") !== false){
		            	ca_bb_mix_notify();
		            	$passed = false;
		            }
		        } 
			}
		}
		return $passed;
	}

	add_action('woocommerce_check_cart_items', 'no_bb_mix_in_ca', 10, 0);
	function no_bb_mix_in_ca(){
		if(get_customer_location() === "CA"){
			global $cart;
			$items = WC()->cart->get_cart();
			if (strpos($items, "The Bella Box") !== false){
				foreach($items as $item => $values) { 
		            $_product =  wc_get_product( $values['data']->get_id());
		            if(strpos($_product->get_categories(), "The Bella Box") === false){
		            	ca_bb_mix_notify();
		            }
		        } 
		    }
	    }
	}

	add_action( 'woocommerce_after_checkout_validation', 'no_vpn_dodge', 10, 2);
	function no_vpn_dodge( $fields, $errors ){
	    if ($fields[ 'shipping_country' ] !== get_customer_location()){
	    	if($fields[ 'shipping_country' ] == "CA"){
	    		global $cart;
				$items = WC()->cart->get_cart();
				if (strpos($items, "The Bella Box") !== false){
					foreach($items as $item => $values) { 
			            $_product =  wc_get_product( $values['data']->get_id());
			            if(strpos($_product->get_categories(), "The Bella Box") === false){
			            	ca_bb_mix_notify();
			            }
			        } 
			    }
		    }
	    }
	}
?>
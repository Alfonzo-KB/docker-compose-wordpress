/* Automatically adding Shop Owner Rewards
	* need to make it apply only to certified shops
	* and only if they're using a coupon
 	* if they go below the threshold then we need to automatically remove the item from their cart as well
	* https://www.tychesoftwares.com/how-to-automatically-add-a-product-to-your-woocommerce-cart-in-3-different-scenarios/
	* OR https://gregbastianelli.com/how-to-add-free-gifts-on-woocommerce-programmatically
 */

// function shop_owner_rewards() {
// 	global $woocommerce;
//
// 	$cart_total	= 500;
//
// 	if ( $woocommerce->cart->total >= $cart_total ) {
// 		if ( ! is_admin() ) {
// 	        $free_product_id = 78555;  // Product Id of the free product which will get added to cart
// 	        $found = false;
//
// 	        //check if product already in cart
// 	        if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
// 	            foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
// 	                $_product = $values['data'];
// 	                if ( $_product->get_id() == $free_product_id )
// 	                	$found = true;
// 	            }
// 	            // if product not found, add it
// 	            if ( ! $found )
// 	                WC()->cart->add_to_cart( $free_product_id );
// 	        } else {
// 	            // if no products in cart, add it
// 	            WC()->cart->add_to_cart( $free_product_id );
// 	        }
// 	    }
// 	}
// }
//
// add_action( 'template_redirect', 'shop_owner_rewards' );

<?php
add_filter( 'woocommerce_get_price_html', 'remove_price_check');
function remove_price_check($price){
	if ( !is_wholesale_customer() ){
		if ( show_category_hide() ){
			return $price;
		}
		return false;
	} else {
		return $price;
	}
}

function show_category_hide(){
	$hide_for_categories = array("fill-in-the-blank","dealer-exclusives", "event-exclusions", "get-it-today", "retired");
	foreach($hide_for_categories as $hide_cat){
		if ( is_product_category($hide_cat) ){
			return false;
			break;
		}
	}
	return true;
}
?>
<?php
// Display the product thumbnail in order view pages
add_filter( 'woocommerce_account_downloads_column_download-product', 'display_product_image_on_account_downloads' );
function display_product_image_on_account_downloads( $download ) {
    // Targeting view order pages only
    if ( is_wc_endpoint_url( array ('downloads' , 'order-received') )) return;

    if ( $download['product_id'] > 0 ) {
        $product = wc_get_product( $download['product_id'] ); // The product object
        $image   = $product->get_image( array(50, 50) ); // The product image

        if ( $download['product_url'] ) {
            echo $image . '<a style="margin-right:10px;" href="' . esc_url( $download['product_url'] ) . '">' . esc_html( $download['product_name'] ) . '</a>';
        } else {
            echo $image . esc_html( $download['product_name'] );
        }
    }
}

add_filter( 'woocommerce_account_downloads_columns', 'sort_downloads' );
function sort_downloads($array){
    $array = array_reverse($array);
}
?>
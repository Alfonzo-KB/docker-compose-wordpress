// Add custom fields to Quick Edit area
// STEP 1
// add_action( 'woocommerce_product_quick_edit_end', function(){
add_action( 'woocommerce_product_bulk_edit_start', 'bbloomer_custom_field_bulk_edit_input', 10, 0 );

function bbloomer_custom_field_bulk_edit_input() {
    ?>
    <div class="custom_field_demo">
        <label class="alignleft">
            <div class="title"><?php _e('Commission Amount', 'woocommerce' ); ?></div>
            <input type="text" name="wpam_woo_product_specific_commission" class="text" placeholder="<?php _e( 'Commission Amount', 'woocommerce' ); ?>" value="">
        </label>
    </div>
    <?php
	}
// } );

// Step 2
// add_action('woocommerce_product_quick_edit_save', function($product){
// if ( $product->is_type('simple') || $product->is_type('external') ) {

add_action('woocommerce_product_bulk_edit_save', 'bbloomer_custom_field_bulk_edit_save', 10, 1);
function bbloomer_custom_field_bulk_edit_save( $product ) {
    $post_id = $product->id;
    if ( isset( $_REQUEST['wpam_woo_product_specific_commission'] ) ) {
        // $customFieldDemo = trim(esc_attr( $_REQUEST['wpam_woo_product_specific_commission'] ));
				$wpam_woo_product_specific_commission = $_REQUEST['wpam_woo_product_specific_commission'];
        update_post_meta( $post_id, 'wpam_woo_product_specific_commission', wc_clean( $wpam_woo_product_specific_commission ) );
    }
// }
}
// , 10, 1);

// Step 3
add_action( 'manage_product_posts_custom_column', function($column,$post_id){
switch ( $column ) {
    case 'name' :
        ?>
        <div class="hidden custom_field_demo_inline" id="custom_field_demo_inline_<?php echo $post_id; ?>">
            <div id="wpam_woo_product_specific_commission"><?php echo get_post_meta($post_id,'wpam_woo_product_specific_commission',true); ?></div>
        </div>
        <?php
        break;
    default :
        break;
}
}, 99, 2);
?>
<script>
jQuery(function(){
jQuery('#the-list').on('click', '.editinline', function(){

    /**
     * Extract metadata and put it as the value for the custom field form
     */
    inlineEditPost.revert();
    var post_id = jQuery(this).closest('tr').attr('id');
    post_id = post_id.replace("post-", "");
    var $cfd_inline_data = jQuery('#custom_field_demo_inline_' + post_id),
        $wc_inline_data = jQuery('#woocommerce_inline_' + post_id );
    jQuery('input[name="wpam_woo_product_specific_commission"]', '.inline-edit-row').val($cfd_inline_data.find("#wpam_woo_product_specific_commission").text());
    /**
     * Only show custom field for appropriate types of products (simple)
     */
    var product_type = $wc_inline_data.find('.product_type').text();
    if (product_type=='simple' || product_type=='external') {
        jQuery('.custom_field_demo', '.inline-edit-row').show();
    } else {
        jQuery('.custom_field_demo', '.inline-edit-row').hide();
    }
});
});
</script>

<?php
// End Add custom fields to Quick Edit area

<?php
	require_once('../../wp-config.php');
    $wp->init();
    $wp->parse_request();
    $wp->query_posts();
    $wp->register_globals();
    $wp->send_headers();

	$query = $_POST['query'];
	switch($query){
        case 1:
            echo json_encode(get_recent_downloads());
        break;
        default:
        break;
    }

    function get_recent_downloads(){
        $user_id = intval(get_current_user_id());
        $results = wc_get_customer_available_downloads($user_id);
        foreach($results as &$item){
            $product =  wc_get_product($item['product_id']);
            $image_id = $product->get_image_id();
            $item['image_src'] = wp_get_attachment_image_url( $image_id, 'thumbnail');
        }

        return $results;
    }
?>
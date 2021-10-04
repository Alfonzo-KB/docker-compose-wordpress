<?php
	require_once('../wp-config.php');
    $wp->init();
    $wp->parse_request();
    $wp->query_posts();
    $wp->register_globals();
    $wp->send_headers();

	$query = $_POST['query'];
	switch($query){
		case 0:
			if (current_user_can('administrator')) {
				//User is an Admin
				echo json_encode(get_all_licenses());
			}else{
				//User is not an Admin
				echo json_encode(get_shop_licenses());
			}
		break;
		case 1:
			echo json_encode(get_licenses_by_product());
		break;
		case 2:
			echo json_encode(get_most_recent_license());
		break;
		case 3:
			echo json_encode(update_license());
		break;
		case 4:	
			attempt_to_assign_license();
		break;
		case 5:
			revoke_or_delete();
		break;
		case 6:
			echo json_encode(get_revoked());
		break;
		case 7:
			echo json_encode(get_all_years());
		break;
		case 8:
			echo json_encode(get_all_quarters());
		break;
		case 9:
			echo json_encode(get_all_associate());
		break;
		case 10:
			echo json_encode(get_licenses_remaining());
		break;
		case 11:
			echo json_encode(send_email());
		break;
		default:
		break;
	}

	function give_to_customer(){
		$cusEmail = $_POST["cus_email"];
		$productId = $_POST["product_id"];
		// Check if the user already exists.
		$userId = email_exists($cusEmail);

		// Create a new user and notify him.
		if (!$userId && !username_exists($cusEmail)) {
			$password = wp_generate_password(8, false);
			$userId   = wc_create_new_customer($cusEmail, '', $password);
		}

		$order = wc_create_order(['customer_id' => $userId]);

		$product = new WC_Product($productId);
		$order->add_product($product, 1);

		$order->add_order_note('Order automatically generated from DDE sale.');
		$order->payment_complete();
		return $order;
	}

	function parent_id_to_child(){
		$parent_id = $_POST['parent_id'];
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT `child_id` FROM DDE_associate 
			WHERE `parent_id` = %d", $parent_id), ARRAY_A);
		$_POST['child_id'] = $results;
	}

	function revoke_or_delete(){
		$order_id = $_POST['order_id'];
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT download_count FROM wp_woocommerce_downloadable_product_permissions
			WHERE order_id = %d", $order_id), ARRAY_A);
		if ($results[0]['download_count'] == 0){
			delete_license();
		}else{
			revoke_license();
		}

	}

	function get_all_licenses(){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_licenses 
			JOIN DDE_associate ON DDE_associate.child_id = DDE_licenses.product_id
			WHERE 1"), ARRAY_A);
		return $results;
	}

	function get_shop_licenses(){
		$shop_id = intval(get_current_user_id());
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_licenses 
			JOIN wp_woocommerce_downloadable_product_permissions ON DDE_licenses.order_id = wp_woocommerce_downloadable_product_permissions.order_id 
			WHERE shop_id = %d", $shop_id), ARRAY_A);
		for($i = 0; $i < count($results); $i++){
			$product = wc_get_product($results[$i]["product_id"]);
			$results[$i]["product_name"] = $product->get_title();
		}
		return $results;
	}

	function get_licenses_by_product(){
		$shop_id = intval(get_current_user_id());
		$product_id = $_POST['product_id']; 
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_licenses 
			JOIN wp_woocommerce_downloadable_product_permissions ON DDE_licenses.order_id = wp_woocommerce_downloadable_product_permissions.order_id
			WHERE shop_id = %d AND DDE_licenses.product_id = %d", 
			$shop_id, $product_id), ARRAY_A);
		return $results;
	}

	function get_most_recent_license(){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_licenses
			JOIN DDE_associate ON DDE_associate.child_id = DDE_licenses.product_id
			ORDER BY id DESC
			LIMIT 1
			"), ARRAY_A);
		return $results;
	}

	function get_all_years(){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT DISTINCT(year) FROM DDE_quarters
			ORDER BY year DESC
			"), ARRAY_A);
		return $results;
	}

	function get_all_quarters(){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_quarters
			ORDER BY quarter_id DESC
			"), ARRAY_A);
		return $results;
	}

	function get_all_associate(){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_associate
			ORDER BY parent_id DESC
			"), ARRAY_A);
		return $results;
	}

	function update_nickname(){
		$nickname = $_POST["nickname"];
		$order_id  = $_POST["order_id"];
		global $wpdb;
		$results  = $wpdb->get_results($wpdb->prepare("
			UPDATE DDE_licenses SET `customer_nickname` = %s
			WHERE `order_id` = %d
			", $nickname, $order_id), ARRAY_A);
	}

	function update_email(){
		$order_id  = $_POST["order_id"];
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM DDE_licenses
			WHERE `order_id` = %d
			", $order_id), ARRAY_A);
		$_POST['product_id'] = $results[0]['product_id'];
		delete_license();
		assign_license();
	}

	function update_license(){
		$order_id  = $_POST["order_id"];
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT download_count FROM wp_woocommerce_downloadable_product_permissions
			WHERE order_id = %d", $order_id), ARRAY_A);
		if ($results[0]['download_count'] == 0){
			update_email();
		}else{
			update_nickname();
		}
	}

	function attempt_to_assign_license(){
		if(get_licenses_remaining() >= 1){
			assign_license();
		}
	}

	function assign_license(){
		$product_id = $_POST['product_id'];
		$shop_id = get_current_user_id();
		$nickname = $_POST['nickname'];
		$cus_email = $_POST['cus_email'];
		
		//generate order for customer
		$order = give_to_customer();
		$orderNumber = $order->get_order_number();

		//send customer an email
		$_POST['email'] = $cus_email;
		send_email();

		global $wpdb;
		$license_update = $wpdb->get_results($wpdb->prepare("
			INSERT INTO `DDE_licenses` (`order_id`, `product_id`, `shop_id`, `assign_date`, `customer_nickname`, `email`) 
			VALUES (%d, %d, %d, CURRENT_TIMESTAMP, %s, %s)
			", $orderNumber, $product_id, $shop_id, $nickname, $cus_email), ARRAY_A);
		
		return true;
	}

	function add_licenses(){
		$product_id = $_POST['product_id'];
		$shop_id = $_POST['shop_id'];
		$amount = $_POST['amount'];
		global $wpdb;
		for($i = 0; $i < $amount; $i++){
			$results = $wpdb->get_results($wpdb->prepare("
				INSERT INTO `DDE_licenses_remaining` (`product_id`, `shop_id`, `amount_purchased`) 
				VALUES (%d, %d, %d)
				", $product_id, $shop_id, $amount), ARRAY_A);
		}
		return $results;
	}

	function revoke_license(){
		$order_id = $_POST['order_id'];
		global $wpdb;
		wp_delete_post($order_id, true);
		$results = $wpdb->get_results($wpdb->prepare("UPDATE `DDE_licenses`
			SET `assign_date` = NULL
			WHERE `order_id` = %d
			", $order_id), ARRAY_A);
		return $results;
	}

	function delete_license(){
		$order_id = $_POST['order_id'];
		global $wpdb;
		wp_delete_post($order_id, true);
		$results = $wpdb->get_results($wpdb->prepare("
			DELETE FROM `DDE_licenses` WHERE `order_id` = %d
			", $order_id), ARRAY_A);
		return $results;
	}

	function get_all_orders(){
		$user_id = get_current_user_id();
		$args = array(
			'customer_id' => $user_id
		);
		$orders = wc_get_orders($args);
		return $orders;
	}

	function get_revoked(){
		$user_id = get_current_user_id();
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM `DDE_licenses`
			JOIN `DDE_associate` ON DDE_associate.child_id = DDE_licenses.product_id
			WHERE `assign_date` IS NULL
			AND `shop_id` = %d
			", $user_id), ARRAY_A);
		return $results;
	}

	function get_licenses_remaining(){
		$user_id = get_current_user_id();
		$product_id = $_POST['product_id'];
		global $wpdb;
		$total = $wpdb->get_results($wpdb->prepare("
			SELECT `amount_purchased`
			FROM `DDE_licenses_remaining` 
			JOIN `DDE_associate` ON DDE_licenses_remaining.product_id = DDE_associate.parent_id
			WHERE `shop_id` = %d AND `child_id` = %d
			", $user_id, $product_id), ARRAY_A);
		$used = $wpdb->get_results($wpdb->prepare("
			SELECT COUNT(`id`)
			FROM `DDE_licenses` 
			WHERE `shop_id` = %d AND `product_id` = %d
			", $user_id, $product_id), ARRAY_A);
		$results = $total[0]['amount_purchased'] - $used[0]["COUNT(`id`)"];
		return $results;
	}

	function send_email(){
		$to = $_POST['email'];
		$subject = "Digital Download Product Recieved!";
		// $body = include '/woocommerce/emails/admin-new-order.php';
		$body = "<table style='margin: auto;	display: block;'>
		<tr>
			<div class='table-header' style='background: #e68a1b; text-align: center; color: white;	font-size: 24px; padding: 8px;'>You've recieved an exclusive digital product!</div>
		</tr>
		<tr>
			<div class='table-row'>You've recieved the following Kimberbell digital exclusive product:</div>
		</tr>
		<tr>
			<td class='table-row'>
				<p>I'm All Ears - Bunny Hoop</p>
			</td>
			<td>
				<a class='btn-orange' style='text-decoration: none;
		font-weight: bold;
		background: #e68a1b;
	    border: 2px solid #e68a1b;
	    color: #fff !important;
	    position: relative;
	    display: inline-block;
	    vertical-align: middle;
	    text-align: center;
	    cursor: pointer;
	    zoom: 1;
	    font-size: 14px;
	    line-height: 1.3;
	    letter-spacing: 1.5px;
	    text-transform: uppercase;
	    color: #fff;
	    -webkit-box-shadow: 0;
	    box-shadow: 0;
	    padding: 16px 20px 12px;
	    overflow: hidden;
	    border-radius: 2px;
	    -webkit-border-radius: 2px;
	    -moz-border-radius: 2px;' href='https://kimberbell.com/my-account/downloads/'>DOWNLOAD NOW</a>
			</td>
		</tr>
	</table>";
		$headers = "";
		$attachments = array();

		if(is_email($to)){
			echo "<h1 class='test'>Email is fine</h1>";
			$content_type = function() { return 'text/html'; };
			add_filter( 'wp_mail_content_type', $content_type );
			wp_mail($to, $subject, $body, $headers);
			remove_filter( 'wp_mail_content_type', $content_type );
		}else{
			echo "<h1 class='test'>Email is invalid</h1>";
		}
	}
?>
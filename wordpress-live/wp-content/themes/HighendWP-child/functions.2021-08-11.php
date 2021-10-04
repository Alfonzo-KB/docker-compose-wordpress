<?php
/**
 * Highend Child Theme functions.
 *
 * @since 1.0.2
 */
/**
 * Define Constants.
 */
define( 'HIGHEND_CHILD_THEME_VERSION', '1.0.2' );

/**
 * Enqueue and register scripts and styles.
 *
 * @since 1.0.0
 */
function highend_child_theme_enqueue_styles() {
	wp_enqueue_style(
		'highend-child-styles',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'highend-style' ),
		HIGHEND_CHILD_THEME_VERSION,
		'all'
	);
}
add_action( 'wp_enqueue_scripts', 'highend_child_theme_enqueue_styles', 15 );

// Individual Product - move Description, Specs, Materials Needed, Reviews tabs to under add to cart button
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_share', 'woocommerce_output_product_data_tabs', 10 );

include_once("duplicateCheck.php");
include_once("DuplicateCheckKDBB004.php");
include_once("hide-price.php");

/**
 * Gravity Wiz // Gravity Forms // Better Inventory with Gravity Forms
 *
 * Implements the concept of "inventory" with Gravity Forms by allowing the specification of a limit determined by the
 * sum of a specific field, typically a quantity field.
 *
 * @version   2.20
 * @author    David Smith <david@gravitywiz.com>
 * @license   GPL-2.0+
 * @link      http://gravitywiz.com/better-inventory-with-gravity-forms/
 */
class GW_Inventory {
	public $_args;
	public function __construct( $args ) {
		$this->_args = $this->parse_args( $args );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		// make sure we're running the required minimum version of Gravity Forms
		if( ! property_exists( 'GFCommon', 'version' ) || ! version_compare( GFCommon::$version, '1.8', '>=' ) ) {
			return;
		}
		add_filter( "gform_pre_render_{$this->_args['form_id']}", array( $this, 'limit_by_field_values' ) );
		add_filter( "gform_validation_{$this->_args['form_id']}", array( $this, 'limit_by_field_values_validation' ) );
		// check 'field_group' for date fields; if found, limit based on exhausted inventory days.
		if( ! empty( $this->_args['field_group'] ) ) {
			add_filter( "gpld_limit_dates_options_{$this->_args['form_id']}", array( $this, 'limit_date_fields' ), 10, 2 );
		}
		// add 'sum' action for [gravityforms] shortcode
		add_filter( 'gform_shortcode_sum',       array( $this, 'shortcode_sum' ), 10, 2 );
		add_filter( 'gform_shortcode_remaining', array( $this, 'shortcode_remaining' ), 10, 2 );
		add_action( 'gwinv_before_get_sum', array( $this, 'before_get_sum' ) );
		add_action( 'gwinv_after_get_sum', array( $this, 'after_get_sum' ) );
		add_filter( 'gform_product_info', array( $this, 'handle_calculated_product_fields' ), 10, 3 );
		if( $this->_args['enable_notifications'] ) {
			$this->enable_notifications();
		}
	}

	public function parse_args( $args ) {
		$args = wp_parse_args( $args, array(
			'form_id'                  => false,
			'field_id'                 => false,
			'input_id'                 => false,
			'stock_qty'                => false,
			'out_of_stock_message'     => __( 'Sorry, this item is out of stock.' ),
			'not_enough_stock_message' => __( 'You ordered %1$s of this item but there are only %2$s of this item left.' ),
			'approved_payments_only'   => false,
			'hide_form'                => false,
			'enable_notifications'     => false,
			'field_group'              => array()
		) );

		/**
		 * @var $stock_qty
		 * @var $field_group
		 */
		extract( $args );
		if( ! $stock_qty && isset( $limit ) ) {
			$args['stock_qty'] = $limit;
			unset( $args['limit'] );
		}

		if( isset( $limit_message ) ) {
			$args['out_of_stock_message'] = $limit_message;
			unset( $args['limit_message'] );
		}

		if( isset( $validation_message ) ) {
			$args['not_enough_stock_message'] = $validation_message;
			unset( $args['validation_message'] );
		}

		if( ! $args['input_id'] ) {
			$args['input_id'] = $args['field_id'];
			unset( $args['field_id'] );
		}

		if( $field_group && ! is_array( $field_group ) ) {
			$args['field_group'] = array( $field_group );
		}

		return $args;
	}

	public function enable_notifications() {

		if( ! class_exists( 'GW_Notification_Event' ) ) {

			_doing_it_wrong( 'GW_Inventory::$enable_notifications', __( 'Inventory notifications require the \'GW_Notification_Event\' class.' ), '1.0' );

		} else {

			$event_slug = "gwinv_out_of_stock_{$this->_args['input_id']}";
			$event_name = GFForms::get_page() == 'notification_edit' ? $this->get_notification_event_name() : __( 'Event name is only populated on Notification Edit view; saves a DB call to get the form on every ' );

			$this->_notification_event = new GW_Notification_Event( array(
				'form_id'    => $this->_args['form_id'],
				'event_name' => $event_name,
				'event_slug' => $event_slug,
				'trigger'    => array( $this, 'notification_event_listener' )
			) );

		}

	}

	public function limit_by_field_values( $form ) {

		if( $this->is_in_stock() ) {
			return $form;
		}

		if( $this->_args['hide_form'] ) {
			add_filter( "gform_get_form_filter_{$form['id']}", array( $this, 'get_out_of_stock_message' ) );
		} else if( empty( $this->_args['field_group'] ) ) {
			add_filter( 'gform_field_input', array( $this, 'hide_field' ), 10, 2 );
		}

		return $form;
	}

	public function limit_by_field_values_validation( $validation_result ) {

		$input_id           = $this->_args['input_id'];
		$limit              = $this->get_stock_quantity();
		$validation_message = $this->_args['not_enough_stock_message'];

		$form = $validation_result['form'];
		$exceeded_limit = false;

		foreach( $form['fields'] as &$field ) {

			if( $field['id'] != intval( $input_id ) ) {
				continue;
			}

			$requested_qty = rgpost( 'input_' . str_replace( '.', '_', $input_id ) );
			$field_sum = $this->get_sum();

			if( rgblank( $requested_qty ) || $field_sum + $requested_qty <= $limit ) {
				continue;
			}

			$exceeded_limit = true;
			$stock_left     = $limit - $field_sum >= 0 ? $limit - $field_sum : 0;

			$field['failed_validation'] = true;
			$field['validation_message'] = sprintf( $validation_message, $requested_qty, $stock_left );

		}

		if( $exceeded_limit && ! empty( $this->_args['field_group'] ) ) {
			foreach( $form['fields'] as &$field ) {
				if( in_array( $field->id, $this->_args['field_group'] ) ) {
					$field['failed_validation'] = true;
					$field['validation_message'] = sprintf( $validation_message, $requested_qty, $stock_left );
				}
			}
		}

		$validation_result['form'] = $form;
		$validation_result['is_valid'] = ! $validation_result['is_valid'] ? false : ! $exceeded_limit;

		return $validation_result;
	}

	public function limit_by_field_group( $query, $form_id, $input_id ) {
		global $wpdb;

		if( $input_id != $this->_args['input_id'] ) {
			return $query;
		}

		$form = GFAPI::get_form( $form_id );
		$join = $where = array();

		foreach( $this->_args['field_group'] as $index => $field_id ) {

			$field = GFFormsModel::get_field( $form, $field_id );
			$alias = sprintf( 'fgld%d', $index + 1 );

			// Fetch entry from submission if available. Otherwise, get default/dynpop value.
			if( rgpost( 'gform_submit' ) == $form_id ) {
				$value = $field->get_value_save_entry( GFFormsModel::get_field_value( $field ), $form, null, null, null );
			} else {
				$value = $field->get_value_default_if_empty( GFFormsModel::get_parameter_value( $field->inputName, array(), $field ) );
			}

			$join[] = "\nINNER JOIN {$wpdb->prefix}gf_entry_meta {$alias} ON em.entry_id = {$alias}.entry_id";
			$where[] = $wpdb->prepare( "CAST( {$alias}.meta_key as unsigned ) = %d AND {$alias}.meta_value = %s ", $field_id, $value );

		}

		$query['join']  .= implode( "\n", $join );
		$query['where'] .= sprintf( "\n AND %s", implode( "\nAND ", $where ) );

		return $query;
	}

	public function limit_date_fields( $options, $form ) {
		global $wpdb;

		foreach( $form['fields'] as $field ) {

			if( ! in_array( $field->id, $this->_args['field_group'] ) || $field->get_input_type() != 'date' || $field->dateType != 'datepicker' ) {
				continue;
			}

			$query = self::get_sum_query( $field->formId, $this->_args['input_id'] );

			if( $this->_args['approved_payments_only'] ) {
				$query = $this->limit_by_approved_payments_only( $query );
			}

			if( ! empty( $this->_args['field_group'] ) ) {

				// add our Date field to the front of the array so we can reliably target it when replacing the queries below
				array_unshift( $this->_args['field_group'], $field->id );
				$this->_args['field_group'] = array_unique( $this->_args['field_group'] );

				$query = $this->limit_by_field_group( $query, $field->formId, $this->_args['input_id'] );

			}

			$regex = sprintf( '/(CAST\( fgld1.meta_key as unsigned \) = %d) AND fgld1.meta_value = \'(?:[\w\d]*)\'/', $field->id );
			preg_match( $regex , $query['where'], $match );
			if( ! empty( $match ) ) {
				list( $search, $replace ) = $match;
				$query['where'] = str_replace( $search, $replace, $query['where'] );
			}

			$query['select']   = 'SELECT sum( em.meta_value ) as total, fgld1.meta_value as date';
			$query['group_by'] = 'GROUP BY date';
			$query['having']   = sprintf( 'HAVING total >= %d', $this->get_stock_quantity() );

			$sql     = implode( "\n", $query );
			$results = $wpdb->get_results( $sql );

			foreach( $results as $result ) {
				$options[ $field->id ]['exceptionsMode'] = 'disable';
				if ( ! is_array( $options[ $field->id ]['exceptions'] ) ) {
					$options[ $field->id ]['exceptions'] = array();
				}
				$options[ $field->id ]['exceptions'][] = date( 'm/d/Y', strtotime( $result->date ) );
			}

		}

		return $options;
	}

	public function get_stock_quantity() {

		$stock = $this->_args['stock_qty'];

		if( is_callable( $stock ) ) {
			$stock = call_user_func( $stock );
		}

		return $stock;
	}

	public function is_in_stock() {
		$count = $this->get_sum();//self::get_field_values_sum( $this->_args['form_id'], $this->_args['input_id'] );
		return $count < $this->get_stock_quantity();
	}

	public function hide_field( $field_content, $field ) {

		if( $field['id'] == intval( $this->_args['input_id'] ) )  {

			$quantity_input = '';
			// GF will default to a quantity of 1 if it can't find the input for a Quantity field.
			if ( $field->type === 'quantity' ) {
				$quantity_input = sprintf( '<input type="hidden" name="input_%d_%d" value="0" />', $field->formId, $field->id );
			}

			return sprintf( '<div class="ginput_container">%s%s</div>', $this->_args['out_of_stock_message'], $quantity_input );
		}

		return $field_content;
	}

	public function notification_event_listener() {

		// really is no better hook to use to send custom notifications just yet
		add_filter( "gform_confirmation_{$this->_args['form_id']}", array( $this, 'send_out_of_stock_notifications' ), 10, 3 );

	}

	public function send_out_of_stock_notifications( $return, $form, $entry ) {

		// if product is still in stock or the entry is spam, don't sent notification
		if( $this->is_in_stock() || $entry['status'] == 'spam' )
			return $return;

		// if product is out of stock and no qty of the product is in current order, assume that out of stock notifications have already been sent
		$requested_qty = intval( rgar( $entry, (string) $this->_args['input_id'] ) );
		if( $requested_qty <= 0 )
			return $return;

		$this->_notification_event->send_notifications( $this->_notification_event->get_event_slug(), $form, $entry );

		return $return;
	}

	public function get_notification_event_name() {

		$form = GFAPI::get_form( $this->_args['form_id'] );
		$field = GFFormsModel::get_field( $form, $this->_args['input_id'] );

		$event_name = sprintf( __( '%s: Out of Stock' ), GFCommon::get_label( $field ) );

		return $event_name;
	}

	public function shortcode_sum( $output, $atts ) {
		$atts = shortcode_atts( array(
			'id' => false,
			'input_id' => false
		), $atts );

		/**
		 * @var $id
		 * @var $input_id
		 */
		extract( $atts ); // gives us $id, $input_id

		return intval( self::get_field_values_sum( $id, $input_id ) );
	}

	public function shortcode_remaining( $output, $atts ) {
		/**
		 * @var $id
		 * @var $input_id
		 * @var $limit
		 */
		$atts = shortcode_atts( array(
			'id'       => false,
			'input_id' => false,
			'limit'    => false,
		), $atts );

		extract( $atts ); // gives us $id, $input_id

		if( $input_id == $this->_args['input_id'] && $id == $this->_args['form_id'] ) {
			$limit     = $limit ? $limit : $this->get_stock_quantity();
			$remaining = $limit - intval( $this->get_sum() );
			$output    = max( 0, $remaining );
		}

		return $output;
	}

	public function get_sum() {

		if( $this->_args['approved_payments_only'] ) {
			add_filter( 'gwinv_query', array( $this, 'limit_by_approved_payments_only' ) );
		}

		if( ! empty( $this->_args['field_group'] ) ) {
			add_filter( 'gwinv_query', array( $this, 'limit_by_field_group' ), 10, 3 );
		}
		$sum = self::get_field_values_sum( $this->_args['form_id'], $this->_args['input_id'] );
		remove_filter( 'gwinv_query', array( $this, 'limit_by_approved_payments_only' ) );
		remove_filter( 'gwinv_query', array( $this, 'limit_by_field_group' ) );
		return $sum;
	}
	public function limit_by_approved_payments_only( $query ) {
		$valid_statuses = array( 'Approved' /* old */, 'Paid', 'Active' );
		$query['where'] .= sprintf( ' AND ( e.payment_status IN ( %s ) OR e.payment_status IS NULL )', self::prepare_strings_for_mysql_in_statement( $valid_statuses ) );
		return $query;
	}
	public function get_out_of_stock_message() {
		return $this->_args['out_of_stock_message'];
	}

	/**
	 * Calculated Product fields limited by their default quantity should be excluded from the order summary.
	 * Since their value is calculated post submission, they function uniquely from other product fields.
	 */
	public function handle_calculated_product_fields( $order, $form, $entry ) {
		foreach( $order['products'] as $field_id => $product ) {
			// Check for if target input ID is for the current field and is targeting a Product field default quantity input (i.e. 1.3).
			if( $field_id != intval( $this->_args['input_id'] ) || rgar( explode( '.', $this->_args['input_id'] ), 1 ) != '3' ) {
				continue;
			}
			$field = GFAPI::get_field( $form, $field_id );
			if( $field->get_input_type() !== 'calculation' ) {
				continue;
			}
			if( ! $this->is_in_stock() ) {
				unset( $order['products'][ $field_id ] );
			}
		}

		return $order;
	}

	public static function get_field_values_sum( $form_id, $input_id ) {
		global $wpdb;
		$query  = self::get_sum_query( $form_id, $input_id );
		$sql    = implode( "\n", $query );
		$result = $wpdb->get_var( $sql );
		return intval( $result );
	}

	public static function get_sum_query( $form_id, $input_id, $suppress_filters = false ) {
		global $wpdb;

		$query = array(
			'select' => 'SELECT sum( em.meta_value )',
			'from'   => "FROM {$wpdb->prefix}gf_entry_meta em",
			'join'   => "INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id",
			'where'  => $wpdb->prepare( "
                WHERE em.form_id = %d
                AND em.meta_key = %s
                AND e.status = 'active'\n",
				$form_id, $input_id
			)
		);

		if( class_exists( 'GF_Partial_Entries' ) ) {
			$query['where'] .= "and em.entry_id NOT IN( SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta WHERE meta_key = 'partial_entry_id' )";
		}

		if( ! $suppress_filters ) {
			$query  = apply_filters( 'gwlimitbysum_query',                 $query, $form_id, $input_id );
			$query  = apply_filters( 'gwinv_query',                        $query, $form_id, $input_id );
			$query  = apply_filters( "gwinv_query_{$form_id}",             $query, $form_id, $input_id );
			$query  = apply_filters( "gwinv_query_{$form_id}_{$input_id}", $query, $form_id, $input_id );
		}
		return $query;
	}
	public static function prepare_strings_for_mysql_in_statement( $strings ) {
		$wrapped = array();
		foreach( $strings as $string ) {
			$wrapped[] = sprintf( '"%s"', $string );
		}
		return implode( ', ', $wrapped );
	}
}

// Auto-complete Virtual orders
add_action('woocommerce_order_status_changed', 'ts_auto_complete_virtual');
function ts_auto_complete_virtual($order_id)
{
  if ( ! $order_id ) {
        return;
  }
  global $product;
  $order = wc_get_order( $order_id );
  if ($order->data['status'] == 'processing') {
    $virtual_order = null;
    if ( count( $order->get_items() ) > 0 ) {
      foreach( $order->get_items() as $item ) {
        if ( 'line_item' == $item['type'] ) {
          $_product = $order->get_product_from_item( $item );
          if ( ! $_product->is_virtual() ) {
            // once we find one non-virtual product, break out of the loop
            $virtual_order = false;
            break;
          }
          else {
            $virtual_order = true;
          }
       }
     }
   }
    // if all are virtual products, mark as completed
    if ( $virtual_order ) {
      $order->update_status( 'completed' );
    }
  }
}

/**
 * Disable Admin Notification of User Password Change
 *
 * @see pluggable.php
 */
if ( ! function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification( $user ) {
        return;
    }
}

// Add menu items to WooCommerce Dashboard
add_filter ( 'woocommerce_account_menu_items', 'misha_one_more_link' );
function misha_one_more_link( $menu_links ){

	// we will hook "anyuniquetext123" later
	$new = array( 'affilateDashboard' => 'Affiliate Dashboard', 'publicProfile' => 'Public Profile', 'publicProfileEdit' => 'Edit Public Profile');

	// or in case you need 2 links
	// $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );

	// array_slice() is good when you want to add an element between the other ones
	$menu_links = array_slice( $menu_links, 0, 1, true )
	+ $new
	+ array_slice( $menu_links, 1, NULL, true );
	return $menu_links;
}

add_filter( 'woocommerce_get_endpoint_url', 'misha_hook_endpoint', 10, 4 );
function misha_hook_endpoint( $url, $endpoint, $value, $permalink ){
	if( $endpoint === 'affilateDashboard' ) {
		$url = '/affiliate-dashboard/';
	}
	elseif($endpoint === 'publicProfile' ) {
		$url = '/store-profile/';
	}
	elseif($endpoint === 'publicProfileEdit' ) {
		$url = '/store-profile/?um_action=edit';
	}
	return $url;
}

// Product Page Changes
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_sharing', 5 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 19 );

// Email Changes
function sww_add_images_woocommerce_emails( $output, $order ) {
	// set a flag so we don't recursively call this filter
	static $run = 0;
	// if we've already run this filter, bail out
	if ( $run ) {
		return $output;
	}
	$args = array(
		'show_image'   	=> true,
		'image_size'    => array( 100, 100 ),
	);
	// increment our flag so we don't run again
	$run++;
	// if first run, give WooComm our updated table
	return $order->email_order_items_table( $args );
}
add_filter( 'woocommerce_email_order_items_table', 'sww_add_images_woocommerce_emails', 10, 2 );

// Allowing coupons to override cart minimum quantity rules
add_filter('woocommerce_add_to_cart_validation', 'remove_quantity_limits_for_coupons', 10, 3);
function remove_quantity_limits_for_coupons($passed, $product_id, $quantity) {
    $current_user = wp_get_current_user();
    if (!$current_user || !in_array('um_certified-shop', $current_user->roles)) {
        return $passed;
    }

    $cart = WC()->cart;
    $coupons = $cart->get_applied_coupons();
    $remove_limitations = false;
    foreach ($coupons as $coupon) {
        if (strpos($coupon, "kdc", 0) !== 0) {
            $remove_limitations = true;
        }
    }
    $product = wc_get_product($product_id);
    $min_quantity = $product->get_min_purchase_quantity();
    if ($remove_limitations === false && $quantity < $min_quantity) {
        $passed = false;
    }
    if ($remove_limitations === true && $quantity < $min_quantity) {
        $passed = true;
    }
    return $passed;
}

add_filter('woocommerce_quantity_input_args', 'remove_quantity_input_limits_for_coupons', 10, 2);
function remove_quantity_input_limits_for_coupons($args, $product) {
    if ($product === null) {
        return $args;
    }

    $current_user = wp_get_current_user();
    if (!$current_user || !in_array('um_certified-shop', $current_user->roles)) {
        return $args;
    }

    $cart = WC()->cart;
    $coupons = $cart->get_applied_coupons();
    $remove_limitations = false;
    foreach ($coupons as $coupon) {
        if (strpos($coupon, "kdc", 0) !== 0) {
            $remove_limitations = true;
        }
    }
    if ($remove_limitations === true) {
        $args["min_value"] = 0;
    }
    return $args;
}
// END COUPON CUSTOMIZATIONS

// BEGIN PRE-ORDER STATUS
// 1. Allow Order Again for Processing Status

add_filter( 'woocommerce_valid_order_statuses_for_order_again', 'bbloomer_order_again_statuses' );

function bbloomer_order_again_statuses( $statuses ) {
    $statuses[] = 'pre-ordered';
    return $statuses;
}

// 2. Add Order Actions @ My Account

add_filter( 'woocommerce_my_account_my_orders_actions', 'bbloomer_add_edit_order_my_account_orders_actions', 50, 2 );

function bbloomer_add_edit_order_my_account_orders_actions( $actions, $order ) {
    if ( $order->has_status('pre-ordered') ) {
        $actions['edit-order'] = array(
            'url'  => wp_nonce_url( add_query_arg( array( 'order_again' => $order->get_id(), 'edit_order' => $order->get_id() ) ), 'woocommerce-order_again' ),
            'name' => __( 'Edit Order', 'woocommerce' )
        );
    }
    return $actions;
}

// 3. Detect Edit Order Action and Store in Session

add_action( 'woocommerce_cart_loaded_from_session', 'bbloomer_detect_edit_order' );

function bbloomer_detect_edit_order( $cart ) {
    if ( isset( $_GET['edit_order'], $_GET['_wpnonce'] ) && is_user_logged_in() && wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'woocommerce-order_again' ) ) WC()->session->set( 'edit_order', absint( $_GET['edit_order'] ) );
}

// 4. Save Order Action if New Order is Placed

add_action( 'woocommerce_checkout_update_order_meta', 'bbloomer_save_edit_order' );

function bbloomer_save_edit_order( $order_id ) {
    $edited = WC()->session->get( 'edit_order' );
    if ( ! empty( $edited ) ) {
        // update this new order
        update_post_meta( $order_id, '_edit_order', $edited );
        $neworder = new WC_Order( $order_id );
        $oldorder_edit = get_edit_post_link( $edited );
        $neworder->add_order_note( 'Order placed after editing. Old order number: <a href="' . $oldorder_edit . '">' . $edited . '</a>' );
        // cancel previous order
        $oldorder = new WC_Order( $edited );
        $neworder_edit = get_edit_post_link( $order_id );
        $oldorder->update_status( 'cancelled', 'Order cancelled after editing. New order number: <a href="' . $neworder_edit . '">' . $order_id . '</a> -' );
        WC()->session->set( 'edit_order', null );
    }
}

/**
 * Charge a WooCommerce pre-order when the associated order is set to Completed status
 */
function sv_wc_charge_order_on_order_complete( $order_id, $order ) {

	if ( ! class_exists( 'WC_Pre_Orders_Order' ) ) {
		return;
	}

	if ( WC_Pre_Orders_Order::order_contains_pre_order( $order ) && WC_Pre_Orders_Manager::can_pre_order_be_changed_to( 'completed', $order ) ) {
		WC_Pre_Orders_Manager::complete_pre_order( $order, $message );
	}
}
add_action( 'woocommerce_order_status_completed', 'sv_wc_charge_order_on_order_complete', 10, 2 );

// Disable Add to Cart for Customers
add_filter('woocommerce_is_purchasable', 'purshasabale_course', 10, 2 );
function purshasabale_course( $is_purchasable, $product ) {
  $user = wp_get_current_user();
	$is_purchasable = true;
	$allowed_roles = array('customer');
	if( has_term( 'machine-embroidery', 'product_cat', $product->get_id() ) ) {
		if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		    $is_purchasable = false;
				remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
	}
	elseif( has_term( 'pre-order', 'product_cat', $product->get_id() ) ) {
		if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		    $is_purchasable = false;
				remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
	}
	elseif( has_term( 'retired', 'product_cat', $product->get_id() ) ) {
		if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		    $is_purchasable = false;
				remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
	}
	elseif( has_term( 'shop-owner-rewards', 'product_cat', $product->get_id() ) ) {
		if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		    $is_purchasable = false;
				remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
	}
	elseif( has_term( 'stabilizer', 'product_cat', $product->get_id() ) ) {
		if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		    $is_purchasable = false;
				remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
	}
	elseif( has_term( 'window-shop', 'product_cat', $product->get_id() ) ) {
		if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		    $is_purchasable = false;
				remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
	}
	return $is_purchasable;
}

// Disable Add to Cart for Distributor Sales Reps
add_filter('woocommerce_is_purchasable', 'purshasabale_product', 10, 2 );
function purshasabale_product( $is_purchasable, $product ) {
  $user = wp_get_current_user();
	$is_purchasable = true;
	$allowed_roles = array('um_wholesale-35');

if ( array_intersect($allowed_roles, (array) $user->roles) ) {
		$is_purchasable = false;
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
}
	return $is_purchasable;
}

// Remove Add to Cart for Visitors on Wholesale Categories
add_filter('woocommerce_is_purchasable', 'visitor_product', 10, 2 );

function visitor_product( $is_purchasable, $product_cat) {
    if ( ! is_user_logged_in() && has_term( 'window-shop', 'product_cat', $product_cat->get_id() ) ) {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        return false;
    }
		elseif( ! is_user_logged_in() && has_term( 'pre-order', 'product_cat', $product_cat->get_id() ) ) {
					remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
					return false;
			}
    return $is_purchasable;
}

/** Disable Ajax Call from WooCommerce on front page and posts*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
if (is_front_page()) wp_dequeue_script('wc-cart-fragments');
}

// Update new admin email notification to include user's role
add_filter('woocommerce_email_subject_new_order', 'change_admin_email_subject', 1, 2);
function change_admin_email_subject($subject, $order)
{
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $user_id = $order->get_user_id();
    $user = get_userdata($user_id);
    $user_roles = $user->roles;

    if (in_array('customer', $user_roles, true)) { //Here you can define which role
        $role = 'customer';
    } else {
    }

    $subject = sprintf('%s: New order #%s %s', $blogname, $order->id, $role, wc_format_datetime($order->get_date_created()));
    return $subject;
}

add_filter( 'woocommerce_billing_fields', 'require_wc_company_field');
function require_wc_company_field( $fields ) {
	$user = wp_get_current_user();
	$is_purchasable = true;
	$allowed_roles = array('um_wholesale-35', 'wholesale_customer', 'Wholesale15', 'Wholesale20', 'Wholesale30');

if ( array_intersect($allowed_roles, (array) $user->roles) ) {
$fields['billing_company']['required'] = true;
return $fields;}

	else{}
	return $fields;
}

// Fix reset password link from Ultimate Member
add_filter( 'um_template_tags_patterns_hook', 'my_template_tags_patterns', 10, 1 );
function my_template_tags_patterns( $placeholders ) {

/* your custom password reset page TAG */
$placeholders[] = '{custom_password_reset_link}';
return $placeholders;
}

add_filter( 'um_template_tags_replaces_hook', 'my_template_tags_replaces', 10, 1 );
function my_template_tags_replaces( $replace_placeholders ) {
$currentUserLogin = $replace_placeholders[4];  #this contains the user login;
$currentUser = get_user_by( "login", $currentUserLogin );
$resetPassKey = get_password_reset_key( $currentUser );

/* standard WordPress password reset page URL */
$customResetPassUrl = wp_login_url().'?action=rp&key='.$resetPassKey.'&login='.rawurlencode($currentUserLogin);
$replace_placeholders[] = $customResetPassUrl;
return $replace_placeholders;
}

// Hide Free Shipping for Bella Box
add_filter( 'woocommerce_package_rates', 'hide_free_shipping_bella_box', 10, 2 );

function hide_free_shipping_bella_box( $rates, $package ) {
   $shipping_class_target = 161;
   $in_cart = false;
   foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
      if ( $values[ 'data' ]->get_shipping_class_id() == $shipping_class_target ) {
         $in_cart = true;
         break;
      }
   }
   if ( $in_cart ) {
      unset( $rates['free_shipping:6'] );
			unset( $rates['free_shipping:16'] );
			unset( $rates['free_shipping:26'] );
   }
	 // elseif ( $in_cart ) {
		// 	unset( $rates['free_shipping:16'] );
	 // }
   return $rates;
}

// Hide Pay Now for Pre-Orders
add_filter( 'woocommerce_available_payment_gateways', 'unset_gateway_by_category' );

function unset_gateway_by_category( $available_gateways ) {
    if ( is_admin() ) return $available_gateways;
    if ( ! is_checkout() ) return $available_gateways;
    $unset = false;
    $category_ids = array( 301 );
    foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
        $terms = get_the_terms( $values['product_id'], 'product_cat' );
        foreach ( $terms as $term ) {
            if ( in_array( $term->term_id, $category_ids ) ) {
                $unset = true;
                break;
            }
        }
    }
    if ( $unset == true ) unset( $available_gateways['paytrace'] );
    return $available_gateways;
}

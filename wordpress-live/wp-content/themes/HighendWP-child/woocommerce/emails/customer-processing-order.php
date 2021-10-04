<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
// do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<!-- <p><?php// printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<?php /* translators: %s: Order number */ ?>
<p><?php// printf( esc_html__( 'Just to let you know &mdash; we\'ve received your order #%s, and it is now being processed:', 'woocommerce' ), esc_html( $order->get_order_number() ) ); ?></p> -->

<div style="max-width: 800px; padding: 20px; background: #FFFFFF; border-radius: 5px; margin: 40px auto; font-family: Noto Sans,Open Sans,sans-serif; font-size: 16px; color: #333;">
<img style="width: 677px; height: auto;" src="https://kimberbell.com/wp-content/uploads/2021/05/Email-header.png" alt="Kimberbell Logo" />
<p style="text-align: right; font-size: 22px; color: #333;">Processing Order</p>
<?php /* translators: %s: Customer first name */ ?>
<div style="color: #333; font-size: 16px; padding: 0 30px 10px 30px; border-bottom: 3px solid #E68A1B; margin-bottom: 30px;">
<p><?php printf( esc_html__( 'Hello %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p>We have received your order and it is now being processed.</p>
</div>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
// do_action( 'woocommerce_email_footer', $email ); ?>
<p>You can view your receipt or invoice by visiting your <a href="https://kimberbell.com/my-account/orders/">Orders</a> page.<br />
This email was sent from a notification-only address that cannot accept incoming email.  Please do not reply to this message.</p>

</div>
<?php

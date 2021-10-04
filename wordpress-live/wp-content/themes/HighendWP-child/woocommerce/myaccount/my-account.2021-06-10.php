<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
?>
<?php //do_action( 'woocommerce_account_navigation' );
?>
<div class="your-account">
<h1>Your Account</h1>
<?php
	$user = wp_get_current_user();
	if ( in_array( 'um_certified-shop', (array) $user->roles ) ) {
		echo'
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<li><a href="/my-account/orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-07.png" /> Orders</a></li>
				<li><a href="/my-account/pre-orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Pre-Orders</a></li>
				<li><a href="/my-account/downloads/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-02.png" /> Downloads</a></li>
				<li><a href="/my-account/edit-account/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Account Details</a></li>
				<li><a href="/my-account/edit-address/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Account Addresses</a></li>
				<li><a href="/store-profile/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Public Profile</a></li>
				<li><a href="/my-account/payment-methods/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-06.png" /> Payment Methods</a></li>
				<li><a href="/store-profile/?um_action=edit"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-03.png" /> Shop Address</a></li>
				<li><a href="/wp-login.php?action=logout"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Logout</a></li>
			</ul>
		</nav>';
	}
	elseif ( in_array( 'Wholesale30', (array) $user->roles ) ) {
		echo'
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<li><a href="/distributor-sales-rep-portal/"><img src="/wp-content/uploads/2021/05/Catalog-Distributor-Binder.jpg" style="width: 25px; height: auto;"/> Distributor Sales Rep Portal</a></li>
				<li><a href="/my-account/orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-07.png" /> Orders</a></li>
				<li><a href="/my-account/pre-orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Pre-Orders</a></li>
				<li><a href="/my-account/edit-account/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Account Details</a></li>
				<li><a href="/my-account/edit-address/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Account Addresses</a></li>
				<li><a href="/my-account/payment-methods/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-06.png" /> Payment Methods</a></li>
				<li><a href="/wp-login.php?action=logout"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Logout</a></li>
			</ul>
		</nav>';
	}
	elseif ( in_array( 'um_wholesale-35', (array) $user->roles ) ) {
		echo'
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<li><a href="/distributor-sales-rep-portal/"><img src="/wp-content/uploads/2021/05/Catalog-Distributor-Binder.jpg" style="width: 25px; height: auto;"/> Distributor Sales Rep Portal</a></li>
				<li><a href="/wp-login.php?action=logout"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Logout</a></li>
			</ul>
		</nav>';
	}
	elseif ( in_array( 'customer', (array) $user->roles ) ) {
		echo'
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<li><a href="/my-account/orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-07.png" /> Orders</a></li>
				<li><a href="/my-account/pre-orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Pre-Orders</a></li>
				<li><a href="/my-account/downloads/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-02.png" /> Downloads</a></li>
				<li><a href="/my-account/edit-account/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Account Details</a></li>
				<li><a href="/my-account/edit-address/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Account Addresses</a></li>
				<li><a href="/my-account/payment-methods/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-06.png" /> Payment Methods</a></li>
				<li><a href="/wp-login.php?action=logout"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Logout</a></li>
			</ul>
		</nav>';
	}
	else {
		echo'
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<li><a href="/my-account/orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-07.png" /> Orders</a></li>
				<li><a href="/my-account/pre-orders/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Pre-Orders</a></li>
				<li><a href="/my-account/downloads/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-02.png" /> Downloads</a></li>
				<li><a href="/my-account/edit-account/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Account Details</a></li>
				<li><a href="/my-account/edit-address/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-05.png" /> Account Addresses</a></li>
				<li><a href="/store-profile/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Public Profile</a></li>
				<li><a href="/my-account/payment-methods/"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-06.png" /> Payment Methods</a></li>
				<li><a href="/store-profile/?um_action=edit"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-03.png" />Shop Address</a></li>
				<li><a href="/wp-login.php?action=logout"><img src="/wp-content/uploads/2021/05/WEBSITE-Icons-04.png" /> Logout</a></li>
			</ul>
		</nav>';
	} ?>
</div>

<div class="quick-links">
	<h2>Quick Links</h2>
	<?php
	$user = wp_get_current_user();
	if ( in_array( 'um_certified-shop', (array) $user->roles ) ) {
		echo'
		<ul>
			<li><a href="/easy-order-form/">Easy Order Form</a></li>
			<li><a href="/tuesday-tips/">Videos</a></li>
			<li><a href="/blog/">Blog</a></li>
			<li><a href="/product-updates/">Product Updates</a></li>
			<li><a href="/events/">Events</a></li>
			<li><a href="/schedule-an-event/">Schedule an Event</a></li>
			<li><a href="/affiliate-dashboard/">Affiliate Dashboard</a></li>
			<li><a href="/product-category/pre-order/">Pre-Orders</a></li>
			<li><a href="/easy-order-form/">Easy Order Form</a></li>
		</ul>';
	}
	elseif ( in_array( 'Wholesale30', (array) $user->roles ) ) {
		echo'
		<ul>
			<li><a href="/distributor-sales-rep-portal/">Distributor Sales Rep Portal</a></li>
			<li><a href="/tuesday-tips/">Videos</a></li>
			<li><a href="/blog/">Blog</a></li>
			<li><a href="/product-updates/">Product Updates</a></li>
			<li><a href="/store-locator/">Find a Shop</a></li>
			<li><a href="/latest-products/">New Products</a></li>
			<li><a href="/events/">New Events</a></li>
		</ul>';
	}
	elseif ( in_array( 'um_wholesale-35', (array) $user->roles ) ) {
		echo'
		<ul>
			<li><a href="/distributor-sales-rep-portal/">Distributor Sales Rep Portal</a></li>
			<li><a href="/tuesday-tips/">Videos</a></li>
			<li><a href="/blog/">Blog</a></li>
			<li><a href="/product-updates/">Product Updates</a></li>
			<li><a href="/store-locator/">Find a Shop</a></li>
			<li><a href="/latest-products/">New Products</a></li>
			<li><a href="/events/">New Events</a></li>
		</ul>';
	}
	elseif ( in_array( 'customer', (array) $user->roles ) ) {
		echo'
		<ul>
			<li><a href="/tuesday-tips/">Videos</a></li>
			<li><a href="/blog/">Blog</a></li>
			<li><a href="/product-updates/">Product Updates</a></li>
			<li><a href="/store-locator/">Find a Shop</a></li>
			<li><a href="/latest-products/">New Products</a></li>
			<li><a href="/events/">New Events</a></li>
		</ul>';
	}
	else {
		echo'
		<ul>
			<li><a href="/easy-order-form/">Easy Order Form</a></li>
			<li><a href="/tuesday-tips/">Videos</a></li>
			<li><a href="/blog/">Blog</a></li>
			<li><a href="/product-updates/">Product Updates</a></li>
			<li><a href="/events/">Events</a></li>
			<li><a href="/affiliate-dashboard/">Affiliate Dashboard</a></li>
			<li><a href="/product-category/pre-order/">Pre-Orders</a></li>
			<li><a href="/easy-order-form/">Easy Order Form</a></li>
		</ul>';
	}?>
</div>

<div class="inspiration">
	<h2>Inspiration</h2>
	<ul>
	<?php
	 if ( in_array( 'customer', (array) $user->roles ) ) {
		$recent_posts = wp_get_recent_posts(array(
				'numberposts' => 3,
				'post_status' => 'publish'
		));
		foreach( $recent_posts as $post_item ) : ?>
				<li class="inspiration-post">
					<div>
						<a href="<?php echo get_permalink($post_item['ID']) ?>">
							<?php echo get_the_post_thumbnail($post_item['ID'], 'full'); ?></a>
					</div>
						<p><a href="<?php echo get_permalink($post_item['ID']) ?>"><?php echo $post_item['post_title'] ?></a></p>
				</li>
	<?php endforeach; }
	else {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'product_cat'    => 'bench-pillows'
    );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<li class="inspiration-product">
			<div>
				<?php
        global $product;
        echo '<a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().'</a> <p><a href="'.get_permalink().'">'.get_the_title().'</a></p></div></li>';
    endwhile;

    wp_reset_query();

		$args1 = array(
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'product_cat'    => 'fill-in-the-blank'
    );

    $loop = new WP_Query( $args1 );

    while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<li class="inspiration-product">
			<div>
				<?php
        global $product;
        echo '<a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().'</a> <p><a href="'.get_permalink().'">'.get_the_title().'</a></p></div></li>';
    endwhile;

    wp_reset_query();

		$args2 = array(
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'product_cat'    => 'pre-order'
    );

    $loop = new WP_Query( $args2 );

    while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<li class="inspiration-product">
			<div>
				<?php
        global $product;
        echo '<a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().'</a> <p><a href="'.get_permalink().'">'.get_the_title().'</a></p></div></li>';
    endwhile;

    wp_reset_query();
		}?>
</ul>
</div>

	<?php
	$user = wp_get_current_user();
	if ( in_array( 'customer', (array) $user->roles ) ) {
	}
	elseif ( in_array( 'wholesale_customer', (array) $user->roles ) ) {
		?><div class="shop-updates">
			<h2>What's New</h2>
		<ul>
		<?php $recent_posts = wp_get_recent_posts(array(
				'numberposts' => 6,
				'post_status' => 'publish'
		));
		foreach( $recent_posts as $post_item ) : ?>
				<li class="shop-post">
					<div>
						<a href="<?php echo get_permalink($post_item['ID']) ?>">
							<?php echo get_the_post_thumbnail($post_item['ID'], 'full'); ?></a>
					</div>
						<p><a href="<?php echo get_permalink($post_item['ID']) ?>"><?php echo $post_item['post_title'] ?></a></p>
				</li>
	<?php endforeach; ?>
</ul>
</div>
	<?php }
	elseif ( in_array( 'Wholesale15', (array) $user->roles ) ) {
		?><div class="shop-updates">
			<h2>What's New</h2>
		<ul>
		<?php $recent_posts = wp_get_recent_posts(array(
				'numberposts' => 6,
				'post_status' => 'publish'
		));
		foreach( $recent_posts as $post_item ) : ?>
				<li class="shop-post">
					<div>
						<a href="<?php echo get_permalink($post_item['ID']) ?>">
							<?php echo get_the_post_thumbnail($post_item['ID'], 'full'); ?></a>
					</div>
						<p><a href="<?php echo get_permalink($post_item['ID']) ?>"><?php echo $post_item['post_title'] ?></a></p>
				</li>
	<?php endforeach; ?>
</ul>
</div>
	<?php }
	elseif ( in_array( 'Wholesale20', (array) $user->roles ) ) {
		?><div class="shop-updates">
			<h2>What's New</h2>
		<ul>
		<?php $recent_posts = wp_get_recent_posts(array(
				'numberposts' => 6,
				'post_status' => 'publish'
		));
		foreach( $recent_posts as $post_item ) : ?>
				<li class="shop-post">
					<div>
						<a href="<?php echo get_permalink($post_item['ID']) ?>">
							<?php echo get_the_post_thumbnail($post_item['ID'], 'full'); ?></a>
					</div>
						<p><a href="<?php echo get_permalink($post_item['ID']) ?>"><?php echo $post_item['post_title'] ?></a></p>
				</li>
	<?php endforeach; ?>
</ul>
</div>
	<?php }
	else {
		?><div class="shop-updates">
			<h2>What's New With Certified Shops</h2>
		<ul>
		<?php $recent_posts = wp_get_recent_posts(array(
				'numberposts' => 6,
				'post_status' => 'publish',
				'category' 		=> 113
		));
		foreach( $recent_posts as $post_item ) : ?>
				<li class="shop-post">
					<div>
						<a href="<?php echo get_permalink($post_item['ID']) ?>">
							<?php echo get_the_post_thumbnail($post_item['ID'], 'full'); ?></a>
					</div>
						<p><a href="<?php echo get_permalink($post_item['ID']) ?>"><?php echo $post_item['post_title'] ?></a></p>
				</li>
	<?php endforeach; ?>
</ul>
</div>
	<?php }
	?>

<!-- <div class="woocommerce-MyAccount-content"> -->
	<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		// do_action( 'woocommerce_account_content' );
	// ?>
<!-- </div> -->

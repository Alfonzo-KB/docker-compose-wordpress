<?php
/**
 * Template part for login form.
 *
 * @package Highend
 * @since   3.6.1
 */

if ( ! is_user_logged_in() ) {
	?>
	<div class="hb-login-box">

		<form action="<?php echo esc_url( wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) ; ?>" id="hb-login-form-tmp" name="hb-login-form-tmp" method="post" class="hb-login-form" >

			<!-- Username -->
			<p><input type="text" id="username-tmp" name="log" placeholder="<?php esc_html_e( 'Username', 'hbthemes' ); ?>" class="required requiredField text-input"/></p>

			<!-- Password -->
			<p><input type="password" id="password-tmp" name="pwd" placeholder="<?php esc_html_e( 'Password', 'hbthemes' ); ?>" class="required requiredField text-input"></p>

			<!-- Remember -->
			<p class="hb-checkbox clearfix">
				<label><input name="rememberme" type="checkbox" id="rememberme-tmp" value="forever" class="hb-remember-checkbox" /><?php esc_html_e( 'Remember me?', 'hbthemes' ); ?></label>

				<?php if( get_option( 'users_can_register' ) ) { ?>
					<a href="<?php echo esc_url( site_url() ); ?>/wp-login.php?action=register" id="quick-register-button"><?php esc_html_e( 'Register', 'hbthemes' ); ?></a>
				<?php } ?>
			</p>

			<a href="#" id="hb-submit-login-form-tmp" class="hb-button no-three-d hb-small-button"><?php esc_html_e( 'Login', 'hbthemes' ); ?></a>

		</form>
	</div><!-- END .hb-login-box -->
	<?php
} else {
	$current_user = wp_get_current_user();
	?>
	<div class="hb-logout-box">

		<div class="avatar-image"><?php echo get_avatar( $current_user->ID, 64 ); ?></div>

		<h5><?php esc_html_e( 'You are logged in as', 'hbthemes' ); ?> <strong><?php echo esc_html( $current_user->display_name ); ?></strong></h5>

		<a class="hb-button hb-small-button" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'View your dashboard', 'hbthemes' ); ?></a>

		<small><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Log out from this account.', 'hbthemes' ); ?></a></small>

		<div class="big-overlay">
			<i class="hb-moon-user"></i>
		</div>

	</div><!-- END .hb-logout-box -->
	<?php
}

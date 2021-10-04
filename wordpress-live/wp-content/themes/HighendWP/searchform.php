<?php
/**
 * The template for displaying searchform.
 * 
 * @package Highend
 * @since   1.0.0
 */

?>

<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" placeholder="<?php esc_html_e( 'Enter keywords', 'hbthemes' ); ?>" name="s" id="s" autocomplete="off">
	<button type="submit" id="searchsubmit" aria-hidden="true" role="button"></button>
</form>

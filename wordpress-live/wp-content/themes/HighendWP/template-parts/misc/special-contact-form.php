<?php
/**
 * The template for special contact form.
 *
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.1
 */

?>
<form class="special-contact-form clearfix" id="sp-contact-form">

	<!-- Name -->
	<p><input type="text" name="special-contact-name" id="sp-contact-name" placeholder="<?php esc_html_e( 'Name', 'hbthemes' ); ?>" class="required requiredField" tabindex="65"/></p>

	<!-- Email -->
	<p><input type="email" class="required requiredField" name="special-contact-email" id="sp-contact-email" placeholder="<?php esc_html_e( 'Email', 'hbthemes' ); ?>" tabindex="66"/></p>

	<!-- Subject -->
	<p><input type="text" placeholder="<?php esc_html_e( 'Subject', 'hbthemes' ); ?>" name="hb_contact_subject" id="hb_contact_subject_id"/></p>

	<!-- Message -->
	<p><textarea class="required requiredField" name="special-contact-message" id="sp-contact-message" tabindex="67" placeholder="<?php esc_html_e( 'Your message...', 'hbthemes' ); ?>"></textarea></p>

	<!-- Submit -->
	<a href="#" id="special-submit-form" class="hb-button hb-third-dark"><?php esc_html_e( 'Send', 'hbthemes' ); ?></a>

	<input type="hidden" id="success_text_special" value="<?php esc_html_e( 'Message Sent', 'hbthemes' ); ?>"/>

</form>

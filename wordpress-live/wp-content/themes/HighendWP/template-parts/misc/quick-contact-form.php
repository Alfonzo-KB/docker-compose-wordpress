<?php
/**
 * The template for quick contact form.
 *
 * @package  Highend
 * @author   HB-Themes
 * @since    3.5.2
 * @version  3.5.2
 */

?>
<aside id="contact-panel">
	
	<h4 class="hb-focus-color"><?php echo highend_option( 'hb_quick_contact_box_title' ); ?></h4>

	<p><?php echo highend_option( 'hb_quick_contact_box_text' ); ?></p>

	<form id="contact-panel-form">
		
		<!-- Name -->
		<p><input type="text" placeholder="<?php esc_html_e( 'Name', 'hbthemes' ); ?>" name="hb_contact_name" id="hb_contact_name_id" class="required requiredField" tabindex="33"/></p>

		<!-- Email -->
		<p><input type="email" placeholder="<?php esc_html_e( 'Email', 'hbthemes' ); ?>" name="hb_contact_email" id="hb_contact_email_id" class="required requiredField" tabindex="34"/></p>

		<!-- Subject -->
		<p><input type="text" placeholder="<?php esc_html_e( 'Subject', 'hbthemes' ); ?>" name="hb_contact_subject" id="hb_contact_subject_id"/></p>

		<!-- Message -->
		<p><textarea placeholder="<?php esc_html_e( 'Your message...', 'hbthemes' ); ?>" name="hb_contact_message" id="hb_contact_message_id" class="required requiredField" tabindex="35"></textarea></p>

		<!-- Submit -->
		<a href="#" id="hb-submit-contact-panel-form" class="hb-button no-three-d hb-push-button hb-asbestos hb-small-button">
			<span class="hb-push-button-icon">
				<i class="hb-moon-paper-plane"></i>
			</span>
			<span class="hb-push-button-text"><?php echo highend_option('hb_quick_contact_box_button_title'); ?></span>
		</a>

		<input type="hidden" id="success_text" value="<?php esc_html_e( 'Message Sent!', 'hbthemes' ); ?>"/>
	</form>

</aside><!-- END #contact-panel -->

<a id="contact-button">
	<i class="hb-moon-envelop"></i>
</a><!-- END #hb-contact-button -->

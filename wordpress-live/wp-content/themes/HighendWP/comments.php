<?php
/**
 * The template for displaying comments.
 *
 * @package Highend
 * @since   1.0.0

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Password protected post.
if ( post_password_required() ) {
	echo wp_kses_post(
		sprintf(
			'<p class="nocomments">%1$s</p>',
			esc_html__( 'This post is password protected. Enter the password to view comments.', 'hbthemes' )
		)
	);
	return;
}
?>

<?php if ( have_comments() ) : ?>

	<div class="comments-section aligncenter" id="comments">
		<h4 class="semi-bold"><?php comments_number( esc_html__( 'No Comments', 'hbthemes' ), esc_html__( '1 Comment', 'hbthemes' ), esc_html__( '% Comments', 'hbthemes' ) ); ?></h4>
		<h5 class="leave-your-reply"><?php esc_html_e( 'Leave your reply.', 'hbthemes' ); ?></h5>
	</div>

	<ul class="comments-list" itemscope itemtype="https://schema.org/UserComments">
		<?php
		wp_list_comments(
			array(
				'type'     => 'comment',
				'callback' => 'highend_format_comment',
			)
		);
		?>
	</ul>

	<?php 
	$comments_pagination = paginate_comments_links(
		array(
			'prev_text' => '<i class="icon-angle-left"></i>', 
			'next_text' => '<i class="icon-angle-right"></i>',
			'echo'      => false
		)
	);

	if ( $comments_pagination ) {
		echo wp_kses_post( sprintf( '<div class="pagination">%1$s</div>', $comments_pagination ) );
	}
	?>

<?php endif; ?>

<?php if ( comments_open() ) : 

	$additional_text = highend_option( 'hb_comment_form_text' );
	$additional_text = empty( $additional_text ) ? '' : '<h5 class="aligncenter">' . esc_html( $additional_text ) . '</h5>';

	$args = array(
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => esc_html__( 'Leave a Reply', 'hbthemes' ) . $additional_text,
		'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'hbthemes' ) . $additional_text,
		'cancel_reply_link'    => esc_html__( 'Cancel Reply', 'hbthemes' ),
		'label_submit'         => esc_html__( 'Submit Comment', 'hbthemes' ),
		'comment_field'        => '<p><textarea class="required requiredField" name="comment" id="comment" cols="55" rows="10" tabindex="67"></textarea></p>',
		'must_log_in'          => '<p class="must-log-in">' . wp_kses_post( sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'hbthemes' ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . wp_kses_post( sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'hbthemes' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) ) . '</p>',
		'comment_notes_before' => '',
		'comment_notes_after'  => '<div class="clearfix"></div>',
	);

	comment_form( $args );

endif;

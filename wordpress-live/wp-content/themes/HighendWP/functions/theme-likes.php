<?php
if ( ! function_exists( 'hb_like_this' ) ) {
	function hb_like_this( $post_id, $action = 'get' ) {

		if ( ! is_numeric( $post_id ) ) {
			return;
		}

		switch ( $action ) {
		
			case 'get':
				$data = get_post_meta( $post_id, '_likes' );
				
				if ( ! isset( $data[0] ) || ! is_numeric( $data[0] ) ) {
					$data[0] = 0;
					add_post_meta( $post_id, '_likes', '0', true );
				}
				
				return $data[0];
			break;
			
			
			case 'update':

				if ( isset( $_COOKIE[ "like_" . $post_id ] ) ) {
					return;
				}
				
				$currentValue = get_post_meta( $post_id, '_likes' );
				
				if ( ! is_numeric( $currentValue[0] ) ) {
					$currentValue[0] = 0;
					add_post_meta( $post_id, '_likes', '1', true );
				}
				
				$currentValue[0]++;
				update_post_meta( $post_id, '_likes', $currentValue[0] );
				setcookie( 'like_' . $post_id, $post_id, time() * 20, '/' );
			break;

		}
	}
}

if ( ! function_exists( 'hb_print_likes' ) ) {
	function hb_print_likes( $post_id ) {

		if ( ! is_numeric( $post_id ) ) {
			return;
		}

		$output = '';
		$likes 	= hb_like_this( $post_id );
		$title 	= get_the_title( $post_id );
		$nonce  = ' data-nonce="' . wp_create_nonce( 'hb_like_this' ) . '"';

		if ( isset( $_COOKIE[ 'like_' . $post_id ] ) ) {
			return '<div class="like-holder like-button like-active" id="like-' . $post_id . '" title="'. __('You like this.','hbthemes') . '" data-post-id="' . $post_id . '"' . $nonce . '><i class="hb-moon-heart"></i><span>' . $likes . '</span></div>';	  	
		}

		return '<div title="' . __( 'Like this post.','hbthemes' ) . ' ' . $title . '" id="like-' . $post_id . '" class="like-holder like-button" data-post-id="' . $post_id . '"' . $nonce . '><i class="hb-moon-heart"></i><span>' . $likes . '</span></div>';	  	
			
	}
}

if ( ! function_exists( 'hb_print_portfolio_likes' ) ) {
	function hb_print_portfolio_likes( $post_id ) {
		
		if ( ! is_numeric( $post_id ) ) {
			return;
		}

		$output = '';
		$likes 	= hb_like_this( $post_id );
		$title 	= get_the_title( $post_id );
		$nonce  = ' data-nonce="' . wp_create_nonce( 'hb_like_this' ) . '"';

		if ( isset( $_COOKIE[ "like_" . $post_id ] ) ) {
			return '<div class="portfolio-like-holder"><div class="like-holder like-button like-active" id="like-' . $post_id . '" data-post-id="' . $post_id . '" title="' . __( 'You like this.','hbthemes' ) . '"' . $nonce . '><i class="hb-moon-heart"></i><span>' . $likes . '</span></div></div>';	  	
		}

		return '<div class="portfolio-like-holder"><div title="' . __( 'Like this post.', 'hbthemes' ) . ' ' . $title . '" id="like-' . $post_id . '" class="like-holder like-button" data-post-id="' . $post_id . '"><i class="hb-moon-heart"' . $nonce . '></i><span>' . $likes . '</span></div></div>';	  	
	}
}

if ( ! function_exists( 'setUpPostLikes' ) ) {
	function setUpPostLikes( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			return;
		}

		add_post_meta( $post_id, '_likes', '0', true );
	}
}
add_action( 'publish_post', 'setUpPostLikes' );


if ( ! function_exists( 'highend_update_likes' ) ) {
	function highend_update_likes() {

		check_ajax_referer( 'hb_like_this', 'nonce' );

		if ( ! isset( $_POST['post_id'] ) ) {
			wp_send_json_error();
		}

		$post_id = absint( intval( sanitize_text_field( $_POST['post_id'] ) ) );

		hb_like_this( $post_id, 'update' );

		$likes = hb_like_this( $post_id );

		wp_send_json_success( array(
			'message'	=> $likes
		) );
	}
}
add_action( 'wp_ajax_highend_like_this', 		'highend_update_likes' );
add_action( 'wp_ajax_nopriv_highend_like_this', 'highend_update_likes' );

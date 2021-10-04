<?php
/**
 * Highend Advertisment widget.
 *
 * @package Highend
 * @since   1.0.0
 */

/**
 * Widget class.
 *
 * @package Highend
 * @since   1.0.0
 */
class Highend_Advertisment_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		$this->defaults = array(
			'title'    => esc_html__( 'Advertisement', 'hbthemes' ),
			'new_tab'  => 'n',
			'ads1_img' => '',
			'ads1_url' => '',
			'ads2_img' => '',
			'ads2_url' => '',
			'ads3_img' => '',
			'ads3_url' => '',
			'ads4_img' => '',
			'ads4_url' => '',
			'ads5_img' => '',
			'ads5_url' => '',
		);
	
		// Widget basics.
		$widget_ops = array(
			'classname'   => 'hb_ad_twofifty_widget',
			'description' => esc_html__( 'A widget that displays 300px wide advertisement.', 'hbthemes' ),
		);

		// Widget controls.
		$control_ops = array();

		// Create the widget.
		parent::__construct( 'hb_ad_twofifty_widget', esc_html__( '[HB-Themes] Advertisement Image', 'hbthemes' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @since 1.0.0
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	function widget( $args, $instance ) {
				
		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];
		
		// Title.
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$target = 'y' === $instance['new_tab'] ? '_blank' : '_self';
	
		for ( $i = 1; $i < 6; $i++ ) {
		
			if ( $instance[ 'ads' . $i . '_img' ] ) {

				$before_image = $instance[ 'ads' . $i . '_url' ] ? '<a href="' . esc_url( $instance[ 'ads' . $i . '_url' ] ) . '" target="' . esc_attr( $target ) . '">' : '';
				$after_image  = $instance[ 'ads' . $i . '_url' ] ? '</a>' : '';

				?>
				<div class="ad-cell">
					
					<?php echo wp_kses_post( $before_image ); ?>

					<img src="<?php echo esc_url( $instance['ads' . $i . '_img'] ); ?>" alt="<?php esc_html_e( 'Advertisment', 'hbthemes' ); ?>" />

					<?php echo wp_kses_post( $after_image ); ?>

				</div>
				<?php
			}
		}

		echo $args['after_widget'];
	}
	
	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @since 1.0.0
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 * @return array The validated and (if necessary) amended settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['new_tab'] = $new_instance['new_tab'];

		for ( $i = 1; $i < 6; $i++ ) { 
			$instance[ 'ads' . $i . '_img' ] = $new_instance[ 'ads' . $i . '_img' ];
			$instance[ 'ads' . $i . '_url' ] = $new_instance[ 'ads' . $i . '_url' ];
		}

		return $instance;
	}
	
	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @since 1.0.0
	 * @param array $instance An array of the current settings for this widget.
	 * @return void
	 */
	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		
		<div class="highend-ad-widget">

			<!-- Title -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','hbthemes'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>

			<!-- New tab / target -->
		    <p>
				<label for="<?php echo $this->get_field_id( 'new_tab' ); ?>"><?php _e( 'Open in new tab?', 'hbthemes' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'new_tab' ); ?>" name="<?php echo $this->get_field_name( 'new_tab' ); ?>">
					<option value="y" <?php selected( 'y', $instance['new_tab'], true ); ?>><?php _e( 'Yes', 'hbthemes' ); ?></option>
					<option value="n" <?php selected( 'n', $instance['new_tab'], true ); ?>><?php _e( 'No', 'hbthemes' ); ?></option>
				</select>
			</p>

			<?php for ( $i = 1; $i < 6; $i++ ) { ?>

				<?php
				$preview_class = $instance[ 'ads' . $i . '_img' ] ? '' : ' hidden';
				$button_class  = $instance[ 'ads' . $i . '_img' ] ? ' hidden' : '';
				?>

				<div>
					<span class="heading"><?php echo esc_html( 'AD', 'hbthemes' ); ?> #<?php echo intval( $i ); ?> :</span>

					<p>
						<input id="<?php echo $this->get_field_id( 'ads' . $i . '_img' ); ?>" name="<?php echo $this->get_field_name( 'ads' . $i . '_img' ); ?>" value="<?php echo $instance[ 'ads' . $i . '_img' ]; ?>" class="img hidden" type="text" />

						<span class="ad-preview<?php echo esc_attr( $preview_class ); ?>">
							<img src="<?php echo esc_attr( $instance[ 'ads' . $i . '_img' ] ); ?>" />
							<a href="#" class="remove-image"><span class="dashicons dashicons-no"></span></a>
						</span>
					
						<input type="button" class="button select-image<?php echo esc_attr( $button_class ); ?>" value="<?php echo esc_attr( 'Select Image', 'hbthemes' ); ?>" />
				    </p>
					
					<p class="ad-link<?php echo esc_attr( $preview_class ); ?>">
						<input id="<?php echo $this->get_field_id( 'ads' . $i . '_url' ); ?>" name="<?php echo $this->get_field_name( 'ads' . $i . '_url' ); ?>" value="<?php echo $instance[ 'ads' . $i . '_url' ]; ?>" class="widefat" type="text" placeholder="https://"/>
					</p>

				</div>

			<?php } ?>

		</div>

	<?php
	}
}

/*
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'highend_advertisment_widget' );

/*
 * Register widget.
 */
function highend_advertisment_widget() {
	register_widget( 'Highend_Advertisment_Widget' );
}
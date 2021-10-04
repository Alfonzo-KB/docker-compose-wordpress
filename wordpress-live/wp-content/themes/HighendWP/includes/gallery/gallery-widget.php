<?php
/*
 * Plugin Name: Gallery Widget
 * Plugin URI: http://www.hb-themes.com
 * Description: A widget that displays Gallery posts
 * Version: 1.0
 * Author: HB-Themes
 * Author URI: http://www.hb-themes.com
 */

/*
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'hb_gallery_widgets' );

/*
 * Register widget.
 */
function hb_gallery_widgets() {
	register_widget( 'HB_Gallery_Widget' );
}

/*
 * Widget class.
 */
class hb_gallery_widget extends WP_Widget {

	/* ---------------------------- */
	/* -------- Widget setup -------- */
	/* ---------------------------- */
	
	function __construct() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gallery-widget', 'description' => __('A widget that displays your latest gallery posts.', 'hbthemes') );
		$control_ops = array ();
		/* Create the widget. */
		parent::__construct( 'hb_gallery_widget', __('[HB-Themes] Gallery Grid (Latest)','hbthemes'), $widget_ops, $control_ops );
	}

	/* ---------------------------- */
	/* ------- Display Widget -------- */
	/* ---------------------------- */
	
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$category = $instance['category'];
		$number = $instance['number'];
		$column_class = 'columns-3';

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display Dribbble */

			if ( !$number ) $number = -1;

			$unique_id = "unique_id_".rand(1,50000);
			if ( $category != "" ) {
				$loop = new WP_Query( array( 'post_type' => 'gallery' , 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => $number, 'tax_query' => array( array( 'taxonomy' => 'gallery_categories', 'field' => 'id', 'terms' => $category ) ) ) );
			} else {
				$loop = new WP_Query( array( 'post_type' => 'gallery' , 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => $number ) );
			}

			if ($number == 1 || $number == -1 ){$column_class = 'columns-1';}
			if ($number == 2){$column_class = 'columns-2';}
			if ($number == 3 || $number == 6 || $number == 9 || $number == 15 || $number == 18){$column_class = 'columns-3';}
			if ($number == 4 || $number == 8 || $number == 12 || $number == 16 || $number == 24){$column_class = 'columns-4';}

			echo '<div class="hb-stream '. $column_class .' clearfix" id="'.$unique_id.'"><ul>';
			$counter = (int) $number;
			
			if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
			
			$gallery_rel = rand (1,100000);

			$thumb = get_post_thumbnail_id(); 
			$image = $thumb ? highend_resize( $thumb, 250, 250 ) : false;
			$full_image = wp_get_attachment_image_src($thumb,'full');

			$gallery_attachments = rwmb_meta('hb_gallery_images', array('type' => 'plupload_image', 'size' => 'full') , get_the_ID());

			if ( !$image['url'] && !empty($gallery_attachments))
			{
				reset($gallery_attachments);
				$thumb = key($gallery_attachments);
				$image = highend_resize( $thumb, 75, 75 );
				$full_image = wp_get_attachment_image_src($thumb,'full');
			}

			if ( $thumb ) {
				$counter--;
				if ($counter>=0) {
					echo '<li><a href="'.$full_image[0].'" data-title="' . get_post_field('post_content', $thumb) . '" title="'.get_the_title().'" rel="prettyPhoto[gallery_'.$gallery_rel.']">';
					echo '<img src="'.$image['url'].'" width="'.$image['width'].'" height="'.$image['height'].'" />';
					echo '</a>';

					if ( !empty ( $gallery_attachments ) ) { ?>
						<div class="hb-reveal-gallery">
						<?php 
						foreach ( $gallery_attachments as $gall_key => $gal_att ) { 
							if ( $gall_key != $thumb) { ?>
							<a href="<?php echo $gal_att['url']; ?>" data-title="<?php echo $gal_att['description']; ?>" rel="prettyPhoto[gallery_<?php echo $gallery_rel; ?>]"></a>
							<?php } 
						} ?>
						</div>
					<?php }

					echo '</li>';			
				}
			}
			endwhile; endif;
			wp_reset_query();
			echo '</ul></div>';
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/* ---------------------------- */
	/* ------- Update Widget -------- */
	/* ---------------------------- */
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['number'] = strip_tags( $new_instance['number'] );

		/* No need to strip tags for.. */

		return $instance;
	}
	
	/* ---------------------------- */
	/* ------- Widget Settings ------- */
	/* ---------------------------- */
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'Gallery Widget',
		'category' => '',
		'number' => '9',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'hbthemes'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'From Category:','hbthemes'); ?></label>
			<select id="<?php echo $this->get_field_id( 'category' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'category' ); ?>">
				<option <?php if ( "" == $instance['category'] ) echo ' selected="selected"';?> value=''><?php _e('All','hbthemes'); ?></option>
				<?php
					$gallery_categories = get_terms('gallery_categories', 'orderby=name&hide_empty=0');
					if ( !empty($gallery_categories) )
					{
						foreach ($gallery_categories as $gallery_category) {
							?>
							<option <?php if ( $gallery_category->term_id == $instance['category'] ) echo ' selected="selected"';?> value='<?php echo $gallery_category->term_id; ?>'><?php echo $gallery_category->name; ?></option>
							<?php
						}
					}
				?>
			</select>
		</p>

		<!-- Number: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Gallery Item Number: ','hbthemes'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>

		
	<?php
	}
}
?>
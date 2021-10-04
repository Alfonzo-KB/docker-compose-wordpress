<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Custom Menu Walker admin page
 * 
 * @since 3.4.1
 */
class HB_Custom_Walker_Admin {


	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	function __construct() {

		add_filter( 'wp_setup_nav_menu_item', array( 
			$this, 
			'hb_add_custom_nav_fields' 
		) );

		add_action( 'wp_update_nav_menu_item', array( 
			$this, 
			'hb_update_custom_nav_fields'
		), 15, 3 );

		add_filter( 'wp_edit_nav_menu_walker', array( 
			$this, 
			'hb_edit_walker'
		), 15, 2 );

	}


	/**
	 * Custom fields for menu items
	 *
	 * @since 3.4.1
	 */
	function hb_add_custom_nav_fields( $menu_item ) {

		if ( get_post_meta( $menu_item->ID, '_menu_item_subtitle', true ) ) {
			$menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
			update_post_meta( $menu_item->ID, '_menu_item_icon', $menu_item->icon );
			//update_post_meta( $menu_item->ID, '_menu_item_subtitle', '' );
		} else {
			$menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
		}

		$menu_item->megamenu = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
		$menu_item->megamenu_background = get_post_meta( $menu_item->ID, '_menu_item_megamenu_background', true );
		$menu_item->megamenu_widgetarea = get_post_meta( $menu_item->ID, '_menu_item_megamenu_widgetarea', true );
		$menu_item->megamenu_background_position = get_post_meta( $menu_item->ID, '_menu_item_megamenu_background_position', true );
		$menu_item->megamenu_captions = get_post_meta( $menu_item->ID, '_menu_item_megamenu_captions', true );
		$menu_item->megamenu_columns = get_post_meta( $menu_item->ID, '_menu_item_megamenu_columns', true );
		$menu_item->alignment = get_post_meta( $menu_item->ID, '_menu_item_alignment', true );

        return $menu_item;
	}


	/**
	 * Save fields for menu items
	 *
	 * @since 3.4.1
	 */
	function hb_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

		$fields = array(
			array(
				'name' 	=> 'menu-item-icon',
				'key' 	=> '_menu_item_icon'
			),
			array(
				'name' 	=> 'menu-item-alignment',
				'key' 	=> '_menu_item_alignment'
			),
			array(
				'name' 	=> 'menu-item-megamenu',
				'key' 	=> '_menu_item_megamenu'
			),
			array(
				'name' 	=> 'menu-item-megamenu-background-position',
				'key' 	=> '_menu_item_megamenu_background_position'
			),
			array(
				'name' 	=> 'menu-item-megamenu-background',
				'key' 	=> '_menu_item_megamenu_background'
			),
			array(
				'name' 	=> 'menu-item-megamenu-widget-area',
				'key' 	=> '_menu_item_megamenu_widgetarea'
			),
			array(
				'name' 	=> 'menu-item-megamenu-captions',
				'key' 	=> '_menu_item_megamenu_captions'
			),
			array(
				'name' 	=> 'menu-item-megamenu-columns',
				'key' 	=> '_menu_item_megamenu_columns'
			)
		);

		foreach ( $fields as $field ) {
			if ( ! isset( $_REQUEST[ $field['name'] ][ $menu_item_db_id ] ) ) {
				$_REQUEST[ $field['name'] ][ $menu_item_db_id ] = '';
			}

			$icon_value = $_REQUEST[ $field['name'] ][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, $field['key'], $icon_value );


		}
	}

	function hb_edit_walker( $walker, $menu_id ) {
		return 'HB_Custom_Walker_Edit';
	}
}

new HB_Custom_Walker_Admin();


class HB_Custom_Walker_Edit extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = Array() ) {	
	}

	function end_lvl( &$output, $depth = 0, $args = Array() ) {
	}

	function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {

		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		global $wp_registered_sidebars;

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = false;
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		} elseif ( 'post_type_archive' == $item->type ) {
			$original_object = get_post_type_object( $item->object );
			if ( $original_object ) {
				$original_title = $original_object->labels->archives;
			}
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)', 'hbthemes' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)', 'hbthemes'), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';

		?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> </span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-up" aria-label="<?php esc_attr_e( 'Move up', 'hbthemes' ) ?>">&#8593;</a>
							|
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-down" aria-label="<?php esc_attr_e( 'Move down', 'hbthemes' ) ?>">&#8595;</a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
						?>" aria-label="<?php esc_attr_e( 'Edit menu item', 'hbthemes' ); ?>"><?php _e( 'Edit', 'hbthemes' ); ?></a>
					</span>
				</div>
			</div>

			<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if ( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e( 'URL', 'hbthemes' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e( 'Navigation Label', 'hbthemes' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="field-title-attribute field-attr-title description description-wide">
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e( 'Title Attribute', 'hbthemes' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php _e( 'Open link in a new tab', 'hbthemes' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e( 'CSS Classes (optional)', 'hbthemes' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e( 'Link Relationship (XFN)', 'hbthemes' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e( 'Description', 'hbthemes' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'hbthemes' ); ?></span>
					</label>
				</p>
				
				<div class="clear"></div>
				<div class="hb-menu-options wp-clearfix">
					<p class="hb-menu-title"><?php esc_html_e( 'Highend Menu Options', 'hbthemes' ); ?><a href="https://hb-themes.com/documentation/highend" class="align-right" target="_blank"><i class="dashicons dashicons-sos"></i><?php esc_html_e('How to use?', 'hbthemes'); ?></a></p>

					<div class="hb-menu-item hb-menu-icon wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Menu Icon', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Choose an icon that will be shown before the menu item.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<a href="#" class="button button-primary show-icon-modal" data-nonce="<?php echo wp_create_nonce( 'icon-picker-nonce' ); ?>" data-current="<?php echo $item->icon; ?>"><?php  esc_html_e ('Icon Picker', 'hbthemes'); ?></a>

								<?php $class = $item->icon ? '' : ' hidden'; ?>

								<a href="#" class="remove-selected-icon<?php echo $class; ?>"><i class="dashicons dashicons-no-alt"></i></a>

								<?php if ( $item->icon ) { ?>
									<span class="selected-icon"><?php echo $item->icon; ?></span>
								<?php } else { ?>
									<span class="selected-icon"><em><?php __('No icon selected', 'hbthemes'); ?></em></span>
								<?php } ?>

								<input type="hidden" id="edit-menu-item-icon-<?php echo $item_id; ?>" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo $item->icon; ?>" />
							</div>
						</div>
					</div><!-- // .hb-menu-item -->

					
					<div class="hb-menu-item hb-alignment wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Sub Menu Alignment', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Align sub-menus to the right if they overflow the right border.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<select id="menu-item-alignment-<?php echo $item_id; ?>" name="menu-item-alignment[<?php echo $item_id; ?>]">
									<option value="left" <?php selected( $item->alignment, 'left', true ); ?>><?php esc_html_e( 'Left', 'hbthemes' ); ?></option>
									<option value="right" <?php selected( $item->alignment, 'right', true ); ?>><?php esc_html_e( 'Right', 'hbthemes' ); ?></option>
								</select>
							</div>
						</div>
					</div><!-- // .hb-menu-item -->
					

					<div class="hb-menu-item hb-mega-menu wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Mega Menu', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Check this box if you want to turn this item into Mega Menu.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<?php $checked = $item->megamenu ? "checked='checked'" : ''; ?>
								<input type="checkbox" value="enabled" class="edit-menu-item-hb-megamenu-check" id="menu-item-megamenu-<?php echo $item_id; ?>" name="menu-item-megamenu[<?php echo $item_id; ?>]" <?php echo $checked; ?> />
							</div>
						</div>
					</div><!-- // .hb-menu-item -->

					<div class="hb-menu-item hb-caption wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Show Captions', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Check this box if you want to show Mega Menu column captions.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<?php $checked = $item->megamenu_captions ? "checked='checked'" : ''; ?>
								<input type="checkbox" value="enabled" id="menu-item-megamenu-captions-<?php echo $item_id; ?>" name="menu-item-megamenu-captions[<?php echo $item_id; ?>]" <?php echo $checked; ?> />
							</div>
						</div>
					</div><!-- // .hb-menu-item -->

					<div class="hb-menu-item hb-columns wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Columns', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Choose number of rows to display in Megamenu.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<select id="menu-item-megamenu-columns-<?php echo $item_id; ?>" name="menu-item-megamenu-columns[<?php echo $item_id; ?>]">

									<?php for ( $i = 2; $i <= 6; $i ++ ) { ?>
										<option value="columns-<?php echo $i; ?>" <?php selected( $item->megamenu_columns, 'columns-' . $i, true ); ?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
								
							</div>
						</div>
					</div><!-- // .hb-menu-item -->

					<div class="hb-menu-item hb-background wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Background Image', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Choose a background image for the Mega Menu. Leave empty if you are not going to use a background image.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section hb-menu-upload-image">
							<div>
								<div class="hb-upload-wrapper">
									<input class="hb-upload-url widefat" readonly="readonly" type="hidden" id="menu_item_megamenu_background_<?php echo $item_id; ?>" name="menu-item-megamenu-background[<?php echo $item_id; ?>]" value="<?php echo $item->megamenu_background; ?>">

									<a class="button button-primary hb-upload-button" id="menu_item_megamenu_background_button_<?php echo $item_id; ?>" href="#" data-title="<?php esc_html_e( 'Choose or upload a file', 'hbthemes' ); ?>" data-button="<?php esc_html_e( 'Use this image', 'hbthemes' ); ?>"><?php esc_html_e('Choose File', 'hbthemes'); ?></a>

									<?php $class = $item->megamenu_background ? '' : ' hidden'; ?>

									<a href="#" class="hb-remove-image<?php echo $class; ?>" id="menu_item_megamenu_background_<?php echo $item_id; ?>_remove"><i class="dashicons dashicons-no-alt"></i></a>
								</div>

								<div id="menu_item_megamenu_background_<?php echo $item_id; ?>-preview" class="show-upload-image">
									
									<img src="<?php echo $item->megamenu_background; ?>"/>

								</div>
							</div>
						</div>
					</div><!-- // .hb-menu-item -->

					<div class="hb-menu-item hb-image-position wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Image Position', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('Select image position.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<select id="menu-item-megamenu-background-position-<?php echo $item_id; ?>" name="menu-item-megamenu-background-position[<?php echo $item_id; ?>]">
									<option value="stretched" <?php selected( $item->megamenu_background_position, 'stretched', true ); ?>><?php esc_html_e( 'Stretched Centered', 'hbthemes' ); ?></option>
									<option value="left top" <?php selected( $item->megamenu_background_position, 'left top', true ); ?>><?php esc_html_e( 'Left Top', 'hbthemes' ); ?></option>
									<option value="left center" <?php selected( $item->megamenu_background_position, 'left center', true ); ?>><?php esc_html_e( 'Left Center', 'hbthemes' ); ?></option>
									<option value="left bottom" <?php selected( $item->megamenu_background_position, 'left bottom', true ); ?>><?php esc_html_e( 'Left Bottom', 'hbthemes' ); ?></option>
									<option value="center top" <?php selected( $item->megamenu_background_position, 'center top', true ); ?>><?php esc_html_e( 'Center Top', 'hbthemes' ); ?></option>
									<option value="center center" <?php selected( $item->megamenu_background_position, 'center center', true ); ?>><?php esc_html_e( 'Center Center', 'hbthemes' ); ?></option>
									<option value="center bottom" <?php selected( $item->megamenu_background_position, 'center bottom', true ); ?>><?php esc_html_e( 'Center Bottom', 'hbthemes' ); ?></option>
									<option value="right top" <?php selected( $item->megamenu_background_position, 'right top', true ); ?>><?php esc_html_e( 'Right Top', 'hbthemes' ); ?></option>
									<option value="right center" <?php selected( $item->megamenu_background_position, 'right center', true ); ?>><?php esc_html_e( 'Right Center', 'hbthemes' ); ?></option>
									<option value="right bottom" <?php selected( $item->megamenu_background_position, 'right bottom', true ); ?>><?php esc_html_e( 'Right Bottom', 'hbthemes' ); ?></option>
								</select>
							</div>
						</div>
					</div><!-- // .hb-menu-item -->
				
				
					<div class="hb-menu-item hb-widgets wp-clearfix">
						<div class="hb-left-section">
							<div class="hb-menu-item-title"><?php esc_html_e('Show Widget', 'hbthemes'); ?></div>
							<div class="hb-menu-item-desc"><?php esc_html_e('If you want to show widgets inside your Mega Menu, you can choose which widgetized section will be shown inside this menu item.', 'hbthemes'); ?></div>
						</div>

						<div class="hb-right-section">
							<div>
								<select id="menu-item-megamenu-widget-area-<?php echo $item_id; ?>" name="menu-item-megamenu-widget-area[<?php echo $item_id; ?>]" class="hb-menu-item-megamenu-widget">
									<option value="0"><?php _e( 'Select Widget Area', 'hbthemes' ); ?></option>
									<?php
									if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ) {
										foreach ( $wp_registered_sidebars as $sidebar ) { ?>
											<option value="<?php echo $sidebar['id']; ?>" <?php selected( $item->megamenu_widgetarea, $sidebar['id'] ); ?>><?php echo $sidebar['name']; ?></option>
									<?php } 
									} ?>
								</select>
							</div>
						</div>
					</div><!-- // .hb-menu-item -->

				</div>
					

				<fieldset class="field-move hide-if-no-js description description-wide">
					<span class="field-move-visual-label" aria-hidden="true"><?php _e( 'Move', 'hbthemes' ); ?></span>
					<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php _e( 'Up one', 'hbthemes' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php _e( 'Down one', 'hbthemes' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
					<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
					<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php _e( 'To the top', 'hbthemes' ); ?></button>
				</fieldset>

				<div class="menu-item-actions description-wide submitbox">
					<?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( __('Original: %s', 'hbthemes' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							admin_url( 'nav-menus.php' )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php _e( 'Remove', 'hbthemes' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'hbthemes' ); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}
}
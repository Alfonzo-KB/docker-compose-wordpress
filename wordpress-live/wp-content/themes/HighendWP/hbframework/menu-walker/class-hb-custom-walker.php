<?php
class HB_Custom_Walker extends Walker_Nav_Menu {
 
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		$style = '';
		if ( $depth === 0 && $this->hb_megamenu ) {

			if ( $this->hb_megamenu_bg ) {
				$style = 'background-image:url("' . $this->hb_megamenu_bg . '");';
			}

			if ( $this->hb_megamenu_position == 'stretched' ) {
				$style .= 'background-position:center center;background-size:cover;';
			} else if ( $this->hb_megamenu_position ) {
				$style .= 'background-position:' . $this->hb_megamenu_position . ';';
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names style='$style'>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$this->hb_megamenu_bg = get_post_meta( $item->ID, '_menu_item_megamenu_background', true );
		$this->hb_megamenu_position = get_post_meta( $item->ID, '_menu_item_megamenu_background_position', true );
		$this->hb_megamenu = get_post_meta( $item->ID, '_menu_item_megamenu', true);
		$this->hb_megamenu_widget = get_post_meta( $item->ID, '_menu_item_megamenu_widgetarea', true);
		$this->hb_alignment = get_post_meta( $item->ID, '_menu_item_alignment', true );


		if ( 0 === $depth && $this->hb_megamenu ) {
			$classes[] = 'megamenu';
			$classes[] = get_post_meta( $item->ID, '_menu_item_megamenu_columns', true );

			if ( get_post_meta( $item->ID, '_menu_item_megamenu_captions', true ) ) {
				$classes[] = 'menu-caption';
			} else {
				$classes[] = 'no-caption';
			}
		}

		if ( $this->hb_alignment === 'right' && $depth === 0 ) {
			$classes[] = 'right-align';
		}

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$description = ! empty( $item->description ) ? '<span class="hb-menu-description">' . esc_attr( $item->description ) . '</span>' : '';

		$item_output = $args->before;

		if ( $depth === 1 && is_active_sidebar( $this->hb_megamenu_widget ) ) {

			$item_output .= '<div class="megamenu-widgets-container">';
			ob_start();
			dynamic_sidebar( $this->hb_megamenu_widget );
			$item_output .= ob_get_clean();
			$item_output .= '</div>';

		} else {

			$item_output .= '<a'. $attributes .'>';

			$hb_menu_icon = get_post_meta( $item->ID, '_menu_item_icon', true );
			if ( $hb_menu_icon ) {
				$item_output .= '<i class="' . $hb_menu_icon . '"></i>';
			}

			$item_output .= $args->link_before . $title . $description . $args->link_after;
			$item_output .= '</a>';

		}

		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}
}
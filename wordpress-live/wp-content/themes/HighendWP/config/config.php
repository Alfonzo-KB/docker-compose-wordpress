<?php if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

$theme = wp_get_theme();

return array(
    'prefix'                => 'hb_',
    'wp_version'            => get_bloginfo('version'),
    'theme_name'            => $theme->get('Name'),
    'theme_version'         => $theme->get('Version'),
    'theme_slug'            => sanitize_title($theme->get('Name')),
    'theme_docs_url'        => 'https://documentation.hb-themes.com/' . sanitize_title($theme->get('Name')),
    'demos'                 => array(
        'main-demo' =>
            array(
                'name'              => __('Main Demo', 'hbthemes'),
                'slug'              => 'main-demo',
                'category'          => array( 'Business', 'Blog', 'Photography', 'Portfolio', 'Shop' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                    'woocommerce'       => 'WooCommerce',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend_wp/',
                'screenshot'        => 'config/screenshots/preview-main.jpg',
                'is_shop'           => true,
                'has_sidebars'      => true,
            ),
        'photography' =>
            array(
                'name'              => __('Photography', 'hbthemes'),
                'slug'              => 'photography',
                'category'          => array( 'Portfolio', 'Photography' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                    'essential-grid'    => 'Essential Grid',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/photography',
                'screenshot'        => 'config/screenshots/preview-photography.jpg',
                'has_ess_grid'      => true,
                'has_sidebars'      => true,
            ),
        'presentation' =>
            array(
                'name'              => __('Presentation', 'hbthemes'),
                'slug'              => 'presentation',
                'category'          => array( 'Business', 'Blog', 'Portfolio' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                    'essential-grid'    => 'Essential Grid',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/presentation',
                'screenshot'        => 'config/screenshots/preview-presentation.jpg',
                'has_ess_grid'      => true,
                'has_sidebars'      => true,
            ),
        'simple-blog' =>
            array(
                'name'              => __('Simple Blog', 'hbthemes'),
                'slug'              => 'simple-blog',
                'category'          => array( 'Shop', 'Blog' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'essential-grid'    => 'Essential Grid',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/simple-blog',
                'screenshot'        => 'config/screenshots/preview-sblog.jpg',
                'has_ess_grid'      => true,
                'has_sidebars'      => true,
            ),
        'minimalistic' =>
            array(
                'name'              => __('Minimal', 'hbthemes'),
                'slug'              => 'minimalistic',
                'category'          => array( 'Portfolio', 'Blog' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                    'essential-grid'    => 'Essential Grid',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/minimal',
                'screenshot'        => 'config/screenshots/preview-minimalistic.jpg',
                'has_ess_grid'      => true,
                'has_sidebars'      => true,
            ),
        'cafe' =>
            array(
                'name'              => __('Café', 'hbthemes'),
                'slug'              => 'cafe',
                'category'          => array( 'Business' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/cafe',
                'screenshot'        => 'config/screenshots/preview-cafe.jpg',
                'has_sidebars'      => true,
            ),
        'jasper' =>
            array(
                'name'              => __('Jasper', 'hbthemes'),
                'slug'              => 'jasper',
                'category'          => array( 'Portfolio', 'Photography' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'essential-grid'    => 'Essential Grid',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/jasper',
                'screenshot'        => 'config/screenshots/preview-jasper.jpg',
                'has_ess_grid'      => true,
                'has_sidebars'      => true,
            ),
        'church' =>
            array(
                'name'              => __('Church', 'hbthemes'),
                'slug'              => 'church',
                'category'          => array( 'Business' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/church',
                'screenshot'        => 'config/screenshots/preview-church.jpg',
                'has_sidebars'      => true,
            ),
        'landing' =>
            array(
                'name'              => __('Landing/Startup', 'hbthemes'),
                'slug'              => 'landing',
                'category'          => array( 'Business' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/app',
                'screenshot'        => 'config/screenshots/preview-landing.jpg',
                'has_sidebars'      => false,
            ),
        'life-coach' =>
            array(
                'name'              => __('Life Coach', 'hbthemes'),
                'slug'              => 'life-coach',
                'category'          => array( 'Business', 'Blog' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/life-coach',
                'screenshot'        => 'config/screenshots/preview-life-coach.jpg',
                'has_sidebars'      => true,
            ),
        'bloggera' =>
            array(
                'name'              => __('Bloggera', 'hbthemes'),
                'slug'              => 'bloggera',
                'category'          => array( 'Shop', 'Blog' ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/bloggera',
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                    'woocommerce'       => 'WooCommerce',
                    'wp-instagram-widget'=> 'WP Instagram Widget',
                ),
                'screenshot'        => 'config/screenshots/preview-bloggera.jpg',
                'is_shop'           => true,
                'has_sidebars'      => true,
            ),
        'online-shop' =>
            array(
                'name'              => __('Online Shop', 'hbthemes'),
                'slug'              => 'online-shop',
                'category'          => array( 'Shop', 'Business' ),
                'required_plugins'  => array(
                    'js_composer'       => 'Visual Composer',
                    'revslider'         => 'Revolution Slider',
                    'woocommerce'       => 'WooCommerce',
                ),
                'live_preview'      => 'https://hb-themes.com/themes/highend/online-shop',
                'screenshot'        => 'config/screenshots/preview-online-shop.jpg',
                'is_shop'           => true,
                'has_sidebars'      => true,
            ),

    ),
    'plugins'               => array(
        'js_composer' => array(
            'name'          => 'WPBakery (formerly Visual Composer)',
            'slug'          => 'js_composer',
            'source'        => 'https://hb-themes.com/repository/plugins/js_composer.zip',
            'required'      => true,
            'version'       => '6.0.5',
            'screenshot'    => 'config/screenshots/plugin-visualcomposer.jpg'
        ),
        'woocommerce' => array(
            'name'          => 'WooCommerce',
            'slug'          => 'woocommerce',
            'version'       => '3.8.1',
            'required'      => false,
            'screenshot'    => 'config/screenshots/plugin-woocommerce.jpg'
        ),
        'contact-form-7' => array(
            'name'          => 'Contact Form 7',
            'slug'          => 'contact-form-7',
            'version'       => '5.1.6',
            'required'      => false,
            'screenshot'    => 'config/screenshots/plugin-contactform7.jpg'
        ),
        'revslider' => array(
            'name'          => 'Slider Revolution',
            'slug'          => 'revslider',
            'source'        => 'https://hb-themes.com/repository/plugins/revslider.zip',
            'required'      => false,
            'version'       => '6.1.5',
            'screenshot'    => 'config/screenshots/plugin-revolutionslider.jpg'
        ),
        'LayerSlider' => array(
            'name'          => 'LayerSlider WP',
            'slug'          => 'LayerSlider',
            'source'        => 'https://hb-themes.com/repository/plugins/layerslider.zip',
            'required'      => false,
            'version'       => '6.9.2',
            'screenshot'    => 'config/screenshots/plugin-layerslider.jpg'
        ),
        'essential-grid' => array(
            'name'          => 'Essential Grid',
            'slug'          => 'essential-grid',
            'source'        => 'https://hb-themes.com/repository/plugins/essential-grid.zip',
            'required'      => false,
            'version'       => '2.3.5',
            'screenshot'    => 'config/screenshots/plugin-essentialgrid.jpg'
        ),
        'wp-instagram-widget' => array(
            'name'          => 'WP Instagram Widget',
            'slug'          => 'wp-instagram-widget',
            'source'        => 'https://hb-themes.com/repository/plugins/wp-instagram-widget.zip',
            'version'       => '2.0.4',
            'required'      => false,
            'screenshot'    => 'config/screenshots/plugin-wpinstagramwidget.jpg'
        ),
        'socialsnap' => array(
            'name'          => 'Social Snap',
            'slug'          => 'socialsnap',
            'version'       => '1.1.7',
            'required'      => false,
            'screenshot'    => 'config/screenshots/plugin-socialsnap.jpg'
        ),
    ),
);

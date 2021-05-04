<?php
/**
 * Init Configuration
 */
Kirki::add_config( 'essential', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'option',
	'option_name'   => 'essential_themes_options',
) );

/**
 * General Themes Options
 */
Kirki::add_panel( 'general_options', array(
    'priority'    => 1,
    'title'       => esc_attr__( 'General Options', 'essential' ),
    'description' => esc_attr__( 'All Settings', 'essential' ),
) );

Kirki::add_section( 'header_options', array(
    'panel'          => 'general_options', // Not typically needed.
    'title'          => esc_attr__( 'Header Options', 'essential' ),
    'description'    => '',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

/* Type Logo */
Kirki::add_field( 'essential', array(
	'section'  => 'header_options',
	'settings' => 'essential_type_logo',
	'label'    => esc_attr__( 'Type Logo', 'essential' ),
	'type'     => 'select',
	'priority' => 10,
	'default'  => 'image',
	'choices'     => array(
		'hide' => esc_attr__( 'Hide Logo', 'essential' ),
		'image' => esc_attr__( 'Image Logo', 'essential' ),
		'text' => esc_attr__( 'Text Logo', 'essential' )
	),
) );

/* Image Logo */
Kirki::add_field( 'essential', array(
	'section'     => 'header_options',
	'settings'    => 'essential_logo_image',
	'type'        => 'image',
	'label'       => esc_attr__( 'Image Logo', 'essential' ),
	'default'     => '',
	'priority'    => 10,
	'active_callback'    => array(
		array(
			'setting'  => 'essential_type_logo',
			'operator' => '==',
			'value'    => 'image',
		),
	),
) );

/* Text Logo */
Kirki::add_field( 'essential', array(
	'section'     => 'header_options',
	'settings'    => 'essential_logo_text',
	'type'        => 'text',
	'label'       => esc_attr__( 'Text for Logo', 'essential' ),
	'default'     => 'ESSENTIAL',
	'priority'    => 10,
	'active_callback'    => array(
		array(
			'setting'  => 'essential_type_logo',
			'operator' => '==',
			'value'    => 'text',
		),
	),
) );

Kirki::add_field( 'essential', array(
	'section'     => 'header_options',
	'settings'    => 'essential_page_preloader',
	'label'       => esc_attr__( 'Enable preloader', 'essential' ),
	'type'        => 'switch',
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_attr__( 'On', 'essential' ),
		'off' => esc_attr__( 'Off', 'essential' ),
	),
) );

Kirki::add_section( 'footer_options', array(
    'panel'          => 'general_options', // Not typically needed.
    'title'          => esc_attr__( 'Footer Options', 'essential' ),
    'description'    => '',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

/* Text Logo */
Kirki::add_field( 'essential', array(
	'section'     => 'footer_options',
	'settings'    => 'essential_footer_logo_text',
	'type'        => 'text',
	'label'       => esc_attr__( 'Footer Text for Logo', 'essential' ),
	'default'     => 'ESSENTIAL',
	'priority'    => 10,
) );

/* Type Footer Icons  */
Kirki::add_field( 'essential', array(
	'section'  => 'footer_options',
	'settings' => 'essential_type_icons',
	'label'    => esc_attr__( 'Type Icons', 'essential' ),
	'type'     => 'select',
	'priority' => 10,
	'default'  => 'ionicons',
	'choices'     => array(
		'ionicons' => 'Ionicons',
		'fontawesome' => 'Font Awesome',
		'etline' => 'Et-Line',
	),
) );

/* Footer Socials */

// Ionicons
$ionicons_icons = array_map(function($a) {	return array_keys($a)[0]; }, ionicons_icons());
$ionicons_icons = array_combine($ionicons_icons, $ionicons_icons);

Kirki::add_field( 'essential', array(
	'section'     => 'footer_options',
	'settings'    => 'essential_footer_socials_ionicons',
	'type'        => 'repeater',
	'label'       => esc_attr__( 'Footer Socials Links', 'essential' ),
	'priority'    => 10,
	'default'     => array( ),
	'fields' => array(
		'link_icon' => array(
		    'section'     => 'footer_options',
		    'type'        => 'select',
		    'settings'    => 'range_font_icon',
		    'label'       => esc_attr__( 'This is the label', 'essential' ),
		    'description' => esc_attr__( 'This is the control description', 'essential' ),
		    'help'        => esc_attr__( 'This is some extra help text.', 'essential' ),
		    'default'     => 'option-1',
		    'priority'    => 10,
		    'choices'     => $ionicons_icons,
		),
		'link_url' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link URL', 'essential' ),
			'description' => esc_attr__( 'This will be the link URL', 'essential' ),
			'default'     => '',
		),
	),
	'active_callback'    => array(
		array(
			'setting'  => 'essential_type_icons',
			'operator' => '==',
			'value'    => 'ionicons',
		),
	),
) );

// Font Awesome
Kirki::add_field( 'essential', array(
	'section'     => 'footer_options',
	'settings'    => 'essential_footer_socials_fontawesome',
	'type'        => 'repeater',
	'label'       => esc_attr__( 'Footer Socials Links', 'essential' ),
	'priority'    => 10,
	'default'     => array( ),
	'fields' => array(
		'link_icon' => array(
		    'section'     => 'footer_options',
		    'type'        => 'select',
		    'settings'    => 'range_font_icon',
		    'label'       => esc_attr__( 'This is the label', 'essential' ),
		    'description' => esc_attr__( 'This is the control description', 'essential' ),
		    'help'        => esc_attr__( 'This is some extra help text.', 'essential' ),
		    'default'     => 'option-1',
		    'priority'    => 10,
		    'choices'     => fontawesome_icons(),
		),
		'link_url' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link URL', 'essential' ),
			'description' => esc_attr__( 'This will be the link URL', 'essential' ),
			'default'     => '',
		),
	),
	'active_callback'    => array(
		array(
			'setting'  => 'essential_type_icons',
			'operator' => '==',
			'value'    => 'fontawesome',
		),
	),
) );

// Et-Line
$et_line_icons = array_map(function($a) {  return array_keys($a)[0]; }, et_line_icons());
$et_line_icons = array_combine($et_line_icons,$et_line_icons);

Kirki::add_field( 'essential', array(
	'section'     => 'footer_options',
	'settings'    => 'essential_footer_socials_etline',
	'type'        => 'repeater',
	'label'       => esc_attr__( 'Footer Socials Links', 'essential' ),
	'priority'    => 10,
	'default'     => array( ),
	'fields' => array(
		'link_icon' => array(
		    'section'     => 'footer_options',
		    'type'        => 'select',
		    'settings'    => 'range_font_icon',
		    'label'       => esc_attr__( 'This is the label', 'essential' ),
		    'description' => esc_attr__( 'This is the control description', 'essential' ),
		    'help'        => esc_attr__( 'This is some extra help text.', 'essential' ),
		    'default'     => 'option-1',
		    'priority'    => 10,
		    'choices'     => $et_line_icons,
		),
		'link_url' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link URL', 'essential' ),
			'description' => esc_attr__( 'This will be the link URL', 'essential' ),
			'default'     => '',
		),
	),
	'active_callback'    => array(
		array(
			'setting'  => 'essential_type_icons',
			'operator' => '==',
			'value'    => 'etline',
		),
	),
) );

Kirki::add_field( 'essential', array(
	'section'     => 'footer_options',
	'settings'    => 'essential_back_to_top',
	'label'       => esc_attr__( 'Back to top button', 'essential' ),
	'type'        => 'switch',
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_attr__( 'On', 'essential' ),
		'off' => esc_attr__( 'Off', 'essential' ),
	),
) );

/* Copyright */
Kirki::add_field( 'essential', array(
	'section'  => 'footer_options',
	'settings' => 'essential_copyright_text',
	'label'    => esc_attr__( 'Copyright', 'essential' ),
	'type'     => 'textarea',
	'priority' => 10,
	'default'  => '&copy; ' . date('Y') . esc_attr__(' Essential. All Rights Reserved', 'essential' ),
) );

Kirki::add_section( 'onepage_options', array(
    'panel'          => 'general_options',
    'title'          => esc_attr__( 'One Page Options', 'essential' ),
    'description'    => '',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'essential', array(
	'section'     => 'onepage_options',
	'settings'    => 'enable_onepage',
	'label'       => esc_attr__( 'Enable onepage', 'essential' ),
	'type'        => 'switch',
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'yes'  => esc_attr__( 'Yes', 'essential' ),
		'no' => esc_attr__( 'No', 'essential' ),
	),
) );

$blog_id = get_option( 'page_for_posts' ) ? get_option( 'page_for_posts' ) : '';

Kirki::add_field( 'essential', array(
	'section'     => 'onepage_options',
	'settings'    => 'essential_sortable_pages',
	'label'       => esc_attr__( 'Sortable pages', 'essential' ),
	'type'        => 'sortable',
	'default'     => array(),
	'choices'     => Kirki_Helper::get_posts(array('post_type' => 'page', 'posts_per_page' => '100', 'exclude'=> $blog_id)),
	'priority'    => 10,
	'active_callback'    => array(
		array(
			'setting'  => 'enable_onepage',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_section( 'parallax_options', array(
    'panel'          => 'general_options',
    'title'          => esc_attr__( 'Parallax Options', 'essential' ),
    'description'    => '',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'essential', array(
    'type'        => 'repeater',
    'label'       => esc_attr__( 'Slider Images/Slide', 'essential' ),
    'section'     => 'parallax_options',
    'priority'    => 10,
    'settings'    => 'essential_parallax_images',
    'default'     => array(
        array(
            'image' => get_template_directory_uri() . '/assets/img/slider1.jpg',
        ),
        array(
            'image' => get_template_directory_uri() . '/assets/img/slider2.jpg',
        ),
        array(
            'image' => get_template_directory_uri() . '/assets/img/slider3.jpg',
        ),
    ),
    'fields' => array(
        'image' => array(
            'type'        => 'image',
            'label'       => esc_attr__( 'Slide Image', 'essential' ),
            'description' => esc_attr__( 'Select slide image', 'essential' ),
            'default'     => '',
        ),
    )
) );

Kirki::add_section( 'blog_options', array(
    'panel'          => 'general_options',
    'title'          => esc_attr__( 'Blog Options', 'essential' ),
    'description'    => '',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'essential', array(
	'section'     => 'blog_options',
	'settings'    => 'blog_sidebar',
	'label'       => esc_attr__( 'Enable blog page sidebar', 'essential' ),
	'type'        => 'switch',
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'yes'  => esc_attr__( 'Yes', 'essential' ),
		'no' => esc_attr__( 'No', 'essential' ),
	),
) );

Kirki::add_field( 'essential', array(
	'section'     => 'blog_options',
	'settings'    => 'single_post_sidebar',
	'label'       => esc_attr__( 'Enable single post sidebar', 'essential' ),
	'type'        => 'switch',
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'yes'  => esc_attr__( 'Yes', 'essential' ),
		'no' => esc_attr__( 'No', 'essential' ),
	),
) );

Kirki::add_field( 'essential', array(
	'section'     => 'blog_options',
	'settings'    => 'post_navigation',
	'label'       => esc_attr__( 'Show post navigation', 'essential' ),
	'type'        => 'switch',
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'yes'  => esc_attr__( 'Yes', 'essential' ),
		'no' => esc_attr__( 'No', 'essential' ),
	),
) );

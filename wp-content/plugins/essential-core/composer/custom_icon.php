<?php

function essential_custom_icon_css() {
	wp_enqueue_style( 'et-line', get_theme_file_uri('/assets/css/et-line.min.css'), array(), '1.0.0', 'all' );
 	wp_enqueue_style( 'ionicons', 'https://unpkg.com/ionicons@4.2.2/dist/css/ionicons.min.css', array(), '1.0.0', 'all' );
}
add_action( 'admin_print_styles', 'essential_custom_icon_css' );
add_action( 'wp_print_styles', 'essential_custom_icon_css' );

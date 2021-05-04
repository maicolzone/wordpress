<?php
add_action( 'init', 'essential_register_portfolio');
if (!function_exists('essential_register_portfolio')) {
	function essential_register_portfolio() {

		// New Post Type
		register_post_type( 'projects',
		    array(
		      'labels' => array(
		          'name' => __( 'Projects' ),
		          'singular_name' => __( 'Item' )
		      ),
		      'menu_icon' => 'dashicons-format-gallery',
		      'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		      'public' => true,
		      'has_archive' => true,
		      'rewrite' => array('slug' => 'projects-item'),
		    )
		);

		// New Taxonomy
		register_taxonomy(
		    'projects-category',
		    'projects',
		    array(
		      'label' => __( 'Categories' ),
		      'rewrite' => array( 'slug' => 'projects-category' ),
		      'hierarchical' => true,
		    )
		);
		//end of register 

	}
}
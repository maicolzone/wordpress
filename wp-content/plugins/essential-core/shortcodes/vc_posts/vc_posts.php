<?php

// remove default post types
$post_types = array_diff( get_post_types(), array(
	'revision',
	'attachment',
	'nav_menu_item',
	'booked_appointments',
	'frm_styles',
	'vc4_templates',
	'vc_grid_item',
	'custom_css',
	'frm_form_actions',
	'customize_changeset',
	'wpcf7_contact_form',
	'mc4wp-form'
));


// get all post types
if ( ! function_exists( 'get_vc_post_types' ) ) {
	function get_vc_post_types($post_types){
		$list = array();
		foreach ( $post_types as $post_type ) {
			$list[ucwords($post_type)] = $post_type;
		}
		return $list;
	}
}

/**
 *
 * element values post, page, categories
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'vc_projects_element_values' ) ) {
	function vc_projects_element_values( $type = '', $query_args = array() ) {

		$options = array();

		switch ( $type ) {

			case 'pages':
			case 'page':
				$pages = get_pages( $query_args );

				if ( ! empty( $pages ) ) {
					foreach ( $pages as $page ) {
						$options[ $page->post_title ] = $page->ID;
					}
				}
				break;

			case 'posts':
			case 'post':
				$posts = get_posts( $query_args );

				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						$options[ $post->post_title ] = lcfirst( $post->post_title );
					}
				}
				break;

			case 'tags':
			case 'tag':

				$tags = get_terms( $query_args['taxonomies'] );
				if ( ! empty( $tags ) ) {
					foreach ( $tags as $tag ) {
						$options[ $tag->name ] = $tag->term_id;
					}
				}
				break;

			case 'categories':
			case 'category':

				$categories = get_categories( $query_args );

				if ( ! empty( $categories ) ) {

					if ( is_array( $categories ) ) {
						foreach ( $categories as $category ) {
							$options[ $category->name ] = $category->term_id;
						}
					}

				}
				break;

			case 'custom':
			case 'callback':

				if ( is_callable( $query_args['function'] ) ) {
					$options = call_user_func( $query_args['function'], $query_args['args'] );
				}

				break;

		}

		return $options;

	}
}


$params = array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Title', 'js_composer' ),
				'param_name'  => 'title',
			),
			array (
			  'param_name' => 'separator',
			  'type' => 'checkbox',
			  'description' => '',
			  'heading' => 'Separator',
			  'value' => '',
			),
			array(
				'type' => 'hidden',
				'heading' => __( 'Templates', 'js_composer' ),
				'param_name' => 'templates',
				'value' => vc_get_shortcode_template('vc_posts'),
				'description' => '',
				'dependency' => array(
					'callback' => 'init_ajax_field',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Post types', 'js_composer' ),
				'param_name' => 'post_type',
				'value'      => get_vc_post_types($post_types),
				'description' => __( 'Select source for slider.', 'js_composer' ),
			),

			array(
				'type'        => 'dropdown',
				'heading'     => 'Image original size',
				'param_name'  => 'image_original_size',
				'value'       => array_merge(get_intermediate_image_sizes(), array('full')),
			),

			array(
				'type'        => 'textfield',
				'heading'     => __( 'Total posts', 'js_composer' ),
				'param_name'  => 'posts_per_page',
				'description' => 'Only number'
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Order by', 'js_composer' ),
				'param_name' => 'orderby',
				'value' => array(
					'',
					__( 'Date', 'js_composer' ) => 'date',
					__( 'ID', 'js_composer' ) => 'ID',
					__( 'Author', 'js_composer' ) => 'author',
					__( 'Title', 'js_composer' ) => 'title',
					__( 'Modified', 'js_composer' ) => 'modified',
					__( 'Random', 'js_composer' ) => 'rand',
					__( 'Comment count', 'js_composer' ) => 'comment_count',
					__( 'Menu order', 'js_composer' ) => 'menu_order',
				),
				'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Sort order', 'js_composer' ),
				'param_name' => 'order',
				'value' => array(
					__( 'Descending', 'js_composer' ) => 'DESC',
					__( 'Ascending', 'js_composer' ) => 'ASC',
				),
				'description' => sprintf( __( 'Select ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Filter', 'js_composer' ),
				'param_name'  => 'filter',
				'value'       => array(
					'Disable' => 'disable',
					'Enable'  => 'enable',
				),

			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Show Load More Button', 'js_composer' ),
				'param_name'  => 'enable_load_more',
				'value' => '',
			),

		);

$params_tax = array();

vc_remove_param( "vc_projects_posts", "cats_post" );

foreach ($post_types as $key => $type) {

	$heading = __( 'Select Categories', 'js_composer' );

	$taxonomies = get_taxonomies(array('object_type' => array($type) ), 'names','and');

	if (!empty($taxonomies)) {

		if ($type == 'post') {
			$params_value = array(
					'sort_order' => 'ASC',
					'taxonomy'   => array_shift($taxonomies),
					'hide_empty' => false,
			);
		} else {
			$params_value = array(
					'sort_order' => 'ASC',
					'taxonomies'   => array_shift($taxonomies),
					'hide_empty' => false,
			);
		}

		$type_functions = ($type == 'post') ? 'categories' : 'tags';

		$params_tax[] = array(
			'type'        => 'vc_efa_chosen',
			'heading'     => $heading,
			'param_name'  => 'cats_' . $type,
			'placeholder' => $heading,
			'value'       => vc_projects_element_values( $type_functions, $params_value ),
			'std'         => '',
			'description' => __( 'You can choose spesific categories for portfolio, default is all categories', 'js_composer' ),
			'dependency' => array( 'element' => 'post_type', 'value' => array($type) ),
		);
	}
}

vc_map( array(
		'name'        => __( 'Custom Post Types', 'js_composer' ),
		'base'        => 'vc_projects_posts',
		'description' => __( 'This outputs list shows any of the post-type items', 'js_composer' ),
		'admin_enqueue_js' => array(
			plugins_url('/assets/ajax-fields.js?'.time(), __FILE__)
		),
		'params'      => array_merge(
							array_values($params),
							$params_tax
						)
	)
);

class WPBakeryShortCode_Vc_Projects_Posts extends WPBakeryShortCode {

	protected function content( $atts, $content = null ) {

		// get default template
		$all_params = vc_get_shortcode('vc_projects_posts');
		$default_template = '';
		foreach ($all_params['params'] as $key => $param) {
			if ($param['param_name'] == 'templates') {
				$default_template = reset( $param['value'] );
			}
		}

		extract( shortcode_atts( array(
			'title'				=> '',
			'separator'			=> '',
			'posts_per_page'    => '',
			'post_type' => 'post',
			'orderby' 	=> '',
			'order' 	=> '',
			'templates'     => $default_template,
			'filter' 	   => 'disable',
			'enable_load_more' => '',
			'total_columns' => 'essential_count_col1',
		), $atts ) );

		$category = '';

		$taxonomies = get_taxonomies(array('object_type' => array($post_type) ), 'names','and');

		$atts['taxonomies'] = $taxonomies;

		// select categories post types

		if ( ! empty( $atts['cats_' . $post_type] ) ) {

			$atts['cats'] = $atts['cats_' . $post_type];

			// for custom posttype
			if ($post_type != 'post') {
				$category = array(
					'taxonomy' => array_shift($taxonomies),
					'field'    => 'term_id',
					'terms'    => explode( ',', $atts['cats'] )
				);
			}

			$args = array(
				'tax_query' => array(
					$category
				),
			);

	 		// for default posttype
			if ($post_type == 'post') {
				$args['category__in'] = explode( ',', $atts['cats'] );
			}

		}

		// Order posts
		if ( null !== $orderby ) {
			$args['orderby'] = $orderby;
		}
		$args['order'] = $order;

		// select posttype
		$args['post_type'] = $post_type;

		$tot_posts = wp_count_posts($post_type);

		if(empty($posts_per_page) || $posts_per_page == '') $posts_per_page = $tot_posts->publish;

		$paged = ( get_query_var('page') > 1 ) ? get_query_var('page') : 1;

		$args['paged'] = $paged;

		$posts = new WP_Query( $args );

		$total   = !empty( $posts->max_num_pages ) ? $posts->max_num_pages : 1;

		$class_wrap = 'essential_' . $templates . ' ' . $total_columns;

		$output = '';
		$output .= '<div class="' . esc_attr( $class_wrap ) .  '" data-columns="' . esc_attr( $total_columns ) . '">';

		$atts['posts'] = $posts;

		$atts['uniqid'] = uniqid();

		$atts['terms_string'] = '';

		ob_start();

		if ( $posts->have_posts() ) {

			vcs_locate_template( array( 'vc_posts/' . $templates . '/header.php'), $atts );

			while ( $posts->have_posts() ) : $posts->the_post();

 				if (!empty($atts['taxonomies'])) {

					$terms = get_the_terms( get_the_ID(), key($atts['taxonomies']) );
					if ( $terms && ! is_wp_error( $terms ) ) {

					    $terms_slug = array();
					    foreach ( $terms as $term ) {
					        $terms_slug[] = $term->slug . '_' . $atts['uniqid'];
					    }
					    $atts['terms_string'] = join( " ", $terms_slug );
					}
				}
				// include template item
				vcs_locate_template( array( 'vc_posts/' . $templates . '/index.php'), $atts );

			endwhile;

			vcs_locate_template( array( 'vc_posts/' . $templates . '/footer.php'), $atts );

			$count_posts = wp_count_posts($post_type);

			wp_localize_script(
				'essential-script',
				'essential_load_more_posts',
				array(
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'totalPostsPerPage'	=> $posts_per_page,
					'totalPosts' => $count_posts->publish,
					'startPage' => $paged,
					'maxPage'   => $total,
					'nextLink'  => next_posts( 0, false )
				)
			);

		}

		wp_reset_postdata();

		?>

		</div>

		<?php
		if ( !empty($enable_load_more) && $posts_per_page < $count_posts->publish ) {
			?>
			<div class="essential-purchase section">
				<div class="section-content">
					<div class="container">
						<div class="col-md-12 col-sm-12 col-12 text-center">
							<button class="btn load-more"><?php esc_html_e('Load More', 'essential'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		$output .= ob_get_clean();

		return $output;
	}
}

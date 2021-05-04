<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Icon shortcode', 'js_composer' ),
			'base'						=> 'vc_icon_shortcode',
			'content_element'			=> true,
			'show_settings_on_create'	=> true,
			'description'				=> esc_html__( '', 'js_composer'),
			'params'					=> array (
					array (
					  'param_name' => 'style',
					  'type' => 'dropdown',
					  'description' => '',
					  'heading' => 'Style',
					  'value' =>
					  array (
					  	'About' => 'about',
					  	'Services' => 'services',
					  	'Contact' => 'contact',
					  ),
					),
				  array (
				    'param_name' => 'font_icon',
				    'type' => 'dropdown',
				    'description' => '',
				    'heading' => 'Font Icons',
				    'value' =>
				    	array (
				    		'Fontawesome' => 'fontawesome',
				    		'Etline' => 'etline',
				    		'Ionicons' => 'ionicons',
				    	),
				  ),
				  array (
				    'param_name' => 'icon_fontawesome',
				    'type' => 'iconpicker',
				    'description' => '',
				    'heading' => 'Fontawesome Icon',
				    'value' => '',
				    'settings'    => array(
						'emptyIcon'    => false, // default true, display an "EMPTY" icon?
 				  		'type'         => 'fontawesome',
 				  		'source'       => fontawesome_icons(),
 				  		'iconsPerPage' => 100, // default 100, how many icons per/page to display
				    ),
				    'dependency'  => array(
				    	'element' => 'font_icon',
				    	'value' => 'fontawesome'
				    ),
				    'description' => __( 'Select icon from library.', 'js_composer' ),
				  ),
				  array(
				  	'type'        => 'iconpicker',
				  	'heading'     => __( 'Etline Icon', 'js_composer' ),
				  	'param_name'  => 'icon_etline',
				  	'value'       => '', // default value to backend editor admin_label
				  	'settings'    => array(
				  		'emptyIcon'    => false, // default true, display an "EMPTY" icon?
				  		'type'         => 'etline',
				  		'source'       => et_line_icons(),
				  		'iconsPerPage' => 100, // default 100, how many icons per/page to display
				  	),
				  	'dependency'  => array(
				    	'element' => 'font_icon',
				    	'value' => 'etline'
				    ),
				  	'description' => __( 'Select icon from library.', 'js_composer' ),
				  ),
				  array(
				  	'type'        => 'iconpicker',
				  	'heading'     => __( 'Ionicons Icon', 'js_composer' ),
				  	'param_name'  => 'icon_ionicons',
				  	'value'       => '', // default value to backend editor admin_label
				  	'settings'    => array(
				  		'emptyIcon'    => false, // default true, display an "EMPTY" icon?
				  		'type'         => 'ionicons',
				  		'source'       => ionicons_icons(),
				  		'iconsPerPage' => 100, // default 100, how many icons per/page to display
				  	),
				  	'dependency'  => array(
				    	'element' => 'font_icon',
				    	'value' => 'ionicons'
				    ),
				  	'description' => __( 'Select icon from library.', 'js_composer' ),
				  ),

				  array (
				    'param_name' => 'title',
				    'type' => 'textfield',
				    'description' => '',
				    'heading' => 'Title',
				    'value' => '',
				  ),

				  array (
				    'param_name' => 'content',
				    'type' => 'textarea_html',
				    'description' => '',
				    'heading' => 'Content',
				    'value' => '',
				  ),


				  array (
				    'type' => 'textfield',
				    'heading' => 'Extra class name',
				    'param_name' => 'el_class',
				    'description' => 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
				    'value' => '',
				  ),
				  array (
				    'type' => 'css_editor',
				    'heading' => 'CSS box',
				    'param_name' => 'css',
				    'group' => 'Design options',
				  ),
			)
			//end params
		)
	);
}
if (class_exists('WPBakeryShortCode')) {
	/* Frontend Output Shortcode */
	class WPBakeryShortCode_vc_icon_shortcode extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
						'font_icon'	=> 'fontawesome',
						'icon_fontawesome' => '',
						'icon_etline' => '',
						'icon_ionicons' => '',
						'title'	=> '',
						'style'		=> 'about',
						'el_class'	=> '',
						'css'	=> '',

			), $atts ) );
			/* get param class */
			$wrap_class  = !empty( $el_class ) ? $el_class : '';
			/* get custum css as class*/
			$wrap_class .= vc_shortcode_custom_css_class( $css, ' ' );

			// Enqueue needed icon font
			vc_icon_element_fonts_enqueue( $font_icon );

			// start output
			ob_start(); ?>
			<div class="<?php echo esc_attr( $wrap_class ); ?> <?php echo esc_attr( $style ); ?>-icons">
				<div class="section-content">
					<div class="text-center">

						<?php if(!empty( $atts['icon_' . $font_icon] )) : ?>
						<i class="<?php echo esc_html($atts['icon_' . $font_icon]); ?>"></i>
						<?php endif; ?>

						<?php if(!empty($title)) : ?>
						<h4><?php echo esc_html($title); ?></h4>
						<?php endif; ?>

						<?php if(!empty($content)) : ?>
						<?php echo wp_kses_post( do_shortcode( $content ) ); ?>
						<?php endif; ?>

					</div>
				</div>
			</div>
			<?php
			// end output
			return ob_get_clean();
		}
	}
}

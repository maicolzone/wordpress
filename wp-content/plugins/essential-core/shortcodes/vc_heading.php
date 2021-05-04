<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Heading', 'js_composer' ),
			'base'						=> 'vc_heading',
			'content_element'			=> true,
			'show_settings_on_create'	=> true,
			'description'				=> esc_html__( '', 'js_composer'),
			'params'					=> array (
			  array (
			    'param_name' => 'title',
			    'type' => 'textfield',
			    'description' => '',
			    'heading' => 'Title',
			    'value' => '',
			  ),
			  array (
			    'param_name' => 'separator',
			    'type' => 'checkbox',
			    'description' => '',
			    'heading' => 'Separator',
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
			    'param_name' => 'style',
			    'type' => 'dropdown',
			    'description' => '',
			    'heading' => 'Style',
			    'value' =>
			    array ( 'Black', 'White',
			    ),
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
	class WPBakeryShortCode_vc_heading extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
						'title'	=> '',
						'separator'	=> '',
						'style'	=> 'Black',
						'el_class'	=> '',
						'css'	=> '',

			), $atts ) );
			/* get param class */
			$wrap_class  = !empty( $el_class ) ? $el_class : '';
			/* get custum css as class*/
			$wrap_class .= vc_shortcode_custom_css_class( $css, ' ' );

			// start output
			ob_start();
			if( !empty($wrap_class) ): ?>
			<div class="<?php echo esc_attr( $wrap_class ); ?>">
			<?php endif; ?>
				<?php if(!empty($style) && $style == "Black") : ?>
				<div class="section-heading">
				    <?php if(!empty($title)) : ?>
					<h2><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
					<hr>
					<?php if(!empty($content)) : ?>
				    <div class="section-content"><?php echo wpautop(wp_kses_post($content)); ?></div>
				    <?php endif; ?>
				</div>
				<?php else: ?>
				<div class="section about">
					<div class="text-center">
					    <?php if(!empty($title)) : ?>
						<h3 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s"><?php echo esc_html($title); ?></h3>
						<?php endif; ?>
						<?php if(!empty($separator)) : ?>
						<hr />
						<?php endif; ?>
						<?php if(!empty($content)) : ?>
						<div class="wow fadeInUp description" data-wow-duration="1s" data-wow-delay="0.5s"><?php echo wpautop(wp_kses_post($content)); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			<?php if( !empty($wrap_class) ): ?>
			</div>
			<?php endif;
			// end output
			return ob_get_clean();
		}
	}
}

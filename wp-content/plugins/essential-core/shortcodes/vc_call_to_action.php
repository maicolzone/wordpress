<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Call to Action', 'js_composer' ),
			'base'						=> 'vc_call_to_action',
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
    'param_name' => 'link',
    'type' => 'textfield',
    'description' => '',
    'heading' => 'Button link',
    'value' => '',
  ),
  array (
    'param_name' => 'label',
    'type' => 'textfield',
    'description' => '',
    'heading' => 'Button label',
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
	class WPBakeryShortCode_vc_call_to_action extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
							'title'	=> '',
						'link'	=> '',
						'label'	=> '',
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
				<div class="section dark">
					<div class="section-content text-center">
					    <?php if(!empty($title)) : ?>
						<h2 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.5s"><?php echo esc_html($title); ?></h2>
						<?php endif; ?>
						<?php if(!empty($link)) : ?>
						<a href="<?php echo esc_html($link); ?>" class="btn wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s"><?php echo esc_html($label); ?></a>
					    <?php endif; ?>
					</div>
				</div>
			<?php if( !empty($wrap_class) ): ?>
			</div>
			<?php endif;
			// end output
			return ob_get_clean();
		}
	}
}

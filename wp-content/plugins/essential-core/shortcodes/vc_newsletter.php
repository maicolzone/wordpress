<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Newsletter', 'js_composer' ),
			'base'						=> 'vc_newsletter',
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
    'param_name' => 'content',
    'type' => 'textarea_html',
    'description' => '',
    'heading' => 'Shortcode forms',
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
	class WPBakeryShortCode_vc_newsletter extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
							'title'	=> '',
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
				<!-- NEWSLETTER -->
<div class="section newsletter">
	<div class="section-content">
		<div class="container">
		    <?php if(!empty($title)) : ?>
			<div class="col-md-12 col-sm-12 col-12 text-center">
				<h3 class="wow fadeInDown" data-wow-duration="1s"><?php echo esc_html($title); ?></h3>
			</div>
			<?php endif; ?>
			<?php if(!empty($content)) : ?>
			<div class="col-md-12 col-sm-12 col-12 text-center">
			    <?php echo wpautop(do_shortcode($content)); ?>
			</div>
			<?php endif; ?>
		</div>
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

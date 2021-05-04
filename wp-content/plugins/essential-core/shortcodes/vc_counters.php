<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Counters', 'js_composer' ),
			'base'						=> 'vc_counters',
			'content_element'			=> true,
			'show_settings_on_create'	=> true,
			'description'				=> esc_html__( '', 'js_composer'),
			'params'					=> array (
			  array (
			    'param_name' => 'numbers',
			    'type' => 'textfield',
			    'description' => '',
			    'heading' => 'Numbers',
			    'value' => '',
			  ),
			  array (
			    'param_name' => 'title',
			    'type' => 'textfield',
			    'description' => '',
			    'heading' => 'Title',
			    'value' => '',
			  ),
			  array (
			    'param_name' => 'countdown_on',
			    'type' => 'checkbox',
			    'description' => '',
			    'heading' => 'CountDown (On/Off)',
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
	class WPBakeryShortCode_vc_counters extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
							'numbers'	=> '',
						'title'	=> '',
						'countdown_on'	=> '',
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
				<div class="section counters" >
					<div class="text-center">
					    <?php if(!empty($title)) : ?>
					        <?php if(!empty($countdown_on)) : ?>
						    <h3 class="countTo" data-to="<?php echo esc_html($numbers); ?>" data-from="0" data-speed="1500">0</h3>
						    <?php else: ?>
						    <h3><?php echo esc_html($numbers); ?></h3>
						    <?php endif; ?>
						<?php endif; ?>
						<?php if(!empty($title)) : ?>
						<p><?php echo esc_html($title); ?></p>
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

<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Testimonials', 'js_composer' ),
			'base'						=> 'vc_testimonials',
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
    'param_name' => 'testimonials',
    'type' => 'param_group',
    'description' => '',
    'heading' => 'Testimonials',
    'params' =>
    array (
      array (
        'param_name' => 'name',
        'type' => 'textfield',
        'description' => '',
        'heading' => 'Name',
        'value' => '',
        'parent' => 'testimonials',
      ),
      array (
        'param_name' => 'quote',
        'type' => 'textarea',
        'description' => '',
        'heading' => 'Quote',
        'value' => '',
        'parent' => 'testimonials',
      ),
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
	class WPBakeryShortCode_vc_testimonials extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
							'title'	=> '',
						'separator'	=> '',
						'testimonials'	=> '',
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
			<!-- TESTIMONIALS -->
        <div id="testimonials" class="section testimonials parallax-container" data-parallax="scroll" data-image-src="" data-natural-width="1200" data-natural-height="1200">
        	<div class="section-heading">
        	    <?php if(!empty($title)) : ?>
        		<h2><?php echo esc_html($title); ?></h2>
        		<?php endif; ?>
        		<hr />
        	</div>
        	<div class="section-content">
        		<div class="container">
        			<div class="owl-carousel">
        			    <?php if(!empty($testimonials)): $testimonials = json_decode( urldecode( $testimonials ) ); foreach ($testimonials as $item): ?>
        				<div>
        				    <?php if(!empty($item->quote)) : ?>
        				        <?php echo esc_html($item->quote); ?>
        				    <?php endif; ?>
        				    <?php if(!empty($item->name)) : ?>
        				        <p class="mini"><?php echo esc_html($item->name); ?></p>
        				    <?php endif; ?>
        				</div>
        				<?php endforeach;  endif; ?>
        			</div>
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

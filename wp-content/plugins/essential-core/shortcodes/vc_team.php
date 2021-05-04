<?php

if (function_exists('vc_map')) {
	vc_map(
		array(
			'name'						=> esc_html__( 'Team', 'js_composer' ),
			'base'						=> 'vc_team',
			'content_element'			=> true,
			'show_settings_on_create'	=> true,
			'description'				=> esc_html__( '', 'js_composer'),
			'params'					=> array (
          array (
            'param_name' => 'name',
            'type' => 'textfield',
            'description' => '',
            'heading' => 'Name',
            'value' => '',
          ),
          array (
            'param_name' => 'position',
            'type' => 'textfield',
            'description' => '',
            'heading' => 'Position',
            'value' => '',
          ),
          array (
            'param_name' => 'socials',
            'type' => 'param_group',
            'description' => '',
            'heading' => 'Socials',
            'params' =>
            array (
              array (
                'param_name' => 'link',
                'type' => 'textfield',
                'description' => '',
                'heading' => 'Link',
                'value' => '',
                'parent' => 'socials',
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
                  'iconsPerPage' => 100,
                ),
                'dependency'  => array(
                  'element' => 'font_icon',
                  'value' => array('fontawesome')
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
                  'value' => array('etline')
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
                  'value' => array('ionicons')
                ),
                'description' => __( 'Select icon from library.', 'js_composer' ),
              ),

            ),
          ),
          array (
            'param_name' => 'image',
            'type' => 'attach_image',
            'description' => '',
            'heading' => 'Image',
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
          array (
              'param_name' => 'animation',
              'type' => 'dropdown',
              'description' => '',
              'heading' => 'Animation',
              'value' => array (
                  'FadeInLeft' => 'fadeInLeft',
                  'FadeInRight ' => 'fadeInRight',
                  'FadeInDown' => 'fadeInDown',
                  'FadeInUp' => 'fadeInUp',
              ),
          ),

      )
			//end params
		)
	);
}
if (class_exists('WPBakeryShortCode')) {
	/* Frontend Output Shortcode */
	class WPBakeryShortCode_vc_team extends WPBakeryShortCode {
		protected function content( $atts, $content = null ) {
			/* get all params */
			extract( shortcode_atts( array(
							'name'	=> '',
						'position'	=> '',
						'socials'	=> '',
            'image' => '',
						'animation'	=> 'fadeInLeft',
						'el_class'	=> '',
						'css'	=> '',

			), $atts ) );
			/* get param class */
			$wrap_class  = !empty( $el_class ) ? $el_class : '';
			/* get custum css as class*/
			$wrap_class .= vc_shortcode_custom_css_class( $css, ' ' );

			// start output
			ob_start(); ?>
				<div class="team wow <?php echo esc_attr( $animation ); ?> <?php echo esc_attr( $wrap_class ); ?>" data-wow-duration="1s">
					<?php if(!empty($image)):?><?php $image_src = wp_get_attachment_image_src( $image, 'full' ); $image_src = is_array($image_src) ? $image_src[0] : $image_src; ?> <img src="<?php echo esc_url($image_src );?>"  alt="<?php echo get_post_meta( $image, '_wp_attachment_image_alt', true); ?>"><?php endif; ?>
					<div class="mask">
					    <?php if(!empty($name)) : ?>
				        <h2><?php echo esc_html($name); ?></h2>
				        <?php endif; ?>
						<?php if(!empty($position)) : ?>
						<p><?php echo esc_html($position); ?></p>
						<?php endif; ?>
				        <?php if(!empty($socials)): $socials = json_decode( urldecode( $socials ) ); foreach ($socials as $item): ?>
				            <?php if(!empty($item->link)) : ?>
						    <a href="<?php echo esc_html($item->link); ?>" class="info"><i class="<?php echo esc_html($item->{'icon_' . $item->font_icon}); ?>"></i></a>
						    <?php endif; ?>
						<?php endforeach; endif; ?>
					</div>
				</div>
			<?php
			// end output
			return ob_get_clean();
		}
	}
}

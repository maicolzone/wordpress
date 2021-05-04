<?php
/*
Template: Projects Grid
Version: 1.0
*/
defined( 'ABSPATH' ) or exit;

/**
* @var $data (array) - all params shortcodes
* @var $post
**/

?>

<div class="col-md-4 col-sm-6 col-12 work-item <?php echo esc_attr( $data['terms_string'] ); ?>">

	<?php the_post_thumbnail( (!empty($data['image_original_size']) ? $data['image_original_size'] : 'large' ), array('class'=>'wow fadeIn','height'=>'auto') ); ?>
	<div class="image-overlay">
		<?php if (function_exists('get_field') && !get_field('disable_popup')) { ?>
		<a href="<?php echo esc_url(the_post_thumbnail_url()); ?>" class="media-popup" title="<?php the_title(); ?>">
		<?php }else{ ?>
		<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php the_title(); ?>">
		<?php } ?>
			<div class="work-item-info">
				<?php the_title( '<h3>', '</h3>' ); ?>
				<?php the_excerpt(); ?>
			</div>
		</a>
	</div>

</div>

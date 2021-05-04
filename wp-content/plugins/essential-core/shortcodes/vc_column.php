<?php
 
function cr_get_cell_offset( $pref, $suf, $max = 50, $step = 5 ) {
	$ar = array();
	for ( $i = 0; $i < $max + $step; $i += $step ) {
		$ar[ $i . 'px' ] = $pref . '-' . $i . $suf;
	}

	return array_merge( array( 'Default' => 'none' ), $ar );
}

$responsive_classes = array(
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Desktop margin top', 'js_composer' ),
		'param_name' => 'desktop_mt',
		'value'      => cr_get_cell_offset( 'margin-lg', 't', 170 ),
		'group'      => 'Responsive Margins'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Desktop margin bottom', 'js_composer' ),
		'param_name' => 'desktop_mb',
		'value'      => cr_get_cell_offset( 'margin-lg', 'b', 170 ),
		'group'      => 'Responsive Margins',
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Tablets margin top', 'js_composer' ),
		'param_name' => 'tablets_mt',
		'value'      => cr_get_cell_offset( 'margin-sm', 't' ),
		'group'      => 'Responsive Margins'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Tablets margin bottom', 'js_composer' ),
		'param_name' => 'tablets_mb',
		'value'      => cr_get_cell_offset( 'margin-sm', 'b' ),
		'group'      => 'Responsive Margins'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Mobile margin top', 'js_composer' ),
		'param_name' => 'mobile_mt',
		'value'      => cr_get_cell_offset( 'margin-xs', 't' ),
		'group'      => 'Responsive Margins'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Mobile margin bottom', 'js_composer' ),
		'param_name' => 'mobile_mb',
		'value'      => cr_get_cell_offset( 'margin-xs', 'b' ),
		'group'      => 'Responsive Margins'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Desktop padding top', 'js_composer' ),
		'param_name' => 'desktop_pt',
		'value'      => cr_get_cell_offset( 'padding-lg', 't', 80 ),
		'group'      => 'Responsive Paddings'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Desktop padding bottom', 'js_composer' ),
		'param_name' => 'desktop_pb',
		'value'      => cr_get_cell_offset( 'padding-lg', 'b', 80 ),
		'group'      => 'Responsive Paddings',
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Tablets padding top', 'js_composer' ),
		'param_name' => 'tablets_pt',
		'value'      => cr_get_cell_offset( 'padding-sm', 't' ),
		'group'      => 'Responsive Paddings'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Tablets padding bottom', 'js_composer' ),
		'param_name' => 'tablets_pb',
		'value'      => cr_get_cell_offset( 'padding-sm', 'b' ),
		'group'      => 'Responsive Paddings'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Mobile padding top', 'js_composer' ),
		'param_name' => 'mobile_pt',
		'value'      => cr_get_cell_offset( 'padding-xs', 't' ),
		'group'      => 'Responsive Paddings'
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Mobile padding bottom', 'js_composer' ),
		'param_name' => 'mobile_pb',
		'value'      => cr_get_cell_offset( 'padding-xs', 'b' ),
		'group'      => 'Responsive Paddings'
	),
);
if ( function_exists( 'vc_add_params' ) ) {
	vc_add_params( 'vc_column', $responsive_classes );
}

<?php

$vc_templates = EF_ROOT . '/composer/vc_templates';
vc_set_shortcodes_templates_dir( $vc_templates );

global $vc_manager;
$vc_manager->setIsAsTheme();
$vc_manager->disableUpdater();
$vc_manager->setEditorDefaultPostTypes( array( 'page', 'post', 'portfolio' ) );
$vc_manager->automapper()->setDisabled();

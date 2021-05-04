<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Essential_Import_Demo class
 */
class Essential_Import_Demo {

	/*
	* var. currenct folder
	*/
	public $demo_folder;
	public $demo_folder_uri;

	/**
	 * Ajax handler run install demo
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {

		$this->demo_folder_uri =  plugins_url('/', __FILE__ );
		$this->demo_folder = EF_ROOT . '/includes/demo/';

		/*
		* enqueue script for customizer page
		*/
		add_action( 'customize_controls_enqueue_scripts', array($this, 'enqueue_script' ));


	}

	/**
	 * Enqueue all scripts for instal demo page
	 *
	 * @since 1.0.0
	 *
	 */
	public function enqueue_script(){

		wp_enqueue_style( 'essential_demo', $this->demo_folder_uri . 'css/demo_install.css');
		wp_enqueue_script( 'essential_demo_script', $this->demo_folder_uri . 'js/demo_install.js', array( 'jquery' ), '20160715', true );

		wp_localize_script( 'essential_demo_script', 'essential_demo', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'wpnonce' => wp_create_nonce('essential_install_demo')
		) );

	}

	

	/**
	 * Export general options theme
	 *
	 * @since 1.0.0
	 * @return bool
	 *
	 */
	private function options_export() {
		return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( get_option( 'essential_themes_options' ) ), 9 ) ) ), '+/', '-_' ), '=' );
	}

	

}
//new Essential_Import_Demo();

include_once EF_ROOT . "/includes/demo/importer/import_functions.php";
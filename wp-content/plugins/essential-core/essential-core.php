<?php

/*

Plugin Name: Essential Core

Plugin URI: https://themeforest.net/user/ravenbluethemes/portfolio

Author: Raven Blue Themes

Author URI: https://www.ravenbluethemes.com

Version: 1.8

Description: Includes Portfolio Custom Post Type and WPBakery Page Builder Shortcodes

Text Domain: essential

*/

// Define Constants
defined( 'EF_ROOT' ) or define( 'EF_ROOT', dirname( __FILE__ ) );
defined( 'EF_VERSION' ) or define( 'EF_VERSION', '1.0' );

if ( ! class_exists( 'Essential_Core' ) ) {

	require_once EF_ROOT . '/composer/custom_icon.php';
	require_once EF_ROOT . '/composer/font-class.php';
	require_once EF_ROOT . '/includes/helpers.php';
	require_once EF_ROOT . '/includes/custom-post-type.php';
	include_once EF_ROOT . '/includes/demo/index.php';

	/**
	 * Include Kirki Framework
	 */
	include_once  EF_ROOT . '/includes/kirki/kirki.php';

	/**
	 * Include Default Options
	 */
	include_once EF_ROOT . '/includes/options-theme.php';


	class Essential_Core {

		private $assets_js;
		private $assets_css;

		public function __construct() {

			$this->assets_js  = plugins_url( '/composer/js', __FILE__ );
			$this->assets_css = plugins_url( '/composer/css', __FILE__ );

			add_action( 'admin_print_scripts-post.php', array( $this, 'vc_enqueue_scripts' ), 99 );
			add_action( 'admin_print_scripts-post-new.php', array( $this, 'vc_enqueue_scripts' ), 99 );
			add_action( 'admin_init', array( $this, 'essential_load_shortcodes' ) );
			add_action( 'wp', array( $this, 'essential_load_shortcodes' ) );

		}

		/* Load Shortcodes */
		public function essential_load_shortcodes() {

			if ( class_exists( 'Vc_Manager' ) ) {
				foreach ( glob( EF_ROOT . '/' . 'shortcodes/essential_*.php' ) as $shortcode ) {
					require_once( EF_ROOT . '/' . 'shortcodes/' . basename( $shortcode ) );
				}
				foreach ( glob( EF_ROOT . '/' . 'shortcodes/vc_*.php' ) as $shortcode ) {
					require_once( EF_ROOT . '/' . 'shortcodes/' . basename( $shortcode ) );
				}

				foreach ( glob( EF_ROOT . '/shortcodes/*', GLOB_ONLYDIR) as $shortcode ) {
					require_once( EF_ROOT . '/' . 'shortcodes/' . basename($shortcode) . "/" . basename($shortcode) . ".php" );
				}

				require_once EF_ROOT . '/composer/params.php';

				require_once EF_ROOT . '/composer/init.php';
			}

		}

		public static function essential_plugin_dir() {
			return plugin_dir_path( __FILE__ );
		}

		/* Enqueue Scripts */
		public function vc_enqueue_scripts() {
			wp_enqueue_script( 'vc-script', $this->assets_js . '/vc-script.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_style( 'rs-vc-custom', $this->assets_css . '/vc-style.css' );
		}

		/* Enqueue CSS */
		public function assets_css($param) {
			return $this->assets_css . '/' . $param;
		}

	}

	new Essential_Core;

}

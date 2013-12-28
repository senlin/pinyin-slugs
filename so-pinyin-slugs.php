<?php
/*
Plugin Name: SO Pinyin Slugs
Plugin URI: http://so-wp.com/?p=17
Description: Transforms Chinese character titles (of Posts, Pages and all other content types that use slugs) into a permalink friendly slug, showing pinyin that can be read by humans and (Chinese) search engines alike.
Author: Piet Bos
Version: 2013.12.28
Author URI: http://senlinonline.com
Text Domain: so-pinyin-slugs
Domain Path: /languages
*/

/**
 *
 * The SO Pinyin Slugs plugin is a fork of the original [Pinyin Permalinks](http://wordpress.org/plugins/pinyin-permalink/) plugin
 * by user [xiaole_tao](http://profiles.wordpress.org/xiaole_tao/) who has seemingly abandoned his plugin as he never responded to emails.
 *
 * My ex-colleague [Denis Cao](https://github.com/caoyongsheng) has adapted the original plugin in such a way that when the plugin is used
 * on bi/multilingual websites (for example with WPML), it leaves the slugs of the non-Chinese languages intact.
 *
 */

/* Prevent direct access to files - http://mikejolley.com/2013/08/keeping-your-shit-secure-whilst-developing-for-wordpress/ */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Version check; any WP version under 3.6 is not supported (if only to "force" users to stay up to date)
 * 
 * adapted from example by Thomas Scholz (@toscho) http://wordpress.stackexchange.com/a/95183/2015, Version: 2013.03.31, Licence: MIT (http://opensource.org/licenses/MIT)
 *
 * @since 0.1.2
 */

//Only do this when on the Plugins page.
if ( ! empty ( $GLOBALS['pagenow'] ) && 'plugins.php' === $GLOBALS['pagenow'] )
	add_action( 'admin_notices', 'pinyinslugs_check_admin_notices', 0 );

function pinyinslugs_min_wp_version() {
	global $wp_version;
	$require_wp = '3.6';
	$update_url = get_admin_url( null, 'update-core.php' );

	$errors = array();

	if ( version_compare( $wp_version, $require_wp, '<' ) ) 

		$errors[] = "You have WordPress version $wp_version installed, but <b>this plugin requires at least WordPress $require_wp</b>. Please <a href='$update_url'>update your WordPress version</a>.";

	return $errors;
}

function pinyinslugs_check_admin_notices()
{
	$errors = pinyinslugs_min_wp_version();

	if ( empty ( $errors ) )
		return;

	// Suppress "Plugin activated" notice.
	unset( $_GET['activate'] );

	// this plugin's name
	$name = get_file_data( __FILE__, array ( 'Plugin Name' ), 'plugin' );

	printf( __( '<div class="error"><p>%1$s</p><p><i>%2$s</i> has been deactivated.</p></div>', 'so-pinyin-slugs' ),
		join( '</p><p>', $errors ),
		$name[0]
	);
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Looked at [Members plugin of Justin Tadlock[(http://justintadlock.com/archives/2009/09/17/members-wordpress-plugin) on how to build up this file
 *
 * @since 0.1
 */
class PinyinSlug_Load {
	
	function __construct() {

		global $so_pinyinslugs;

		/* Set up an empty class for the global $so_pinyinslugs object. */
		$so_pinyinslugs = new stdClass;

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 4 );

	}
	
	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 0.1
	 */
	function constants() {

		/* Set the version number of the plugin. */
		define( 'SO_PINYINSLUGS_VERSION', '2013.12.28' );

		/* Set constant path to the so-pinyin-slugs plugin directory. */
		define( 'SO_PINYINSLUGS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set constant path to the so-pinyin-slugs plugin URL. */
		define( 'SO_PINYINSLUGS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the so-pinyin-slugs inc directory. */
		define( 'SO_PINYINSLUGS_INCLUDES', SO_PINYINSLUGS_DIR . trailingslashit( 'inc' ) );

		/* Set the constant path to the members admin directory. */
		define( 'SO_PINYINSLUGS_ADMIN', SO_PINYINSLUGS_DIR . trailingslashit( 'admin' ) );

	}

	/**
	 * Loads the translation file.
	 *
	 * @since 0.1
	 */
	function i18n() {

		/* Load the translation of the plugin. */
		load_plugin_textdomain( 'so-pinyin-slugs', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 0.1
	 */
	function includes() {
	
		/**
		 * Load Chinese character dictionary
		 *
		 * @since 0.1.1
		 */
		global $dictPinyin;
		$dictPinyin = require_once( SO_PINYINSLUGS_INCLUDES . 'dictionary.php' );

		/*
		 * Load the plugin functions file.
		 *
		 * @since 0.1
		 */
		require_once( SO_PINYINSLUGS_INCLUDES . 'functions.php' );

	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since 0.1
	 */
	function admin() {

		/* Only load files if in the WordPress admin. */
		if ( is_admin() ) {

			/* Load the main admin file. */
			require_once( SO_PINYINSLUGS_ADMIN . 'admin.php' );

		}
	}

}

$so_pinyinslugs_load = new PinyinSlug_Load();

register_activation_hook( __FILE__, 'PinyinSlugActive' ); 
register_deactivation_hook( __FILE__, 'PinyinSlugDeactive' );

add_action( 'admin_menu', 'setPinyinSlugMenu' );
add_filter( 'sanitize_title', 'getPinyinSlug', 1 );

function setPinyinSlugMenu() {
	add_options_page( 'SO Pinyin Slugs Settings', 'SO Pinyin Slugs', 'administrator', __FILE__, 'setPinyinSlugOptions' );
	add_action( 'admin_init', 'PinyinSlugRegister' );
}

function PinyinSlugActive() {
	add_option( 'PinyinSlug_length', 100 );
}

function PinyinSlugDeactive() {
	delete_option( 'PinyinSlug_length' );	
}

function PinyinSlugRegister() {
	register_setting( 'PinyinSlug_settings', 'PinyinSlug_length' );
}

/**
 * Register and enqueue the admin stylesheet
 * @since 2013.12.26
 */
function load_custom_PinyinSlug_admin_style() {
        wp_register_style( 'custom_PinyinSlug_admin_css', SO_PINYINSLUGS_URI . 'css/admin.css', false, SO_PINYINSLUGS_VERSION );
        wp_enqueue_style( 'custom_PinyinSlug_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_PinyinSlug_admin_style' );


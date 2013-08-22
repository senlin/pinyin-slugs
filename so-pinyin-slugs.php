<?php
/*
Plugin Name: SO Pinyin Slugs
Plugin URI: https://github.com/senlin/so-pinyin-slugs
Description: Transforms Chinese character titles (of Posts, Pages and all other content types that use slugs) into a permalink friendly slug, showing pinyin that can be read by humans and (Chinese) search engines alike.
Authors: Piet Bos & Denis Cao
Version: 0.1.1
Author URI: http://senlinonline.com
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

/* Version check */
global $wp_version;

$exit_msg=__( 'It looks like your WordPress install needs to be updated, please do that first.', 'pinyinslugs' );

if ( version_compare( $wp_version, "3.5.2", "<" ) ) {
	exit ($exit_msg);
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
		define( 'SO_PINYINSLUGS_VERSION', '0.1.1' );

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
		load_plugin_textdomain( 'pinyinslugs', false, basename( dirname( __FILE__ ) ) . '/languages' );
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

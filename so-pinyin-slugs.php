<?php
/*
Plugin Name: SO Pinyin Slugs
Plugin URI: http://so-wp.com/?p=17
Description: Transforms Chinese character titles (of Posts, Pages and all other content types that use slugs) into a permalink friendly slug, showing pinyin that can be read by humans and (Chinese) search engines alike.
Author: SO WP
Version: 2.1.0
Author URI: http://so-wp.com/plugins/
Text Domain: so-pinyin-slugs
Domain Path: /languages
*/

/**
 * Copyright 2014-2016  Piet Bos  (email : piet@so-wp.com)
 *
 * The SO Pinyin Slugs plugin is a fork of the original [Pinyin Permalinks](http://wordpress.org/plugins/pinyin-permalink/) plugin
 * by user [xiaole_tao](http://profiles.wordpress.org/xiaole_tao/) who has seemingly abandoned his plugin as he never responded to emails.
 *
 * My ex-colleague [Denis Cao](https://github.com/caoyongsheng) has adapted the original plugin in such a way that when the plugin is used
 * on bi/multilingual websites (for example with WPML), it leaves the slugs of the non-Chinese languages intact.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// For debugging purposes
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//define('WP-DEBUG', true);

/**
 * Prevent direct access to files
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Version check; any WP version under 4.0 is not supported (if only to "force" users to stay up to date)
 * 
 * adapted from example by Thomas Scholz (@toscho) http://wordpress.stackexchange.com/a/95183/2015, Version: 2013.03.31, Licence: MIT (http://opensource.org/licenses/MIT)
 *
 * @since 2014.07.28
 */

//Only do this when on the Plugins page.
if ( ! empty ( $GLOBALS['pagenow'] ) && 'plugins.php' === $GLOBALS['pagenow'] )
	add_action( 'admin_notices', 'sops_check_admin_notices', 0 );

function sops_min_wp_version() {
	global $wp_version;
	$require_wp = '4.0';
	$update_url = get_admin_url( null, 'update-core.php' );

	$errors = array();

	if ( version_compare( $wp_version, $require_wp, '<' ) ) 

		$errors[] = "You have WordPress version $wp_version installed, but <b>this plugin requires at least WordPress $require_wp</b>. Please <a href='$update_url'>update your WordPress version</a>.";

	return $errors;
}

function sops_check_admin_notices()
{
	$errors = sops_min_wp_version();

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
 * Rewrite of the plugin
 *
 * @since 2014.07.28
 */
class SOPS_Load {
	
	function __construct() {

		global $sops;

		/* Set up an empty class for the global $sops object. */
		$sops = new stdClass;

		/* Set the init. */
		add_action( 'admin_init', array( &$this, 'init' ), 1 );

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 2 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( &$this, 'i18n' ), 3 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 4 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 5 );

	}
	
	/**
	 * Init plugin options to white list our options
	 */
	function init() {
		
		register_setting( 'sops_plugin_options', 'sops_options', 'validate_field' );
		
	}


	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 2014.07.28
	 */
	function constants() {

		/* Set the version number of the plugin. */
		define( 'SOPS_VERSION', '2.0.4' );

		/* Set constant path to the plugin directory. */
		define( 'SOPS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set constant path to the plugin URL. */
		define( 'SOPS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the inc directory. */
		define( 'SOPS_INCLUDES', SOPS_DIR . trailingslashit( 'inc' ) );

		/* Set the constant path to the admin directory. */
		define( 'SOPS_ADMIN', SOPS_DIR . trailingslashit( 'admin' ) );

	}

	/**
	 * Loads the translation file.
	 *
	 * @since 2014.07.28
	 */
	function i18n() {

		/* Load the translation of the plugin. */
		load_plugin_textdomain( 'so-pinyin-slugs', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 2014.07.28
	 */
	function includes() {

		/* Load the plugin functions file. */
		require_once( SOPS_INCLUDES . 'functions.php' );

		/* Load the dictionary file. */
		global $dictPinyin;
		$dictPinyin = require_once( SOPS_INCLUDES . 'dictionary.php' );

	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since 2014.07.28
	 */
	function admin() {

		/* Only load files if in the WordPress admin. */
		if ( is_admin() ) {

			/* Load the main admin file. */
			require_once( SOPS_ADMIN . 'settings.php' );

		}
	}

}

$sops_load = new SOPS_Load();

/**
 * Register activation/deactivation hooks
 * @since 2014.07.28
 */
register_activation_hook( __FILE__, 'sops_add_default' ); 
register_uninstall_hook( __FILE__, 'sops_delete_plugin_options' );

add_action( 'admin_menu', 'sops_add_options_page' );

function sops_add_options_page() {
	// Add the new admin menu and page and save the returned hook suffix
	$hook = add_options_page( 'SO Pinyin Slugs Settings', 'SO Pinyin Slugs', 'manage_options', __FILE__, 'sops_render_form' );
	// Use the hook suffix to compose the hook and register an action executed when plugin's options page is loaded
	add_action( 'admin_print_styles-' . $hook , 'sops_load_settings_style' );
}


/**
 * Define default option settings
 * @since 2014.07.28
 */
function sops_add_default() {
	
	$tmp = get_option( 'sops_options' );
	
	if ( ( ! is_array( $tmp ) ) ) {
	
		$default = array(
			'slug_length' => '100'
		);
		
		update_option( 'sops_options', $default );
	
	}
		
}

/**
 * Delete options table entries ONLY when plugin deactivated AND deleted 
 * @since 2014.07.28
 */
function sops_delete_plugin_options() {
	
	delete_option( 'sops_options' );

}

/**
 * Register and enqueue the settings stylesheet
 * @since 2014.07.28
 */
function sops_load_settings_style() {

	wp_register_style( 'custom_sops_settings_css', SOPS_URI . 'css/settings.css', false, SOPS_VERSION );

	wp_enqueue_style( 'custom_sops_settings_css' );

}

/**
 * Set-up Filter Hook
 * @since 2014.07.28
 */
add_filter( 'sanitize_title', 'getPinyinSlug', 1 );
 
add_filter( 'plugin_action_links', 'sops_plugin_action_links', 10, 2 );


/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 * @since 2014.07.29
 */
function validate_field( $data ) {

	// strip html from textboxes
	$data['slug_length'] =  wp_filter_nohtml_kses( $data['slug_length'] ); // Sanitize input (strip html tags, and escape characters)

	return $data;
}

/**
 * Display a Settings link on the main Plugins page
 * @since 2014.07.28
 */
function sops_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$sops_links = '<a href="' . get_admin_url() . 'options-general.php?page=so-pinyin-slugs/so-pinyin-slugs.php">' . __( 'Settings', 'so-pinyin-slugs' ) . '</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $sops_links );
	}

	return $links;
}


/** The End **/

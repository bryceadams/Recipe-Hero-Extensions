<?php
/**
 * Plugin Name: Recipe Hero Video
 * Plugin URI: http://recipehero.in/
 * Description: Embed responsive videos rather than boring images in your Recipe Hero recipes
 * Author: Recipe Hero / Bryce Adams
 * Author URI: http://recipehero.in/
 * Version: 1.0.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'recipe-hero/recipe-hero.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// Load plugin text domain
	add_action( 'init', 'rhv_load_textdomain' );

	// Load Updater
	add_action( 'plugins_loaded', 'rhv_updater' );

	// Load up...
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rhv-meta.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rhv-video.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rhv-settings.php' );

	// Vroom.. Vroom..
	add_action( 'plugins_loaded', array( 'RHV_Meta', 'get_instance' ) );
	add_action( 'plugins_loaded', array( 'RHV_Video', 'get_instance' ) );
	add_action( 'plugins_loaded', array( 'RHV_Settings', 'get_instance' ) );


} else {

	add_action( 'admin_notices', 'rhv_rh_deactivated' );

}

/**
 * Recipe Hero Updater
 **/
if ( ! function_exists( 'rhv_updater' ) ) {
	function rhv_updater() {
		if ( ! class_exists( 'RH_Video_Updater' ) ) {
			include( plugin_dir_path( __FILE__ ) . '/updater/class-rh-updater.php' );
		}
	}
}

/**
 * Load the plugin text domain for translation.
 *
 * @return void
 */
if ( ! function_exists( 'rhv_load_textdomain' ) ) {
	function rhv_load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'recipe-hero-video' );

		load_textdomain( 'recipe-hero-video', trailingslashit( WP_LANG_DIR ) . 'recipe-hero-video/recipe-hero-video-' . $locale . '.mo' );
		load_plugin_textdomain( 'recipe-hero-video', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/**
 * Recipe Hero Deactivated Notice
 **/
if ( ! function_exists( 'rhv_rh_deactivated' ) ) {
	function rhv_rh_deactivated() {
		echo '<div class="error"><p>' . sprintf( __( 'Recipe Hero Video requires %s to be installed and active.', 'recipe-hero-video' ), '<a href="http://www.recipehero.in/" target="_blank">Recipe Hero</a>' ) . '</p></div>';
	}
}
<?php
/**
 * Plugin Name:       Recipe Hero Likes
 * Plugin URI:        http://recipehero.in/
 * Description:       Add Likes to your Recipe Hero recipes
 * Author:            Recipe Hero / Bryce Adams
 * Author URI:        http://recipehero.in/
 * Version:           1.0.1
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

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

// Check if Recipe Hero is installed and activated
if ( in_array( 'recipe-hero/recipe-hero.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// Load plugin text domain
	add_action( 'init', 'rhlikes_load_textdomain' );

	// Load Updater
	add_action( 'plugins_loaded', 'rhlikes_updater' );

	// Load up...
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-likes.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-likes-widget.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-likes-methods.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-likes-columns.php' );

    // Vroom.. Vroom..
	add_filter( 'recipe_hero_get_settings_pages', 'rhlikes_settings_page' );
	add_action( 'plugins_loaded', array( 'RH_Likes', 'get_instance' ) );
	add_action( 'plugins_loaded', array( 'RH_Likes_Methods', 'get_instance' ) );
	add_action( 'plugins_loaded', array( 'RH_Likes_Columns', 'get_instance' ) );

} else {

	add_action( 'admin_notices', 'rhlikes_rh_deactivated' );

}


/**
* Recipe Here Include Settings
**/
if ( ! function_exists( 'rhlikes_settings_page' ) ) {
	function rhlikes_settings_page( $settings ) {
		$settings[] = include( 'includes/class-rh-likes-settings.php' );
		return $settings;
	}
}

/**
 * Recipe Hero Updater
 **/
if ( ! function_exists( 'rhlikes_updater' ) ) {
	function rhlikes_updater() {
		if ( ! class_exists( 'RH_Likes_Updater' ) ) {
			include( plugin_dir_path( __FILE__ ) . '/updater/class-rh-updater.php' );
		}
	}
}

/**
 * Load the plugin text domain for translation.
 *
 * @return void
 */
if ( ! function_exists( 'rhlikes_load_textdomain' ) ) {
	function rhlikes_load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'recipe-hero-likes' );

		load_textdomain( 'recipe-hero-likes', trailingslashit( WP_LANG_DIR ) . 'recipe-hero-likes/recipe-hero-likes-' . $locale . '.mo' );
		load_plugin_textdomain( 'recipe-hero-likes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/**
 * Recipe Hero Deactivated Notice
 **/
if ( ! function_exists( 'rhlikes_rh_deactivated' ) ) {
	function rhlikes_rh_deactivated() {
		echo '<div class="error"><p>' . sprintf( __( 'Recipe Hero Likes requires %s to be installed and active.', 'recipe-hero-likes' ), '<a href="http://www.recipehero.in/" target="_blank">Recipe Hero</a>' ) . '</p></div>';
	}
}
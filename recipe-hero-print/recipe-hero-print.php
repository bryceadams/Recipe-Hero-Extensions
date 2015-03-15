<?php
/**
 * Plugin Name: Recipe Hero Print
 * Plugin URI: http://recipehero.in/
 * Description: Include a 'print' button in your recipes, allowing for easy, beautiful recipe printing
 * Author: Recipe Hero / Bryce Adams
 * Author URI: http://recipehero.in/
 * Version: 1.1.1
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
	add_action( 'init', 'rhprint_load_textdomain' );

	// Load up...
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-print.php' );

	// Vroom.. Vroom..
	add_filter( 'recipe_hero_get_settings_pages', 'rhprint_settings_page' );
	add_action( 'plugins_loaded', array( 'RH_Print_Init', 'get_instance' ) );
	add_action( 'plugins_loaded', 'rhprint_updater' ); // Updater

} else {

	add_action( 'admin_notices', 'rhprint_rh_deactivated' );

}

/**
* Recipe Hero Include Settings
**/
if ( ! function_exists( 'rhprint_settings_page' ) ) {
	function rhprint_settings_page( $settings ) {
		$settings[] = include( 'includes/class-rh-print-settings.php' );
		return $settings;
	}
}

/**
 * Recipe Hero Updater
 **/
if ( ! function_exists( 'rhprint_updater' ) ) {
	function rhprint_updater() {
		if ( ! class_exists( 'RH_Print_Updater' ) ) {
			include( plugin_dir_path( __FILE__ ) . '/updater/class-rh-updater.php' );
		}
	}
}

/**
 * Load the plugin text domain for translation.
 *
 * @return void
 */
if ( ! function_exists( 'rhprint_load_textdomain' ) ) {
	function rhprint_load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'recipe-hero-print' );

		load_textdomain( 'recipe-hero-print', trailingslashit( WP_LANG_DIR ) . 'recipe-hero-print/recipe-hero-print-' . $locale . '.mo' );
		load_plugin_textdomain( 'recipe-hero-print', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/**
 * Recipe Hero Deactivated Notice
 **/
if ( ! function_exists( 'rhprint_rh_deactivated' ) ) {
	function rhprint_rh_deactivated() {
		echo '<div class="error"><p>' . sprintf( __( 'Recipe Hero Print requires %s to be installed and active.', 'recipe-hero-print' ), '<a href="http://www.recipehero.in/" target="_blank">Recipe Hero</a>' ) . '</p></div>';
	}
}
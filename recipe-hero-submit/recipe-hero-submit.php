<?php
/**
 * Plugin Name: Recipe Hero Submit
 * Plugin URI: http://recipehero.in/
 * Description: Add a front-end submission form for recipes to your site, using the [recipe_hero_submit] shortcode
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
	add_action( 'init', 'rhs_load_textdomain' );

	// Load up...
	add_action( 'plugins_loaded', 'rhs_includes' ); // Includes
	add_filter( 'recipe_hero_get_settings_pages', 'rhs_settings_page' );
	add_action( 'plugins_loaded', 'rhs_updater' ); // Updater

} else {

	add_action( 'admin_notices', 'rhs_rh_deactivated' );

}

/**
 * Recipe Hero Submit Includes
 **/
if ( ! function_exists( 'rhs_includes' ) ) {
	function rhs_includes() {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-rh-submit-form.php' );
	}
}

/**
* Recipe Hero Include Settings
**/
if ( ! function_exists( 'rhs_settings_page' ) ) {
	function rhs_settings_page( $settings ) {
		$settings[] = include( 'includes/class-rh-submit-settings.php' );
		return $settings;
	}
}

/**
 * Recipe Hero Updater
 **/
if ( ! function_exists( 'rhs_updater' ) ) {
	function rhs_updater() {
		if ( ! class_exists( 'RH_Submit_Updater' ) ) {
			include( plugin_dir_path( __FILE__ ) . '/updater/class-rh-updater.php' );
		}
	}
}

/**
 * Load the plugin text domain for translation.
 *
 * @return void
 */
if ( ! function_exists( 'rhs_load_textdomain' ) ) {
	function rhs_load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'recipe-hero-submit' );

		load_textdomain( 'recipe-hero-submit', trailingslashit( WP_LANG_DIR ) . 'recipe-hero-submit/recipe-hero-submit-' . $locale . '.mo' );
		load_plugin_textdomain( 'recipe-hero-submit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/**
 * Recipe Hero Deactivated Notice
 **/
if ( ! function_exists( 'rhs_rh_deactivated' ) ) {
	function rhs_rh_deactivated() {
		echo '<div class="error"><p>' . sprintf( __( 'Recipe Hero Submit requires %s to be installed and active.', 'recipe-hero-submit' ), '<a href="http://www.recipehero.in/" target="_blank">Recipe Hero</a>' ) . '</p></div>';
	}
}
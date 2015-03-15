<?php
/**
 * Recipe Hero Print Main Class
 *
 * @package   Recipe Hero Print
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class RH_Print_Init {

	protected static $instance = null;

    function __construct() {
 
 		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
 		add_action( 'wp_head', array( $this, 'option_styles' ) );
 		add_action( 'recipe_hero_before_main_content', array( $this, 'print_message' ) );
 		add_action( 'recipe_hero_single_recipe_content', array( $this, 'print_button' ) );
 		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

 		add_shortcode( 'recipe_hero_print', array( $this, 'print_button_shortcode' ) );

    }

    /**
	 * Start the Class when called
	 *
	 * @package Recipe Hero Print
	 * @author  Bryce Adams <bryce@bryce.se>
	 * @since   1.0.0
	 */

	public static function get_instance() {

	  // If the single instance hasn't been set, set it now.
	  if ( null == self::$instance ) {
		self::$instance = new self;
	  }

	  return self::$instance;

	}

	/**
	 * Enqueue assets
	 */

	public function assets() {

		wp_register_style( 'rhprint-styles', plugins_url( '../assets/css/recipe-hero-print-styles.css', __FILE__ ) );		

		if ( is_recipe_hero() ) {
			wp_enqueue_style( 'rhprint-print', plugins_url( '../assets/css/recipe-hero-print.css', __FILE__ ), '', '1.0', 'print' );
			wp_enqueue_style( 'rhprint-styles' );
		}

	}

	/**
	 * Enqueue admin assets
	 */
	public function admin_assets() {
	
		wp_enqueue_style( 'rhprint-styles-admin', plugins_url( '../assets/css/recipe-hero-print-styles.css', __FILE__ ) );
	
	}

	/** 
	 * Print Button
	 */

	public function print_button() {

		$icon = get_option( 'rhprint_icon', 'rhprintprint-0' );
		$text = get_option( 'rhprint_text', __( 'Print', 'recipe-hero-print' ) );
		echo '<a href="javascript:window.print()" class="rh-print-icon"><span class="' . $icon . '"></span>' . $text . '</a>';
	
	}

	/** 
	 * Print Button Shortcode
	 */

	public function print_button_shortcode() {

		$icon = get_option( 'rhprint_icon', 'rhprintprint-0' );
		$text = get_option( 'rhprint_text', __( 'Print', 'recipe-hero-print' ) );
		return '<a href="javascript:window.print()" class="rh-print-icon"><span class="' . $icon . '"></span>' . $text . '</a>';
	
	}

	/** 
	 * Print Message
	 */

	public function print_message() {

		echo '<h1 class="print print-title">' . get_bloginfo( 'name' ) . '</h1>';

		if ( $message = get_option( 'rhprint_message' ) ) {
			echo '<div class="print print-message">';
			echo $message;
			echo '</div>';
		}

	}

	/**
	 * Styles defined by options
	 */

	public function option_styles() {

		if ( ! is_recipe_hero() ) {
			return false;
		} ?>
		<style type="text/css">
			@media print {
				<?php if ( get_option( 'rhprint_gallery' ) == 'yes' ) { ?>
					.images .thumbnails { display: none; }
				<?php } ?>
				<?php if ( get_option( 'rhprint_cat' ) == 'yes' ) { ?>
					.recipe-single-tax { display: none; }
				<?php } ?>
				<?php if ( get_option( 'rhprint_details' ) == 'yes' ) { ?>
					.recipe-single-details { display: none; }
				<?php } ?>
				<?php if ( get_option( 'rhprint_description' ) == 'yes' ) { ?>
					.recipe-single-content { display: none; }
				<?php } ?>
				<?php if ( get_option( 'rhprint_ingredients' ) == 'yes' ) { ?>
					.recipe-single-ingredients { display: none; }
				<?php } ?>
				<?php if ( get_option( 'rhprint_instructions' ) == 'yes' ) { ?>
					.recipe-single-instructions { display: none; }
				<?php } ?>
				<?php if ( get_option( 'rhprint_comments' ) == 'yes' ) { ?>
					#comments { display: none; }
				<?php } ?>
			}
		</style>

		<?php

	}

}
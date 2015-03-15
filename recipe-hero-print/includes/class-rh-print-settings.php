<?php
/**
 * Recipe Hero Print Settings
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

if ( ! class_exists( 'RH_Print_Settings' ) ) :

class RH_Print_Settings extends RH_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'rh-print';
		$this->label = __( 'Print', 'recipe-hero-print' );
		add_filter( 'recipe_hero_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'recipe_hero_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'recipe_hero_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = apply_filters( 'recipe_hero_print_settings', array(
			array( 'title' => __( 'Recipe Hero Print Settings', 'recipe-hero-print' ), 'type' => 'title', 'desc' => '', 'id' => 'rhs_options' ),
			array(
				'title'    => __( 'Print Button Text', 'recipe-hero-print' ),
				'id'       => 'rhprint_text',
				'desc'	   => __( 'Customise the text for the print button', 'recipe-hero-print' ),
				'placeholder'	=> '',
				'type'     => 'text',
				'default'  => 'Print',
				'class'    => '',
			),
			array(
				'title'    => __( 'Print Button Icon', 'recipe-hero-print' ),
				'id'       => 'rhprint_icon',
				'type'     => 'radio',
				'options'  => $this->icons(),
				'default'  => 'rhprintprint-0',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Gallery?', 'recipe-hero-print' ),
				'id'       => 'rhprint_gallery',
				'desc'	   => __( 'Do you want to hide the image gallery in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Cuisine / Course?', 'recipe-hero-print' ),
				'id'       => 'rhprint_cat',
				'desc'	   => __( 'Do you want to hide the cuisine / course in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Details?', 'recipe-hero-print' ),
				'id'       => 'rhprint_details',
				'desc'	   => __( 'Do you want to hide the details (eg. serves, equipment) in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Description?', 'recipe-hero-print' ),
				'id'       => 'rhprint_description',
				'desc'	   => __( 'Do you want to hide the description / content in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Ingredients?', 'recipe-hero-print' ),
				'id'       => 'rhprint_ingredients',
				'desc'	   => __( 'Do you want to hide the ingredients in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Instructions?', 'recipe-hero-print' ),
				'id'       => 'rhprint_instructions',
				'desc'	   => __( 'Do you want to hide the instructions in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Hide Comments?', 'recipe-hero-print' ),
				'id'       => 'rhprint_comments',
				'desc'	   => __( 'Do you want to hide comments in the printed version?', 'recipe-hero-print' ),
				'desc_tip' => false,
				'type'     => 'checkbox',
				'class'    => '',
			),
			array(
				'title'    => __( 'Print Message', 'recipe-hero-print' ),
				'id'       => 'rhprint_message',
				'desc'	   => __( 'Optionally you can add a message that will display above recipes when printed - some valid HTML allowed', 'recipe-hero-print' ),
				'desc_tip' => true,
				'type'     => 'textarea',
				'placeholder'	=> '',
				'default'  => '',
				'class'    => '',
				'css'	   => 'width: 400px; height: 100px;',
			),
			array( 'type' => 'sectionend', 'id' => '' ),
		) );
		return apply_filters( 'recipe_hero_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();
		RH_Admin_Settings::save_fields( $settings );
	}

	/**
	 * Helper method for print icons (7)
	 */
	public function icons() {
		$icons = array( '' => __( 'None', 'recipe-hero-print' ) );
		for ( $i = 0 ; $i < 7; $i++ ) {
			$icons['rhprintprint-' . $i] = '<span class="rhprintprint-' . $i . '"></span>';
		}
		return $icons;
	}

}

endif;

return new RH_Print_Settings();
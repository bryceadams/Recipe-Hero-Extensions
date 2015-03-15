<?php
/**
 * Recipe Hero Submit Settings
 *
 * @package   Recipe Hero Submit
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'RH_Submit_Settings' ) ) :

class RH_Submit_Settings extends RH_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'rhs-submit';
		$this->label = __( 'Submit', 'recipe-hero-submit' );
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
		$settings = apply_filters( 'recipe_hero_submit_settings', array(
			array( 'title' => __( 'Recipe Hero Submit Settings', 'recipe-hero-submit' ), 'type' => 'title', 'desc' => '', 'id' => 'rhs_options' ),
			array(
				'title'    => __( 'Default Recipe Status', 'recipe-hero-submit' ),
				'id'       => 'rhs_default_status',
				'desc'	   => __( 'What should the default status be for a recipe after it\'s submitted?', 'recipe-hero-submit' ),
				'type'     => 'select',
				'default'  => 'draft',
				'options'  => array(
					'draft'		=> __( 'Draft', 'recipe-hero-submit' ),
					'pending'	=> __( 'Pending', 'recipe-hero-submit' ),
					'private'	=> __( 'Private', 'recipe-hero-submit' ),
					'publish'	=> __( 'Published', 'recipe-hero-submit' ),
				),
				'class'    => '',
				'css'      => '',
			),
			array(
				'title'    => __( 'Allowed User Roles', 'recipe-hero-submit' ),
				'id'       => 'rhs_user_levels',
				'desc'	   => __( 'Which user roles are allowed to submit a recipe using the form?', 'recipe-hero-submit' ),
				'desc_tip' => false,
				'type'     => 'multiselect',
				'default'  => 'subscriber',
				'options'  => $this->user_roles(),
				'class'    => '',
				'css'      => 'height:150px;',
			),
			array( 'type' => 'sectionend', 'id' => '' ),
		) );
		return apply_filters( 'recipe_hero_get_settings_' . $this->id, $settings );
	}

	/**
	 * Helper method to get site's user roles
	 */
	public function user_roles() {

		global $wp_roles;
    	$roles = $wp_roles->get_names();

    	// Add 'Guest' to array
    	$guest = array( 'guest' => __( 'Guests / Anyone', 'recipe-hero-submit' ) );

    	// Combine $roles and $guest
    	$roles = $guest + $roles;

    	return $roles;

    }

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();
		RH_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new RH_Submit_Settings();
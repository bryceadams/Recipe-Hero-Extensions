<?php
/**
 * Recipe Hero Likes Settings
 *
 * @package   Recipe Hero Likes
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RH_Likes_Settings' ) ) :

/**
 * RHL_Settings
 */
class RH_Likes_Settings extends RH_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'rhlikes';
		$this->label = __( 'Likes', 'recipe-hero-likes' );

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

		// Enqueue heart icons
        wp_enqueue_style( 'rhl-hearts', plugins_url( '/assets/css/rhl-hearts.css', __FILE__ ) );

		$settings = apply_filters( 'recipe_hero_likes_settings', array(

			array( 'title' => __( 'Recipe Hero Likes Settings', 'recipe-hero-likes' ), 'type' => 'title', 'desc' => '', 'id' => 'rhl_options' ),

			array(
				'title'    => __( 'Auto Insert Like Button', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_auto_insert',
				'type'     => 'checkbox',
				'default'  => '',
				'desc'	   => __( 'Do you want to automatically insert the like button into your recipes?', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Insert Location Page Type', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_location_type',
				'type'     => 'select',
				'options'  => array(
					'single'	=> __( 'Single', 'recipe-hero-likes' ),
					'archive'	=> __( 'Archive', 'recipe-hero-likes' ),
					'both'		=> __( 'Both', 'recipe-hero-likes' ),
				),
				'default'  => 'single',
				'desc'     => __( 'Which recipe page types do you want to insert the like button automatically on?', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Insert Location Area', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_location_area',
				'type'     => 'select',
				'options'  => array(
					'start' 		=> __( 'Start', 'recipe-hero-likes' ),
					'title' 		=> __( 'Title', 'recipe-hero-likes' ),
					'meta' 			=> __( 'Meta', 'recipe-hero-likes' ),
					'image' 		=> __( 'Image / Gallery', 'recipe-hero-likes' ),
					'tax' 			=> __( 'Cuisine / Course', 'recipe-hero-likes' ),
					'details' 		=> __( 'Details', 'recipe-hero-likes' ),
					'description' 	=> __( 'Description', 'recipe-hero-likes' ),
					'end'			=> __( 'End', 'recipe-hero-likes' ),
				),
				'description'	=> __( 'Choose the area that you want the likes to be outputted after', 'recipe-hero-likes' ),
				'default'  => 'title',
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Postfix (0 - None)', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_postfix_none',
				'type'     => 'text',
				'default'  => '',
				'placeholder'	=> __( 'Likes', 'recipe-hero-likes' ),
				'desc'	   => __( '(Optional) - Postfix for After Heart Count: eg. Likes', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Postfix (1 - Single)', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_postfix_single',
				'type'     => 'text',
				'default'  => '',
				'placeholder'	=> __( 'Like', 'recipe-hero-likes' ),
				'desc'	   => __( '(Optional) - Postfix for After Heart Count: eg. Like', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Postfix (2+ - Multiple)', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_postfix_multiple',
				'type'     => 'text',
				'default'  => '',
				'placeholder'	=> __( 'Likes', 'recipe-hero-likes' ),
				'desc'	   => __( '(Optional) - Postfix for After Heart Count: eg. Likes', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Heart Style / Icon', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_heart_style',
				'type'     => 'radio',
				'options' => array(
                    'rhl-icon-heart' 			=> '<span class="rhl-icon-heart"></span>',
                    'rhl-icon-heart-empty' 		=> '<span class="rhl-icon-heart-empty"></span>',
                    'rhl-icon-heart-1' 			=> '<span class="rhl-icon-heart-1"></span>',
                    'rhl-icon-heart-empty-1' 	=> '<span class="rhl-icon-heart-empty-1"></span>',
                    'rhl-icon-heart-2' 			=> '<span class="rhl-icon-heart-2"></span>',
                    'rhl-icon-heart-filled' 	=> '<span class="rhl-icon-heart-filled"></span>',
                    'rhl-icon-heart-3' 			=> '<span class="rhl-icon-heart-3"></span>',
                    'rhl-icon-heart-empty-2' 	=> '<span class="rhl-icon-heart-empty-2"></span>',
                    'rhl-icon-heart-4' 			=> '<span class="rhl-icon-heart-4"></span>',
                    'rhl-icon-heart-5' 			=> '<span class="rhl-icon-heart-5"></span>',
                    'rhl-icon-heart-broken' 	=> '<span class="rhl-icon-heart-broken"></span>',
                    'rhl-icon-heart-6' 			=> '<span class="rhl-icon-heart-6"></span>',
                    'rhl-icon-heart-circled' 	=> '<span class="rhl-icon-heart-circled"></span>',
                    'rhl-icon-heart-empty-3' 	=> '<span class="rhl-icon-heart-empty-3"></span>',
                    'rhl-icon-heart-7' 			=> '<span class="rhl-icon-heart-7"></span>',
                    'rhl-icon-heart-8' 			=> '<span class="rhl-icon-heart-8"></span>',
                    'rhl-icon-heart-empty-4' 	=> '<span class="rhl-icon-heart-empty-4"></span>',
                ),
				'default'  => '',
				'class'    => '',
				'css'      => '',
			),

			// @todo replace color settings with colorpickers

			array(
				'title'    => __( 'Heart Color (non-active)', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_color_nonactive',
				'type'     => 'color',
				'placeholder'  => '#eaeaea',
				'desc'	   => __( 'The color of the heart (or custom) icon before it has been liked etc. (non-active)', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
			),

			array(
				'title'    => __( 'Heart Color (active)', 'recipe-hero-likes' ),
				'id'       => 'rhlikes_color_active',
				'type'     => 'color',
				'placeholder'  => '#c90000',
				'desc'	   => __( 'The color of the heart (or custom) icon after it has been liked etc. (active)', 'recipe-hero-likes' ),
				'class'    => '',
				'css'      => '',
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

}

endif;

return new RH_Likes_Settings();
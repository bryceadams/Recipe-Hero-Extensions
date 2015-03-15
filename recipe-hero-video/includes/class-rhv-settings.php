<?php
/**
 * Recipe Hero Video Settings
 *
 * @package   Recipe Hero Video
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class RHV_Settings {

	protected static $instance = null;

    function __construct() {
 
 		add_filter( 'recipe_hero_general_settings_images', array( $this, 'settings' ) );

    }

    /**
	 * Start the Class when called
	 */

	public static function get_instance() {
	  // If the single instance hasn't been set, set it now.
	  if ( null == self::$instance ) {
		self::$instance = new self;
	  }
	  return self::$instance;
	}

	/**
	 * Filter settings to add Recipe Hero Video settings
	 */

	public function settings( $settings ) {

		$settings[] = array(
			'title'         => __( 'Recipe Videos on Archive', 'recipe-hero-video' ),
			'desc'          => __( 'Enable the recipe videos added with Recipe Hero Video on the archives too', 'recipe-hero-video' ),
			'id'            => 'recipe_hero_video_archive',
			'default'       => 'no',
			'type'          => 'checkbox'
		);

		return $settings;

	}

}
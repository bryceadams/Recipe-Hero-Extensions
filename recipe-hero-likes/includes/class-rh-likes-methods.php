<?php
/**
 * @package   Recipe Hero Likes
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'RH_Likes_Methods' ) ) :

class RH_Likes_Methods {

	protected static $instance = null;

    function __construct() {
 
		add_action( 'init', array( $this, 'display_likes' ), 99999 );
		add_action( 'wp_head', array( $this, 'likes_color' ), 99999 );

    }

    /**
	 * Start the Class when called
	 *
	 * @package Recipe Hero Likes
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
	 * Display Likes
	 **/

	public function display_likes() {

		// Variables
		$auto_insert = get_option( 'rhlikes_auto_insert', 'no' );

		$location_type = get_option( 'rhlikes_location_type', 'single' );
		$location_area = get_option( 'rhlikes_location_area', '' );

		if ( $auto_insert == 'no' ) {

			return false;

		} else {

			switch ( $location_area ) {

				case 'start':
					$location_priority = 6;
					break;
				case 'title':
					$location_priority = 11;
					break;
				case 'meta':
					$location_priority = 21;
					break;
				case 'image':
					$location_priority = 31;
					break;
				case 'tax':
					$location_priority = 41;
					break;
				case 'details':
					$location_priority = 51;
					break;
				case 'description':
					$location_priority = 61;
					break;
				case 'end':
					$location_priority = 150;
					break;
		
			}

			switch ( $location_type ) {

				case 'single':

					add_action( 'recipe_hero_single_recipe_content', 'recipe_hero_likes_output', $location_priority );
					break;

				case 'archive':

					add_action( 'recipe_hero_archive_recipe_content', 'recipe_hero_likes_output', $location_priority );
					break;

				case 'both':

					add_action( 'recipe_hero_single_recipe_content', 'recipe_hero_likes_output', $location_priority );
					add_action( 'recipe_hero_archive_recipe_content', 'recipe_hero_likes_output', $location_priority );
					break;

			}

		}

	}

	/**
	 * Likes Color
	 **/

	public function likes_color() {

		// Variables
		$color_non_active = get_option( 'rhlikes_color_nonactive', '#eaeaea' );
		$color_active = get_option( 'rhlikes_color_active', '#c90000' );

		if ( $color_non_active || $color_active ) { ?>
		
			<style type="text/css">
				.recipe-hero-likes .heart-icon {
					color: <?php echo $color_non_active; ?>;
				}

				.recipe-hero-likes:hover .heart-icon,
				.recipe-hero-likes.active .heart-icon,
				.recipe-hero-likes-count .heart-icon {
					color: <?php echo $color_active; ?>;
				}
			</style>

		<?php }

	}

}

endif;
<?php
/**
 * Recipe Hero Video Videos
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

class RHV_Video {

	protected static $instance = null;

    function __construct() {
 
 		add_filter( 'recipe_hero_single_recipe_image_html', array( $this, 'featured_video' ), 20, 2 );
 		add_filter( 'recipe_hero_step_image', array( $this, 'steps_video' ), 20, 2 );

 		if ( get_option( 'recipe_hero_video_archive' ) == 'yes' ) {
 			add_filter( 'recipe_hero_archive_recipe_image_html', array( $this, 'featured_video' ), 20, 2 );
 		}

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
	 * Replace Featured Image with Featured Video
	 */

	public function featured_video( $content, $id ) {

		global $wp_embed;

		$video_url = get_post_meta( $id, '_rhv_video_url', true );

		if ( $video_url ) {
			$content = $wp_embed->run_shortcode( '[embed]' . $video_url . '[/embed]' );
		}

		return $content;

	}

	/**
	 * Replace Steps Image with Steps Video
	 */

	public function steps_video( $content, $instruction ) {

		global $wp_embed;

		$step_video = '';

		if ( isset( $instruction['_recipe_hero_video_step_video'] ) ) {

			$step_video = $instruction['_recipe_hero_video_step_video'];

			// Vimeo multiple videos per page fix
			if ( strpos( $step_video, 'vimeo' ) !== false ) {
				$step_video .= '&autopause=0';
			}

			// Only output shortcode if there is a video / url
			if ( $step_video ) {
				$content = $wp_embed->run_shortcode( '[embed]' . $step_video . '[/embed]' );
			}
		
		}

		return $content;

	}

}
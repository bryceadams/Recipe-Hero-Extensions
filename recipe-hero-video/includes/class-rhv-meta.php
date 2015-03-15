<?php
/**
 * Recipe Hero Video Meta
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

class RHV_Meta {

	protected static $instance = null;

    /**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_filter( 'recipe_hero_meta_steps_fields', array( $this, 'add_steps_meta' ) );

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
	 * Enqueue scripts / styles (admin)
	 */

	public function assets() {

		wp_register_style( 'rhv-styles', plugin_dir_url( __FILE__ ) . '../assets/css/rhv-admin.css' );
		wp_enqueue_style( 'rhv-styles' );

	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {

        $post_types = 'recipe';
        
        if ( $post_types == $post_type ) {

			add_meta_box(
				'recipe_hero_video',
				__( 'Recipe Video', 'recipe-hero-video' ),
				array( $this, 'render_meta_box_content' ),
				$post_types,
				'side',
				'core'
			);

        }

	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['recipe_hero_video_inner_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['recipe_hero_video_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'recipe_hero_video_inner_custom_box' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'recipe' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
	
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST['recipe_hero_video_url'] );

		// Update the meta field.
		update_post_meta( $post_id, '_rhv_video_url', $mydata );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		global $wp_embed;
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'recipe_hero_video_inner_custom_box', 'recipe_hero_video_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_rhv_video_url', true );

		// Display the form, using the current value.
		echo '<label for="recipe_hero_video_new_field">';
		_e( 'Video URL', 'recipe-hero-video' );
		echo '</label> ';
		echo '<input type="text" id="recipe_hero_video_url" name="recipe_hero_video_url" class="video-url"';
                echo ' value="' . esc_attr( $value ) . '" />';

        if ( $value ) {
			echo $wp_embed->run_shortcode( '[embed]' . $value . '[/embed]' );
		}
	}

	/**
	 * Video Field for each step
	 */
	public function add_steps_meta( $fields ) {

		$fields[] = array(
			'name' => __( 'Step Video', 'recipe-hero-video' ),
			'desc' => __( 'Add a link to a video to use for this step. It will override the step image', 'recipe-hero-video' ),
			'id'   => '_recipe_hero_video_step_video',
			'type' => 'text',
		);

		return $fields;

	}

}